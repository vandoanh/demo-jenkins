<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'fullname' => 'required|max:200',
            'email' => 'required|email|max:200|unique:users,email,null,id',
            'password' => 'required|password',
            'confirm_password' => 'same:password',
            'birthday' => 'nullable|date_format:"d/m/Y"',
            'gender' => 'nullable|in:' . implode(',', config('constants.user.gender')),
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }
}
