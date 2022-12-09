<?php

namespace App\Models\Organisation;

use App\Helpers\DataBase;
use Illuminate\Support\Facades\URL;

class Tenant extends \Slides\Saml2\Models\Tenant
{
    public $hidden = [
        'relay_state_url',
    ];

    public $appends = [
        'settings',
    ];

    public function getSettingsAttribute(): array
    {
        $params = [
            'org_uuid' => DataBase::getUUID(),
            'uuid' => $this->uuid,
        ];

        return [
            'idp' => [
                'reply' => URL::route('saml.acs', $params),
                'identifier' => URL::route('saml.metadata', $params),
            ],
            'login' => URL::route('saml.login', $params),
            'logout' => URL::route('saml.logout', $params),
        ];
    }
}
