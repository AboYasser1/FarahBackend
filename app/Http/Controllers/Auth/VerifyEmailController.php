<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    /**
     * Mark the user's email as verified.
     * Works without a web session — relies only on the signed URL (id + hash).
     */
    public function __invoke(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json([
                'icon' => 'error',
                'title' => 'رابط التحقق غير صالح.',
            ], 403);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                'icon' => 'success',
                'title' => 'تم التحقق من البريد الإلكتروني مسبقًا. يمكنك تسجيل الدخول الآن.',
            ], 200);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return response()->json([
            'icon' => 'success',
            'title' => 'تم التحقق من البريد الإلكتروني بنجاح. يمكنك تسجيل الدخول الآن.',
        ], 200);
    }
}