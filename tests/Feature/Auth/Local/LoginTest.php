<?php

namespace Tests\Feature\Auth\Local;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Organisation\User;
use Tests\Seeders\UsersSeeder;
use Tests\TestCase;

class LoginTest extends TestCase
{

    protected const USER_UUID = 'uuid-role-admin';

    protected const SEEDERS = [
        UsersSeeder::class,
    ];

    public function test_the_login_return_unauthorized_response()
    {
        $response = $this->post(route('user.login.password'), ['email' => 'wrong', 'password' => 'wrong', 'subdomain' => 'wrong']);

        $response->assertStatus(401);
    }

    public function test_login_return_token()
    {
        $response = $this->post(route('user.login.password'), [
            'email' => 'test@example.com',
            'password' => 'test',
            'subdomain' => 'test',
        ]);

        $response->assertOk();
        $response->assertSeeText(['access_token', 'token_type', 'expires_in']);
    }

    public function test_inactive_user()
    {
        User::where('uuid', static::USER_UUID)->update(['status' => User::STATUS_INACTIVE]);
        $response = $this->post(route('user.login.password'), [
            'email' => 'test@example.com',
            'password' => 'test',
            'subdomain' => 'test',
        ]);

        $response->assertStatus(423);
    }
}
