<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest {
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
				'title' => 'required | unique:projects',
				'deadline' => 'required',
				'logo' => 'required | mimes:jpeg,jpg,png',
			];
			break;

		case 'PATCH':
			return [
				'title' => 'required | unique:projects,id,:id',
				'deadline' => 'required',
				'logo' => 'required | mimes:jpeg,jpg,png',
			];
			break;

		default:
			# code...
			break;
		}
	}
}
