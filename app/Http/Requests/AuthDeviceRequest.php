<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuthDeviceRequest extends FormRequest
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
                'device_id' => 'required | unique:auth_devices',
                'device_name' => 'required',
            ];
            break;

        case 'PATCH':
            return [
                'device_id' => 'required | unique:auth_devices,id,:id',
                'device_name' => 'required',
            ];
            break;

        default:
            # code...
            break;
        }
    }
}
