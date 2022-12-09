<?php

namespace Tests\Feature\Admin\Settings\Saml2;

use App\Models\Organisation\User;
use Tests\Seeders\Saml2Seeder;
use Tests\Seeders\UsersSeeder;
use Tests\TestCase;

class IndexTest extends TestCase
{
    protected const UUID = 'uuid-role-admin';

    protected const SEEDERS = [
        Saml2Seeder::class,
        UsersSeeder::class,
    ];

    public function test_get_unauthorised()
    {
        $response = $this->get(route('settings.saml2.index'));

        $response->assertStatus(401);
    }

    public function test_get_list()
    {
        $response = $this->withTokenByUUID(static::UUID)->get(route('settings.saml2.index'));

        $response->assertOk();
        $response->assertSee([
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
        $response = $this->withTokenByUUID(static::UUID)->get(route('settings.saml2.index'));
        $response->assertStatus(403);
    }

}
