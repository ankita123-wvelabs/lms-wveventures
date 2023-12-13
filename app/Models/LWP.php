<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LWP extends Model {
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'user_id', 'count', 'year', 'month'
	];
}
