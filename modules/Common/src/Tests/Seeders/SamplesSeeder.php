<?php

namespace OxfordRisk\Common\Tests\Seeders;

use OxfordRisk\Common\Models\Sample;
use OxfordRisk\Common\Tests\Seeders\Contracts\ContractSeeder;
use OxfordRisk\Common\Tests\Readers\JsonReader;

class SamplesSeeder extends Seeder implements ContractSeeder
{
    protected const CONFIG = [
        self::FILE => __DIR__ . '/Seeds/samples.json',
        self::READER => JsonReader::class,
        self::MODEL => Sample::class,
    ];
}
