<?php

namespace OxfordRisk\Common\Tests\Feature;

use OxfordRisk\Common\Models\Sample;
use OxfordRisk\Common\Tests\Seeders\CustomSamplesSeeder;
use Tests\TestCase;

class CustomSamplesTest extends TestCase
{
    protected const SEEDERS = [
        CustomSamplesSeeder::class,
    ];

    public function testDataSeeded()
    {
        $table = app(Sample::class)->getTable();

        $this->assertDatabaseHas($table, [
            'data' => 'Custom test data 1'
        ]);
        $this->assertDatabaseHas($table, [
            'data' => 'Custom test data 2',
            'custom' => '2021-09-21 13:03:28',
        ]);
        $this->assertDatabaseHas($table, [
            'data' => 'Custom test data 3',
            'custom' => '2021-09-21 15:00:00',
        ]);
    }
}
