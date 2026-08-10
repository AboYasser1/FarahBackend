<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function apilogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.email' => 'الرجاء إدخال بريد إلكتروني صالح',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'icon' => 'error',
                'title' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'icon' => 'error',
                'title' => 'The email or password is incorrect.',
            ], 401);
        }

        if ($user->status === 'inactive') {
            return response()->json([
                'icon' => 'error',
                'title' => 'This account is inactive. Please contact admin.',
            ], 403);
        }

        if (! $user->hasVerifiedEmail()) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Please verify your email before logging in.',
            ], 403);
        }

        $token = $user->createToken('api_Token')->plainTextToken;

        return response()->json([
            'icon' => 'success',
            'title' => 'Login successful',
            'data' => [
                'token' => $token,
                'token_type' => 'Bearer',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->user_type ?? 'customer',
                ],
            ],
        ], 200);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|confirmed|min:8',
            'user_type' => 'nullable|in:customer,provider',
            'phone' => 'nullable|string|max:45',
            'city_id' => 'nullable|exists:cities,id',
        ], [
            'email.email' => 'الرجاء إدخال بريد إلكتروني صالح',
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل',
            'user_type.in' => 'نوع الحساب غير صالح. استخدم customer أو provider.',
            'city_id.exists' => 'المنطقة غير صالحة. اختر المدينة من القائمة.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'icon' => 'error',
                'title' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type ?? 'customer',
            'phone' => $request->phone,
            'city_id' => $request->city_id,
            'status' => 'active',
        ]);

        $user->sendEmailVerificationNotification();

        return response()->json([
            'icon' => 'success',
            'title' => 'Registration successful. Please verify your email.',
        ], 201);
    }

    public function logout(Request $request)
    {
        $user = $request->user();

        if (! $user || ! $request->user()->currentAccessToken()) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Unable to logout. No active token found.',
            ], 400);
        }

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'icon' => 'success',
            'title' => 'Logout successful.',
        ], 200);
    }

    public function resendVerificationEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'هذا البريد غير مسجل.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'icon' => 'error',
                'title' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Email is already verified.',
            ], 400);
        }

        $user->sendEmailVerificationNotification();

        return response()->json([
            'icon' => 'success',
            'title' => 'Verification email sent.',
        ], 200);
    }
}