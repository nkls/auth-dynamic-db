<?php

namespace App\Resources\Organisation;

use App\Exceptions\DataNotFoundException;
use App\Exceptions\LockedException;
use App\Models\Organisation\AuthHash;
use App\Models\Organisation\User;
use App\Resources\Organisation\Settings\GuestSettings;
use App\Resources\Organisation\Settings\UserSettings;
use Carbon\Carbon;

class AuthHashResource
{

    /**
     * @param string $hash
     * @param bool $deactivate
     * @return User
     * @throws DataNotFoundException
     * @throws LockedException
     */
    public function getUser(string $hash, bool $deactivate = false): User
    {
        if (!$entity = AuthHash::where('hash', $hash)->expires()->select('user_id', 'status')->first()) {
            throw new DataNotFoundException(sprintf('The hash "%s" does not exist.', $hash));
        }

        if ($entity->status === AuthHash::STATUS_INACTIVE) {
            throw new LockedException(sprintf('The entity with hash "%s" is locked.', $hash));
        }

        $this->userIsActive($entity->user);

        if ($deactivate) {
            $this->deactivateByUser($entity->user);
        }

        return $entity->user;
    }

    /**
     * @param string $ref
     * @return AuthHash
     * @throws DataNotFoundException
     */
    public function getActiveByRef(string $ref): AuthHash
    {
        if (!$entity = AuthHash::ref($ref)->expires()->active()->first()) {
            throw new DataNotFoundException(sprintf('The ref "%s" does not exist.', $ref));
        }

        return $entity;
    }

    /**
     * @param string $ref
     * @return AuthHash
     * @throws DataNotFoundException
     * @throws LockedException
     */
    public function createByRef(string $ref): AuthHash
    {
        if (!$user = app(UserResource::class)->getByRef($ref)) {
            throw new DataNotFoundException(sprintf('Cannot to get or create a client with ref "%s".', $ref));
        }

        $this->userIsActive($user);
        $this->deactivateByUser($user);

        return AuthHash::create([
            'user_id' => $user->id,
            'expires' => Carbon::now()->addDays(
                GuestSettings::get(GuestSettings::HASH_TTL)
            ),
        ]);
    }

    /**
     * @param string $email
     * @return AuthHash
     * @throws LockedException
     */
    public function createByEmail(string $email): AuthHash
    {
        $user = app(UserResource::class)->getByEmail($email);

        $this->userIsActive($user);
        $this->deactivateByUser($user);

        return AuthHash::create([
            'user_id' => $user->id,
            'expires' => Carbon::now()->addDays(
                UserSettings::get(UserSettings::RESET_HASH_TTL)
            ),
        ]);
    }

    protected function deactivateByUser(User $user): void
    {
        AuthHash::where('user_id', $user->id)
            ->expires()
            ->active()
            ->update(['status' => AuthHash::STATUS_INACTIVE]);
    }

    /**
     * @param User $user
     * @return bool
     * @throws LockedException
     */
    protected function userIsActive(User $user): bool
    {
        if ($user->status === User::STATUS_INACTIVE) {
            throw new LockedException('Locked user.');
        }

        return true;
    }
}
