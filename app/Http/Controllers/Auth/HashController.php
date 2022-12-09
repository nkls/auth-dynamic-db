<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\DataNotFoundException;
use App\Exceptions\LockedException;
use App\Exceptions\UserRoleException;
use App\Helpers\Message;
use App\Helpers\Token;
use App\Http\Controllers\Controller;
use App\Models\Organisation\User;
use App\Resources\Organisation\AuthHashResource;
use Illuminate\Http\JsonResponse;

class HashController extends Controller
{

    public function guest(string $hash): JsonResponse
    {
        try {
            $user = app(AuthHashResource::class)->getUser($hash);

            if ($user->role !== User::ROLE_GUEST) {
                throw new UserRoleException();
            }

            return Token::getByUser($user);
        } catch (DataNotFoundException|UserRoleException) {
            Message::get(403);
        } catch (LockedException) {
            Message::get(423);
        } catch (\Exception) {
            Message::get(401);
        }
    }

    public function onetime(string $hash): JsonResponse
    {
        try {
            return Token::getByUser(
                app(AuthHashResource::class)->getUser($hash, true)
            );
        } catch (DataNotFoundException|LockedException) {
            Message::get(403);
        } catch (\Exception) {
            Message::get(401);
        }
    }
}
