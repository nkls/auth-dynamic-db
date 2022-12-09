<?php

namespace Tests\Seeders;

use OxfordRisk\Common\Tests\Readers\JsonReader;
use OxfordRisk\Common\Tests\Seeders\Contracts\ContractSeeder;
use OxfordRisk\Common\Tests\Seeders\Seeder;
use Slides\Saml2\Models\Tenant;

class Saml2Seeder extends Seeder implements ContractSeeder
{
    protected const CONFIG = [
        self::FILE => __DIR__ . '/Seeds/data/saml2.json',
        self::READER => JsonReader::class,
        self::MODEL => Tenant::class,
    ];
}
