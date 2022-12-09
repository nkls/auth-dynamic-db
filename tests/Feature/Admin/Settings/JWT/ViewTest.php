<?php

namespace Tests\Feature\Admin\Settings\JWT;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Organisation\Setting;
use App\Models\Organisation\User;
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
            Seeder::FILE => __DIR__ . '/../../../../Seeders/Seeds/data/settings/jwt.json',
        ],
    ];

    public function test_get_unauthorised_settings()
    {
        $response = $this->get(route('settings.jwt.view'));

        $response->assertStatus(401);
    }

    public function test_get_jwt_settings()
    {
        $response = $this->withTokenByUUID(static::UUID)
            ->get(route('settings.jwt.view'));

        $response->assertOk();
        $this->assertEquals(['ttl' => 60], $response->json());
    }

    public function test_get_default_jwt_settings()
    {
        Setting::where('param', 'jwt')->delete();

        $response = $this->withTokenByUUID(static::UUID)
            ->get(route('settings.jwt.view'));

        $response->assertOk();
        $this->assertEquals(
            ['ttl' => config('jwt.ttl')],
            $response->json()
        );
    }

    public function test_access_denied_for_role_user()
    {
        User::where('uuid', static::UUID)->update(['role' => User::ROLE_USER]);
        $response = $this->withTokenByUUID(static::UUID)
            ->get(route('settings.jwt.view'));
        $response->assertStatus(403);
    }
}
