<?php

namespace App\Http\Resources\Api\V1\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

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

        // Fetch the token details from the `personal_access_tokens` table
        $tokenDetails = auth()->user()->tokens()->where('token', hash('sha256', explode('|', $token)[1]))->first();

        return [
            'user' => auth()->user(),
            'token' => "Bearer " . $token,
            'token_details' => [
                'type' => 'Bearer',
                'id' => $tokenDetails->id,
                'separator' => '|',
                'token' => $tokenDetails->token,
                'tokenable_id' => $tokenDetails->tokenable_id,
                'name' => $tokenDetails->name,
                'last_used_at' => $tokenDetails->last_used_at,
                'expires_at' => $tokenDetails->expires_at,
                'created_at' => $tokenDetails->created_at,
                'updated_at' => $tokenDetails->updated_at,
            ]
        ];
    }
}
