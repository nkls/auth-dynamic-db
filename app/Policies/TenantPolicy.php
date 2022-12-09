<?php

namespace App\Policies;

use App\Helpers\Message;
use App\Models\Organisation\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TenantPolicy
{
    use HandlesAuthorization;

    public function index(): bool
    {
        return $this->has();
    }

    public function view(): bool
    {
        return $this->has();
    }

    public function update(): bool
    {
        return $this->has();
    }

    public function create(): bool
    {
        return $this->has();
    }

    public function delete(): bool
    {
        return $this->has();
    }

    protected function has(): bool
    {
        if (auth()->user()->role === User::ROLE_ADMIN) {
            return true;
        }

        Message::get(403);
    }
}
