<?php

namespace App\Http\Requests\User;

use App\Models\Organisation\User;
use Illuminate\Validation\Rule;

class AdminRequest extends AbstractRequest
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
                Rule::unique((new User)->getTable())->ignore(
                    $this->route('key'),
                    $this->resolveField()
                ),
            ]),
            'role' => $this->resolveRules('role'),
            'status' => $this->resolveRules('status'),
            'ref' => $this->resolveRules('ref', [
                Rule::unique((new User)->getTable())->ignore(
                    $this->route('key'),
                    $this->resolveField()
                ),
            ]),
        ];
    }

    protected function resolveField(): string
    {
        return is_numeric($this->route('key')) ? 'id' : 'uuid';
    }
}
