<?php

namespace App\Console\Commands\Saml2;

use App\Console\Commands\Saml2\Traits\OrganisationDatabaseTrait;

class RestoreTenant extends \Slides\Saml2\Commands\RestoreTenant
{

    use OrganisationDatabaseTrait;
}
