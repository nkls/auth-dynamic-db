<?php

namespace Tests\Feature\Supports\Links;

use Tests\Seeders\Saml2Seeder;
use Tests\TestCase;

class LoginTest extends TestCase
{

    protected const SUBDOMAIN = 'test';

    protected const SEEDERS = [
        Saml2Seeder::class,
    ];

    public function test_links_response(): void
    {
        $response = $this->get(route('supports.links.login', ['subdomain' => static::SUBDOMAIN]));

        $response->assertStatus(200);
        $response->assertSee([
            'saml-key',
            '61a568e1-9f46-4ffa-8c23-4acd4c632117',
            'microsoft',
            'e09a42f9-d129-4b6b-8261-b287094ac3b0',
        ]);
    }
}
