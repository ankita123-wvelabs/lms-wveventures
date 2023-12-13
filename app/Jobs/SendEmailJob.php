<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendEmailJob implements ShouldQueue {

	use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	/**
	 * Create a new job instance.
	 *
	 * @return void
	 */
	public $view;
	public $data;

	public function __construct($view, $data) {
		$this->view = $view;
		$this->data = $data;
	}

	/**
	 * Execute the job.
	 *
	 * @return void
	 */
	/*
		    | Description : It will send Email Notification to specified user..
	*/
	public function handle() {
		$data = $this->data;

		Mail::send($this->view, ['data' => $data], function ($message) use ($data) {
			$message->to($data['email'])
				->subject("Login Credentials -" . env('APP_NAME'));
		});
	}
}
