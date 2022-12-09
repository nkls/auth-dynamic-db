<?php

namespace App\Resources\Organisation\Settings;

class JWTSettings extends AbstractSettings
{

    protected const PARAM = 'jwt';
    public const TTL = 'ttl';

    protected function setDefault(): void
    {
        $this->default = (object)[
            static::TTL => config('jwt.ttl'),
        ];
    }
}
