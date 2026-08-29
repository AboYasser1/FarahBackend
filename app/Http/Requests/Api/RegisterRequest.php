<?php

namespace App\Http\Requests\Api;

class RegisterRequest extends BaseApiRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
            'user_type' => 'nullable|in:customer,provider',
            'phone' => 'nullable|string|max:45',
            'city_id' => 'nullable|exists:cities,id',
        ];
    }

    public function messages(): array
    {
        return [
            'email.email' => 'الرجاء إدخال بريد إلكتروني صالح',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل',
            'user_type.in' => 'نوع الحساب غير صالح. استخدم customer أو provider.',
            'city_id.exists' => 'المنطقة غير صالحة. اختر المدينة من القائمة.',
        ];
    }
}
