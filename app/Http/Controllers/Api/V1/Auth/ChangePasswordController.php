<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\ChangePasswordRequest;
use App\Mail\Auth\ChangePasswordEmail;
use App\Services\MailService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

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
                Response::message('Current password is incorrect', 401);
            }

            // Change Password
            $user->password = bcrypt($validated['password']);
            $user->save();

            $mailable = new ChangePasswordEmail($user);

            try {
                $this->mailService->sendEmail($mailable, $user->email);

                return Response::success('Password Changed Successfully');
            } catch (Exception $e) {
                return Response::error('Failed to send email: ' . $e->getMessage(), 500);
            }
        } catch (Exception $e) {
            return Response::error($e->getMessage(), 500);
        }
    }
}
