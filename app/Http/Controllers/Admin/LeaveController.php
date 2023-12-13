<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Leave;
use App\Models\LWP;
use App\Models\User;
use App\Models\UserDevice;
use App\Models\Notification;
use App\Models\UserLeaveBalance;
use DateTime;
use Illuminate\Http\Request;
use App\Library\Push\PushNotification;

class LeaveController extends Controller {
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request) {
		if ($request->ajax()) {
			$where_str = '1 = ?';
			$where_params = [1];

			if ($request->has('sSearch')) {
				$search = $request->get('sSearch');
				$where_str .= " and ( name like \"%{$search}%\""
					. ")";
			}

			$data = Leave::select('leaves.id', 'name', 'email', 'date', 'type', 'reason', 'status', 'half_day_count')
				->leftjoin('users', 'users.id', 'leaves.user_id')
				->orderBy('id', 'desc')
				->whereRaw($where_str, $where_params);

			$data_count = Leave::select('id')
				->leftjoin('users', 'users.id', 'leaves.user_id')
				->orderBy('leaves.id', 'desc')
				->whereRaw($where_str, $where_params)
				->count();

			$columns = ['leaves.id', 'name', 'email', 'date', 'type', 'reason', 'status', 'half_day_count'];

			if ($request->has('iDisplayStart') && $request->get('iDisplayLength') != '-1') {
				$data = $data->take($request->get('iDisplayLength'))->skip($request->get('iDisplayStart'));
			}

			if ($request->has('iSortCol_0')) {
				for ($i = 0; $i < $request->get('iSortingCols'); $i++) {
					$column = $columns[$request->get('iSortCol_' . $i)];
					if (false !== ($index = strpos($column, ' as '))) {
						$column = substr($column, 0, $index);
					}
					$data = $data->orderBy($column, $request->get('sSortDir_' . $i));
				}
			}

			$data = $data->get();

			foreach ($data as $key => $value) {
				$explode = explode(',', $value['date']);

				$value['date'] = implode(', ', $explode);

				$last_date = end($explode);

				$start_date = $explode[0];

				$value['date'] = sizeof($explode) > 1 ? $start_date . ' To ' .$last_date : $start_date;

				if (strtotime($last_date) <= strtotime(date('d-m-Y'))) {
					if($value['status'] == 'Approved') {
						$value['status'] = 'Past';
					}
				}
				$days = sizeof($explode);
				
				$value['days'] = $days - ($value['half_day_count'] * 0.5);
			}
			
			$response['iTotalDisplayRecords'] = $data_count;
			$response['iTotalRecords'] = $data_count;

			$response['sEcho'] = intval($request->get('sEcho'));

			$response['aaData'] = $data;

			return $response;
		}

		return view('admin.leave.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request) {
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($id) {
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id) {
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id) {
		//
	}

	/**
	 * Approve/Reject leave.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function changeStatus(Request $request) {
		$id = $request->get('id');
		
		if (!is_array($id)) {
			$id = array($id);
		}
		
		foreach ($id as $key => $value) {
			$leave = Leave::where('id', $value)->first();

			$explode_date = explode(',', $leave['date']);

			$month_array = [];
			
			foreach ($explode_date as $key => $value) {
				$month = date('m', strtotime($value));
				$year = date('Y', strtotime($value));

				$month_array[$month] = [
					'count' => array_key_exists($month, $month_array) ? $month_array[$month]['count'] + 1 : 1,
					'year' => $year,
				];
			}

			if($leave['half_day_count'] == 1 && sizeof($month_array) == 2) {
				$last_key = key( array_slice( $month_array, -1, 1, TRUE ) );

				$month_array[$last_key]['count'] = $month_array[$last_key]['count'] * 0.5; 
			}
			
			
			$leave_count = sizeof($explode_date) - ($leave['half_day_count'] * 0.5);
			
			$user = User::with('getDevice')->find($leave['user_id']);

			$today = strtotime(date('Y-m-d'));

			$joining_date = strtotime($user['joining_date']);

			$joining_period = 0;

			while (($joining_date = strtotime('+1 MONTH', $joining_date)) <= $today)
			    $joining_period++;
			
			$past_leave_count = Leave::where('user_id', $leave['user_id'])->where('year', date('Y'))->where('status', 'Approved')->count();

			$year_leave = Config::first();

			$leave_balance = UserLeaveBalance::where('user_id', $leave['user_id'])->first();
			
			switch ($request->status) {
				
			case 'Approved':
				
				if ($joining_period >= 3 && $leave_balance['leave_balance'] > $leave_count) {
					
					$updated_leave_balance = $leave_balance['leave_balance'] - $leave_count;

					UserLeaveBalance::where('user_id', $leave['user_id'])->update(['leave_balance' => $updated_leave_balance]);

					Leave::whereIn('id', $id)->update(['status' => 'Approved']);
				} else {
					
					
					$leave_count = $leave_count - $leave_balance['leave_balance'];
					$existing_leave_balance = UserLeaveBalance::where('user_id', $leave['user_id'])->first();
					UserLeaveBalance::where('user_id', $leave['user_id'])->update(['leave_balance' => 0]);

					if(isset($month_array)) {
						
						foreach ($month_array as $key => $month) {
							
							if( $leave['half_day_count'] == 1 && sizeof($month_array) == 1) {
							
								$month_array[$key]['count'] = $month_array[$key]['count'] * 0.5; 
							} elseif($leave['half_day_count'] == 2 && sizeof($month_array) == 1) {
								
								$month_array[$key]['count'] = $month_array[$key]['count'] * 0.5;
							} else {
								
								$month_array[$key]['count'] = $month_array[$key]['count'] * 0.5;
							}
							
							$lwp = LWP::where('user_id', $leave['user_id'])->where('year', $month['year'])->where('month', $key)->first();
							$lwp_count = ($lwp) ? $lwp['count'] : 0; 

							LWP::updateOrCreate([
								'user_id' => $leave['user_id'],
								'year' => $month['year'],
								'month' => $key,
							], [
								'count' => $lwp_count + ( $leave_count < $month['count'] ? $leave_count : $month['count']),
							]);
						}	
					}
					
					if($existing_leave_balance['leave_balance'] == 0) {
						
						$payload = [
				            'type' => 'Leave Balance',
				            'title' => 'LMS',
				            'description' => 'This leave/leaves will be considered as LWP',
				            'receiver' => $leave['user_id'],
				        ];

				         if($user['getDevice']) {
			        	    if(isset($user['getDevice']['device_token']) && strlen($user['getDevice']['device_token']) > 4 && $user['getDevice']['device_type'] == 'iOS')
			                {
			                    PushNotification::iOS($payload, $user['getDevice']['device_token']);
			                }

			                if(isset($user['getDevice']['device_token']) && strlen($user['getDevice']['device_token']) > 4 && $user['getDevice']['device_type'] == 'android')
			                {
			                    PushNotification::android($payload, $user['getDevice']['device_token']);
			                }
			            }

				        Notification::create($payload);
					} else {

						$payload = [
				            'type' => 'Leave Balance',
				            'title' => 'LMS',
				            'description' => 'You have no leaves left',
				            'receiver' => $leave['user_id'],
				        ];

				         if($user['getDevice']) {
			        	    if(isset($user['getDevice']['device_token']) && strlen($user['getDevice']['device_token']) > 4 && $user['getDevice']['device_type'] == 'iOS')
			                {
			                    PushNotification::iOS($payload, $user['getDevice']['device_token']);
			                }

			                if(isset($user['getDevice']['device_token']) && strlen($user['getDevice']['device_token']) > 4 && $user['getDevice']['device_type'] == 'android')
			                {
			                    PushNotification::android($payload, $user['getDevice']['device_token']);
			                }
			            }

				        Notification::create($payload);
					}

					Leave::whereIn('id', $id)->update(['status' => 'Approved', 'is_lwp' => 1]);
				}
				break;

			case 'Cancel':

				if ($leave['is_lwp'] == 1) {

					$lwp_count = 0;

					foreach ($month_array as $key => $month) {
						$check_detail = LWP::where([ 
							'user_id' => $leave['user_id'],
							'year' => $month['year'],
							'month' => $key,
						])->first();

						$lwp_count += $check_detail['count']; 
					}

					if($lwp_count < $leave_count) {
						foreach ($month_array as $key => $month) {
							LWP::where([ 
								'user_id' => $leave['user_id'],
								'year' => $month['year'],
								'month' => $key,
							])->delete();
						}

						$credit_balance = $leave_count - $lwp_count;

						UserLeaveBalance::where('user_id', $leave['user_id'])->update(['leave_balance' => $credit_balance]);

					} else {

						foreach ($month_array as $key => $month) {
							
							if( $leave['half_day_count'] == 1 && sizeof($month_array) == 1) {
							
								$month_array[$key]['count'] = $month_array[$key]['count'] * 0.5; 
							} elseif($leave['half_day_count'] == 2 && sizeof($month_array) == 1) {
								
								$month_array[$key]['count'] = $month_array[$key]['count'] * 0.5;
							} else {
								
								$month_array[$key]['count'] = $month_array[$key]['count'] * 0.5;
							}
							$lwp = LWP::where('user_id', $leave['user_id'])->where('year', $month['year'])->where('month', $key)->first();
							
							LWP::updateOrCreate([
								'user_id' => $leave['user_id'],
								'year' => $month['year'],
								'month' => $key,
							], [
								'count' => $lwp['count'] - $month['count'],
							]);
						}	
						
					}

				} else {
					$updated_leave_balance = $leave_balance['leave_balance'] + $leave_count;

					UserLeaveBalance::where('user_id', $leave['user_id'])->update(['leave_balance' => $updated_leave_balance]);
				}

				Leave::whereIn('id', $id)->update(['status' => 'Pending']);
				break;

			case 'Rejected':

				if($leave['status'] == 'Approved') {
					if ($leave['is_lwp'] == 1) {

						$check_detail = LWP::where([ 'user_id' => $leave['user_id'],
						'year' => date('Y'), ])->first();

						if($check_detail['count'] < $leave_count) {
							LWP::where([
								'user_id' => $leave['user_id'],
								'year' => date('Y'),
							])->delete();

							$credit_balance = $leave_count - $check_detail['count'];

							UserLeaveBalance::where('user_id', $leave['user_id'])->update(['leave_balance' => $credit_balance]);

						} else {
							LWP::updateOrCreate([
								'user_id' => $leave['user_id'],
								'year' => date('Y'),
							], [
								'count' => $lwp['count'] - $leave_count,
							]);
						}

					} else {
						$updated_leave_balance = $leave_balance['leave_balance'] + $leave_count;

						UserLeaveBalance::where('user_id', $leave['user_id'])->update(['leave_balance' => $updated_leave_balance]);
					}
				}

				Leave::whereIn('id', $id)->update(['status' => 'Rejected']);
				break;

			default:
				# code...
				break;
			}
			
	        $payload = [
	            'type' => 'Leave Status',
	            'title' => 'LMS',
	            'description' => 'Your leave application has been ' . $request->status,
	            'receiver' => $leave['user_id'],
	        ];

	        if($user['getDevice']) {
        	    if(isset($user['getDevice']['device_token']) && strlen($user['getDevice']['device_token']) > 4 && $user['getDevice']['device_type'] == 'iOS')
                {
                    PushNotification::iOS($payload, $user['getDevice']['device_token']);
                }

                if(isset($user['getDevice']['device_token']) && strlen($user['getDevice']['device_token']) > 4 && $user['getDevice']['device_type'] == 'android')
                {
                    PushNotification::android($payload, $user['getDevice']['device_token']);
                }
            }

	        Notification::create($payload);
		}

		return redirect()->back()->with('message', 'Record saved successfully')
			->with('type', 'success');
	}

}
