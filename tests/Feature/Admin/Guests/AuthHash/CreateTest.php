<?php

namespace Tests\Feature\Admin\Guests\AuthHash;

use App\Models\Organisation\AuthHash;
use App\Models\Organisation\User;
use Tests\Seeders\AuthHashesSeeder;
use Tests\Seeders\UsersSeeder;
use Tests\TestCase;

class CreateTest extends TestCase
{
    protected const SEEDERS = [
        UsersSeeder::class,
        AuthHashesSeeder::class,
    ];

    protected const UUID = 'uuid-role-admin';
    protected const REF_EXIST = 'ref_test_client';
    protected const REF_NEW = 'ref_new_client';
    protected const HASH_ACTIVE = 'hash-guest-active';
    protected const HASH_EXPIRED = 'hash-guest-active-expired';

    public function test_create_unauthorized()
    {
        $response = $this->post(route('guests.auth.create', ['ref' => static::REF_NEW]), []);
        $response->assertStatus(401);
    }

    public function test_role_user_access()
    {
        $response = $this->withTokenByUUID('uuid-role-user')
            ->post(route('guests.auth.create', ['ref' => static::REF_NEW]));
        $response->assertStatus(201);
    }

    public function test_role_guest_access()
    {
        $response = $this->withTokenByUUID('uuid-role-guest')
            ->post(route('guests.auth.create', ['ref' => static::REF_NEW]));
        $response->assertStatus(403);
    }

    public function test_create_new_client_new_hash()
    {
        $this->assertDatabaseMissing(User::class, ['ref' => static::REF_NEW]);

        $response = $this->withTokenByUUID(static::UUID)
            ->post(route('guests.auth.create', ['ref' => static::REF_NEW]));

        $response->assertStatus(201);
        $this->assertDatabaseHas(User::class, ['ref' => static::REF_NEW, 'role' => User::ROLE_GUEST]);
        $this->assertDatabaseHas(AuthHash::class, ['user_id' => User::where('ref', static::REF_NEW)->first()->id ?? null]);
    }

    public function test_create_new_hash_inactive_exist_hash()
    {
        $user_id = User::where('ref', static::REF_EXIST)->first()->id;
        $this->assertEquals(2, AuthHash::where('user_id', $user_id)->count());

        $response = $this->withTokenByUUID(static::UUID)
            ->post(route('guests.auth.create', ['ref' => static::REF_EXIST]));

        $response->assertStatus(201);
        $this->assertDatabaseHas(AuthHash::class, [
            'user_id' => $user_id,
            'hash' => static::HASH_ACTIVE,
            'status' => AuthHash::STATUS_INACTIVE]);
        $this->assertDatabaseHas(AuthHash::class, [
            'user_id' => $user_id,
            'hash' => static::HASH_EXPIRED,
            'status' => AuthHash::STATUS_ACTIVE]);
        $this->assertEquals(3, AuthHash::where('user_id', $user_id)->count());
    }
}
