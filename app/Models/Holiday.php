<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Holiday extends Model
{
    /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'date', 'info', 'image'
	];

	public function getDateAttribute($value) {
		return date('d-m-Y', strtotime($value));
	}

	public function setDateAttribute($value) {
		$this->attributes['date'] = date('Y-m-d', strtotime($value));
	}
}
