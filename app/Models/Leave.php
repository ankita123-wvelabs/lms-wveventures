<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model {
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'user_id', 'date', 'type', 'reason', 'type_description', 'status', 'year', 'half_day_count', 'is_lwp',
	];

	// public function getDateAttribute($value) {
	// 	return date('d-m-Y', strtotime($value));
	// }
}
