<?php

namespace OxfordRisk\Common\Tests\Feature;

use OxfordRisk\Common\Models\Sample;
use OxfordRisk\Common\Tests\Seeders\SamplesSeeder;
use Tests\TestCase;

class SamplesTest extends TestCase
{
    protected const SEEDERS = [
        SamplesSeeder::class,
    ];

    public function testDataSeeded()
    {
        $table = app(Sample::class)->getTable();

        $this->assertDatabaseHas($table, [
            'data' => 'test data 1'
        ]);
        $this->assertDatabaseHas($table, [
            'data' => 'test data 2',
            'custom' => '2021-09-20 13:03:28',
        ]);
        $this->assertDatabaseHas($table, [
            'data' => 'test data 3',
            'custom' => '2021-09-20 15:00:00',
        ]);
    }
}
