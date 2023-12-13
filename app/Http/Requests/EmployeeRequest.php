<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmployeeRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		switch ($this->method()) {
		case 'POST':
			return [
				'name' => 'required',
				'email' => 'required | unique:users',
				'password' => 'required | min:6',
				'joining_date' => 'date',
				'dob' => 'date',
				'image' => 'required | mimes:jpeg,jpg,png',
			];
			break;

		case 'PATCH':
			return [
				'name' => 'required',
				'email' => 'required | unique:users,id,:id',
				'joining_date' => 'date',
				'dob' => 'date',
			];
			break;

		default:
			# code...
			break;
		}
	}
}
