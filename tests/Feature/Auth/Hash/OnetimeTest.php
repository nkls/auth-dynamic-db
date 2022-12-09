<?php

namespace Tests\Feature\Auth\Hash;

use App\Models\Organisation\AuthHash;
use App\Models\Organisation\User;
use Tests\Seeders\AuthHashesSeeder;
use Tests\Seeders\UsersSeeder;
use Tests\TestCase;

class OnetimeTest extends TestCase
{
    protected const SEEDERS = [
        UsersSeeder::class,
        AuthHashesSeeder::class,
    ];

    protected const ORG_UUID = 'b71547e4-3c34-4d53-84e3-f31ff86bebba';
    protected const USER_UUID = 'uuid-role-user';
    protected const HASH = 'hash-user-active';

    public function test_unauthorized_response()
    {
        $response = $this->get(route('user.onetime.hash', [
            'org_uuid' => 'wrong-route-uuid',
            'hash' => 'wrong-client-hash']));
        $response->assertStatus(403);

        $response = $this->get(route('user.onetime.hash', [
            'org_uuid' => static::ORG_UUID,
            'hash' => 'wrong-client-hash']));
        $response->assertStatus(403);
    }

    public function test_reject_inactive_hash()
    {
        AuthHash::where('hash', static::HASH)->update(['status' => AuthHash::STATUS_INACTIVE]);
        $response = $this->get(route('user.onetime.hash', [
            'org_uuid' => static::ORG_UUID,
            'hash' => static::HASH]));
        $response->assertStatus(403);
    }

    public function test_reject_inactive_user()
    {
        User::where('uuid', static::USER_UUID)->update(['status' => User::STATUS_INACTIVE]);
        $response = $this->get(route('user.onetime.hash', [
            'org_uuid' => static::ORG_UUID,
            'hash' => static::HASH]));
        $response->assertStatus(403);
    }

    public function test_hash_deactivate_after_one_use()
    {
        $response = $this->get(route('user.onetime.hash', [
            'org_uuid' => static::ORG_UUID,
            'hash' => static::HASH]));
        $response->assertOk();

        $response = $this->get(route('user.onetime.hash', [
            'org_uuid' => static::ORG_UUID,
            'hash' => static::HASH]));
        $response->assertStatus(403);
    }
}
