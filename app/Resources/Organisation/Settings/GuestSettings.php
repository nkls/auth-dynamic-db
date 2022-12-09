<?php

namespace App\Resources\Organisation\Settings;

class GuestSettings extends AbstractSettings
{

    protected const PARAM = 'guest';
    public const HASH_TTL = 'hash_ttl';

    protected function setDefault(): void
    {
        $this->default = (object)[
            static::HASH_TTL => config('guest.hash.ttl', 30),
        ];
    }
}
