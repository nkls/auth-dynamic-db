<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\LockedException;
use App\Helpers\Message;
use App\Helpers\Token;
use App\Resources\Organisation\SamlUserResource;
use Exception;
use Illuminate\Http\JsonResponse;
use OneLogin\Saml2\Error as OneLoginError;
use OneLogin\Saml2\ValidationError;
use Slides\Saml2\Auth;

class Saml2Controller extends \Slides\Saml2\Http\Controllers\Saml2Controller
{

    /**
     * Process the SAML Response sent by the IdP.
     *
     * Fires "SignedIn" event if a valid user is found.
     *
     * @param Auth $auth
     *
     * @return JsonResponse
     * @throws ValidationError
     *
     * @throws OneLoginError
     */
    public function acs(Auth $auth): JsonResponse
    {
        try {
            if ($errors = $auth->acs()) {
                logger()->error('saml2.error_detail', ['error' => $auth->getLastErrorReason()]);
                session()->flash('saml2.error_detail', [$auth->getLastErrorReason()]);

                logger()->error('saml2.error', $errors);
                session()->flash('saml2.error', $errors);

                Message::get(400, null, $errors);
            }

            return Token::getByUser(
                app(SamlUserResource::class)->getUser(
                    $auth->getSaml2User()
                )
            );
        } catch (LockedException) {
            Message::get(423);
        } catch (Exception $e) {
            logger()->error('saml2.controller.acs', ['error' => $e->getMessage()]);
            Message::get(400);
        }
    }
}
