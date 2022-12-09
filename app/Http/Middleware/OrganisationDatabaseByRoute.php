<?php

namespace App\Http\Middleware;

use App\Helpers\DataBase;
use Closure;
use Exception;
use Illuminate\Http\Request;

class OrganisationDatabaseByRoute
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            if ($uuid = $request->route('org_uuid')) {
                DataBase::setDefaultByUUID($uuid);
            }

            $request->route()->forgetParameter('org_uuid');
        } catch (Exception) {
        }

        return $next($request);
    }
}
