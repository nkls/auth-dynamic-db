<?php

use App\Models\Organisation\User;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['unauth'],
], function ($router) {
    Route::post('/login', 'Auth\LocalController@login')
        ->name('user.login.password');
    Route::post('/refresh', 'Auth\LocalController@refresh')
        ->name('user.refresh.token');

    Route::get('/{org_uuid}/guest/login/{hash}', 'Auth\HashController@guest')
        ->name('guest.login.hash');
    Route::get('/{org_uuid}/onetime/login/{hash}', 'Auth\HashController@onetime')
        ->name('user.onetime.hash');

    Route::get('/supports/{subdomain}/links/login', 'Supports\LinksController@login')
        ->name('supports.links.login');

    Route::post('/user/reset', 'User\ResetController@email')
        ->name('user.reset.email');
});

Route::group([
    'middleware' => ['saml2'],
], function () {
    Route::get('/{org_uuid}/{uuid}/logout', [
        'as' => 'saml.logout',
        'uses' => 'Auth\Saml2Controller@logout',
    ]);
    Route::get('/{org_uuid}/{uuid}/login', [
        'as' => 'saml.login',
        'uses' => 'Auth\Saml2Controller@login',
    ]);
    Route::get('/{org_uuid}/{uuid}/metadata', [
        'as' => 'saml.metadata',
        'uses' => 'Auth\Saml2Controller@metadata',
    ]);
    Route::post('/{org_uuid}/{uuid}/acs', [
        'as' => 'saml.acs',
        'uses' => 'Auth\Saml2Controller@acs',
    ]);
    Route::get('/{org_uuid}/{uuid}/sls', [
        'as' => 'saml.sls',
        'uses' => 'Auth\Saml2Controller@sls',
    ]);
});

Route::group([
    'middleware' => ['api', 'auth:api'],
], function ($router) {
    Route::post('/logout', 'Auth\LocalController@logout')
        ->name('logout');


    Route::get('/me', 'User\MeController@view')
        ->name('me');
    Route::patch('/me', 'User\MeController@update')
        ->name('me')
        ->can('updateSelf', User::class);;
});
