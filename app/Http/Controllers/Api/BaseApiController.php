<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Library\Push\PushNotification;

class BaseApiController extends Controller {

	/**
	 * default status code
	 *
	 * @var integer
	 */
	protected $statusCode = 200;

	/**
	 * get the leave balance
	 *
	 * @return statuscode
	 */

	public function getLeaveBalance() {
		return Config::value('leave_balance');
	}

	/**
	 * get the status code
	 *
	 * @return statuscode
	 */

	public function getStatusCode() {
		return $this->statusCode;
	}

	/**
	 * Returns Authenticated User Details
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function authUser() {

		return auth()->user();
	}

	/**
	 * Success Response
	 *
	 * @param array $data
	 * @param string $message
	 * @param int $code
	 * @return json|string
	 */
	public function successResponse($data = array(), $message = 'Success', $code = 200) {
		$response = [
			'data' => $data,
			'message' => $message ? $message : 'Success',
			'code' => $code ? $code : $this->getStatusCode(),
		];

		return response()->json(
			(object) $response,
			$this->getStatusCode()
		);
	}

	/**
	 * Failure Response
	 *
	 * @param array $data
	 * @param string $message
	 * @param int $code
	 * @return json|string
	 */
	public function failureResponse($data = array(), $message = 'Failure', $code = null) {
		$response = [
			'error' => $data,
			'message' => $message ? $message : 'Failure',
			'code' => $code ? $code : $this->getStatusCode(),
		];

		return response()->json(
			(object) $response,
			$this->getStatusCode()
		);
	}

	public function sendNotification($payload, $connectedDevices) 
    {
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
        return;
    }
}
