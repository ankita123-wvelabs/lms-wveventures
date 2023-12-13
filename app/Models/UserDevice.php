<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model {
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'device_token', 'device_type', 'user_id', 'access_token',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'created_at', 'updated_at', 'access_token',
	];
}
