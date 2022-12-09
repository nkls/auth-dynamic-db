<?php

namespace App\Policies;

use App\Helpers\Message;
use App\Models\Organisation\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AuthHashPolicy
{
    use HandlesAuthorization;

    public function view(): bool
    {
        return $this->has();
    }

    public function create(): bool
    {
        return $this->has();
    }

    protected function has(): bool
    {
        switch (auth()->user()->role) {
            case User::ROLE_USER:
            case User::ROLE_ADMIN:
                return true;
        }

        Message::get(403);
    }
}
