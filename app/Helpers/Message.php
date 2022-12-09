<?php

namespace App\Helpers;

use App\Contracts\MessageInterface;
use Illuminate\Http\Exceptions\HttpResponseException;

class Message implements MessageInterface
{
    public const HTTP_STATUS = [
        200 => 'OK',
        201 => 'Created',
        400 => 'Bad request',
        401 => 'Unauthorised',
        403 => 'Forbidden',
        404 => 'Not Found',
        423 => 'Locked',
    ];

    public const UNKNOWN = 'Unknown';

    public static function get(int $status = 200, ?string $message = null, array $errors = []): void
    {
        throw new HttpResponseException(
            response()->json(
                array_merge(
                    ['message' => $message ?: static::getMessageByStatus($status)],
                    !empty($errors) ? ['errors' => $errors] : []
                ),
                $status
            )
        );
    }

    public static function getMessageByStatus(int $status): string
    {
        return static::HTTP_STATUS[$status] ?? 'Unknown';
    }
}
