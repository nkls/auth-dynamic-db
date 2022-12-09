<?php

namespace Tests\Feature\Admin\Settings\Saml2;

use App\Models\Organisation\User;
use Tests\Seeders\Saml2Seeder;
use Tests\Seeders\UsersSeeder;
use Tests\TestCase;

class ViewTest extends TestCase
{
    protected const UUID = 'uuid-role-admin';
    protected const FIELD_ID = 1;
    protected const FIELD_KEY = 'saml-key';
    protected const FIELD_UUID = '61a568e1-9f46-4ffa-8c23-4acd4c632117';

    protected const SEEDERS = [
        Saml2Seeder::class,
        UsersSeeder::class,
    ];

    public function test_get_unauthorised()
    {
        $response = $this->get(
            route('settings.saml2.view', ['key' => 'wrong_key']));

        $response->assertStatus(401);
    }

    public function test_get_by_id()
    {
        $response = $this->withTokenByUUID(static::UUID)
            ->get(
                route('settings.saml2.view', ['key' => static::FIELD_ID]));

        $response->assertOk();
        $response->assertSee([
            static::FIELD_ID,
            static::FIELD_UUID,
            static::FIELD_KEY,
            'idp_entity_id',
            'idp_login_url',
            'idp_logout_url',
            'idp_x509_cert',
            'metadata',
            'name_id_format',
        ]);
    }

    public function test_get_by_uuid()
    {
        $response = $this->withTokenByUUID(static::UUID)
            ->get(
                route('settings.saml2.view', ['key' => static::FIELD_UUID]));

        $response->assertOk();
        $response->assertSee([
            static::FIELD_ID,
            static::FIELD_UUID,
            static::FIELD_KEY,
            'idp_entity_id',
            'idp_login_url',
            'idp_logout_url',
            'idp_x509_cert',
            'metadata',
            'name_id_format',
        ]);
    }

    public function test_get_by_key()
    {
        $response = $this->withTokenByUUID(static::UUID)
            ->get(
                route('settings.saml2.view', ['key' => static::FIELD_KEY]));

        $response->assertOk();
        $response->assertSee([
            static::FIELD_UUID,
            static::FIELD_KEY,
            'idp_entity_id',
            'idp_login_url',
            'idp_logout_url',
            'idp_x509_cert',
            'metadata',
            'name_id_format',
        ]);
    }

    public function test_access_denied_for_role_user()
    {
        User::where('uuid', static::UUID)->update(['role' => User::ROLE_USER]);
        $response = $this->withTokenByUUID(static::UUID)
            ->get(
                route('settings.saml2.view', ['key' => static::FIELD_KEY]));
        $response->assertStatus(403);
    }
}
