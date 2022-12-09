<?php

namespace Tests\Feature\User\Me;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Organisation\User;
use Tests\Seeders\UsersSeeder;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    protected const UUID = 'uuid-role-admin';

    protected const SEEDERS = [
        UsersSeeder::class,
    ];

    public function test_update_current_user_endpoint()
    {
        $response = $this->withTokenByUUID(static::UUID)
            ->patch(route('me'), ['name' => 'foo']);

        $response->assertOk();
    }

    public function test_role_guest_no_access()
    {
        $response = $this->withTokenByUUID('uuid-role-guest')
            ->patch(route('me'), ['name' => 'foo']);

        $response->assertStatus(403);
    }

    public function test_update_current_user_bad_request()
    {
        $response = $this->withTokenByUUID(static::UUID)
            ->patch(route('me'), []);

        $response->assertStatus(400);
    }

    public function test_update_current_user_name()
    {
        $response = $this->withTokenByUUID(static::UUID)
            ->patch(route('me'), ['name' => '']);

        $response->assertStatus(400);

        $response = $this->withTokenByUUID(static::UUID)
            ->patch(route('me'), ['name' => 'sergey']);

        $this->assertDatabaseHas(User::class, [
            'uuid' => static::UUID,
            'name' => 'sergey',
            'email' => 'test@example.com',
        ]);
    }

    public function test_do_not_update_protected_fields()
    {
        $response = $this->withTokenByUUID(static::UUID)
            ->patch(route('me'), [
                'name' => 'sergey',
                'role' => User::ROLE_USER,
                'uuid' => 'uuid-uuid-uuid',
                'ref' => 'ref',
            ]);

        $this->assertEquals(
            User::where('uuid', static::UUID)
                ->select(['role', 'uuid', 'ref', 'name', 'email'])
                ->first()
                ->toArray(),
            [
                'role' => User::ROLE_ADMIN,
                'uuid' => static::UUID,
                'ref' => null,
                'name' => 'sergey',
                'email' => 'test@example.com',
            ]);
    }

    public function test_update_current_user_email()
    {
        $response = $this->withTokenByUUID(static::UUID)
            ->patch(route('me'), ['email' => 'sergey']);

        $response->assertStatus(400);
        $response->assertSee('The email must be a valid email address.');

        $response = $this->withToken(auth()->tokenById(static::UUID))
            ->patch(route('me'), ['email' => 'sergey@example.com']);

        $this->assertDatabaseHas(User::class, [
            'uuid' => static::UUID,
            'name' => 'test',
            'email' => 'sergey@example.com',
        ]);
    }

    public function test_update_current_user_password()
    {
        User::where('uuid', static::UUID)->update(['password' => null]);
        $response = $this->withTokenByUUID(static::UUID)
            ->patch(route('me'), ['password' => 'pass']);
        $response->assertStatus(400);
        $response->assertSeeText(['errors', 'password']);

        $response = $this->withTokenByUUID(static::UUID)
            ->patch(route('me'), ['password' => 'password']);
        $response->assertStatus(400);
        $response->assertSeeText(['errors', 'password']);

        $response = $this->withTokenByUUID(static::UUID)
            ->patch(route('me'), ['password' => 'PASSword78979']);
        $response->assertStatus(400);
        $response->assertSeeText(['errors', 'password']);

        $response = $this->withTokenByUUID(static::UUID)
            ->patch(route('me'), ['password' => '±%Pa3ss£w0rd&s']);
        $response->assertOk();
        $this->assertNotNull(User::where('uuid', static::UUID)->first()->password);
    }
}
