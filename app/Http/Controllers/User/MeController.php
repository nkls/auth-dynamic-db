<?php

namespace App\Http\Controllers\User;

use App\Helpers\Message;
use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\User\SelfRequest;
use App\Resources\Organisation\UserResource;
use Illuminate\Http\JsonResponse;

class MeController extends BaseController
{

    public function view(): JsonResponse
    {
        return response()->json(
            collect(auth()->user())
                ->except(['id'])
                ->all()
        );
    }

    public function update(SelfRequest $request): void
    {
        $values = $request->safe()->only(['name', 'email', 'password']);

        if (empty($values)){
            Message::get(400, null, ['No data to update']);
        }

        if (!app(UserResource::class)->update(auth()->user()->id, $values)) {
            Message::get(400, null, ['Failed to save']);
        }

        Message::get(200);;
    }

}
