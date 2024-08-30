<?php

namespace App\Http\Controllers\Api\Owner\Auth;

use Exception;
use App\Models\User;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Owner\Auth\RegisterRequest;
use App\Http\Resources\Api\Owner\Auth\RegisterResource;
use Illuminate\Support\Facades\Response;

class RegisterController extends BaseController
{
    public function __invoke(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();

            $user = User::create($validated);

            // return resource
            return Response::success('You are registered', new RegisterResource($user));
        } catch (Exception $e) {
            Response::error($e->getMessage(), 500);
        }
    }
}
