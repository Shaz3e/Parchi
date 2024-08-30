<?php

namespace App\Http\Controllers\Api\Owner\Auth;

use Exception;
use App\Models\User;
use App\Jobs\SendEmailJob;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Owner\Auth\ForgetPasswordRequest;
use Illuminate\Support\Facades\Response;
use App\Mail\Owner\Auth\OwnerForgotPasswordEmail;

class ForgetPasswordController extends BaseController
{

    public function __invoke(ForgetPasswordRequest $request)
    {
        try {
            // Validation
            $validated = $request->validated();

            // Check User
            $user = User::where('email', $validated['email'])->first();
            if (!$user) {
                return Response::message('This email does not exist' . 404);
            }

            // if token exists delete
            DB::table('password_reset_tokens')->where('email', $validated['email'])->delete();

            // Create Token
            $token = Str::random(255);

            // Add new token
            DB::table('password_reset_tokens')->insert([
                'email' => $user->email,
                'token' => $token,
                'created_at' => now(),
            ]);

            $mailData = [
                'name' => $user->name,
                'email' => $user->email,
                'token' => $token,
                'url' => url('/api/v1/reset-password/' . $user->email . '/' . $token),
            ];

            // Create mailable data
            $mailable = new OwnerForgotPasswordEmail($mailData);

            try {
                SendEmailJob::dispatch($mailable, $user->email);

                return Response::success('Password reset link has been sent to your email address', $user);
            } catch (Exception $e) {
                Response::message('Failed to send Email: ' . $e->getMessage(), 500);
            }
        } catch (Exception $e) {
            Response::error($e->getMessage(), 500);
        }
    }
}
