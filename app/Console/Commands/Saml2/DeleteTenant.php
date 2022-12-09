<?php

namespace App\Console\Commands\Saml2;

use App\Console\Commands\Saml2\Traits\OrganisationDatabaseTrait;

class DeleteTenant extends \Slides\Saml2\Commands\DeleteTenant
{

    use OrganisationDatabaseTrait;
}
