<?php

namespace App\Policies;

use App\Helpers\Message;
use App\Models\Organisation\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(): bool
    {
        return $this->has();
    }

    public function view(): bool
    {
        return $this->has();
    }

    public function create(): bool
    {
        return $this->has();
    }

    public function update(): bool
    {
        return $this->has();
    }

    public function updateSelf(): bool
    {
        if (auth()->user()->role === User::ROLE_GUEST) {
            Message::get(403);
        }

        return true;
    }

    protected function has(): bool
    {
        if (auth()->user()->role === User::ROLE_ADMIN) {
            return true;
        }

        Message::get(403);
    }
}
