<?php

namespace Tests\Feature\Admin\Guests\AuthHash;

use Tests\Seeders\AuthHashesSeeder;
use Tests\Seeders\UsersSeeder;
use Tests\TestCase;

class ViewTest extends TestCase
{
    protected const SEEDERS = [
        UsersSeeder::class,
        AuthHashesSeeder::class,
    ];

    protected const UUID = 'uuid-role-admin';
    protected const REF = 'ref_test_client';
    protected const ORG_UUID = 'b71547e4-3c34-4d53-84e3-f31ff86bebba';
    protected const HASH = 'hash-guest-active';

    public function test_auth_view_unauthorized()
    {
        $response = $this->get(route('guests.auth.view', ['ref' => 'wrong-client-ref']));

        $response->assertStatus(401);
    }

    public function test_role_user_access()
    {
       $response = $this->withTokenByUUID('uuid-role-user')
            ->get(route('guests.auth.view', ['ref' => static::REF]));

        $response->assertOk();
    }

    public function test_role_guest_access()
    {
        $response = $this->withTokenByUUID('uuid-role-guest')
            ->get(route('guests.auth.view', ['ref' => static::REF]));

        $response->assertStatus(403);
    }

    public function test_auth_view_wrong_ref()
    {
        $response = $this->withTokenByUUID(static::UUID)
            ->get(route('guests.auth.view', ['ref' => 'wrong-client-ref']));

        $response->assertStatus(404);
    }

    public function test_auth_view_method()
    {
        $response = $this->withTokenByUUID(static::UUID)
            ->get(route('guests.auth.view', ['ref' => static::REF]));
        $response->assertOk();
        $response->assertSee(
            explode('/', route('guest.login.hash', ['org_uuid' => static::ORG_UUID, 'hash' => static::HASH], false))
        );
    }
}
