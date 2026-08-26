<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Str;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Auth", description: "إدارة الحسابات والمصادقة والملف الشخصي")]
class AuthController extends Controller
{
    #[OA\Post(
        path: "/api/login",
        summary: "تسجيل الدخول",
        tags: ["Auth"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email", "password"],
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", example: "user@example.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "password123"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "تم تسجيل الدخول بنجاح",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "icon", type: "string", example: "success"),
                        new OA\Property(property: "title", type: "string", example: "Login successful"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "token", type: "string", example: "1|laravel_sanctum_token_here"),
                                new OA\Property(property: "token_type", type: "string", example: "Bearer"),
                                new OA\Property(
                                    property: "user",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "name", type: "string", example: "محمد ياسر"),
                                        new OA\Property(property: "email", type: "string", example: "user@example.com"),
                                        new OA\Property(property: "role", type: "string", example: "customer")
                                    ]
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "بيانات الدخول غير صحيحة",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "icon", type: "string", example: "error"),
                        new OA\Property(property: "title", type: "string", example: "The email or password is incorrect.")
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: "الحساب غير مفعل أو غير مأكد",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "icon", type: "string", example: "error"),
                        new OA\Property(property: "title", type: "string", example: "This account is inactive. Please contact admin.")
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "خطأ في البيانات المدخلة",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "icon", type: "string", example: "error"),
                        new OA\Property(property: "title", type: "string", example: "الرجاء إدخال بريد إلكتروني صالح"),
                        new OA\Property(property: "errors", type: "object")
                    ]
                )
            )
        ]
    )]
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

    #[OA\Post(
        path: "/api/register",
        summary: "تسجيل حساب جديد",
        tags: ["Auth"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "email", "password", "password_confirmation"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "محمد ياسر"),
                    new OA\Property(property: "email", type: "string", format: "email", example: "user@example.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "password123"),
                    new OA\Property(property: "password_confirmation", type: "string", format: "password", example: "password123"),
                    new OA\Property(property: "user_type", type: "string", enum: ["customer", "provider"], example: "customer"),
                    new OA\Property(property: "phone", type: "string", example: "0599000000"),
                    new OA\Property(property: "city_id", type: "integer", example: 1)
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "تم إنشاء الحساب بنجاح",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "icon", type: "string", example: "success"),
                        new OA\Property(property: "title", type: "string", example: "Registration successful. Please verify your email.")
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "خطأ في البيانات المدخلة",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "icon", type: "string", example: "error"),
                        new OA\Property(property: "title", type: "string", example: "هذا البريد الإلكتروني مستخدم بالفعل"),
                        new OA\Property(property: "errors", type: "object")
                    ]
                )
            )
        ]
    )]
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

    #[OA\Post(
        path: "/api/logout",
        summary: "تسجيل الخروج",
        security: [["bearerAuth" => []]],
        tags: ["Auth"],
        responses: [
            new OA\Response(
                response: 200,
                description: "تم تسجيل الخروج بنجاح",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "icon", type: "string", example: "success"),
                        new OA\Property(property: "title", type: "string", example: "Logout successful.")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "لا يوجد رمز دخول نشط",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "icon", type: "string", example: "error"),
                        new OA\Property(property: "title", type: "string", example: "Unable to logout. No active token found.")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "غير مصرح (Unauthenticated)")
        ]
    )]
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

    #[OA\Post(
        path: "/api/resend-verification-email",
        summary: "إعادة إرسال بريد التفعيل",
        tags: ["Auth"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email"],
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", example: "user@example.com")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "تم إرسال رابط التفعيل",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "icon", type: "string", example: "success"),
                        new OA\Property(property: "title", type: "string", example: "Verification email sent.")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "البريد مفعّل مسبقاً",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "icon", type: "string", example: "error"),
                        new OA\Property(property: "title", type: "string", example: "Email is already verified.")
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "البريد غير مسجل",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "icon", type: "string", example: "error"),
                        new OA\Property(property: "title", type: "string", example: "هذا البريد غير مسجل."),
                        new OA\Property(property: "errors", type: "object")
                    ]
                )
            )
        ]
    )]
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

    #[OA\Get(
        path: "/api/profile",
        summary: "عرض الملف الشخصي",
        security: [["bearerAuth" => []]],
        tags: ["Auth"],
        responses: [
            new OA\Response(
                response: 200,
                description: "تم جلب البيانات بنجاح",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "icon", type: "string", example: "success"),
                        new OA\Property(property: "title", type: "string", example: "Profile loaded"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "name", type: "string", example: "محمد ياسر"),
                                new OA\Property(property: "phone", type: "string", example: "0599000000"),
                                new OA\Property(property: "avatar", type: "string", nullable: true, example: "/storage/avatars/avatar1.jpg"),
                                new OA\Property(property: "city_id", type: "integer", example: 1),
                                new OA\Property(
                                    property: "city",
                                    type: "object",
                                    nullable: true,
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "name", type: "string", example: "غزة")
                                    ]
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(response: 401, description: "غير مصرح (Unauthenticated)")
        ]
    )]
    public function profile(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'icon' => 'success',
            'title' => 'Profile loaded',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'avatar' => $user->avatar ? Storage::url($user->avatar) : null,
                'city_id' => $user->city_id,
                'city' => $user->city ? [
                    'id' => $user->city->id,
                    'name' => $user->city->name,
                ] : null,
            ],
        ], 200);
    }

    #[OA\Post(
        path: "/api/profile/update",
        summary: "تحديث الملف الشخصي",
        security: [["bearerAuth" => []]],
        tags: ["Auth"],
        requestBody: new OA\RequestBody(
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "name", type: "string", example: "محمد ياسر"),
                        new OA\Property(property: "phone", type: "string", example: "0599000000"),
                        new OA\Property(property: "city_id", type: "integer", example: 1),
                        new OA\Property(property: "avatar", type: "string", format: "binary")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "تم تحديث البيانات بنجاح",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "icon", type: "string", example: "success"),
                        new OA\Property(property: "title", type: "string", example: "Profile updated successfully."),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "name", type: "string", example: "محمد ياسر"),
                                new OA\Property(property: "phone", type: "string", example: "0599000000"),
                                new OA\Property(property: "avatar", type: "string", nullable: true, example: "/storage/avatars/new_avatar.jpg"),
                                new OA\Property(property: "city_id", type: "integer", example: 1),
                                new OA\Property(
                                    property: "city",
                                    type: "object",
                                    nullable: true,
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "name", type: "string", example: "غزة")
                                    ]
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(response: 401, description: "غير مصرح (Unauthenticated)"),
            new OA\Response(
                response: 422,
                description: "خطأ في البيانات المدخلة",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "icon", type: "string", example: "error"),
                        new OA\Property(property: "title", type: "string", example: "المدينة غير صالحة. اختر المدينة من القائمة."),
                        new OA\Property(property: "errors", type: "object")
                    ]
                )
            )
        ]
    )]
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:45',
            'city_id' => 'nullable|exists:cities,id',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ], [
            'city_id.exists' => 'المدينة غير صالحة. اختر المدينة من القائمة.',
            'avatar.image' => 'الملف يجب أن يكون صورة.',
            'avatar.mimes' => 'نوع الصورة غير مدعوم. استخدم jpeg أو png أو jpg أو gif أو svg.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'icon' => 'error',
                'title' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        if ($request->hasFile('avatar')) {
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($data);

        return response()->json([
            'icon' => 'success',
            'title' => 'Profile updated successfully.',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
                'avatar' => $user->avatar ? Storage::url($user->avatar) : null,
                'city_id' => $user->city_id,
                'city' => $user->city ? [
                    'id' => $user->city->id,
                    'name' => $user->city->name,
                ] : null,
            ],
        ], 200);
    }

    #[OA\Delete(
        path: "/api/account",
        summary: "حذف الحساب",
        security: [["bearerAuth" => []]],
        tags: ["Auth"],
        responses: [
            new OA\Response(
                response: 200,
                description: "تم حذف الحساب بنجاح",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "icon", type: "string", example: "success"),
                        new OA\Property(property: "title", type: "string", example: "Account deleted successfully.")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "غير مصرح (Unauthenticated)")
        ]
    )]
    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        if ($request->user()->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        $user->delete();

        return response()->json([
            'icon' => 'success',
            'title' => 'Account deleted successfully.',
        ], 200);
    }

    #[OA\Post(
        path: "/api/change-password",
        summary: "تغيير كلمة المرور",
        security: [["bearerAuth" => []]],
        tags: ["Auth"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["current_password", "new_password", "new_password_confirmation"],
                properties: [
                    new OA\Property(property: "current_password", type: "string", format: "password", example: "oldpassword123"),
                    new OA\Property(property: "new_password", type: "string", format: "password", example: "newpassword123"),
                    new OA\Property(property: "new_password_confirmation", type: "string", format: "password", example: "newpassword123")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "تم تغيير كلمة المرور بنجاح",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "icon", type: "string", example: "success"),
                        new OA\Property(property: "title", type: "string", example: "Password changed successfully.")
                    ]
                )
            ),
            new OA\Response(response: 401, description: "غير مصرح (Unauthenticated)"),
            new OA\Response(
                response: 403,
                description: "كلمة المرور الحالية غير صحيحة",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "icon", type: "string", example: "error"),
                        new OA\Property(property: "title", type: "string", example: "Current password is incorrect.")
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "خطأ في مطابقة البيانات",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "icon", type: "string", example: "error"),
                        new OA\Property(property: "title", type: "string", example: "تأكيد كلمة المرور الجديدة لا يطابق."),
                        new OA\Property(property: "errors", type: "object")
                    ]
                )
            )
        ]
    )]
    public function changePassword(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|confirmed|min:8',
        ], [
            'new_password.confirmed' => 'تأكيد كلمة المرور الجديدة لا يطابق.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'icon' => 'error',
                'title' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'icon' => 'error',
                'title' => 'Current password is incorrect.',
            ], 403);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'icon' => 'success',
            'title' => 'Password changed successfully.',
        ], 200);
    }

    #[OA\Post(
        path: "/api/forgot-password",
        summary: "طلب إعادة ضبط كلمة المرور",
        tags: ["Auth"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email"],
                properties: [
                    new OA\Property(property: "email", type: "string", format: "email", example: "user@example.com")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "تم إرسال رابط إعادة الضبط",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "icon", type: "string", example: "success"),
                        new OA\Property(property: "title", type: "string", example: "Password reset link sent.")
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "البريد الإلكتروني غير موجود",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "icon", type: "string", example: "error"),
                        new OA\Property(property: "title", type: "string", example: "هذا البريد غير مسجل."),
                        new OA\Property(property: "errors", type: "object")
                    ]
                )
            )
        ]
    )]
    public function forgotPassword(Request $request)
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

        $status = Password::sendResetLink($request->only('email'));

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'icon' => 'success',
                'title' => 'Password reset link sent.',
            ], 200);
        }

        return response()->json([
            'icon' => 'error',
            'title' => 'Unable to send password reset link.',
        ], 500);
    }

    #[OA\Post(
        path: "/api/reset-password",
        summary: "إعادة ضبط كلمة المرور باستخدام التوكين",
        tags: ["Auth"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["token", "email", "password", "password_confirmation"],
                properties: [
                    new OA\Property(property: "token", type: "string", example: "sampletoken123"),
                    new OA\Property(property: "email", type: "string", format: "email", example: "user@example.com"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "newpassword123"),
                    new OA\Property(property: "password_confirmation", type: "string", format: "password", example: "newpassword123")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "تم إعادة ضبط كلمة المرور بنجاح",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "icon", type: "string", example: "success"),
                        new OA\Property(property: "title", type: "string", example: "Password has been reset successfully.")
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "التوكين غير صالح أو البيانات غير متطابقة",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "icon", type: "string", example: "error"),
                        new OA\Property(property: "title", type: "string", example: "Unable to reset password."),
                        new OA\Property(property: "errors", type: "object")
                    ]
                )
            )
        ]
    )]
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|confirmed|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'icon' => 'error',
                'title' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'icon' => 'success',
                'title' => 'Password has been reset successfully.',
            ], 200);
        }

        return response()->json([
            'icon' => 'error',
            'title' => 'Unable to reset password.',
            'errors' => ['token' => [trans($status)]],
        ], 422);
    }
}