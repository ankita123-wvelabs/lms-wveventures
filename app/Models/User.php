<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable {
	use HasApiTokens, Notifiable;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'image', 'password', 'emp_id', 'position', 'reporting_manager', 'joining_date', 'dob', 'salary',
		'phone', 'address', 'pan', 'bank_name', 'account_holder', 'account_number', 'base64', 'spacial_case'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	public function setPasswordAttribute($value) {
		$this->attributes['password'] = \Hash::make($value);
	}

	public function getImageAttribute($value) {
		return $value != '' ? env('APP_URL') . $value : '';
	}

	public function getDobAttribute($value) {
		return date('d-m-Y', strtotime($value));
	}

	public function setDobAttribute($value) {
		$this->attributes['dob'] = date('Y-m-d', strtotime($value));
	}

	public function setJoiningDateAttribute($value) {
		$this->attributes['joining_date'] = date('Y-m-d', strtotime($value));
	}

	public function getJoiningDateAttribute($value) {
		return date('d-m-Y', strtotime($value));
	}

	public function getLeaves() {
		return $this->hasMany('App\Models\Leave')->where('year', date('y'));
	}

	public function getDevice() {
		return $this->hasOne('App\Models\UserDevice');
	}

	public function setBankNameAttribute($value) {
		$this->attributes['bank_name'] = ucwords($value);
	}

	public function setAccountHolderAttribute($value) {
		$this->attributes['account_holder'] = ucwords($value);
	}
}
