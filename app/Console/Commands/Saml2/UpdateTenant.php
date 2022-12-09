<?php

namespace App\Console\Commands\Saml2;

use App\Console\Commands\Saml2\Traits\OrganisationDatabaseTrait;

class UpdateTenant extends \Slides\Saml2\Commands\UpdateTenant
{

    use OrganisationDatabaseTrait;
}
