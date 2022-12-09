<?php

namespace Tests\Seeders;

use App\Models\Organisation\User;
use OxfordRisk\Common\Tests\Readers\JsonReader;
use OxfordRisk\Common\Tests\Seeders\Contracts\ContractSeeder;
use OxfordRisk\Common\Tests\Seeders\Seeder;

class UsersSeeder extends Seeder implements ContractSeeder
{
    protected const CONFIG = [
        self::FILE => __DIR__ . '/Seeds/data/users.json',
        self::READER => JsonReader::class,
        self::MODEL => User::class,
    ];
}
