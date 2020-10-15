<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        return [
            'name' => 'required|min:3',
            'gender' => '',
            'birthday' => 'required|date',
            'id_number' => 'required|numeric|min:9|unique:users',
            'id_date' => 'required|date',
            'permanent_residence' => 'required',
            'staying_address' => 'required',
            'job' => 'required',
            'company' => 'required',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:8',
            'role_id' => '',
            'avatar' => 'sometimes|image',
        ];
    }

    public function messages()
    {
        return [
            'name.min' => 'Tên tối thiểu là 3 ký tự',
            'id_number.unique' => 'CMND đã tồn tại',
            'id_number.numeric' => 'CMND phải là một số ',
            'id_number.min' => 'CMND tối thiểu có 9 số ',
            'id_number.max' => 'CMND tối đa có 12 số',
        ];
    }
}
