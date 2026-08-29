<?php

namespace App\Http\Requests\Api;

class ChangePasswordRequest extends BaseApiRequest
{
    public function rules(): array
    {
        return [
            'current_password' => 'required|string',
            'new_password' => 'required|string|confirmed|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'new_password.confirmed' => 'تأكيد كلمة المرور الجديدة لا يطابق.',
        ];
    }
}
