<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaskCommentRequest extends FormRequest
{
    public function rules()
    {
        return [
            'comment' => 'required|string',
            'user_id' => 'required|exists:users,id',
        ];
    }
}