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
}