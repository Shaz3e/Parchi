<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Resources\Api\V1\Auth\LoginResource;
use App\Models\User;
use Exception;
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

            $user = User::where('email', $validated['email'])->get();

            return Response::success(new LoginResource($user));
        } catch (Exception $e) {
            return Response::error($e->getMessage(), 500);
        }
    }
}
