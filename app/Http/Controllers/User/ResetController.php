<?php

namespace App\Http\Controllers\User;

use App\Events\UserReset;
use App\Exceptions\LockedException;
use App\Helpers\DataBase;
use App\Helpers\Message;
use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\User\ResetRequest;
use App\Resources\Organisation\AuthHashResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ResetController extends BaseController
{

    public function email(ResetRequest $request): void
    {
        try {
            if (!DataBase::setDefaultBySubdomain($request->get('subdomain'))) {
                throw new Exception('Cannot resolve database by subdomain.');
            }

            event(new UserReset(
                app(AuthHashResource::class)->createByEmail($request->get('email'))->hash
            ));
        } catch (ModelNotFoundException) {
            Message::get(404);
        } catch (LockedException) {
            Message::get(423);
        } catch (Exception $e) {
            logger('user.reset.password', ['error' => $e->getMessage()]);
            Message::get(400);
        }

        Message::get(200);
    }
}
