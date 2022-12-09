<?php

namespace App\Console\Commands\Saml2;

use App\Console\Commands\Saml2\Traits\OrganisationDatabaseTrait;

class ListTenants extends \Slides\Saml2\Commands\ListTenants
{

    use OrganisationDatabaseTrait;
}
