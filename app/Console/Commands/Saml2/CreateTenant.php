<?php

namespace App\Console\Commands\Saml2;

use App\Console\Commands\Saml2\Traits\OrganisationDatabaseTrait;

class CreateTenant extends \Slides\Saml2\Commands\CreateTenant
{

    use OrganisationDatabaseTrait;
}
