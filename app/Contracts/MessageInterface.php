<?php

namespace App\Contracts;

interface MessageInterface
{
    public static function get(int $status = 200, ?string $message = null, array $errors =[]): void;

}
