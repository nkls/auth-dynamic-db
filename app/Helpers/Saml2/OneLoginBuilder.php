<?php

namespace App\Helpers\Saml2;

use App\Helpers\DataBase;
use Illuminate\Support\Facades\URL;

class OneLoginBuilder extends \Slides\Saml2\OneLoginBuilder
{

    /**
     * Configuration default values that must be replaced with custom ones.
     *
     * @return array
     */
    protected function configDefaultValues()
    {
        $params = [
            'org_uuid' => DataBase::getUUID(),
            'uuid' => $this->tenant->uuid
        ];

        return [
            'sp.entityId' => URL::route('saml.metadata', $params),
            'sp.assertionConsumerService.url' => URL::route('saml.acs', $params),
            'sp.singleLogoutService.url' => URL::route('saml.sls', $params)
        ];
    }
}
