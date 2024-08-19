<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ResetPasswordController extends BaseController
{
    public function __invoke(Request $request)
    {
        try {
            // Check token
            $tokenExists = DB::table('password_reset_tokens')
                ->where([
                    'email' => $request->email,
                    'token' => $request->token
                ])
                ->exists();

            if (!$tokenExists) {
                Response::message('Password reset link is expired, please reset your password again.');
            }

            // When token and email are valid reset password
            $user = User::where('email', $request->email)->first();

            $validated = $request->validate([
                'password' => 'required|string|max:255',
                'confirm_password' => 'required|same:password|string|max:255',
            ]);

            // Change Password
            $user->password = bcrypt($validated['password']);
            $user->save();

            // Delete the token from database
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            return Response::success("Password has been reset successfully. You can now login");
        } catch (Exception $e) {
            Response::error($e->getMessage(), 500);
        }
    }
}
