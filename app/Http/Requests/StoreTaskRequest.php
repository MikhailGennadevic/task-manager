<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
            'priority' => 'nullable|in:high,normal,low',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Title is required',
            'user_id.exists' => 'Selected user does not exist',
            'priority.in' => 'Priority must be high, normal, or low',
        ];
    }
}