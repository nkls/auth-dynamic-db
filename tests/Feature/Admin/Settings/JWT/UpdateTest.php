<?php

namespace Tests\Feature\Admin\Settings\JWT;

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
            Seeder::FILE => __DIR__ . '/../../../../Seeders/Seeds/data/settings/jwt.json',
        ],
    ];

    public function test_unauthorised_settings()
    {
        $response = $this->patch(route('settings.jwt.update'), ['ttl' => 60]);

        $response->assertStatus(401);
    }

    public function test_create_jwt_settings()
    {
        Setting::where('param', 'jwt')->delete();

        $response = $this->withTokenByUUID(static::UUID)
            ->patch(route('settings.jwt.update'), ['ttl' => 60, 'refresh_ttl' => 180]);

        $response->assertOk();
        $this->assertDatabaseHas(Setting::class, [
            'param' => 'jwt',
            'data' => json_encode(['ttl' => 60])
        ]);
    }

    public function test_update_jwt_settings()
    {
        $this->assertDatabaseHas(Setting::class, [
            'param' => 'jwt',
            'data' => json_encode(['ttl' => 60])
        ]);

        $response = $this->withTokenByUUID(static::UUID)
            ->patch(route('settings.jwt.update'), ['ttl' => '30']);

        $response->assertOk();
        $this->assertDatabaseHas(Setting::class, [
            'param' => 'jwt',
            'data' => json_encode(['ttl' => '30'])
        ]);
    }

    public function test_validate_jwt_settings()
    {
        $response = $this->withTokenByUUID(static::UUID)
            ->patch(route('settings.jwt.update'), ['ttl' => 30.5]);
        $response->assertSee('The ttl must be an integer.');

        $response = $this->withTokenByUUID(static::UUID)
            ->patch(route('settings.jwt.update'), ['ttl' => 'foo']);
        $response->assertSee('The ttl must be an integer.');

        $response = $this->withTokenByUUID(static::UUID)
            ->patch(route('settings.jwt.update'), ['refresh_ttl' => 200]);
        $response->assertSee('The ttl field is required.');

        $response = $this->withTokenByUUID(static::UUID)
            ->patch(route('settings.jwt.update'), []);
        $response->assertSee([
            'The ttl field is required.'
        ]);
    }


    public function test_access_denied_for_role_user()
    {
        User::where('uuid', static::UUID)->update(['role' => User::ROLE_USER]);
        $response = $this->withTokenByUUID(static::UUID)
            ->get(route('settings.jwt.update'));
        $response->assertStatus(403);
    }
}
