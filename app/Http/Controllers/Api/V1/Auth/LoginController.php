<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Http\Resources\Api\V1\Auth\LoginResource;
use App\Models\User;
use Exception;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request)
    {
        try {
            // Validation
            $validated = $request->validated();

            // Login
            $credentials = $request->only(['email', 'password']);
            if (!auth()->attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }

            $user = User::where('email', $validated['email'])->get();

            return new LoginResource($user);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
