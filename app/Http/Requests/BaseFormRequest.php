<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BaseFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @link https://laravel.com/docs/11.x/validation#available-validation-rules
     */
    public function authorize(): bool
    {
        return true;
    }
}
