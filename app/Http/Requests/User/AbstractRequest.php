<?php

namespace App\Http\Requests\User;

use App\Http\Requests\Traits\FailedValidation;
use App\Http\Requests\Traits\RuleResolver;
use App\Models\Organisation\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

abstract class AbstractRequest extends FormRequest
{
    use FailedValidation, RuleResolver;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {
        foreach (['role', 'status'] as $value) {
            if (!$this->get($value)) {
                continue;
            }

            $this->merge([
                $value => strtolower($this->get($value)),
            ]);
        }

    }

    protected function defaultRules(): array
    {
        return [
            'name' => 'min:1|max:255',
            'email' => 'max:255|email:rfc',
            'password' => [
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
            ],
            'role' => [Rule::in(User::ROLES)],
            'status' => [Rule::in(User::STATUSES)],
            'ref' => 'nullable|max:255',
        ];
    }
}
