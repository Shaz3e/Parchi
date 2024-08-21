<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\MailServiceController;
use App\Http\Requests\Api\V1\Auth\ForgetPasswordRequest;
use App\Mail\Auth\ForgetPasswordEmail;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class ForgetPasswordController extends MailServiceController
{

    public function __invoke(ForgetPasswordRequest $request)
    {
        try {
            // Validation
            $validated = $request->validated();

            // Check User
            $user = User::where('email', $validated['email'])->first();
            if (!$user) {
                return Response::message('This email does not exist'. 404);
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
            $mailable = new ForgetPasswordEmail($user, $token);

            try {
                $this->mailService->sendEmail($mailable, $user->email);

                return Response::success('Password reset link has been sent to your email address', $user);
            } catch (Exception $e) {
                Response::message('Failed to send Email: ' . $e->getMessage(), 500);
            }
        } catch (Exception $e) {
            Response::error($e->getMessage(), 500);
        }
    }
}
