<?php

namespace App\Http\Controllers\Admin;
use App\http\Requests\ChangePasswordRequest;
use App\Models\Admin;
use App\Models\Config;
use App\Models\UserLeaveBalance;
use App\Models\LWP;
use App\Models\InOut;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController {
	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {

		$config = Config::first();

		$users = User::pluck('name', 'id');

		return view('admin.dashboard', compact('config', 'users'));
	}

	/**
	 * Set the application config.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function config(Request $request) {
		
		if($request->submit == 'credit_balance') {
			$config = Config::first();

			$user_balances = UserLeaveBalance::get();

			foreach ($user_balances as $key => $user_balance) {
				$year = date('Y');

				$lwp = LWP::where('user_id', $user_balance['user_id'])->where('month', 1)->where('year', $year)->first();

				if($lwp) {
					$config['leave_balance'] = $config['leave_balance'] - $lwp['count']; 
					$lwp->delete();
				}

				$total_leave = $user_balance['leave_balance'] + $config['leave_balance'];

				if($total_leave > 30) {
					UserLeaveBalance::where('user_id', $user_balance['user_id'])->update([ 'leave_balance' => 30]);
				} else {
					UserLeaveBalance::where('user_id', $user_balance['user_id'])->update([ 'leave_balance' => $total_leave]);
				}
			}

			return redirect()->back()->with('message', 'Record saved successfully')
			->with('type', 'success');
		} else {
			Config::updateOrCreate(['id' => 1], $request->except('_token'));
		}

		return redirect()->back()->with('message', 'Record saved successfully')
			->with('type', 'success');
	}

	/**
	 * Show the change password form.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function getChangePassword() {
		return view('admin.change_password');
	}

	/**
	 * Update new password.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function updatePassword(ChangePasswordRequest $request) {

		$old_input = $request->get('old_password');

		$user = Admin::find(\Auth::guard('admin')->user()->id);

		if (!\Hash::check($old_input, $user->password)) {
			return back()->with('message', 'Password is incorrect')->with('type', 'warning');
		}

		$user->password = \Hash::make($request->password);
		$user->save();

		return redirect()->back()->with('message', 'Record saved successfully')
			->with('type', 'success');
	}

	public function userMispunch(Request $request) {
		$data = $request->all();
		//dd($data);
		$log = InOut::where('user_id', $data['user_id'])->where('date', $data['date'])->first();

		// dd($data['time'], trim(' ', $data['time']));

		$explode = explode(',', $data['time']);
		
		$x = [];
		foreach ($explode as $key => $value) {
			$x[] = trim($this->convertTimeToUSERzone($value,"asia/kolkata","UTC"));
		}
		// 09:18, 13:52, 14:19, 18:47
		// TO
		// "03:48","08:22","08:49","13:17"
		$log['timing'] = serialize($x);
		$log->save();

		return redirect()->back()->with('message', 'Record saved successfully')
			->with('type', 'success');
	}

	public function getUserMispunch(Request $request) {
		$data = $request->all();

		$log = InOut::where('user_id', $data['userId'])->where('date', $data['date'])->first();

		$unserialize = unserialize($log['timing']);
		//dd($unserialize);
		$newTime = array();
		foreach ($unserialize as $key => $value) {
			$newTime[] = $this->convertTimeToUSERzone($value,"UTC","asia/kolkata");
		}
		
		if($newTime && sizeof($newTime) > 0) {
			return response()->json(['code' => 200, 'data' => implode(', ', $newTime)], 200);
		}
		return response()->json(['code' => 200, 'data' => ''], 200);

	}

	//this function converts string from UTC time zone to current user timezone
	public static function convertTimeToUSERzone($time, $fromUserTimezone="UTC", $toUserTimezone="asia/kolkata", $format = 'H:i'){
	    if(empty($time)){
	        return '';
	    }
	    $tzFrom 		= new \DateTimeZone($fromUserTimezone);
		$tzTo 			= new \DateTimeZone($toUserTimezone);

		$origTime		= new \DateTime($time, $tzFrom);
		$newTime 		= $origTime->setTimezone($tzTo);

		return $newTime->format('H:i');
	}
}
