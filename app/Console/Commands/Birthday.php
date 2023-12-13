<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserDevice;
use App\Models\Notification;
use App\Library\Push\PushNotification;

class Birthday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'birthday:celebrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Employee birthday reminder.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $users = User::all();
        
        $today = \Carbon\Carbon::now()->format('m-d');

        foreach ($users as $key => $user) {
            
            if(date('m-d', strtotime($user['dob'])) == $today) {
                $birthday_users[$user['id']] = $user['name'];        
            }
        }
        
        if(isset($birthday_users) && sizeof($birthday_users) > 0) {

            $except_user_ids = array_keys($birthday_users);
            $user_names = array_values($birthday_users);

            $user_devices = UserDevice::whereNotIn('id', $except_user_ids)->get();
            
            if($user_devices) {
               foreach ($user_devices as $key => $value) {

                    $payload = [
                        'type' => 'Birthday Celebration',
                        'title' => 'Birthday Celebration',
                        'description' => sizeof($user_names) == 1 ? $user_names[0] . ' is celebrating a birthday today!' :implode(', ', $user_names) . ' are celebrating a birthday today!',
                        'receiver' => $value['user_id'],
                    ];

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
        }
    }
}
