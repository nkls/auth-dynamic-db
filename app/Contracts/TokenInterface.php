<?php

namespace App\Contracts;

use Illuminate\Http\JsonResponse;

interface TokenInterface
{
    public static function respond(string $token): JsonResponse;

}
