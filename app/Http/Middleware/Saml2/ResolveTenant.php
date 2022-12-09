<?php

namespace App\Http\Middleware\Saml2;

use App\Helpers\Saml2\OneLoginBuilder;
use Slides\Saml2\Repositories\TenantRepository;

class ResolveTenant extends \Slides\Saml2\Http\Middleware\ResolveTenant
{

    /**
     * ResolveTenant constructor.
     *
     * @param TenantRepository $tenants
     * @param OneLoginBuilder $builder
     */
    public function __construct(TenantRepository $tenants, OneLoginBuilder $builder)
    {
        $this->tenants = $tenants;
        $this->builder = $builder;
    }
}
