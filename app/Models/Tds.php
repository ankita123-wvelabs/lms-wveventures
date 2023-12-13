<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tds extends Model
{
    /**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'user_id', 'exmpt', '80c', '80d', 'nps', '80c_proof', '80d_proof', 'nps_proof', 'exmpt_proof'
	];
}
