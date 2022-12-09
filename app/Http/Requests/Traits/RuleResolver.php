<?php

namespace App\Http\Requests\Traits;

trait RuleResolver
{

    protected function resolveRules(string $name, string|array $add = null): array
    {
        if (method_exists($this, 'defaultRules')) {
            $rules = $this->defaultRules()[$name] ?? [];
        }

        return array_merge(
            $this->asArray($rules ?? []),
            $this->asArray($add),
        );
    }

    protected function asArray(null|string|array $value): array
    {
        if (is_string($value)) {
            return explode('|', $value);
        }

        return $value ?: [];
    }
}
