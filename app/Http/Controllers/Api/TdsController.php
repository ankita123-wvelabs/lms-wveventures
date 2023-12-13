<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\BaseApiController;
use App\Models\Tds;
use App\Models\User;
use App\Models\Config;
use App\Models\LWP;
use App\Models\InOut;
use App\Models\UserLeaveBalance;
use App\Models\Salary;

class TdsController extends BaseApiController
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    	$data = $request->all();

    	$data['user_id'] = $this->authUser()->id;

    	if(isset($data['80c_proof'])) {
    		$eighty_c = $data['80c_proof'];
			$eighty_c_name = time() .'_80c_proof_'. '.' . $eighty_c->getClientOriginalExtension();

			$destinationPath = public_path('/uploads/tds_record');
			$eighty_c->move($destinationPath, $eighty_c_name);
			$data['80c_proof'] = 'uploads/tds_record/' . $eighty_c_name;
    	}

    	if(isset($data['80d_proof'])) {
    		$eighty_d = $data['80d_proof'];
			$eighty_d_name = time() .'_80d_proof_'. '.' . $eighty_d->getClientOriginalExtension();

			$destinationPath = public_path('/uploads/tds_record');
			$eighty_d->move($destinationPath, $eighty_d_name);
			$data['80d_proof'] = 'uploads/tds_record/' . $eighty_d_name;
    	}

    	if(isset($data['nps_proof'])) {
    		$nps = $data['nps_proof'];
			$nps_name = time() .'_nps_proof_'.'.' . $nps->getClientOriginalExtension();

			$destinationPath = public_path('/uploads/tds_record');
			$nps->move($destinationPath, $nps_name);
			$data['nps_proof'] = 'uploads/tds_record/' . $nps_name;
    	}

    	if(isset($data['exmpt_proof'])) {
    		$exmpt = $data['exmpt_proof'];
			$exmpt_name = time() .'_exmpt_proof_'. '.' . $exmpt->getClientOriginalExtension();

			$destinationPath = public_path('/uploads/tds_record');
			$exmpt->move($destinationPath, $exmpt_name);
			$data['exmpt_proof'] = 'uploads/tds_record/' . $exmpt_name;
    	}
    	
    	$tds = Tds::firstOrNew(['user_id' => $this->authUser()->id]);
    	$tds->fill($data);
    	$tds->save();
        
        return $this->successResponse($tds, '', 200);
    }

    public function salary(Request $request)
    {
        $userId = $this->authUser()->id;

        $salaries = Salary::where('user_id', $userId)->where('status', 1)->orderBy('id', 'desc')->get();

        return $this->successResponse($salaries, '', 200);
    }

    public function mailSalarySlip(Request $request)
    {
        $userId = $this->authUser()->id;

        $salary = Salary::find($request->id);

        $user = User::find($userId);

        if($salary['user_id'] == $userId) {
            $config = Config::first();

            $user['tds'] = 0;
            $tds = Tds::where('user_id', $user['id'])->first();
            $joining_month = date('m', strtotime($user['joining_date']));
            $joining_year = date('Y', strtotime($user['joining_date']));
            
            $current_year = $salary['year'];
            $current_month = $salary['month'];
            
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

                // $month = date('m'); // Month ID, 1 through to 12.
                $year = date('Y'); // Year in 4 digit 2009 format.
                $day_count = cal_days_in_month($type, $salary['month'], $year); // Get the amount of days

                //loop through all days
                for ($i = 1; $i <= $day_count; $i++) {

                    $date = $year . '-' . $salary['month'] . '-' . $i; //format date
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
                $lwp = LWP::where('user_id', $user['id'])->where('month', $salary['month'])->where('year', $year)->first();
                $per_day_salary = ( 1/sizeof($workdays)) * $user['salary'];

                // $user['net_salary'] = $user['salary'] > 0 ? $user['salary'] - ($lwp['count'] * $per_day_salary) - $config['prof_tax'] - $user['monthly_tds'] : 0;

                $user['net_salary'] = $salary['amount'];

                $salary_status = Salary::where(['user_id' => $user['id'], 'month' => $salary['month'] , 'year' => $year])->first();

                $user['status'] = isset($salary_status) ? $salary_status['status'] : 2;

                $user['present_days'] = InOut::where('month', $current_month)->where('year', $current_year)->count();
                $user['leave_balance'] = UserLeaveBalance::where('user_id', $user['id'])->value('leave_balance');
                $user['salary_month'] = date('F',strtotime($date));

                $timestamp = time();
                $pdf = \PDF::loadView('admin.tds.salary_slip', compact('user', 'current_month', 'current_year', 'workdays', 'lwp' ))->setPaper('a4', 'landscape')->setWarnings(false);
                $pdf->save(public_path('uploads/salary/'.$timestamp.'.pdf'));
                
                \Mail::send('emails.salary_slip',[],function ($message) use ($user,$timestamp) {
                    $message->to($user['email'])
                    ->subject("Salary Slip -" . $user['salary_month'])->attach(public_path('uploads/salary/'.$timestamp.'.pdf'));
                });

                chmod(public_path('uploads/salary/'.$timestamp.'.pdf'), 777);
                if(file_exists(public_path('uploads/salary/'.$timestamp.'.pdf'))) {
                    unlink(public_path('uploads/salary/'.$timestamp.'.pdf'));
                }

            return $this->successResponse(new \StdClass(), 'Please check your inbox.', 200);

        } else {
            
            return $this->failureResponse(new \StdClass(), 'unauthorize', 403);
        }
    }
}
