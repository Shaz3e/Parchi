<?php

namespace App\Http\Controllers\Api\Owner\Auth;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Response;

class LogoutController extends BaseController
{
    public function __invoke(Request $request)
    {
        try {
            // Revoke the token that was used to authenticate the current request
            $request->user()->currentAccessToken()->delete();

            return Response::message('You have successfully logged out');
        } catch (Exception $e) {
            return Response::error($e->getMessage(), 500);
        }
    }
}
