<?php

namespace App\Resources\Organisation;

use App\Models\Organisation\SamlUser;
use App\Models\Organisation\User;
use Slides\Saml2\Saml2User;

class SamlUserResource
{

    public function getUser(Saml2User $saml2): User
    {
        $saml_user = SamlUser::where('remote_id', $saml2->getUserId())
            ->where('tenant_id', $saml2->getTenant()->id)
            ->firstOr(function () use ($saml2) {
                return SamlUser::create([
                    'remote_id' => $saml2->getUserId(),
                    'tenant_id' => $saml2->getTenant()->id,
                    'user_id' => $this->getUserId(),
                ]);
            });
        $saml_user->update(['attributes' => $saml2->getAttributes()]);

        return $saml_user->user;
    }

    protected function getUserId(): int
    {
        if (!$user = auth()->user()) {
            $user = User::create();
        }

        return $user->id;
    }
}
