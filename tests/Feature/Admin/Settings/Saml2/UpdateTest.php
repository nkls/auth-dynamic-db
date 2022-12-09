<?php

namespace Tests\Feature\Admin\Settings\Saml2;

use App\Models\Organisation\User;
use Slides\Saml2\Models\Tenant;
use Tests\Seeders\Saml2Seeder;
use Tests\Seeders\UsersSeeder;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    protected const UUID = 'uuid-role-admin';
    protected const FIELD_ID = 1;

    protected const SEEDERS = [
        UsersSeeder::class,
        Saml2Seeder::class,
    ];

    public function test_unauthorised()
    {
        $response = $this->put(route('settings.saml2.update', ['key' => static::FIELD_ID]), []);

        $response->assertStatus(401);
    }

    public function test_bad_request()
    {
        $response = $this->withTokenByUUID(static::UUID)
            ->put(route('settings.saml2.update', ['key' => static::FIELD_ID]), []);

        $response->assertStatus(400);
    }

    public function test_update()
    {
        $response = $this->withTokenByUUID(static::UUID)
            ->put(route('settings.saml2.update', ['key' => static::FIELD_ID]), [
                'key' => 'update_test_key',
                'idp_entity_id' => 'idp_entity_id',
                'idp_login_url' => 'idp_login_url',
                'idp_logout_url' => 'idp_logout_url',
                'idp_x509_cert' => 'idp_x509_cert',
                'relay_state_url' => 'relay_state_url',
                'name_id_format' => 'persistent',
                'metadata' => ['metadata' => 'metadata-update-1'],
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas(Tenant::class, [
            'id' => static::FIELD_ID,
            'metadata' => json_encode(['metadata' => 'metadata-update-1']),
        ]);

        $response = $this->withTokenByUUID(static::UUID)
            ->put(route('settings.saml2.update', ['key' => static::FIELD_ID]), [
                'key' => 'update_test_key',
                'idp_entity_id' => 'idp_entity_id',
                'idp_login_url' => 'idp_login_url',
                'idp_logout_url' => 'idp_logout_url',
                'idp_x509_cert' => 'idp_x509_cert',
                'relay_state_url' => 'relay_state_url',
                'name_id_format' => 'persistent',
                'metadata' => ['metadata' => 'metadata-update-2'],
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas(Tenant::class, [
            'id' => static::FIELD_ID,
            'metadata' => json_encode(['metadata' => 'metadata-update-2']),
        ]);
    }

    public function test_access_denied_for_role_user()
    {
        User::where('uuid', static::UUID)->update(['role' => User::ROLE_USER]);
        $response = $this->withTokenByUUID(static::UUID)
            ->put(route('settings.saml2.update', ['key' => static::FIELD_ID]), []);
        $response->assertStatus(403);
    }
}
