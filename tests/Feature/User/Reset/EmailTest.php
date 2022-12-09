<?php

namespace Tests\Feature\User\Reset;

use App\Models\Organisation\User;
use Tests\Seeders\UsersSeeder;
use Tests\TestCase;

class EmailTest extends TestCase
{
    protected const SUBDOMAIN = 'test';
    protected const EMAIL = 'user@example.com';

    protected const SEEDERS = [
        UsersSeeder::class,
    ];

    public function test_wrong_subdomain()
    {
        $response = $this->post(route('user.reset.email'), [
            'subdomain' => 'wrong_subdomain',
        ]);
        $response->assertStatus(400);

        $response = $this->post(route('user.reset.email'));
        $response->assertStatus(400);
    }

    public function test_wrong_email()
    {
        $response = $this->post(route('user.reset.email'), [
            'subdomain' => static::SUBDOMAIN,
        ]);
        $response->assertStatus(400);

        $response = $this->post(route('user.reset.email'), [
            'subdomain' => static::SUBDOMAIN,
            'email' => 'not_exist@example.com'
        ]);
        $response->assertStatus(404);
    }

    public function test_inactive_user()
    {
        User::where('email', static::EMAIL)->update(['status' => User::STATUS_INACTIVE]);
        $response = $this->post(route('user.reset.email'), [
            'subdomain' => static::SUBDOMAIN,
            'email' => static::EMAIL,
        ]);
        $response->assertStatus(423);
    }

    public function test_success()
    {
        $response = $this->post(route('user.reset.email'), [
            'subdomain' => static::SUBDOMAIN,
            'email' => static::EMAIL,
        ]);
        $response->assertStatus(200);
    }
}
