<?php

namespace Tests\Seeders;

use App\Models\Route\ShardCoordinator;
use OxfordRisk\Common\Tests\Readers\JsonReader;
use OxfordRisk\Common\Tests\Seeders\Contracts\ContractSeeder;
use OxfordRisk\Common\Tests\Seeders\Seeder;

class ShardCoordinatorsSeeder extends Seeder implements ContractSeeder
{
    protected const CONFIG = [
        self::FILE => __DIR__ . '/Seeds/data/shard_coordinators.json',
        self::READER => JsonReader::class,
        self::MODEL => ShardCoordinator::class,
    ];
}
