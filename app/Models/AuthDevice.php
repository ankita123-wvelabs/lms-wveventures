<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthDevice extends Model
{
    /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'device_id', 'device_name', 'device_app_version_code', 'device_app_version_name', 'device_wifi_mac_address', 'device_bluetooth_mac_address', 
		'location', 'status'
	];
}
