<?php

namespace Tests\Feature\Admin\Users;

use Tests\Seeders\UsersSeeder;
use Tests\TestCase;

class ViewTest extends TestCase
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
        $response = $this->get(route('users.view', ['key' => 'foo']));
        $response->assertStatus(401);
    }

    /**
     * @dataProvider dataRoles
     * @return void
     */
    public function test_role_access(string $uuid, int $code): void
    {
        $response = $this->withTokenByUUID($uuid)
            ->get(route('users.view', ['key' => static::UUID_USER]));
        $response->assertStatus($code);
    }

    protected function dataRoles(): array
    {
        return [
            [static::UUID_ADMIN, 200],
            [static::UUID_USER, 403],
            [static::UUID_GUEST, 403],
        ];
    }

    /**
     * @dataProvider dataView
     * @return void
     */
    public function test_view(int|string $key, int $code, array $see): void
    {
        $response = $this->withTokenByUUID(static::UUID_ADMIN)
            ->get(
                route('users.view', ['key' => $key])
            );
        $response->assertStatus($code);
        $response->assertSee($see);
    }

    protected function dataView(): array
    {
        return [
            [static::UUID_ADMIN, 200, [static::UUID_ADMIN, 'test@example.com']],
            [static::ID_ADMIN, 200, [static::UUID_ADMIN, 'test@example.com']],
            [static::UUID_USER, 200, [static::UUID_USER, 'user@example.com']],
            [static::UUID_GUEST, 200, [static::UUID_GUEST, 'client@example.com']],
            ['not-exist', 404, []],
        ];
    }
}
