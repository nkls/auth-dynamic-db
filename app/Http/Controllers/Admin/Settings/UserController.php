<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Helpers\Message;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\UserRequest;
use App\Resources\Organisation\Settings\UserSettings;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function view(): JsonResponse
    {
        return response()->json(
            UserSettings::get()
        );
    }

    public function update(UserRequest $request): void
    {
        if (!UserSettings::init()->update($request->validated())) {
            Message::get(400, null, ['Cannot update data.']);
        }

        Message::get('200');
    }

    public function delete(): void
    {
        if (!UserSettings::init()->delete()) {
            Message::get(400, null, ['Cannot delete.']);
        }

        Message::get('200');
    }

}
