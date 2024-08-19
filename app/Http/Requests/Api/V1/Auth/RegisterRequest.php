<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|max:255',
            'confirm_password' => 'required|same:password'
        ];
    }
}
