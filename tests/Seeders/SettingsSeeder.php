<?php

namespace Tests\Seeders;

use App\Models\Organisation\Setting;
use OxfordRisk\Common\Tests\Readers\JsonReader;
use OxfordRisk\Common\Tests\Seeders\Contracts\ContractSeeder;
use OxfordRisk\Common\Tests\Seeders\Seeder;

class SettingsSeeder extends Seeder implements ContractSeeder
{
    protected const CONFIG = [
        self::READER => JsonReader::class,
        self::MODEL => Setting::class,
    ];
}
