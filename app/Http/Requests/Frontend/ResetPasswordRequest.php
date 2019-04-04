<?php

namespace App\Http\Requests\Frontend;

use App\Library\Models\Token;
use App\Library\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $tokenInfo = Token::instance()->getTokenKey([
                'type' => config('constants.token.type.reset_password'),
                'key' => $this->token,
            ]);

            if (!$tokenInfo) {
                $validator->errors()->add('token', 'Token không đúng.');
            } else {
                //get user info by email
                $userInfo = User::instance()->getDetailUserByEmail($this->email);
                if (!$userInfo || $userInfo->id != $tokenInfo->user_id) {
                    $validator->errors()->add('email', 'Email không đúng.');
                }
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
            'email' => 'required|email|exists:users,email,status,' . config('constants.status.active'),
            'password' => 'required|password',
            'confirm_password' => 'same:password',
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
