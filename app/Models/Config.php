<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model {
	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'leave_balance', 'prof_tax', 'std_deduction'
	];
}
