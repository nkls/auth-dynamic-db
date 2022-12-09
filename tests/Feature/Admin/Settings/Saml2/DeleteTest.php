<?php

namespace Tests\Feature\Admin\Settings\Saml2;

use App\Models\Organisation\User;
use Slides\Saml2\Models\Tenant;
use Tests\Seeders\Saml2Seeder;
use Tests\Seeders\UsersSeeder;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    protected const UUID = 'uuid-role-admin';
    protected const FIELD_ID = 1;
    protected const FIELD_KEY = 'saml-key';
    protected const FIELD_UUID = '61a568e1-9f46-4ffa-8c23-4acd4c632117';

    protected const SEEDERS = [
        Saml2Seeder::class,
        UsersSeeder::class,
    ];

    public function test_delete_unauthorised()
    {
        $response = $this->delete(
            route('settings.saml2.delete', ['key' => 'wrong-value']));

        $response->assertStatus(401);
    }

    public function test_delete_not_found()
    {
        $response = $this->withTokenByUUID(static::UUID)
            ->delete(
                route('settings.saml2.delete', ['key' => 'wrong-value']));

        $response->assertStatus(404);
    }

    public function test_delete_by_id()
    {
        $this->assertEquals(2, Tenant::count());

        $response = $this->withTokenByUUID(static::UUID)
            ->delete(
                route('settings.saml2.delete', ['key' => static::FIELD_ID]));

        $response->assertOk();
        $this->assertEquals(1, Tenant::count());
    }

    public function test_delete_by_uuid()
    {
        $this->assertEquals(2, Tenant::count());

        $response = $this->withTokenByUUID(static::UUID)
            ->delete(
                route('settings.saml2.delete', ['key' => static::FIELD_UUID]));

        $response->assertOk();
        $this->assertEquals(1, Tenant::count());
    }

    public function test_delete_by_key()
    {
        $this->assertEquals(2, Tenant::count());

        $response = $this->withTokenByUUID(static::UUID)
            ->delete(
                route('settings.saml2.delete', ['key' => static::FIELD_KEY]));

        $response->assertOk();
        $this->assertEquals(1, Tenant::count());
    }

    public function test_access_denied_for_role_user()
    {
        User::where('uuid', static::UUID)->update(['role' => User::ROLE_USER]);
        $response = $this->withTokenByUUID(static::UUID)
            ->delete(
                route('settings.saml2.delete', ['key' => static::FIELD_KEY]));

        $response->assertStatus(403);
    }
}
