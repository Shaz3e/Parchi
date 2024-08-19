<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\ChangePasswordRequest;
use App\Mail\Auth\ChangePasswordEmail;
use App\Services\MailService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChangePasswordController extends Controller
{
    protected $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }
    public function __invoke(ChangePasswordRequest $request)
    {
        try {
            // validation
            $validated = $request->validated();

            // Check current password
            $user = auth()->user();
            if (!password_verify($validated['current_password'], $user->password)) {
                return response()->json(['message' => 'Current password is incorrect'], 401);
            }

            // Change Password
            $user->password = bcrypt($validated['password']);
            $user->save();

            $mailable = new ChangePasswordEmail($user);

            try {
                $this->mailService->sendEmail($mailable, $user->email);

                return response()->json(['message' => 'Password changed successfully'], 200);
            } catch (Exception $e) {
                return response()->json(['message' => $e->getMessage()]);
            }
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
