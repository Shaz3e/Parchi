<?php

namespace App\Http\Controllers\Api\Tenant\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Tenant\Auth\ForgetPasswordRequest;
use App\Jobs\SendEmailJob;
use App\Mail\Tenant\Auth\ForgotPasswordEmail;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

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

            // Create mailable data
            $mailData = [
                'name' => $user->name,
                'email' => $user->email,
                'token' => $token,
                'url' => url('/api/reset-password/' . $user->email . '/' . $token),
            ];

            $mailable = new ForgotPasswordEmail($mailData);

            try {
                SendEmailJob::dispatch($mailable, $user->email);

                return Response::success('Password reset link has been sent to your email address', $user);
            } catch (Exception $e) {
                return Response::message('Failed to send Email: ' . $e->getMessage(), 500);
            }
        } catch (Exception $e) {
            return Response::error($e->getMessage(), 500);
        }
    }
}
