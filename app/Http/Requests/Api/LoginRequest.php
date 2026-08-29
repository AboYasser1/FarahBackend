<?php

namespace App\Http\Requests\Api;

class LoginRequest extends BaseApiRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'email.email' => 'الرجاء إدخال بريد إلكتروني صالح',
        ];
    }
}
