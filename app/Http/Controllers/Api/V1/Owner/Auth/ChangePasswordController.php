<?php

namespace App\Http\Controllers\Api\V1\Owner\Auth;

use Exception;
use App\Jobs\SendEmailJob;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Response;
use App\Mail\Owner\Auth\OwnerChangePasswordEmail;
use App\Http\Requests\Api\V1\Auth\ChangePasswordRequest;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\password;

class ChangePasswordController extends BaseController
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

            $mailable = new OwnerChangePasswordEmail($user);

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
