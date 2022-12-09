<?php

namespace App\Console\Commands\Saml2;

use App\Console\Commands\Saml2\Traits\OrganisationDatabaseTrait;

class TenantCredentials extends \Slides\Saml2\Commands\TenantCredentials
{

    use OrganisationDatabaseTrait;
}
