<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\Leave;
use App\Models\LWP;
use App\Models\Project;
use App\Models\User;
use App\Models\FeedBack;
use App\Models\Holiday;
use App\Models\UserLeaveBalance;
use Illuminate\Http\Request;

class HomeController extends BaseApiController {
	/**
	 * get the leave history
	 *
	 * @return statuscode
	 */
	public function index(Request $request) {

		$leaves_used = Leave::where('user_id', $this->authUser()->id)->where('status', 'Approve')->where('year', date('Y'))->count();

		$leaves = Leave::where('user_id', $this->authUser()->id)->where('year', date('Y'))->orderBy('id', 'desc')->take(3)->get();

		$data['leaves'] = [];

		foreach ($leaves as $leave) {
			$days = sizeof(explode(',', $leave['date']));
			$range = explode(',', $leave['date']);
			$last_date = end($range);
			if (strtotime($last_date) <= strtotime(date('d-m-Y'))) {
				if($leave['status'] == 'Approved') {
					$leave['status'] = 'Consumed';
				}
			}
			$leave['date'] = sizeof($range) > 1 ? $range[0] . ' - ' . end($range) : $range[0];
			$leave['days'] = $days - ($leave['half_day_count'] * 0.5);
			$data['leaves'][] = $leave;
		}

		// $data['remaining_leaves'] = $this->getLeaveBalance() - $leaves_used;
		$data['remaining_leaves'] = UserLeaveBalance::where('user_id', $this->authUser()->id)->value('leave_balance');

		$data['lwp'] = LWP::where('user_id', $this->authUser()->id)->where('year', date('Y'))->value('count');

		$projects = Project::whereIn('status', ['Design', 'Development', 'QA', 'Integration'])->get();

		$data['projects'] = [];

		foreach ($projects as $key => $project) {
			$unserialize = unserialize($project['user_ids']);

			if (in_array($this->authUser()->id, $unserialize)) {
				$data['projects'][] = $project;
			}
		}

		return $this->successResponse($data, '', 200);
	}

	/**
	 * get my coilleague list
	 *
	 * @return statuscode
	 */
	public function colleague(Request $request) {
		$data = User::whereNotIn('id', [$this->authUser()->id])->orderBy('name', 'asc')->get();

		return $this->successResponse($data, '', 200);
	}

	/**
	 * get my projects
	 *
	 * @return statuscode
	 */
	public function projects(Request $request) {

		$projects = Project::orderBy('title', 'asc')->get();
		$data['projects'] = [];
		foreach ($projects as $key => $project) {
			$project['logo'] = $project['logo'] != '' ? env('APP_URL') . $project['logo'] : '';
			$unserialize = unserialize($project['user_ids']);
			$users = User::whereIn('id', $unserialize)->get();

			$project['users'] = '';

			foreach ($users as $key => $user) {
				$project['users'] = $project['users'] != '' ? $project['users'] . ',' . $user['name'] : $user['name'];
			}

			if (in_array($this->authUser()->id, $unserialize)) {
				$data['projects'][] = $project;
			}
		}

		return $this->successResponse($data, '', 200);
	}

	/**
	 * submit feedback
	 *
	 * @return statuscode
	 */
	public function feedback(Request $request) {

		$data = $request->all();

		$rules = [
			'subject' => 'required',
			'description' => 'required',
		];
		// $message = [
		// 	'reason.required' => 'Title field is missing.',
		// ];

		$validator = \Validator::make($request->all(), $rules);

		if ($validator->fails()) {
			return $this->failureResponse(new \StdClass(), $validator->errors()->first(), 400);
		}

		if(isset($data['image'])) {
            $image = $data['image'];
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/uploads/feedback');
            $image->move($destinationPath, $name);
            $data['image'] = 'uploads/feedback/'.$name;
        }

        $data['user_id'] = $this->authUser()->id;

		FeedBack::create($data);

		return $this->successResponse(new \StdClass(), 'Feedback Submitted successfully.', 200);
	}

	public function holidays(Request $request) {
		$data = Holiday::orderBy('date', 'asc')->get();

		foreach ($data as $key => $value) {
			$value['image'] = env('APP_URL').$value['image'];
		}

		return $this->successResponse($data, '', 200);
	}
}
