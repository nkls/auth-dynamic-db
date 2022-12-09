<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Helpers\Message;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\GuestRequest;
use App\Resources\Organisation\Settings\GuestSettings;
use Illuminate\Http\JsonResponse;

class GuestController extends Controller
{
    public function view(): JsonResponse
    {
        return response()->json(
            GuestSettings::get()
        );
    }

    public function update(GuestRequest $request): void
    {
        if (!GuestSettings::init()->update($request->validated())) {
            Message::get(400, null, ['Cannot update data.']);
        }

        Message::get('200');
    }

    public function delete(): void
    {
        if (!GuestSettings::init()->delete()) {
            Message::get(400, null, ['Cannot delete.']);
        }

        Message::get('200');
    }

}
