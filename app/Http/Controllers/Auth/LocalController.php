<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\LockedException;
use App\Helpers\DataBase;
use App\Helpers\Message;
use App\Helpers\Token;
use App\Http\Controllers\Controller as BaseController;
use Exception;
use Illuminate\Http\JsonResponse;

class LocalController extends BaseController
{

    public function login(): JsonResponse
    {
        try {
            if (!DataBase::setDefaultBySubdomain(request('subdomain'))) {
                throw new Exception('Cannot resolve database via subdomain.');
            }

            return Token::attempt(request(['email', 'password']));
        } catch (LockedException) {
            Message::get(423);
        } catch (Exception) {
            Message::get(401);
        }
    }

    public function logout(): void
    {
        auth()->logout();
        Message::get(200, 'Successfully logged out');
    }

    public function refresh(): JsonResponse
    {
        try {
            return Token::refresh();
        } catch (LockedException) {
            Message::get(423);
        } catch (Exception) {
            Message::get(401);
        }
    }

}
