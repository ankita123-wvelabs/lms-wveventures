<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\User;
use App\Models\InOut;
use App\Models\AuthDevice;
use App\Models\UserDevice;
use App\Models\Notification;
use Auth;
use Illuminate\Http\Request;
use Validator;
use Aws\Kinesis\KinesisClient;
use Aws\Rekognition\RekognitionClient;
use Aws\S3\S3Client;
use Illuminate\Support\Str;

class PassportController extends BaseApiController {
	/**
	 * Handles Registration Request
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function register(Request $request) {
		$this->validate($request, [
			'name' => 'required|min:3',
			'email' => 'required|email|unique:users',
			'password' => 'required|min:6',
		]);

		$user = User::create([
			'name' => $request->name,
			'email' => $request->email,
			'password' => $request->password,
		]);

		$token = $user->createToken('TutsForWeb');
		$user['token'] = $token;

		return $this->successResponse($user);
	}

	/**
	 * Handles face Registration Request
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function faceRegister(Request $request) {
		$data = $request->all();

		$user = User::where('name', $data['name'])->first();
		
		if($user) {

			$s3 = new S3Client([
			    'version' => 'latest',
			    'region'  => 'us-west-2',
			    'credentials' => [
					'key' => env('AWSAccessKeyId'),
					'secret' => env('AWSSecretKey'),
				],
			]);


			$image_one = time() . '_one' . '.' . $data['image']->getClientOriginalExtension();
			$destinationPath = public_path('/uploads');
			$data['image']->move($destinationPath, $image_one);
			
			$photo = public_path('uploads/' . $image_one);

			try {
		    	$s3->putObject([
			        'Bucket' => env('S3_BUCKET_NAME'),
			        'Key'    => Str::snake($data['name']). '.' . $data['image']->getClientOriginalExtension(),
			        'Body'   => fopen($photo, 'r'),
			        'ACL'    => 'public-read',
			    ]);

			    $options = [
					'region' => 'us-west-2',
					'version' => 'latest',
					'credentials' => [
						'key' => env('AWSAccessKeyId'),
						'secret' => env('AWSSecretKey'),
					],
				];

				$rekognition = new RekognitionClient($options);

		    	$result = $rekognition->IndexFaces(array(
					'CollectionId' => 'WveEmployees',
					'Image' => array(
						"S3Object" => array(
							"Bucket" => env('S3_BUCKET_NAME'),
							"Name" => Str::snake($data['name']).'.'.$data['image']->getClientOriginalExtension(),
						),
					),
					"ExternalImageId" => Str::snake($data['name']).'.'.$data['image']->getClientOriginalExtension(),
					"DetectionAttributes" => ["ALL", "DEFAULT"],
					"MaxFaces" => 1,
					"QualityFilter" => "AUTO",
				));

			    User::where('name', $data['name'])->update([
			    	'face_id' => $result['FaceRecords'][0]['Face']['FaceId'],
			    ]);


			    if (file_exists(public_path('uploads/' . $image_one))) {
					unlink(public_path('uploads/' . $image_one));
				}

			} catch (Aws\S3\Exception\S3Exception $e) {
			    echo "There was an error uploading the file.\n";
			}

			return $this->successResponse(new \StdClass(), "Hy ".$data['name']. ", You are successfully registered to WveLabs.", 200);
		} else {
			return $this->failureResponse(new \StdClass(), 'Unknown user detected.', 404);
		}
	}

	/**
	 * Handles User In Out Request
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	*/
	public function inOuts(Request $request) {
		$data = $request->all();

		if(!$request->image) {
			return $this->failureResponse(new \StdClass(), 'Image is required.', 400);
		}
		$auth_device = AuthDevice::where('device_id', $data['device_id'])->first();

		if($auth_device && $auth_device['status'] == 'Active') {

			$date = \Carbon\Carbon::now();
	    	
	    	// echo print_r(serialize([
	    	// 	'7:40',
	    	// 	'10:20',
	    	// 	'11:30',
	    	// 	'12:10'
	    	// ]));
	    	// exit;
			$date_array = $date->toArray();
			
			$options = [
				'region' => 'us-west-2',
				'version' => 'latest',
				'credentials' => [
					'key' => env('AWSAccessKeyId'),
					'secret' => env('AWSSecretKey'),
				],
			];

			$rekognition = new RekognitionClient($options);

			$result = $rekognition->SearchFacesByImage(array(
				'CollectionId' => 'WveEmployees',
				"Image" => array(
					"Bytes" => file_get_contents($data['image']),
					// "S3Object" => array(
					// 	"Bucket" => env('S3_BUCKET_NAME'),
					// 	"Name" => "bhavik_img.png",
					// ),
				),
				"DetectionAttributes" => ["ALL", "DEFAULT"],
				"FaceMatchThreshold" => 90,
			));

			if(sizeof($result['FaceMatches']) > 0) {

					// $faceId = $result['FaceMatches'][0]['Face']['FaceId'];
								
				foreach ($result['FaceMatches'] as $key => $faceResult) {
					
					$faceId = $faceResult['Face']['FaceId'];
					
					$match = User::where('face_id', $faceId)->first();
					
					if($match) {
						$user = $match;
					}
				}
				
				if(isset($user) && $user) {
					$in_outs = InOut::where('user_id', $user['id'])->where('year', $date_array['year'])->where('month', $date_array['month'])->where('date', date('Y-m-d'))->first();
					$time = $date_array['hour'].':'.$date_array['minute'];
					if($in_outs) {
						$unserialize = unserialize($in_outs['timing']);

						array_push($unserialize, $time);

						InOut::where('id', $in_outs['id'])->update(['timing' => serialize($unserialize)]);
					} else {
						InOut::create([
							'user_id' => $user['id'],
							'year' => $date_array['year'],
							'month' => $date_array['month'],
							'date' => date('Y-m-d'),
							'timing' => serialize(array($time))
						]);
					}
					$today = date('Y-m-d');

					$attendence = InOut::where('user_id', $user['id'])->where('date', $today)->orderBy('date', 'asc')->first();
					$attendence['timing'] = unserialize($attendence['timing']);
					if(sizeof($attendence['timing']) % 2 != 0) {
						return $this->successResponse(new \StdClass(), "Welcome to WveLabs " .$user['name'], 200);
					} else {
						return $this->successResponse(new \StdClass(), "You logged out " .$user['name'], 200);
					}
				} else {
					return $this->failureResponse(new \StdClass(), 'Unknown face detected.', 404);
				}

			} else {
				return $this->failureResponse(new \StdClass(), 'Unknown face detected.', 404);
			}
		} else {
			return $this->failureResponse(new \StdClass(), 'Unauthorized device found.', 403);
		}
	}

	/**
	 * Handles Login Request
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function login(Request $request) {
		$credentials = [
			'email' => $request->email,
			'password' => $request->password,
		];

		if (auth()->attempt($credentials)) {
			$token = auth()->user()->createToken('TutsForWeb')->accessToken;
			$user = auth()->user();
			$user['token'] = $token;

			//---Delete same device token For other user---
			UserDevice::whereNotIn('user_id', [$user['id']])->where(['device_type' => $request->get('device_type'), 'device_token' => $request->get('device_token')])->delete();
			//---End---

			$user['unread_notification'] = Notification::where([ 'receiver' => $user['id'], 'read' => 0])->count();

			User::updateOrCreate(['id' => $user['id']], ['timezone' => $request->get('timezone')]);
			//---Delete same device token For other user---
			UserDevice::whereNotIn('user_id', [$user['id']])->where(['device_type' => $request->get('device_type'), 'device_token' => $request->get('device_token')])->delete();
			//---End---
			UserDevice::updateOrCreate(['user_id' => $user['id']], ['user_id' => $user['id'], 'device_type' => $request->get('device_type'), 'device_token' => $request->get('device_token'), 'access_token' => $token]);

			return $this->successResponse($user);
		} else {
			return $this->failureResponse(new \StdClass(), 'Invalid Credentials', 400);
		}
	}

	/**
	 * Returns Authenticated User Details
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function details() {
		$user = auth()->user();

		return $this->successResponse($user, 'Profile get successfully.');
	}

	public function refreshToken(Request $request) {

	}

	public function updateProfile(Request $request) {
		$rules = [
			'image' => 'required | mimes:jpeg,jpg,png',
		];
		$message = [
			'image.mimes' => 'File format is invalid',
		];

		$validator = Validator::make($request->all(), $rules, $message);
		if ($validator->fails()) {
			return $this->failureResponse(new \StdClass(), $validator->errors()->first(), 400);
		}

		$data = User::find($this->authUser()->id);

		if ($request->hasfile('image')) {
			if ($data['image'] != '' && file_exists(public_path($data['image']))) {
				unlink(public_path($data['image']));
			}
		}

		if ($request->hasfile('image')) {

			$image = $request->file('image');
			$name = time() . '.' . $image->getClientOriginalExtension();
			$destinationPath = public_path('/uploads/profiles');
			$image->move($destinationPath, $name);
			$data['image'] = 'uploads/profiles/' . $name;
		}
		$data->save();

		$user = User::find($this->authUser()->id);
		$user['image'] = $user['image'] != '' ? $user['image'] : '';

		return $this->successResponse($user, 'Profile get successfully.');

	}

	/**
	 * Logout User
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function logout(Request $request) {
		$user = auth()->user();
		$user->token()->revoke();
		$user->token()->delete();

		return $this->successResponse(new \StdClass(), 'Logout successfully.');
	}
}
