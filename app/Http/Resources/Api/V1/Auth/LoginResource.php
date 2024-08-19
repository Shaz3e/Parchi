<?php

namespace App\Http\Resources\Api\V1\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LoginResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Generate token
        $token = auth()->user()->createToken('auth_token')->plainTextToken;

        return [
            'user' => auth()->user(),
            'token' => "Bearer " . $token,
        ];
    }
}
