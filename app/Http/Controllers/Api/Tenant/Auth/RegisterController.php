<?php

namespace App\Http\Controllers\Api\Tenant\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Api\Tenant\Auth\RegisterRequest;
use App\Http\Resources\Api\Tenant\Auth\RegisterResource;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Response;

class RegisterController extends BaseController
{
    /**
     * Handle the incoming request.
     */
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
