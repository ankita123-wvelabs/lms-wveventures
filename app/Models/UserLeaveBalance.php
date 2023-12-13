<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLeaveBalance extends Model {
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'user_id', 'leave_balance',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'created_at', 'updated_at',
	];
}
