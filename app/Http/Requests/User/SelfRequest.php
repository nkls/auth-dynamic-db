<?php

namespace App\Http\Requests\User;

use App\Models\Organisation\User;
use Illuminate\Validation\Rule;

class SelfRequest extends AbstractRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => $this->resolveRules('name'),
            'email' => $this->resolveRules('email', [
                Rule::unique((new User)->getTable())->ignore($this->user()->id),
            ]),
            'password' => $this->resolveRules('password'),
        ];
    }
}
