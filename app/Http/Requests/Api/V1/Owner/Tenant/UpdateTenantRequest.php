<?php

namespace App\Http\Requests\Api\V1\Owner\Tenant;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Validation\Rule;

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
            'email' => 'required|email|max:255|' . Rule::unique('tenants', 'email')->ignore($this->tenant),
        ];
    }
}
