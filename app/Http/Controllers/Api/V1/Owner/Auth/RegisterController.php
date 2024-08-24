<?php

namespace App\Http\Controllers\Api\V1\Owner\Auth;

use Exception;
use App\Models\User;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Response;
use App\Http\Requests\Api\V1\Auth\RegisterRequest;
use App\Http\Resources\Api\V1\Auth\RegisterResource;

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
