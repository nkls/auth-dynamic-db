<?php

namespace Tests\Feature\Auth\Saml2;

use Tests\Seeders\Saml2Seeder;
use Tests\Seeders\UsersSeeder;
use Tests\TestCase;

class AcsTest extends TestCase
{
    protected const DB_UUID = 'b71547e4-3c34-4d53-84e3-f31ff86bebba';
    protected const SAML_UUID = 'e09a42f9-d129-4b6b-8261-b287094ac3b0';
    protected const USER_UUID = 'uuid-role-admin';

    protected const SEEDERS = [
        UsersSeeder::class,
        Saml2Seeder::class,
    ];

    public function test_bad_request()
    {
        $response = $this->post(route('saml.acs', [
            'org_uuid' => static::DB_UUID,
            'uuid' => static::SAML_UUID],
        ), []);

        $response->assertStatus(400);
    }

}
