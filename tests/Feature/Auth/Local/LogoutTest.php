<?php

namespace Tests\Feature\Auth\Local;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Seeders\UsersSeeder;
use Tests\TestCase;

class LogoutTest extends TestCase
{

    protected const SEEDERS = [
        UsersSeeder::class,
    ];

    public function test_logout()
    {
        $response = $this->withTokenByUUID('uuid-role-admin')
            ->post(route('logout'));

        $response->assertOk();
        $response->assertSee('Successfully logged out');
    }
}
