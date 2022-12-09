<?php

namespace Tests\Feature\Admin\Settings\User;

use App\Models\Organisation\Setting;
use App\Models\Organisation\User;
use OxfordRisk\Common\Tests\Seeders\Seeder;
use Tests\Seeders\SettingsSeeder;
use Tests\Seeders\UsersSeeder;
use Tests\TestCase;

class DeleteTest extends TestCase
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
        $response = $this->delete(route('settings.user.delete'));

        $response->assertStatus(401);
    }

    public function test_delete_settings()
    {
        $response = $this->withTokenByUUID(static::UUID)
            ->delete(route('settings.user.delete'));

        $response->assertOk();
        $this->assertDatabaseMissing(Setting::class, ['param' => 'user']);
    }

    public function test_error_delete_settings()
    {
        Setting::where('param', 'user')->delete();

        $response = $this->withTokenByUUID(static::UUID)
            ->delete(route('settings.user.delete'));

        $response->assertStatus(400);
        $response->assertSee('Cannot delete.');
    }

    public function test_access_denied_for_role_user()
    {
        User::where('uuid', static::UUID)->update(['role' => User::ROLE_USER]);
        $response = $this->withTokenByUUID(static::UUID)
            ->get(route('settings.user.delete'));
        $response->assertStatus(403);
    }
}
