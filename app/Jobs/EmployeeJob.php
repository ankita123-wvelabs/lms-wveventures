<?php

namespace App\Jobs;
use App\Models\Config;
use App\Models\User;
use App\Models\UserLeaveBalance;
use Aws\Kinesis\KinesisClient;
use Aws\Rekognition\RekognitionClient;
use Aws\S3\S3Client;

class EmployeeJob {
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
        | Description : It will store resource in DB if not exists else update it.
	*/
	public function handle() {
		if (!isset($this->data['id'])) {
			$this->data['id'] = null;
		}

		$config = Config::first();

		$joining_month = date('m', strtotime($this->data['joining_date']));
		$joining_year = date('Y', strtotime($this->data['joining_date']));
		$joining_day = date('d', strtotime($this->data['joining_date']));
		
		$current_year = date('Y');

		if ($joining_year != $current_year) {
			$year_count = $current_year - $joining_year;

			$leave_credit = ((12 - $joining_month) - ( (int) $joining_day <= 07 ? 2 : env('PROBATION_PERIOD'))) * (!array_key_exists('special_case', $this->data) ? env('LEAVE_PER_MONTH') : env('LEAVE_PER_MONTH_SPECIAL_CASE'));

			$leave_credit = $leave_credit + ($year_count * 12 *(!array_key_exists('special_case', $this->data) ? env('LEAVE_PER_MONTH') : env('LEAVE_PER_MONTH_SPECIAL_CASE')));
		} else {
			$leave_credit = ((12 - $joining_month) - ( (int) $joining_day <= 07 ? 2 : env('PROBATION_PERIOD'))) * (!array_key_exists('special_case', $this->data) ? env('LEAVE_PER_MONTH') : env('LEAVE_PER_MONTH_SPECIAL_CASE'));
		}
		
		$data = User::firstOrNew(['id' => $this->data['id']]);

		if (isset($this->data['image'])) {
			$image = $this->data['image'];
			$name = time() . '.' . $image->getClientOriginalExtension();

			$destinationPath = public_path('/uploads/profiles');
			$image->move($destinationPath, $name);
			$this->data['image'] = 'uploads/profiles/' . $name;
		}

		$data->fill($this->data);
		array_key_exists('special_case', $this->data) ? $data['special_case'] = 1 : $data['special_case'] = 0;
		$data->save();

		
		//if ($this->data['id'] == null) {

			$s3 = new S3Client([
			    'version' => 'latest',
			    'region'  => 'us-west-2',
			    'credentials' => [
					'key' => env('AWSAccessKeyId'),
					'secret' => env('AWSSecretKey'),
				],
			]);


			$photo = public_path($this->data['image']);

			try {
		    	$s3->putObject([
			        'Bucket' => env('S3_BUCKET_NAME'),
			        'Key'    => $name,
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
							"Name" => $name,
						),
					),
					"ExternalImageId" => $name,
					"DetectionAttributes" => [
						"DEFAULT",
					],
					"MaxFaces" => 1,
					"QualityFilter" => "AUTO",
				));

			    User::where('id', $data['id'])->update([
			    	'face_id' => $result['FaceRecords'][0]['Face']['FaceId'],
			    ]);


			 //    if (file_exists(public_path('uploads/' . $image_one))) {
				// 	unlink(public_path('uploads/' . $image_one));
				// }

			} catch (Aws\S3\Exception\S3Exception $e) {
			    echo "There was an error uploading the file.\n";
			}

			UserLeaveBalance::updateOrCreate([
				'user_id' => $data['id'],
			], [
				'user_id' => $data['id'],
				'leave_balance' => $leave_credit > 0 ? $leave_credit : 0,
			]);
		//}
	}
}
