<?php

namespace App\Http\Requests\Frontend;

use Hash;
use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
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
     * Get data to be validated from the request.
     *
     * @return array
     */
    protected function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!Hash::check($validator->getData()['old_password'], auth()->user()->getAuthPassword())) {
                $validator->errors()->add('old_password', 'Your old password is invalid.');
            }
        });
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'old_password' => 'required|password',
            'new_password' => 'required|password|different:old_password',
            'password_confirm' => 'required|same:new_password',
        ];
    }

    /**
     * Get the message that apply to the check validate form.
     *
     * @return array
     */

    public function messages()
    {
        return [];
    }
}
