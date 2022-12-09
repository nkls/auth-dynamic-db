<?php

namespace Tests\Feature\Admin\Settings\Saml2;

use App\Models\Organisation\User;
use Slides\Saml2\Models\Tenant;
use Tests\Seeders\Saml2Seeder;
use Tests\Seeders\UsersSeeder;
use Tests\TestCase;

class CreateTest extends TestCase
{
    protected const UUID = 'uuid-role-admin';

    protected const SEEDERS = [
        UsersSeeder::class,
        Saml2Seeder::class,
    ];

    public function test_get_unauthorised()
    {
        $response = $this->post(route('settings.saml2.create'), []);

        $response->assertStatus(401);
    }

    public function test_bad_request()
    {
        $response = $this->withTokenByUUID(static::UUID)
            ->post(route('settings.saml2.create'), []);

        $response->assertStatus(400);
    }

    public function test_create()
    {
        $this->assertDatabaseCount(Tenant::class, 2);

        $response = $this->withTokenByUUID(static::UUID)
            ->post(route('settings.saml2.create'), [
                'key' => 'create_test_key',
                'idp_entity_id' => 'idp_entity_id',
                'idp_login_url' => 'idp_login_url',
                'idp_logout_url' => 'idp_logout_url',
                'idp_x509_cert' => 'idp_x509_cert',
                'relay_state_url' => 'relay_state_url',
                'name_id_format' => 'persistent',
                'metadata' => ['metadata' => 'metadata'],
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseCount(Tenant::class, 3);
        $this->assertDatabaseHas(Tenant::class, ['key' => 'create_test_key']);
    }

    public function test_prevent_double_key()
    {
        $response = $this->withTokenByUUID(static::UUID)
            ->post(route('settings.saml2.create'), [
                'key' => 'saml-key',
                'idp_entity_id' => 'idp_entity_id',
                'idp_login_url' => 'idp_login_url',
                'idp_logout_url' => 'idp_logout_url',
                'idp_x509_cert' => 'idp_x509_cert',
                'relay_state_url' => 'relay_state_url',
                'name_id_format' => 'persistent',
                'metadata' => ['metadata' => 'metadata'],
            ]);

        $response->assertStatus(400);
    }

    public function test_access_denied_for_role_user()
    {
        User::where('uuid', static::UUID)->update(['role' => User::ROLE_USER]);
        $response = $this->withTokenByUUID(static::UUID)
            ->post(route('settings.saml2.create'), [
                'key' => 'create_test_key',
                'idp_entity_id' => 'idp_entity_id',
                'idp_login_url' => 'idp_login_url',
                'idp_logout_url' => 'idp_logout_url',
                'idp_x509_cert' => 'idp_x509_cert',
                'relay_state_url' => 'relay_state_url',
                'name_id_format' => 'persistent',
                'metadata' => ['metadata' => 'metadata'],
            ]);
        $response->assertStatus(403);
    }
}
