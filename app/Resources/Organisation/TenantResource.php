<?php

namespace App\Resources\Organisation;

use App\Models\Organisation\Tenant;
use Slides\Saml2\Repositories\TenantRepository;

class TenantResource extends TenantRepository
{

    /**
     * Create a new query.
     *
     * @param bool $withTrashed Whether need to include safely deleted records.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(bool $withTrashed = false)
    {
        $query = Tenant::query();

        if($withTrashed) {
            $query->withTrashed();
        }

        return $query;
    }
}
