<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InOut extends Model
{
    /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'user_id', 'year', 'month', 'date', 'timing'
	];

}
