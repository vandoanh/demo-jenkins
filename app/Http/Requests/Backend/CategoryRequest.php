<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        $rules = [
            'title' => 'required|max:250',
            'code' => 'required|regex:[^[a-z0-9\-]+$]|max:250|unique:categories,code',
            'parent_id' => 'required',
            'status' => 'required|in:' . implode(',', config('constants.status')),
            'display_order' => 'nullable|integer'
        ];

        if (isset($this->id)) {
            $rules['code'] = 'required|regex:[^[a-z0-9\-]+$]|max:250|unique:categories,code,' . $this->id . ',id';
        }

        return $rules;
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
