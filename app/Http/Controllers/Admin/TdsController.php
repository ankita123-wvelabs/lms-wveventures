<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tds;
use App\Models\LWP;
use App\Models\Config;
use App\Models\Salary;
use App\Models\InOut;
use App\Models\UserLeaveBalance;

class TdsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $request['month'] = date('m') - 1;

        $request['year'] = date('Y');

        $users = User::get();

        $config = Config::first();

        foreach ($users as $key => $user) {
            $user['tds'] = 0;
            $tds = Tds::where('user_id', $user['id'])->first();
            $joining_month = date('m', strtotime($user['joining_date']));
            $joining_year = date('Y', strtotime($user['joining_date']));
            
            $current_year = date('Y');
            $current_month = date('m');
            
            $month_constant = 0;

            $match_date = '01-04-'.$current_year;

            $d1 = new \DateTime($user['joining_date']);
			$d2 = new \DateTime($match_date);
			
			// @link http://www.php.net/manual/en/class.dateinterval.php
			$interval = $d2->diff($d1);

			$month_constant = $interval->m;
			if(($joining_year == $current_year) && ($joining_month <= 04 || $joining_month <= 4)) {

				$month_constant = 4 - $joining_month;

            	$yearly_salary = $user['salary'] * $month_constant;

			} elseif(($current_year - $joining_year) == 1 && ($joining_month <= 04 || $joining_month <= 4)) {

				$month_constant = 12;

            	$yearly_salary = $user['salary'] * $month_constant;

			} elseif(($joining_year == $current_year) && ($joining_month > 04 || $joining_month > 4)) {
				$countable_month = ( 12 - $joining_month ) + 4;

            	$month_constant = $countable_month;
            	
            	$yearly_salary = $user['salary'] * $month_constant;
			} elseif(($current_year - $joining_year) == 1 && ($joining_month > 04 || $joining_month > 4)) {
				$countable_month = ( 12 - $joining_month ) + 4;

            	$month_constant = $countable_month;
            	
            	$yearly_salary = $user['salary'] * $month_constant;
			} elseif(($current_year - $joining_year) > 1 ) {
				$month_constant = 12;

            	$yearly_salary = $user['salary'] * $month_constant;
			} else {
				$month_constant = 1;

				$yearly_salary = 0;
			}
            

            
            $taxable_income = $yearly_salary > 0 ? $yearly_salary - ($config['prof_tax'] * 12) - $config['std_deduction'] - $tds['80c'] - $tds['80d'] - $tds['nps'] - $tds['exmpt'] : 0;
           
            if($taxable_income <= 500000) {
            	$tds = 0;
            } elseif($taxable_income > 500000 && $taxable_income <= 1000000) {
            	$tds = 12500 + (($taxable_income - 500000) * 0.2);
            } else {
            	$tds = 12500 + 100000 + (($taxable_income - 1000000) * 0.3);
            }
            
            $user['tax_income'] = $taxable_income ?: 0;
            $user['tds'] = $tds + ($tds * 0.04);
            $user['monthly_tds'] = 0;
            if($tds > 0){
                $user['monthly_tds'] = ($tds + ($tds * 0.04)) / $month_constant;
            }
           

            //--- Calculate salary of month ----
            	$workdays = array();
				$weekends = array();
				$all_days = array();
				$type = CAL_GREGORIAN;

				$month = date('n'); // Month ID, 1 through to 12.
				$year = date('Y'); // Year in 4 digit 2009 format.
				$day_count = cal_days_in_month($type, $month - 1, $year); // Get the amount of days

				//loop through all days
				for ($i = 1; $i <= $day_count; $i++) {

					$date = $year . '-' . $month . '-' . $i; //format date
					$get_name = date('l', strtotime($date)); //get week day
					$day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

					//if not a weekend add day to array
					if ($day_name != 'Sun' && $day_name != 'Sat') {
						// $workdays[] = $i;
						$workdays[] = $date;
					}
					if ($day_name == 'Sun' || $day_name == 'Sat') {
						$weekends[] = $date;
					}

					$all_days[] = $date;

				}
				$lwp = LWP::where('user_id', $user['id'])->where('month',$month -1)->where('year', $year)->first();
				$per_day_salary = ( 1/sizeof($workdays)) * $user['salary'];

				$user['net_salary'] = $user['salary'] > 0 ? $user['salary'] - (($lwp && $lwp['count'])?$lwp['count']:0 * $per_day_salary) - $config['prof_tax'] - $user['monthly_tds'] : 0;

				$salary_status = Salary::where(['user_id' => $user['id'], 'month' => $month -1, 'year' => $year])->first();

				$user['status'] = isset($salary_status) ? $salary_status['status'] : 2;

            //--- End of salary ----------------
        }

        $last = 2000;
        
        $years = [];

        for($i = $request['year']; $i > $last; $i--) { 
            $years[] = $i;
        }

        $months = ['01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun',  
                    '07' => 'July', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'];

        return view('admin.tds.index', compact('years', 'users', 'request', 'months'));
    }

    public function getSalary(Request $request) {
    	$data = $request->except('_token');

    	if($data == null) {
            $data['month'] = date('m') - 1;

            $data['year'] = date('Y');
        }

        \Session::put('salary_report', $data);

        $users = User::get();

        $config = Config::first();

        foreach ($users as $key => $user) {
            $user['tds'] = 0;
            $tds = Tds::where('user_id', $user['id'])->first();
            $joining_month = date('m', strtotime($user['joining_date']));
            $joining_year = date('Y', strtotime($user['joining_date']));
            
            $current_year = date('Y');
            $current_month = date('m');
            
            $month_constant = 0;

            $match_date = '01-04-'.$current_year;

            $d1 = new \DateTime($user['joining_date']);
			$d2 = new \DateTime($match_date);
			
			// @link http://www.php.net/manual/en/class.dateinterval.php
			$interval = $d2->diff($d1);

			$month_constant = $interval->m;
			if(($joining_year == $current_year) && ($joining_month <= 04 || $joining_month <= 4)) {

				$month_constant = 4 - $joining_month;

            	$yearly_salary = $user['salary'] * $month_constant;

			} elseif(($current_year - $joining_year) == 1 && ($joining_month <= 04 || $joining_month <= 4)) {

				$month_constant = 12;

            	$yearly_salary = $user['salary'] * $month_constant;

			} elseif(($joining_year == $current_year) && ($joining_month > 04 || $joining_month > 4)) {
				$countable_month = ( 12 - $joining_month ) + 4;

            	$month_constant = $countable_month;
            	
            	$yearly_salary = $user['salary'] * $month_constant;
			} elseif(($current_year - $joining_year) == 1 && ($joining_month > 04 || $joining_month > 4)) {
				$countable_month = ( 12 - $joining_month ) + 4;

            	$month_constant = $countable_month;
            	
            	$yearly_salary = $user['salary'] * $month_constant;
			} elseif(($current_year - $joining_year) > 1 ) {
				$month_constant = 12;

            	$yearly_salary = $user['salary'] * $month_constant;
			} else {
				$month_constant = 1;

				$yearly_salary = 0;
			}

            $taxable_income = $yearly_salary > 0 ? $yearly_salary - ($config['prof_tax'] * 12) - $config['std_deduction'] - $tds['80c'] - $tds['80d'] - $tds['nps'] - $tds['exmpt'] : 0;

            if($taxable_income <= 500000) {
            	$tds = 0;
            } elseif($taxable_income > 500000 && $taxable_income <= 1000000) {
            	$tds = 12500 + (($taxable_income - 500000) * 0.2);
            } else {
            	$tds = 12500 + 100000 + (($taxable_income - 1000000) * 0.3);
            }

            $user['tax_income'] = $taxable_income ?: 0;
            $user['tds'] = $tds + ($tds * 0.04);
            $user['monthly_tds'] = ($tds + ($tds * 0.04)) / $month_constant;


            //--- Calculate salary of month ----
            	$workdays = array();
				$weekends = array();
				$all_days = array();
				$type = CAL_GREGORIAN;

				$month = $data['month']; // Month ID, 1 through to 12.
				$year = $data['year']; // Year in 4 digit 2009 format.
				$day_count = cal_days_in_month($type, $month, $year); // Get the amount of days

				//loop through all days
				for ($i = 1; $i <= $day_count; $i++) {

					$date = $year . '-' . $month . '-' . $i; //format date
					$get_name = date('l', strtotime($date)); //get week day
					$day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

					//if not a weekend add day to array
					if ($day_name != 'Sun' && $day_name != 'Sat') {
						// $workdays[] = $i;
						$workdays[] = $date;
					}
					if ($day_name == 'Sun' || $day_name == 'Sat') {
						$weekends[] = $date;
					}

					$all_days[] = $date;

				}
				$lwp = LWP::where('user_id', $user['id'])->where('month',$month - 1)->where('year', $year)->first();
				$per_day_salary = ( 1/sizeof($workdays)) * $user['salary'];

				$user['net_salary'] = $user['salary'] > 0 ? $user['salary'] - ($lwp['count'] * $per_day_salary) - $config['prof_tax'] - $user['monthly_tds'] : 0;

				$salary_status = Salary::where(['user_id' => $user['id'], 'month' => $month , 'year' => $year])->first();

				$user['status'] = isset($salary_status) ? $salary_status['status'] : 2;

            //--- End of salary ----------------
        }
        
        $html = view('admin.tds.table_view', compact('users'))->render();

        return response()->json(['data' => $html, 'code' => 200, 'success' => true]);
    }

    public function salaryProcessing(Request $request) {
    	$data = \Session::get('salary_report');

        if($data == null) {
            $data['month'] = date('m') - 1;

            $data['year'] = date('Y');
        }

        $users = User::get();

        $config = Config::first();

        foreach ($users as $key => $user) {
            $user['tds'] = 0;
            $tds = Tds::where('user_id', $user['id'])->first();
            $joining_month = date('m', strtotime($user['joining_date']));
            $joining_year = date('Y', strtotime($user['joining_date']));
            
            $current_year = date('Y');
            $current_month = date('m');
            
            $month_constant = 0;

            $match_date = '01-04-'.$current_year;

            $d1 = new \DateTime($user['joining_date']);
			$d2 = new \DateTime($match_date);
			
			// @link http://www.php.net/manual/en/class.dateinterval.php
			$interval = $d2->diff($d1);

			$month_constant = $interval->m;
			if(($joining_year == $current_year) && ($joining_month <= 04 || $joining_month <= 4)) {

				$month_constant = 4 - $joining_month;

            	$yearly_salary = $user['salary'] * $month_constant;

			} elseif(($current_year - $joining_year) == 1 && ($joining_month <= 04 || $joining_month <= 4)) {

				$month_constant = 12;

            	$yearly_salary = $user['salary'] * $month_constant;

			} elseif(($joining_year == $current_year) && ($joining_month > 04 || $joining_month > 4)) {
				$countable_month = ( 12 - $joining_month ) + 4;

            	$month_constant = $countable_month;
            	
            	$yearly_salary = $user['salary'] * $month_constant;
			} elseif(($current_year - $joining_year) == 1 && ($joining_month > 04 || $joining_month > 4)) {
				$countable_month = ( 12 - $joining_month ) + 4;

            	$month_constant = $countable_month;
            	
            	$yearly_salary = $user['salary'] * $month_constant;
			} elseif(($current_year - $joining_year) > 1 ) {
				$month_constant = 12;

            	$yearly_salary = $user['salary'] * $month_constant;
			} else {
				$month_constant = 1;

				$yearly_salary = 0;
			}

            $taxable_income = $yearly_salary > 0 ? $yearly_salary - ($config['prof_tax'] * 12) - $config['std_deduction'] - $tds['80c'] - $tds['80d'] - $tds['nps'] - $tds['exmpt'] : 0;

            if($taxable_income <= 500000) {
            	$tds = 0;
            } elseif($taxable_income > 500000 && $taxable_income <= 1000000) {
            	$tds = 12500 + (($taxable_income - 500000) * 0.2);
            } else {
            	$tds = 12500 + 100000 + (($taxable_income - 1000000) * 0.3);
            }

            $user['tax_income'] = $taxable_income ?: 0;
            $user['tds'] = $tds + ($tds * 0.04);
            $user['monthly_tds'] = ($tds + ($tds * 0.04)) / $month_constant;


            //--- Calculate salary of month ----
            	$workdays = array();
				$weekends = array();
				$all_days = array();
				$type = CAL_GREGORIAN;

				$month = $data['month']; // Month ID, 1 through to 12.
				$year = $data['year']; // Year in 4 digit 2009 format.
				$day_count = cal_days_in_month($type, $month, $year); // Get the amount of days

				//loop through all days
				for ($i = 1; $i <= $day_count; $i++) {

					$date = $year . '-' . $month . '-' . $i; //format date
					$get_name = date('l', strtotime($date)); //get week day
					$day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

					//if not a weekend add day to array
					if ($day_name != 'Sun' && $day_name != 'Sat') {
						// $workdays[] = $i;
						$workdays[] = $date;
					}
					if ($day_name == 'Sun' || $day_name == 'Sat') {
						$weekends[] = $date;
					}

					$all_days[] = $date;

				}
				$lwp = LWP::where('user_id', $user['id'])->where('month',$month -1)->where('year', $year)->first();
				$per_day_salary = ( 1/sizeof($workdays)) * $user['salary'];

				$user['net_salary'] = $user['salary'] > 0 ? $user['salary'] - ($lwp['count'] * $per_day_salary) - $config['prof_tax'] - $user['monthly_tds'] : 0;

            //--- End of salary ----------------
			Salary::updateOrCreate([
				'user_id' => $user['id'], 'month' => $month, 'year' => $year
			],[
				'user_id' => $user['id'], 'month' => $month, 'year' => $year, 'amount' => $user['net_salary']
			]);

        }
			return redirect()->back();
    }

    public function getUserSalary(Request $request) {
    	$data = $request->except('_token');

    	if($data == null) {
            $data['year'] = date('Y');
        }

        $salaries = Salary::where(['user_id' => $data['user'], 'year' => $data['year']])->get();

        $html = view('admin.tds.employee_yearly_salary_list', compact('salaries'))->render();

        return response()->json(['data' => $html, 'code' => 200, 'success' => true]);
    }

    public function viewSalarySlip(Request $request, $user, $month)
    {
        $user = User::find($user);

        $config = Config::first();

            $user['tds'] = 0;
            $tds = Tds::where('user_id', $user['id'])->first();
            $joining_month = date('m', strtotime($user['joining_date']));
            $joining_year = date('Y', strtotime($user['joining_date']));
            
            $current_year = date('Y');
            $current_month = $month;
            
            $month_constant = 0;

            $match_date = '01-04-'.$current_year;

            $d1 = new \DateTime($user['joining_date']);
            $d2 = new \DateTime($match_date);
            
            // @link http://www.php.net/manual/en/class.dateinterval.php
            $interval = $d2->diff($d1);

            $month_constant = $interval->m;
            if(($joining_year == $current_year) && ($joining_month <= 04 || $joining_month <= 4)) {

                $month_constant = 4 - $joining_month;

                $yearly_salary = $user['salary'] * $month_constant;

            } elseif(($current_year - $joining_year) == 1 && ($joining_month <= 04 || $joining_month <= 4)) {

                $month_constant = 12;

                $yearly_salary = $user['salary'] * $month_constant;

            } elseif(($joining_year == $current_year) && ($joining_month > 04 || $joining_month > 4)) {
                $countable_month = ( 12 - $joining_month ) + 4;

                $month_constant = $countable_month;
                
                $yearly_salary = $user['salary'] * $month_constant;
            } elseif(($current_year - $joining_year) == 1 && ($joining_month > 04 || $joining_month > 4)) {
                $countable_month = ( 12 - $joining_month ) + 4;

                $month_constant = $countable_month;
                
                $yearly_salary = $user['salary'] * $month_constant;
            } elseif(($current_year - $joining_year) > 1 ) {
                $month_constant = 12;

                $yearly_salary = $user['salary'] * $month_constant;
            } else {
                $month_constant = 1;

                $yearly_salary = 0;
            }

            $taxable_income = $yearly_salary > 0 ? $yearly_salary - ($config['prof_tax'] * 12) - $config['std_deduction'] - $tds['80c'] - $tds['80d'] - $tds['nps'] - $tds['exmpt'] : 0;

            if($taxable_income <= 500000) {
                $tds = 0;
            } elseif($taxable_income > 500000 && $taxable_income <= 1000000) {
                $tds = 12500 + (($taxable_income - 500000) * 0.2);
            } else {
                $tds = 12500 + 100000 + (($taxable_income - 1000000) * 0.3);
            }

            $user['tax_income'] = $taxable_income ?: 0;
            $user['tds'] = $tds + ($tds * 0.04);
            $user['monthly_tds'] = 0;
            if($tds > 0){
                $user['monthly_tds'] = ($tds + ($tds * 0.04)) / $month_constant;
            }


            //--- Calculate salary of month ----
                $workdays = array();
                $weekends = array();
                $all_days = array();
                $type = CAL_GREGORIAN;

                // $month = date('m'); // Month ID, 1 through to 12.
                $year = date('Y'); // Year in 4 digit 2009 format.
                $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days

                //loop through all days
                for ($i = 1; $i <= $day_count; $i++) {

                    $date = $year . '-' . $month . '-' . $i; //format date
                    $get_name = date('l', strtotime($date)); //get week day
                    $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

                    //if not a weekend add day to array
                    if ($day_name != 'Sun' && $day_name != 'Sat') {
                        // $workdays[] = $i;
                        $workdays[] = $date;
                    }
                    if ($day_name == 'Sun' || $day_name == 'Sat') {
                        $weekends[] = $date;
                    }

                    $all_days[] = $date;

                }
                $lwp = LWP::where('user_id', $user['id'])->where('month',$month)->where('year', $year)->first();

                $per_day_salary = ( 1/sizeof($workdays)) * $user['salary'];
                //dd($user['monthly_tds']);
                $user['net_salary'] = $user['salary'] > 0 ? $user['salary'] - ( ($lwp)?$lwp['count']:0 * $per_day_salary) - $config['prof_tax'] - $user['monthly_tds'] : 0;

                $salary_status = Salary::where(['user_id' => $user['id'], 'month' => $month , 'year' => $year])->first();

                $user['status'] = isset($salary_status) ? $salary_status['status'] : 2;

                $user['present_days'] = InOut::where('month', $current_month)->where('year', $current_year)->count();
                $user['leave_balance'] = UserLeaveBalance::where('user_id', $user['id'])->value('leave_balance');
                $user['salary_month'] = date('F',strtotime($date));
            //--- End of salary ----------------

        return view('admin.tds.salary_slip', compact('user', 'current_month', 'current_year', 'workdays', 'lwp'));
    }
}
