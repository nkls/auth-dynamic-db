<?php

namespace App\Helpers\Supports;

use App\Resources\Organisation\TenantResource;

class Links
{

    public function login(): array
    {
        return app(TenantResource::class)
            ->all()
            ->mapWithKeys(function ($entity) {
                return [
                    $entity->key => $entity->settings['login']
                ];
            })
            ->toArray();
    }
}
