<?php

namespace Tests\Feature\Admin\Settings\User;

use App\Models\Organisation\Setting;
use App\Models\Organisation\User;
use OxfordRisk\Common\Tests\Seeders\Seeder;
use Tests\Seeders\SettingsSeeder;
use Tests\Seeders\UsersSeeder;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    protected const UUID = 'uuid-role-admin';

    protected const SEEDERS = [
        UsersSeeder::class,
        SettingsSeeder::class => [
            Seeder::FILE => __DIR__ . '/../../../../Seeders/Seeds/data/settings/user.json',
        ],
    ];

    public function test_unauthorised_settings()
    {
        $response = $this->patch(route('settings.user.update'), ['hash_ttl' => 60]);

        $response->assertStatus(401);
    }

    public function test_create_settings()
    {
        Setting::where('param', 'user')->delete();

        $response = $this->withTokenByUUID(static::UUID)
            ->patch(route('settings.user.update'), ['reset_password_ttl' => 60]);

        $response->assertOk();
        $this->assertDatabaseHas(Setting::class, [
            'param' => 'user',
            'data' => json_encode(['reset_password_ttl' => 60])
        ]);
    }

    public function test_update_settings()
    {
        $this->assertDatabaseHas(Setting::class, [
            'param' => 'user',
            'data' => json_encode(['reset_password_ttl' => 120])
        ]);

        $response = $this->withTokenByUUID(static::UUID)
            ->patch(route('settings.user.update'), ['reset_password_ttl' => '70']);

        $response->assertOk();
        $this->assertDatabaseHas(Setting::class, [
            'param' => 'user',
            'data' => json_encode(['reset_password_ttl' => '70'])
        ]);
    }

    public function test_validate_settings()
    {
        $response = $this->withTokenByUUID(static::UUID)
            ->patch(route('settings.user.update'), ['reset_password_ttl' => 30.5]);
        $response->assertSee('The reset password ttl must be an integer.');

        $response = $this->withTokenByUUID(static::UUID)
            ->patch(route('settings.user.update'), ['reset_password_ttl' => 'foo']);
        $response->assertSee('The reset password ttl must be an integer.');

        $response = $this->withTokenByUUID(static::UUID)
            ->patch(route('settings.user.update'), []);
        $response->assertSee([
            'The reset password ttl field is required.'
        ]);
    }

    public function test_access_denied_for_role_user()
    {
        User::where('uuid', static::UUID)->update(['role' => User::ROLE_USER]);
        $response = $this->withTokenByUUID(static::UUID)
            ->get(route('settings.user.update'));
        $response->assertStatus(403);
    }

}
