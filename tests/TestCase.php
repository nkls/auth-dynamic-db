<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use OxfordRisk\Common\Tests\Seeders\Seeder;
use Tests\Seeders\ShardCoordinatorsSeeder;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;

    /**
     * string:class => array:config
     *
     * default Seeder::class
     * default:Seeder::class => array:config
     */
    protected const SEEDERS = [];
    protected const DEFAULT_SEEDERS = [ShardCoordinatorsSeeder::class];

    public function setUp(): void
    {
        parent::setUp();
        config([
            'jwt.algo' => 'HS256',
            'jwt.secret' => '8d23a1b6c408d3a52b98c835a13a52b67408db98ccb8a089'
        ]);

        $this->artisan('migrate', ['--path' => '/database/migrations/route']);

        foreach (array_merge(self::DEFAULT_SEEDERS, static::SEEDERS) as $key => $value) {
            $class = is_int($key) && is_string($value) ? $value : $key;
            $config = is_array($value) ? $value : [];

            if(!is_subclass_of($class, Seeder::class)) {
                $class = Seeder::class;
            }

            app($class, ['config' => $config])->run();
        }
    }

    public function withTokenByUUID(string $uuid): static
    {
        config(['database.connections.mysql.database' => 'auth_test']);

        return $this->withToken(auth()->tokenById($uuid));
    }

    protected function mockResponse(array $mock): array
    {
        foreach ($mock as $class) {
            $settings = array_merge($settings ?? [], app($class)->get());
        }

        return $settings ?? [];
    }
}
