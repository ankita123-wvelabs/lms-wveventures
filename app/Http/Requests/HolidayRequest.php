<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HolidayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
        case 'POST':
            return [
                'date' => 'required | unique:holidays',
                'info' => 'required',
            ];
            break;

        case 'PATCH':
            return [
                'date' => 'required | unique:holidays,id,:id',
                'name' => 'required',
            ];
            break;

        default:
            # code...
            break;
        }
    }
}
