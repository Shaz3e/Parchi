<?php

namespace App\Http\Requests\Api\V1\Owner\Tenant;

use App\Http\Requests\BaseFormRequest;

class UpdateTenantRequest extends BaseFormRequest
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
            'email' => 'required|email|unique:tenants,email,' . $this->route('tenant')->id
        ];
    }
}
