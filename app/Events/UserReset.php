<?php

namespace App\Events;

class UserReset
{
    public function __construct(protected string $hash)
    {
    }

    public function getHash(): string
    {
        return $this->hash;
    }
}
