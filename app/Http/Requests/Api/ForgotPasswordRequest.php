<?php

namespace App\Http\Requests\Api;

class ForgotPasswordRequest extends BaseApiRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
        ];
    }

    public function messages(): array
    {
        return [
            'email.exists' => 'هذا البريد غير مسجل.',
        ];
    }
}
