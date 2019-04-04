<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class CreateNoticeRequest extends FormRequest
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
            'content' => 'required',
            'content_chatwork' => 'required_if:push_chatwork,1',
            'push_notification' => 'required|in:' . implode(',', config('constants.notice.notification')),
            'push_chatwork' => 'required|in:' . implode(',', config('constants.notice.chatwork')),
            'status' => 'required|in:' . implode(',', config('constants.status')),
            'published_at' => 'nullable|date_format:"d/m/Y H:i"',
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
