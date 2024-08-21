<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\Api\V1\Auth\RegisterResource;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Response;

class RegisterController extends BaseController
{
    public function __invoke(RegisterRequest $request)
    {
        try {
            $validated = $request->validated();

            $user = User::create($validated);

            // return resource
            return Response::success('You are registered',new RegisterResource($user));
        } catch (Exception $e) {
            Response::error($e->getMessage(), 500);
        }
    }
}
