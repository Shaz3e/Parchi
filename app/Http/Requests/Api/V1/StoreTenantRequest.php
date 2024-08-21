<?php

namespace App\Http\Requests\Api\V1;

use App\Http\Requests\BaseFormRequest;

class StoreTenantRequest extends BaseFormRequest
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
            'email' => 'required|email|unique:tenants,email',
            'password' => 'required|string|max:255',
            'confirm_password' => 'required|same:password',
            'domain' => 'required|max:255|unique:domains,domain'
        ];
    }
}
