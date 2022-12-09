<?php

namespace App\Resources\Organisation;

use App\Models\Organisation\User;
use Exception;

class UserResource
{

    /**
     * @param mixed $key
     * @return User
     * @throws Exception
     */
    public function getBy(int|string $key): User
    {
        return User::oneOf($key)
            ->firstOrFail();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(int|string $key, array $data): bool
    {
        return User::oneOf($key)
            ->firstOrFail()
            ->update($data);
    }

    public function getByRef(string $ref): User
    {
        return User::firstOrCreate(
            ['ref' => $ref],
            ['role' => User::ROLE_GUEST]
        );
    }

    public function getByEmail(string $email): User
    {
        return User::where('email', $email)->firstOrFail();
    }
}
