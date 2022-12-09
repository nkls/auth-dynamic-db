<?php

namespace Tests\Feature\User\Me;

use App\Models\Organisation\User;
use Tests\Seeders\UsersSeeder;
use Tests\TestCase;

class ViewTest extends TestCase
{

    protected const USER_UUID = 'uuid-role-admin';

    protected const SEEDERS = [
        UsersSeeder::class,
    ];

    public function test_return_unauthorized_response()
    {
        $response = $this->get(route('me'));

        $response->assertStatus(401);
    }

    public function test_get_current_user_response()
    {
        $user = User::where('uuid', static::USER_UUID)->first();

        $response = $this->withTokenByUUID(static::USER_UUID)
            ->get(route('me'));

        $response->assertOk();
        $response->assertSee(static::USER_UUID);
        $this->assertEquals($user->uuid, $response->json('uuid'));
        $this->assertEquals($user->name, $response->json('name'));
        $this->assertEquals($user->email, $response->json('email'));
    }

    public function test_inactive_user()
    {
        User::where('uuid', static::USER_UUID)->update(['status' => User::STATUS_INACTIVE]);
        $response = $this->withTokenByUUID(static::USER_UUID)
            ->get(route('me'));

        $response->assertStatus(423);
    }
}
