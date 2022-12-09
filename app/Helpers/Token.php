<?php

namespace App\Helpers;

use App\Contracts\TokenInterface;
use App\Exceptions\LockedException;
use App\Models\Organisation\User;
use App\Resources\Organisation\Settings\JWTSettings;
use Exception;
use Illuminate\Http\JsonResponse;

class Token implements TokenInterface
{

    /**
     * @param array $credentials
     * @return JsonResponse
     * @throws LockedException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function attempt(array $credentials): JsonResponse
    {
        static::configDuration();

        if (!$token = auth()->attempt($credentials)) {
            throw new Exception('Cannot authorize.');
        }

        return static::respond($token);
    }

    /**
     * @param User $user
     * @return JsonResponse
     * @throws LockedException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function getByUser(User $user): JsonResponse
    {
        static::configDuration();

        if (!$token = auth()->login($user)) {
            throw new Exception('Cannot authorize.');
        }

        return static::respond($token);
    }

    /**
     * @return JsonResponse
     * @throws LockedException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public static function refresh(): JsonResponse
    {
        static::configDuration();

        return static::respond(
            auth()->refresh()
        );
    }

    /**
     * @param string $token
     * @return JsonResponse
     * @throws LockedException
     */
    public static function respond(string $token): JsonResponse
    {
        if (auth()->user()->status === User::STATUS_INACTIVE) {
            throw new LockedException();
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    /**
     * @return void
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected static function configDuration(): void
    {
        auth()->factory()->setTTL(
            JWTSettings::get(JWTSettings::TTL)
        );
    }
}
