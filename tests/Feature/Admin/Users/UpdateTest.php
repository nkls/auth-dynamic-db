<?php

namespace Tests\Feature\Admin\Users;

use App\Models\Organisation\User;
use Tests\Seeders\UsersSeeder;
use Tests\TestCase;

class UpdateTest extends TestCase
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
        $response = $this->patch(route('users.update', ['key' => 'foo']));
        $response->assertStatus(401);
    }

    /**
     * @param string $uuid
     * @param int $code
     * @param int|string $key
     * @param array $data
     * @dataProvider dataUpdate
     * @return void
     */
    public function test_update_and_access(string $uuid, int $code, int|string $key, array $data): void
    {
        $response = $this->withTokenByUUID($uuid)
            ->patch(route('users.update', ['key' => $key]), $data);
        $response->assertStatus($code);

        if ($code === 200 && !empty($data)) {
            $this->assertEquals($data[array_key_first($data)], User::oneOf($key)->first()->{array_key_first($data)});
        }
    }

    protected function dataUpdate(): array
    {
        return [
            [static::UUID_ADMIN, 200, static::UUID_USER, ['role' => User::ROLE_ADMIN]],
            [static::UUID_ADMIN, 200, static::ID_ADMIN, ['role' => User::ROLE_USER]],
            [static::UUID_ADMIN, 200, static::ID_ADMIN, ['status' => User::STATUS_INACTIVE]],
            [static::UUID_ADMIN, 200, static::UUID_ADMIN, []],
            [static::UUID_ADMIN, 200, static::ID_ADMIN, ['email' => 'test@example.com']],
            [static::UUID_ADMIN, 200, static::UUID_USER, ['email' => 'user@example.com']],
            [static::UUID_ADMIN, 200, static::UUID_USER, ['email' => 'new@example.com']],
            [static::UUID_ADMIN, 200, static::UUID_USER, ['name' => 'Update Name']],
            [static::UUID_ADMIN, 200, static::UUID_USER, ['ref' => 'new ref']],
            [static::UUID_ADMIN, 200, static::UUID_GUEST, ['ref' => 'ref_test_client']],
            [static::UUID_ADMIN, 200, static::UUID_USER, ['ref' => null]],
            [static::UUID_ADMIN, 400, static::UUID_USER, ['ref' => 'ref_test_client']],
            [static::UUID_ADMIN, 400, static::UUID_USER, ['email' => 'test@example.com']],
            [static::UUID_ADMIN, 400, static::UUID_USER, ['email' => 'test']],
            [static::UUID_ADMIN, 400, static::UUID_USER, ['role' => 'not-exist']],
            [static::UUID_USER, 403, static::UUID_USER, []],
            [static::UUID_GUEST, 403, static::UUID_USER, []],
        ];
    }

    /**
     * @param string $role
     * @dataProvider dataRoles
     * @return void
     */
    public function test_update_role(string $role): void
    {
        $response = $this->withTokenByUUID(static::UUID_ADMIN)
            ->patch(route('users.update', ['key' => static::UUID_GUEST]), ['role' => $role]);

        $response->assertStatus(200);
        $this->assertEquals(strtolower($role), User::oneOf(static::UUID_GUEST)->first()->role);
    }

    protected function dataRoles(): array
    {
        return [
            [User::ROLE_USER],
            ['User'],
            ['uSeR'],
            ['USER'],
        ];
    }

    /**
     * @param string $status
     * @dataProvider dataStatus
     * @return void
     */
    public function test_update_status(string $status): void
    {
        $response = $this->withTokenByUUID(static::UUID_ADMIN)
            ->patch(route('users.update', ['key' => static::UUID_GUEST]), ['status' => $status]);

        $response->assertStatus(200);
        $this->assertEquals(strtolower($status), User::oneOf(static::UUID_GUEST)->first()->status);
    }

    protected function dataStatus(): array
    {
        return [
            [User::STATUS_INACTIVE],
            ['INACTIVE'],
            ['Inactive'],
            ['InAcTiVe'],
        ];
    }
}
