<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserDevice;
use App\Models\Notification;
use App\Models\InOut;
use App\Library\Push\PushNotification;

class DailyHourCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hour:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check user daily hour, if it is less then 6 then send him a notification.';

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
        
        foreach ($users as $key => $user) {
            $today = date('Y-m-d');

            $date_array = \Carbon\Carbon::now()->toArray();
            
            $attendence = InOut::where('user_id', $user['id'])->where('date', $today)->orderBy('date', 'asc')->first();
            
            $attendence['timing'] = unserialize($attendence['timing']);

            $counter = 1;
            $total_time = 0;

            if($attendence['timing']) {
                foreach ($attendence['timing'] as $key => $value) {
                    
                    if($counter % 2 != 0) {
                        if(array_key_exists($key + 1, $attendence['timing'])) {
                            $time = strtotime($attendence['timing'][$key + 1]) - strtotime($attendence['timing'][$key]);
                        } else {
                            $time = strtotime($date_array['hour'].':'.$date_array['minute']) - strtotime($attendence['timing'][$key]);
                        }
                        $total_time += $time;
                    }
                    $counter++;
                }
                
                $present_time = date('H:i', $total_time);
                
                //--- Due hours -----
                $connectedDevice = UserDevice::where('user_id', $user['id'])->orderBy('id', 'desc')->first();

                if($present_time < '06:00' || $present_time < '6:00' && $present_time > '00:00') {

                    $payload = [
                        'type' => 'Timing',
                        'title' => 'Timing',
                        'description' => 'Today will be considered as a half-day.',
                        'receiver' => $user['id'],
                    ];

                    if($connectedDevice) {
                        // foreach ($connectedDevices as $key => $value) {
                            if(isset($connectedDevice['device_token']) && strlen($connectedDevice['device_token']) > 4 && $connectedDevice['device_type'] == 'iOS')
                            {
                                // try {
                                //     PushNotification::iOS($payload, $connectedDevice['device_token']);
                                // } catch (Exception $e) {
                                //     \Log::info($e);                                
                                // }
                            }

                            if(isset($connectedDevice['device_token']) && strlen($connectedDevice['device_token']) > 4 && $connectedDevice['device_type'] == 'android')
                            {
                                PushNotification::android($payload, $connectedDevice['device_token']);
                            }
                        // }
                    }

                    Notification::create($payload);
                }
                
                //--- Mispunch Notification -----

                if( sizeof($attendence['timing']) % 2 != 0 ) {
                    $mispunch_payload = [
                        'type' => 'Mispunch',
                        'title' => 'Mispunch',
                        'description' => 'You have a mis-punch today.',
                        'receiver' => $user['id'],
                    ];

                    if($connectedDevice) {
                        // foreach ($connectedDevices as $key => $value) {
                            // if(isset($connectedDevice['device_token']) && strlen($connectedDevice['device_token']) > 4 && $connectedDevice['device_type'] == 'iOS')
                            // {
                            //     PushNotification::iOS($mispunch_payload, $connectedDevice['device_token']);
                            // }

                            if(isset($connectedDevice['device_token']) && strlen($connectedDevice['device_token']) > 4 && $connectedDevice['device_type'] == 'android')
                            {
                                PushNotification::android($mispunch_payload, $connectedDevice['device_token']);
                            }
                        // }
                    }

                    Notification::create($mispunch_payload);
                } 
            } 
        }
    }
}
