<?php

namespace Tests\Feature\Admin\Settings\User;

use App\Models\Organisation\User;
use App\Resources\Organisation\Settings\UserSettings;
use OxfordRisk\Common\Tests\Seeders\Seeder;
use Tests\Seeders\SettingsSeeder;
use Tests\Seeders\UsersSeeder;
use Tests\TestCase;

class ViewTest extends TestCase
{
    protected const UUID = 'uuid-role-admin';

    protected const SEEDERS = [
        UsersSeeder::class,
        SettingsSeeder::class => [
            Seeder::FILE => __DIR__ . '/../../../../Seeders/Seeds/data/settings/user.json',
        ],
    ];

    public function test_get_unauthorised_settings()
    {
        $response = $this->get(route('settings.user.view'));

        $response->assertStatus(401);
    }

    public function test_get_settings()
    {
        $response = $this->withTokenByUUID(static::UUID)
            ->get(route('settings.user.view'));

        $response->assertOk();
        $this->assertEquals(['reset_password_ttl' => 120], $response->json());
    }

    public function test_get_default_settings()
    {
        UserSettings::init()->delete();

        $response = $this->withTokenByUUID(static::UUID)
            ->get(route('settings.user.view'));

        $response->assertOk();
        $this->assertEquals(
            ['reset_password_ttl' => config('user.reset_password_ttl')],
            $response->json()
        );
    }

    public function test_access_denied_for_role_user()
    {
        User::where('uuid', static::UUID)->update(['role' => User::ROLE_USER]);
        $response = $this->withTokenByUUID(static::UUID)
            ->get(route('settings.user.view'));
        $response->assertStatus(403);
    }
}
