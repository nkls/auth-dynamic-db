<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Message;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\AdminRequest;
use App\Models\Organisation\User;
use App\Resources\Organisation\UserResource;
use Exception;
use Illuminate\Http\JsonResponse;

class UsersController extends Controller
{

    public function index(): JsonResponse
    {
        return response()->json(
            User::paginate(
                request()->input('limit') > 0 ? request()->input('limit') : 100
            )
        );
    }

    public function view(int|string $key): JsonResponse
    {
        try {
            return response()->json(
                app(UserResource::class)->getBy($key)
            );
        } catch (Exception) {
            Message::get(404);
        }
    }

    public function create(AdminRequest $request): void
    {
        if (!app(UserResource::class)->create($request->validated())) {
            Message::get(400);
        }

        Message::get(201);
    }

    public function update(AdminRequest $request, int|string $key): void
    {
        try {
            if (!app(UserResource::class)->update($key, $request->validated())) {
                Message::get(400, null, ['Cannot update data.']);
            }
        } catch (Exception) {
            Message::get(400);
        }

        Message::get(200);
    }

}
