<?php

namespace App\Http\Requests\Api;

class ResetPasswordRequest extends BaseApiRequest
{
    public function rules(): array
    {
        return [
            'token' => 'required|string',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|confirmed|min:8',
        ];
    }
}
