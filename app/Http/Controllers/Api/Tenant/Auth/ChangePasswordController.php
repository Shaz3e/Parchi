<?php

namespace App\Http\Controllers\Api\Tenant\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Tenant\Auth\ChangePasswordRequest;
use App\Jobs\SendEmailJob;
use App\Mail\Tenant\Auth\ChangePasswordEmail;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class ForgetPasswordController extends BaseController
{
    public function __invoke(ChangePasswordRequest $request)
    {
        try {
            // validation
            $validated = $request->validated();

            // Check current password
            $user = auth()->user();
            if (!Hash::check($request->current_password, $user->password)) {
                return Response::message('Current password is incorrect', 401);
            }

            // Change password
            $user->password = $validated['password'];
            $user->save();

            $mailable = new ChangePasswordEmail($user);

            try {
                SendEmailJob::dispatch($mailable, $user->email);

                return Response::success('Password Changed Successfully', $user);
            } catch (Exception $e) {
                return Response::error('Failed to send email: ' . $e->getMessage(), 500);
            }
        } catch (Exception $e) {
            return Response::error($e->getMessage(), 500);
        }
    }
}
