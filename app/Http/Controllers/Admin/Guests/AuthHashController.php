<?php

namespace App\Http\Controllers\Admin\Guests;

use App\Exceptions\DataNotFoundException;
use App\Helpers\DataBase;
use App\Helpers\Message;
use App\Http\Controllers\Controller;
use App\Resources\Organisation\AuthHashResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\URL;

class AuthHashController extends Controller
{
    public function view(string $ref): JsonResponse
    {
        try {
            $entity = app(AuthHashResource::class)->getActiveByRef($ref);
        } catch (DataNotFoundException) {
            Message::get(404);
        }

        return response()->json([
            'link' => URL::route('guest.login.hash', [
                'org_uuid' => DataBase::getUUID(),
                'hash' => $entity->hash,
            ]),
        ]);
    }

    public function create(string $ref): void
    {
        try {
            if (!app(AuthHashResource::class)->createByRef($ref)) {
                Message::get(400);
            }
        } catch (DataNotFoundException) {
            Message::get(400, 'Cannot to get or create a client.');
        }

        Message::get(201);
    }
}
