<?php

namespace Tests\Feature\Auth\Hash;

use App\Models\Organisation\AuthHash;
use App\Models\Organisation\User;
use Tests\Seeders\AuthHashesSeeder;
use Tests\Seeders\UsersSeeder;
use Tests\TestCase;

class GuestTest extends TestCase
{
    protected const SEEDERS = [
        UsersSeeder::class,
        AuthHashesSeeder::class,
    ];

    protected const ORG_UUID = 'b71547e4-3c34-4d53-84e3-f31ff86bebba';
    protected const USER_UUID = 'uuid-role-guest';
    protected const HASH = 'hash-guest-active';

    public function test_login_return_unauthorized_response()
    {
        $response = $this->get(route('guest.login.hash', [
            'org_uuid' => 'wrong-route-uuid',
            'hash' => 'wrong-client-hash']));
        $response->assertStatus(403);

        $response = $this->get(route('guest.login.hash', [
            'org_uuid' => static::ORG_UUID,
            'hash' => 'wrong-client-hash']));
        $response->assertStatus(403);
    }

    public function test_login_return_token()
    {
        $response = $this->get(route('guest.login.hash', [
            'org_uuid' => static::ORG_UUID,
            'hash' => static::HASH]));
        $response->assertOk();
        $response->assertSeeText(['access_token', 'token_type', 'expires_in']);
    }

    public function test_login_inactive_hash()
    {
        AuthHash::where('hash', static::HASH)->update(['status' => AuthHash::STATUS_INACTIVE]);
        $response = $this->get(route('guest.login.hash', [
            'org_uuid' => static::ORG_UUID,
            'hash' => static::HASH]));
        $response->assertStatus(423);
    }

    public function test_login_inactive_user()
    {
        User::where('uuid', static::USER_UUID)->update(['status' => User::STATUS_INACTIVE]);
        $response = $this->get(route('guest.login.hash', [
            'org_uuid' => static::ORG_UUID,
            'hash' => static::HASH]));
        $response->assertStatus(423);
    }

    public function test_login_not_guest_user()
    {
        User::where('uuid', static::USER_UUID)->update(['role' => User::ROLE_USER]);
        $response = $this->get(route('guest.login.hash', [
            'org_uuid' => static::ORG_UUID,
            'hash' => static::HASH]));
        $response->assertStatus(403);
    }
}
