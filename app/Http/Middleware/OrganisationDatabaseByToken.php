<?php

namespace App\Http\Middleware;

use App\Helpers\DataBase;
use App\Resources\Route\ShardCoordinatorResource;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrganisationDatabaseByToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            if ($uuid = JWTAuth::parseToken()->getPayload()->get(ShardCoordinatorResource::FIELD_UUID)) {
                DataBase::setDefaultByUUID($uuid);
            }
        } catch (Exception) {}

        return $next($request);
    }
}
