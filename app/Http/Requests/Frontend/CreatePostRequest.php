<?php

namespace App\Http\Requests\Frontend;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
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
            'title' => 'required|max:250',
            'code' => 'required|regex:[^[a-z0-9\-]+$]|max:250|unique:posts,code',
            'content' => 'required',
            'status' => 'required|in:' . implode(',', config('constants.status')),
            'description' => 'required|max:1000',
            'category_id' => 'required|exists:categories,id,status,' . config('constants.status.active'),
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
