<?php

namespace Tests\Feature\Admin\Users;

use Tests\Seeders\UsersSeeder;
use Tests\TestCase;

class IndexTest extends TestCase
{
    protected const SEEDERS = [
        UsersSeeder::class,
    ];

    protected const UUID_ADMIN = 'uuid-role-admin';
    protected const UUID_GUEST = 'uuid-role-guest';
    protected const UUID_USER = 'uuid-role-user';

    public function test_unauthorized()
    {
        $response = $this->get(route('users.index'));
        $response->assertStatus(401);
    }

    /**
     * @dataProvider dataRoles
     * @return void
     */
    public function test_role_access(string $uuid, int $code): void
    {
       $response = $this->withTokenByUUID($uuid)
            ->get(route('users.index'));
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

    public function test_view(): void
    {
        $response = $this->withTokenByUUID(static::UUID_ADMIN)->get(route('users.index'));
       $response->assertStatus(200);

        $response = $this->withTokenByUUID(static::UUID_ADMIN)
            ->get(route('users.index', ['limit' => 1]));

        $this->assertEquals(1, $response->json('per_page'));
        $this->assertEquals(1, $response->json('current_page'));

        $response = $this->withTokenByUUID(static::UUID_ADMIN)
            ->get(route('users.index', ['limit' => 1, 'page' => 2]));

        $this->assertEquals(2, $response->json('current_page'));
    }

}
