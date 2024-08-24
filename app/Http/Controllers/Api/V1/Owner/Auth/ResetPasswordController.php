<?php

namespace App\Http\Controllers\Api\V1\Owner\Auth;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Response;

class ResetPasswordController extends BaseController
{
    public function __invoke(Request $request, $email, $token)
    {
        try {
            // Check Token
            $tokenExists = DB::table('password_reset_tokens')
                ->where('email', $email)
                ->where('token', $token)
                ->exists();

            if (!$tokenExists) {
                return Response::message('Invalid password reset link');
            }

            // Validation
            $validated = $request->validate([
                'password' => 'required|string|min:8',
                'confirm_password' => 'required|same:password',
            ]);

            // Reset Password
            $user = User::where('email', $email)->first();
            if (!$user) {
                return Response::error('User not found', 404);
            }

            $user->password = bcrypt($validated['password']);
            $user->save();

            // Delete token
            DB::table('password_reset_tokens')
                ->where('email', $email)
                ->delete();

            return Response::message('Password reset successfully');
        } catch (Exception $e) {
            return Response::error($e->getMessage(), 500);
        }
    }
}
