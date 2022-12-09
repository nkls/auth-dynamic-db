<?php

namespace Tests\Feature\Auth\Local;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Organisation\User;
use Tests\Seeders\UsersSeeder;
use Tests\TestCase;

class RefreshTest extends TestCase
{

    protected const USER_UUID = 'uuid-role-admin';

    protected const SEEDERS = [
        UsersSeeder::class,
    ];

    public function test_refresh_token()
    {
        $response = $this->withTokenByUUID(static::USER_UUID)
            ->post(route('user.refresh.token'));

        $response->assertOk();
        $response->assertSeeText(['access_token', 'token_type', 'expires_in']);
    }

    public function test_inactive_user()
    {
        User::where('uuid', static::USER_UUID)->update(['status' => User::STATUS_INACTIVE]);
        $response = $this->withTokenByUUID(static::USER_UUID)
            ->post(route('user.refresh.token'));

        $response->assertStatus(423);
    }
}
