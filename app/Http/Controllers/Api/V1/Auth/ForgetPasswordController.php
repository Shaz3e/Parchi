<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\ForgetPasswordRequest;
use App\Mail\Auth\ForgetPasswordEmail;
use App\Models\User;
use App\Services\MailService;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ForgetPasswordController extends Controller
{
    protected $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function forgetPassword(ForgetPasswordRequest $request)
    {
        try {
            // Validation
            $validated = $request->validated();

            // Check User
            $user = User::where('email', $validated['email'])->first();
            if (!$user) {
                return response()->json(['message' => 'Email not found'], 404);
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

                return response()->json(['message' => 'Email has been sent']);
            } catch (Exception $e) {
                return response()->json(['message' => $e->getMessage()]);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()]);
        }
    }
}
