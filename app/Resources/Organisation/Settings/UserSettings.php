<?php

namespace App\Resources\Organisation\Settings;

class UserSettings extends AbstractSettings
{

    protected const PARAM = 'user';
    public const RESET_HASH_TTL = 'reset_password_ttl';

    protected function setDefault(): void
    {
        $this->default = (object)[
            static::RESET_HASH_TTL => config('user.reset_password_ttl', 1440),
        ];
    }
}
