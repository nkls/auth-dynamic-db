<?php

namespace App\Http\Requests\Settings;

use App\Http\Requests\Traits\FailedValidation;
use App\Resources\Organisation\Settings\JWTSettings;
use Illuminate\Foundation\Http\FormRequest;

class JWTRequest extends FormRequest
{
    use FailedValidation;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            JWTSettings::TTL => 'required|integer',
        ];
    }
}
