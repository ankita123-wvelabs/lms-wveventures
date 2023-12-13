<?php

namespace App\Jobs;

use App\Models\Project;
use App\Models\UserDevice;
use App\Models\User;
use App\Models\Notification;
use App\Library\Push\PushNotification;


class ProjectJob {
	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public $data;
	public function __construct($data) {
		$this->data = $data;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	/*
		Description : It will store resource in DB if not exists else update it.
	*/
	public function handle() {
		if (!isset($this->data['id'])) {
			$this->data['id'] = null;
		}

		$data = Project::firstOrNew(['id' => $this->data['id']]);

		$old_user = $data['user_ids'] ? unserialize($data['user_ids']) : [];

		$old_image = $data['logo'];

		if (isset($this->data['logo'])) {
			$logo = $this->data['logo'];
			$name = time() . '.' . $logo->getClientOriginalExtension();

			$destinationPath = public_path('/uploads/projects/logo');
			$logo->move($destinationPath, $name);
			$this->data['logo'] = 'uploads/projects/logo/' . $name;
		}

		if (!isset($this->data['logo'])) {
			$this->data['logo'] = $old_image;
		}

		$data->fill($this->data);

		$data['user_ids'] = serialize($this->data['user_ids']);

		$data->save();

		if($data) {
			foreach ($this->data['user_ids'] as $key => $userId) {
				if(!in_array($userId, $old_user) && sizeof($old_user) < sizeof($this->data['user_ids'])) {

					$connectedDevices = UserDevice::where('user_id', $userId)->get();

					$user = User::find($userId);

		            $payload = [
		                'type' => 'New Project',
		                'title' => 'LMS',
		                'description' => 'You have been added to a Project - ' . $data['title'],
		                'receiver' => $userId,
		            ];

		            if($connectedDevices) {
			            foreach ($connectedDevices as $key => $value) {
			                if(isset($value['device_token']) && strlen($value['device_token']) > 4 && $value['device_type'] == 'iOS')
			                {
			                    PushNotification::iOS($payload, $value['device_token']);
			                }

			                if(isset($value['device_token']) && strlen($value['device_token']) > 4 && $value['device_type'] == 'android')
			                {
			                    PushNotification::android($payload, $value['device_token']);
			                }
			            }
			        }

		            Notification::create($payload);
				}
			}
		}

	}
}
