<?php

namespace Tests\Feature\Admin\Users;

use App\Models\Organisation\User;
use Illuminate\Validation\Rule;
use Tests\Seeders\UsersSeeder;
use Tests\TestCase;

class CreateTest extends TestCase
{
    protected const SEEDERS = [
        UsersSeeder::class,
    ];

    protected const ID_ADMIN = 1;
    protected const UUID_ADMIN = 'uuid-role-admin';
    protected const UUID_GUEST = 'uuid-role-guest';
    protected const UUID_USER = 'uuid-role-user';

    public function test_unauthorized()
    {
        $response = $this->post(route('users.create'));
        $response->assertStatus(401);
    }

    /**
     * @param string $uuid
     * @param int $code
     * @return void
     * @dataProvider dataRoles
     */
    public function test_role_access(string $uuid, int $code): void
    {
        $response = $this->withTokenByUUID($uuid)
            ->post(route('users.create'), []);
        $response->assertStatus($code);
    }

    protected function dataRoles(): array
    {
        return [
            [static::UUID_ADMIN, 201],
            [static::UUID_USER, 403],
            [static::UUID_GUEST, 403],
        ];
    }

    /**
     * @param array $data
     * @return void
     * @dataProvider dataSuccessfulCreate
     */
    public function test_successful_create(array $data): void
    {
        $response = $this->withTokenByUUID(static::UUID_ADMIN)
            ->post(route('users.create'), $data);
        $response->assertStatus(201);
        $user = User::orderBy('id', 'desc')->first();

        $this->assertTrue($user->id != ($data['id'] ?? null));
        $this->assertTrue($user->uuid != ($data['uuid'] ?? null));
        $this->assertTrue($user->role === ($data['role'] ?? User::ROLE_USER));
        $this->assertTrue($user->ref === ($data['ref'] ?? null));
        $this->assertTrue($user->name === ($data['name'] ?? null));
        $this->assertTrue($user->email === ($data['email'] ?? null));
        $this->assertTrue($user->status === ($data['status'] ?? User::STATUS_ACTIVE));
        $this->assertNull($user->password);
    }

    protected function dataSuccessfulCreate(): array
    {
        return [
            [['id' => 10]],
            [['uuid' => 'uuid-uuid-uuid-uuid']],
            [['role' => User::ROLE_ADMIN]],
            [['role' => User::ROLE_GUEST]],
            [['ref' => null]],
            [['ref' => 'ref-ref-ref-ref']],
            [['name' => 'Name']],
            [['email' => 'email@example.com']],
            [['password' => 'password']],
            [['status' => User::STATUS_ACTIVE]],
            [['status' => User::STATUS_INACTIVE]],
        ];
    }

    /**
     * @param array $data
     * @return void
     * @dataProvider dataUnsuccessfulCreate
     */
    public function test_unsuccessful_create(array $data): void
    {
        $response = $this->withTokenByUUID(static::UUID_ADMIN)
            ->post(route('users.create'), $data);
        $response->assertStatus(400);
    }

    protected function dataUnsuccessfulCreate(): array
    {
        return [
            [['role' => null]],
            [['role' => 'not-exist-role']],
            [['ref' => 'ref_test_client']],
            [['name' => null]],
            [['email' => null]],
            [['email' => 'wrong-format']],
            [['email' => 'test@example.com']],
        ];
    }
}
