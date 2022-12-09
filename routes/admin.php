<?php

use App\Models\Organisation\AuthHash;
use App\Models\Organisation\Setting;
use App\Models\Organisation\Tenant;
use App\Models\Organisation\User;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['api', 'auth:api'],
], function ($router) {
    Route::get('/settings/jwt', 'Admin\Settings\JWTController@view')
        ->name('settings.jwt.view')
        ->can('view', Setting::class);
    Route::patch('/settings/jwt', 'Admin\Settings\JWTController@update')
        ->name('settings.jwt.update')
        ->can('update', Setting::class);
    Route::delete('/settings/jwt', 'Admin\Settings\JWTController@delete')
        ->name('settings.jwt.delete')
        ->can('delete', Setting::class);

    Route::get('/settings/guest', 'Admin\Settings\GuestController@view')
        ->name('settings.guest.view')
        ->can('view', Setting::class);
    Route::patch('/settings/guest', 'Admin\Settings\GuestController@update')
        ->name('settings.guest.update')
        ->can('update', Setting::class);
    Route::delete('/settings/guest', 'Admin\Settings\GuestController@delete')
        ->name('settings.guest.delete')
        ->can('delete', Setting::class);

    Route::get('/settings/user', 'Admin\Settings\UserController@view')
        ->name('settings.user.view')
        ->can('view', Setting::class);
    Route::patch('/settings/user', 'Admin\Settings\UserController@update')
        ->name('settings.user.update')
        ->can('update', Setting::class);
    Route::delete('/settings/user', 'Admin\Settings\UserController@delete')
        ->name('settings.user.delete')
        ->can('delete', Setting::class);

    Route::get('/settings/saml2', 'Admin\Settings\Saml2Controller@index')
        ->name('settings.saml2.index')
        ->can('index', Tenant::class);
    Route::get('/settings/saml2/{key}', 'Admin\Settings\Saml2Controller@view')
        ->name('settings.saml2.view')
        ->can('view', Tenant::class);
    Route::post('/settings/saml2', 'Admin\Settings\Saml2Controller@create')
        ->name('settings.saml2.create')
        ->can('create', Tenant::class);
    Route::put('/settings/saml2/{key}', 'Admin\Settings\Saml2Controller@update')
        ->name('settings.saml2.update')
        ->can('update', Tenant::class);
    Route::delete('/settings/saml2/{key}', 'Admin\Settings\Saml2Controller@delete')
        ->name('settings.saml2.delete')
        ->can('delete', Tenant::class);

    Route::get('/guests/{ref}', 'Admin\Guests\AuthHashController@view')
        ->name('guests.auth.view')
        ->can('view', AuthHash::class);
    Route::post('/guests/{ref}', 'Admin\Guests\AuthHashController@create')
        ->name('guests.auth.create')
        ->can('create', AuthHash::class);

    Route::get('/users', 'Admin\UsersController@index')
        ->name('users.index')
        ->can('viewAny', User::class);
    Route::get('/users/{key}', 'Admin\UsersController@view')
        ->name('users.view')
        ->can('view', User::class);
    Route::post('/users', 'Admin\UsersController@create')
        ->name('users.create')
        ->can('create', User::class);
    Route::patch('/users/{key}', 'Admin\UsersController@update')
        ->name('users.update')
        ->can('update', User::class);
});
