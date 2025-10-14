<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskStatusRequest extends FormRequest
{
    public function rules()
    {
        return [
            'status' => 'required|in:new,in_progress,completed,cancelled',
            'user_id' => 'required|exists:users,id',
        ];
    }
}