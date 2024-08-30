<?php

namespace App\Http\Controllers\Api\Owner\Auth;

use Exception;
use App\Models\User;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Owner\Auth\LoginRequest;
use App\Http\Resources\Api\Owner\Auth\LoginResource;
use Illuminate\Support\Facades\Response;

class LoginController extends BaseController
{
    public function __invoke(LoginRequest $request)
    {
        try {
            // Validation
            $validated = $request->validated();

            // Login
            $credentials = $request->only(['email', 'password']);
            if (!auth()->attempt($credentials)) {
                return Response::error('Invalid Credentials', 401);
            }

            $user = User::where('email', $validated['email'])->first();

            // check if user is active
            if (!$user->is_active) {
                return Response::error('Your account is restricted, please contact support', 401);
            }

            return Response::success('Welcome Back ' . $user->name, new LoginResource($user));
        } catch (Exception $e) {
            return Response::error($e->getMessage(), 500);
        }
    }
}
