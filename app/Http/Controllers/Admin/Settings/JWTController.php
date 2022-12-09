<?php

namespace App\Http\Controllers\Admin\Settings;

use App\Helpers\Message;
use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\JWTRequest;
use App\Resources\Organisation\Settings\JWTSettings;
use Illuminate\Http\JsonResponse;

class JWTController extends Controller
{
    public function view(): JsonResponse
    {
        return response()->json(
            JWTSettings::get()
        );
    }

    public function update(JWTRequest $request): void
    {
        if (!JWTSettings::init()->update($request->validated())) {
            Message::get(400, null, ['Cannot update data.']);
        }

        Message::get('200');
    }

    public function delete(): void
    {
        if (!JWTSettings::init()->delete()) {
            Message::get(400, null, ['Cannot delete.']);
        }

        Message::get('200');
    }

}
