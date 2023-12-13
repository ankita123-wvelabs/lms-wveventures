<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\Leave;
use App\Models\InOut;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Validator;

class LeaveController extends BaseApiController {
	/**
	 * Check for authnticate use and retrived it.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function __construct(Request $request) {

	}

	/**
	 * get the leave history
	 *
	 * @return statuscode
	 */
	public function index(Request $request) {

		$data = Leave::where('user_id', $this->authUser()->id)->orderBy('id','desc')->get();

		foreach ($data as $leave) {
			$days = sizeof(explode(',', $leave['date']));
			$range = explode(',', $leave['date']);
			$last_date = end($range);
			if (strtotime($last_date) <= strtotime(date('d-m-Y'))) {
				if($leave['status'] == 'Approved') {
					$leave['status'] = 'Consumed';
				}
			}
			// $leave['date'] = $range[0] . ' - ' . end($range);
			$leave['date'] = sizeof($range) > 1 ? $range[0] . ' - ' . end($range) : $range[0];
			$leave['days'] = $days - ($leave['half_day_count'] * 0.5);
		}

		return $this->successResponse($data, '', 200);
	}

	public function create(Request $request) {

		$rules = [
			'date' => 'required',
			'type' => 'required',
			'reason' => 'required',
			// 'type_description' => 'required_if:type,==,Half Day',
		];
		// $message = [
		// 	'reason.required' => 'Title field is missing.',
		// ];

		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return $this->failureResponse(new \StdClass(), $validator->errors()->first(), 400);
		}

		$data = $request->all();

		$data['user_id'] = $this->authUser()->id;

		$applied_leaves = Leave::where('user_id', $this->authUser()->id)->where('status', '!=', 'Rejected')->get();

		$data['year'] = date('Y');

		$dates = explode(',', $data['date']);
		
		foreach ($applied_leaves as $key => $applied_leave) {
			$past_leave = explode(',', $applied_leave['date']);
			
			foreach ($past_leave as $key => $value) {
				if(in_array($value, $dates)) {
					return $this->failureResponse(new \StdClass(), 'You have already applied for this leave(s). Please apply again.', 400);
				}
			}

		}
		sort($dates);
		$holidays = Holiday::whereYear('date', date('Y'))->get();

		foreach ($holidays as $key => $holiday) {
			if(in_array($holiday['date'], $dates)) {
				if (($key = array_search($holiday['date'], $dates)) !== false) {
				    unset($dates[$key]);
				}
			}
		}
		
		$data['date'] = implode(',', $dates);
		Leave::create($data);

		return $this->successResponse(new \StdClass(), '', 200);

	}

	public function attendences(Request $request) {

		$userId = $this->authUser()->id;

		$date = \Carbon\Carbon::now();
    	
		$date_array = $date->toArray();
		
		switch ($request->type) {
			case 'today':
				$today = date('Y-m-d');

				$attendence = InOut::where('user_id', $userId)->where('date', $today)->orderBy('date', 'asc')->first();
				$attendence['timing'] = unserialize($attendence['timing']);

				$counter = 1;
				$total_time = 0;
				if($attendence['timing']) {
					foreach ($attendence['timing'] as $key => $value) {
						
						if($counter % 2 != 0) {
							if(array_key_exists($key + 1, $attendence['timing'])) {
								$time = strtotime($attendence['timing'][$key + 1]) - strtotime($attendence['timing'][$key]);
							} else {
								$time = strtotime($date_array['hour'].':'.$date_array['minute']) - strtotime($attendence['timing'][$key]);
							}
							$total_time += $time;
						}
						$counter++;
					}
					
					$attendence['present_time'] = date('H:i',$total_time);
					$attendence['total_time'] = '8:15';
					$attendence['info'] = sizeof($attendence['timing']) % 2 != 0 ? 'In' : 'Out'; 
					return $this->successResponse($attendence, 'Success', 200);
				} else {
					return $this->successResponse(new \StdClass(), 'No Logs for Today.', 200);
				}

				break;

			case 'current_month':
				$month = date('m');

				// $attendences = InOut::where('user_id', $userId)->where('month', $month)->orderBy('date', 'asc')->get();
				
				$monthly_hours = 0;

				$workdays = array();
				$weekends = array();
				$all_days = array();
				$type = CAL_GREGORIAN;

				$month = date('n'); // Month ID, 1 through to 12.
				$year = date('Y'); // Year in 4 digit 2009 format.
				$day_count = cal_days_in_month($type, $month, $year); // Get the amount of days
				//loop through all days
				for ($i = 1; $i <= $day_count; $i++) {

					if($month < 9) {
						$month = str_pad($month, 2, '0', STR_PAD_LEFT);
					}

					if($i < 9) {
						$i = str_pad($i, 2, '0', STR_PAD_LEFT);
					}

					$date = $year . '-' . $month . '-' . $i; //format date
					$get_name = date('l', strtotime($date)); //get week day
					$day_name = substr($get_name, 0, 3); // Trim day name to 3 chars
					if($i == 1) {
						$first_day = $date;
					}
					if($i == $day_count) {
						$last_date = $date;
					}
					//if not a weekend add day to array
					if ($day_name != 'Sun' && $day_name != 'Sat') {
						// $workdays[] = $i;
						$holiday = Holiday::where('date', $date)->first();
						if(!$holiday) {
							$workdays[] = $date;
						}
					}
					if ($day_name == 'Sun' || $day_name == 'Sat') {
						$weekends[] = $date;
					}

					$all_days[] = $date;

				}

				$attendences = [];
				foreach ($all_days as $key => $date) {
					$attendence = InOut::where('user_id', $userId)->where('date', $date)->orderBy('date', 'asc')->first();
					if($attendence) {
						$attendence['timing'] = unserialize($attendence['timing']);

						$counter = 1;
						$total_time = 0;
						foreach ($attendence['timing'] as $key => $value) {
							
							if($counter % 2 != 0) {
								if(array_key_exists($key + 1, $attendence['timing'])) {
									$time = strtotime($attendence['timing'][$key + 1]) - strtotime($attendence['timing'][$key]);
								} else {
									if($attendence['date'] == date('Y-m-d')) {
										$time = strtotime($date_array['hour'].':'.$date_array['minute']) - strtotime($attendence['timing'][$key]);
									} else {
										$time = strtotime('24:00') - strtotime($attendence['timing'][$key]);
									}
								}
								$total_time += $time;
							}
							$counter++;
						}
						
						$monthly_hours += $total_time;
						$attendence['present_time'] = date('H:i',$total_time);
						$attendence['info'] = sizeof($attendence['timing']) % 2 != 0 ? 'In' : 'Out'; 
						$attendences[] = $attendence;
						
					} else {

						$holiday = Holiday::where('date', $date)->first();

						if(!$holiday && $date <= date('Y-m-d')) {
							$attendence['date'] = $date;
							$attendence['present_time'] = '0:0';
							$attendences[] = $attendence;
						}
					}
					
				}

				// foreach ($attendences as $attendence) {
					
				// 	$attendence['timing'] = unserialize($attendence['timing']);

				// 	$counter = 1;
				// 	$total_time = 0;
				// 	foreach ($attendence['timing'] as $key => $value) {
						
				// 		if($counter % 2 != 0) {
				// 			if(array_key_exists($key + 1, $attendence['timing'])) {
				// 				$time = strtotime($attendence['timing'][$key + 1]) - strtotime($attendence['timing'][$key]);
				// 			} else {
				// 				if($attendence['date'] == date('Y-m-d')) {
				// 					$time = strtotime($date_array['hour'].':'.$date_array['minute']) - strtotime($attendence['timing'][$key]);
				// 				} else {
				// 					$time = strtotime('24:00') - strtotime($attendence['timing'][$key]);
				// 				}
				// 			}
				// 			$total_time += $time;
				// 		}
				// 		$counter++;
				// 	}
				// 	$monthly_hours += $total_time;
				// 	$attendence['present_time'] = date('H:i',$total_time);
				// 	$attendence['info'] = sizeof($attendence['timing']) % 2 != 0 ? 'In' : 'Out'; 
				// }

				$data['attendences'] = $attendences;
				$data['monthly_present_hours'] = str_replace('.',':', number_format(($monthly_hours/3600),2));
				$data['monthly_total_hours'] = str_replace('.', ':', number_format(sizeof($workdays) * 8.25, 2));
				$data['days'] = date('dS', strtotime($first_day)).' - '.date('dS', strtotime($last_date));;
				$data['month'] = date('F');
				$data['day_hour'] = '8:15';

				return $this->successResponse($data, 'Success', 200);

				break;

			case 'previous_month':
				
				$current_month = date('n');
				
				if($current_month == 1) {
					$month = 12;
					$year = $year - 1;
				}

				$month = $current_month - 1;
				$year = date('Y'); // Year in 4 digit 2009 format.

				// $attendences = InOut::where('user_id', $userId)->where('month', $month)->orderBy('date', 'asc')->get();
				
				$monthly_hours = 0;

				$workdays = array();
				$weekends = array();
				$all_days = array();
				$type = CAL_GREGORIAN;

				$day_count = cal_days_in_month($type, $month, $year); // Get the amount of days
				
				//loop through all days
				for ($i = 1; $i <= $day_count; $i++) {

					$date = $year . '-' . $month . '-' . $i; //format date
					$get_name = date('l', strtotime($date)); //get week day
					$day_name = substr($get_name, 0, 3); // Trim day name to 3 chars
					if($i == 1) {
						$first_day = $date;
					}
					if($i == $day_count) {
						$last_date = $date;
					}
					//if not a weekend add day to array
					if ($day_name != 'Sun' && $day_name != 'Sat') {
						// $workdays[] = $i;
						$holiday = Holiday::where('date', $date)->first();
						if(!$holiday) {
							$workdays[] = $date;
						}
					}
					if ($day_name == 'Sun' || $day_name == 'Sat') {
						$weekends[] = $date;
					}

					$all_days[] = $date;

				}
				$attendences = [];
				foreach ($all_days as $key => $date) {
					$attendence = InOut::where('user_id', $userId)->where('date', $date)->orderBy('date', 'asc')->first();
					if($attendence) {
						$attendence['timing'] = unserialize($attendence['timing']);

						$counter = 1;
						$total_time = 0;
						foreach ($attendence['timing'] as $key => $value) {
							
							if($counter % 2 != 0) {
								if(array_key_exists($key + 1, $attendence['timing'])) {
									$time = strtotime($attendence['timing'][$key + 1]) - strtotime($attendence['timing'][$key]);
								} else {
									if($attendence['date'] == date('Y-m-d')) {
										$time = strtotime($date_array['hour'].':'.$date_array['minute']) - strtotime($attendence['timing'][$key]);
									} else {
										$time = strtotime('24:00') - strtotime($attendence['timing'][$key]);
									}
								}
								$total_time += $time;
							}
							$counter++;
						}
						
						$monthly_hours += $total_time;
						$attendence['present_time'] = date('H:i',$total_time);
						$attendence['info'] = sizeof($attendence['timing']) % 2 != 0 ? 'In' : 'Out'; 
						$attendences[] = $attendence;
						
					} else {
						$holiday = Holiday::where('date', $date)->first();

						if(!$holiday && $date <= date('Y-m-d')) {
							$attendence['date'] = $date;
							$attendence['present_time'] = '0:0';
							$attendences[] = $attendence;
						}
					}
					
				}
				
				// foreach ($attendences as $attendence) {
					
				// 	$attendence['timing'] = unserialize($attendence['timing']);

				// 	$counter = 1;
				// 	$total_time = 0;
				// 	foreach ($attendence['timing'] as $key => $value) {
						
				// 		if($counter % 2 != 0) {
				// 			if(array_key_exists($key + 1, $attendence['timing'])) {
				// 				$time = strtotime($attendence['timing'][$key + 1]) - strtotime($attendence['timing'][$key]);
				// 			} else {
				// 				if($attendence['date'] == date('Y-m-d')) {
				// 					$time = strtotime($date_array['hour'].':'.$date_array['minute']) - strtotime($attendence['timing'][$key]);
				// 				} else {
				// 					$time = strtotime('24:00') - strtotime($attendence['timing'][$key]);
				// 				}
				// 			}
				// 			$total_time += $time;
				// 		}
				// 		$counter++;
				// 	}
				// 	$monthly_hours += $total_time;
				// 	$attendence['present_time'] = date('H:i',$total_time);
				// 	$attendence['info'] = sizeof($attendence['timing']) % 2 != 0 ? 'In' : 'Out'; 
				// }
				$data['attendences'] = $attendences;
				$data['monthly_present_hours'] = str_replace('.',':',number_format(($monthly_hours/3600),2));
				$data['monthly_total_hours'] = str_replace('.', ':', number_format(sizeof($workdays) * 8.25, 2));
				$data['days'] = date('dS', strtotime($first_day)).' - '.date('dS', strtotime($last_date));
				$data['month'] = date('F', strtotime(date('F') . " last month"));
				$data['day_hour'] = '8:15';
				
				return $this->successResponse($data, 'Success', 200);

				break;

			case 'current_week':
				$monday = date( 'Y-m-d', strtotime( 'monday this week' ) );
				$tuesday = date( 'Y-m-d', strtotime( 'tuesday this week' ) );
				$wednesday = date( 'Y-m-d', strtotime( 'wednesday this week' ) );
				$thrusday = date( 'Y-m-d', strtotime( 'thursday this week' ) );
				$friday = date( 'Y-m-d', strtotime( 'friday this week' ) );

				$weekdates = [$monday, $tuesday, $wednesday, $thrusday, $friday];
				
				$attendences = [];				
				$weekly_hours = 0;
				foreach ($weekdates as $key => $date) {
					$attendence = InOut::where('user_id', $userId)->where('date', $date)->orderBy('date', 'asc')->first();
					if($attendence) {
						$attendence['timing'] = unserialize($attendence['timing']);

						$counter = 1;
						$total_time = 0;
						foreach ($attendence['timing'] as $key => $value) {
							
							if($counter % 2 != 0) {
								if(array_key_exists($key + 1, $attendence['timing'])) {
									$time = strtotime($attendence['timing'][$key + 1]) - strtotime($attendence['timing'][$key]);
								} else {
									if($attendence['date'] == date('Y-m-d')) {
										$time = strtotime($date_array['hour'].':'.$date_array['minute']) - strtotime($attendence['timing'][$key]);
									} else {
										$time = strtotime('24:00') - strtotime($attendence['timing'][$key]);
									}
								}
								$total_time += $time;
							}
							$counter++;
						}
						
						$weekly_hours += $total_time;
						$attendence['present_time'] = date('H:i',$total_time);
						$attendence['info'] = sizeof($attendence['timing']) % 2 != 0 ? 'In' : 'Out'; 
						$attendences[] = $attendence;
						
					} else {
						$holiday = Holiday::where('date', $date)->first();

						if(!$holiday && $date <= date('Y-m-d')) {
							$attendence['date'] = $date;
							$attendence['present_time'] = '0:0';
							$attendences[] = $attendence;
						}
					}
					
				}

				// $attendences = InOut::where('user_id', $userId)->whereBetween('date', [$monday, $friday])->orderBy('date', 'asc')->get();

				// $weekly_hours = 0;
				// foreach ($attendences as $attendence) {
					
				// 	$attendence['timing'] = unserialize($attendence['timing']);

				// 	$counter = 1;
				// 	$total_time = 0;
				// 	foreach ($attendence['timing'] as $key => $value) {
						
				// 		if($counter % 2 != 0) {
				// 			if(array_key_exists($key + 1, $attendence['timing'])) {
				// 				$time = strtotime($attendence['timing'][$key + 1]) - strtotime($attendence['timing'][$key]);
				// 			} else {
				// 				if($attendence['date'] == date('Y-m-d')) {
				// 					$time = strtotime($date_array['hour'].':'.$date_array['minute']) - strtotime($attendence['timing'][$key]);
				// 				} else {
				// 					$time = strtotime('24:00') - strtotime($attendence['timing'][$key]);
				// 				}
				// 			}
				// 			$total_time += $time;
				// 		}
				// 		$counter++;
				// 	}
					
				// 	$weekly_hours += $total_time;
				// 	$attendence['present_time'] = date('H:i',$total_time);
				// 	$attendence['info'] = sizeof($attendence['timing']) % 2 != 0 ? 'In' : 'Out'; 
				// }
				$data['attendences'] = $attendences;
				$data['weekly_present_hours'] = str_replace('.',':', number_format(($weekly_hours/3600),2));
				$data['weekly_total_hours'] = str_replace('.', ':', number_format(5 * 8.25, 2));

				$data['days'] = date('dS', strtotime($monday)).' - '.date('dS', strtotime($friday));
				$data['month'] = date('F');
				$data['day_hour'] = '8:15';

				return $this->successResponse($data, 'Success', 200);
				break;

			case 'previous_week':
				$monday = date( 'Y-m-d', strtotime( 'monday last week' ) );
				$tuesday = date( 'Y-m-d', strtotime( 'tuesday last week' ) );
				$wednesday = date( 'Y-m-d', strtotime( 'wednesday last week' ) );
				$thrusday = date( 'Y-m-d', strtotime( 'thursday last week' ) );
				$friday = date( 'Y-m-d', strtotime( 'friday last week' ) );

				
				$last_week_month = date('m', strtotime($monday));
				// if($last_week_month == date('m')) {
				$weekdates = [$monday, $tuesday, $wednesday, $thrusday, $friday];
				
				$attendences = [];				
				$weekly_hours = 0;
				$holiday_in_week = 0;
				foreach ($weekdates as $key => $date) {
					$attendence = InOut::where('user_id', $userId)->where('date', $date)->orderBy('date', 'asc')->first();
					if($attendence) {
						$attendence['timing'] = unserialize($attendence['timing']);

						$counter = 1;
						$total_time = 0;
						foreach ($attendence['timing'] as $key => $value) {
							
							if($counter % 2 != 0) {
								if(array_key_exists($key + 1, $attendence['timing'])) {
									$time = strtotime($attendence['timing'][$key + 1]) - strtotime($attendence['timing'][$key]);
								} else {
									if($attendence['date'] == date('Y-m-d')) {
										$time = strtotime($date_array['hour'].':'.$date_array['minute']) - strtotime($attendence['timing'][$key]);
									} else {
										$time = strtotime('24:00') - strtotime($attendence['timing'][$key]);
									}
								}
								$total_time += $time;
							}
							$counter++;
						}
						
						$weekly_hours += $total_time;
						$attendence['present_time'] = date('H:i',$total_time);
						$attendence['info'] = sizeof($attendence['timing']) % 2 != 0 ? 'In' : 'Out'; 
						$attendences[] = $attendence;
						
					} else {
						$holiday = Holiday::where('date', $date)->first();

						if(!$holiday && $date <= date('Y-m-d')) {
							$attendence['date'] = $date;
							$attendence['present_time'] = '0:0';
							$attendences[] = $attendence;
						} else {
							$holiday_in_week += 1;
						}

					}
					
				}
					// $attendences = InOut::where('user_id', $userId)->whereBetween('date', [$monday, $friday])->orderBy('date', 'asc')->get();

					// $weekly_hours = 0;
					// foreach ($attendences as $attendence) {
						
					// 	$attendence['timing'] = unserialize($attendence['timing']);

					// 	$counter = 1;
					// 	$total_time = 0;
					// 	foreach ($attendence['timing'] as $key => $value) {
							
					// 		if($counter % 2 != 0) {
					// 			if(array_key_exists($key + 1, $attendence['timing'])) {
					// 				$time = strtotime($attendence['timing'][$key + 1]) - strtotime($attendence['timing'][$key]);
					// 			} else {
					// 				if($attendence['date'] == date('Y-m-d')) {
					// 					$time = strtotime($date_array['hour'].':'.$date_array['minute']) - strtotime($attendence['timing'][$key]);
					// 				} else {
					// 					$time = strtotime('24:00') - strtotime($attendence['timing'][$key]);
					// 				}
					// 			}
					// 			$total_time += $time;
					// 		}
					// 		$counter++;
					// 	}
					// 	$weekly_hours += $total_time;
					// 	$attendence['present_time'] = date('H:i',$total_time);
					// 	$attendence['info'] = sizeof($attendence['timing']) % 2 != 0 ? 'In' : 'Out'; 
					// }
					$data['attendences'] = $attendences;
					$data['weekly_present_hours'] = str_replace('.',':', number_format(($weekly_hours/3600),2));
					$data['weekly_total_hours'] = str_replace('.', ':', number_format(( 5 - $holiday_in_week ) * 8.25, 2));
					$data['days'] = date('dS', strtotime($monday)).' - '.date('dS', strtotime($friday));
					$data['month'] = date('F');
					$data['day_hour'] = '8:15';

					return $this->successResponse($data, 'Success', 200);
				// }
					return $this->successResponse(new \StdClass(), 'Success', 200);
				break;
			
			default:
				# code...
				break;
		}
	}
}
