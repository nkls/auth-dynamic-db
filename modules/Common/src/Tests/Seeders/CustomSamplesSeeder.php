<?php

namespace OxfordRisk\Common\Tests\Seeders;

use OxfordRisk\Common\Models\Sample;
use Carbon\Carbon;
use OxfordRisk\Common\Tests\Seeders\Contracts\ContractSeeder;
use OxfordRisk\Common\Tests\Readers\JsonReader;

class CustomSamplesSeeder extends Seeder implements ContractSeeder
{
    const CONFIG = [
        'file' => __DIR__ . '/Seeds/samples.json',
        'reader' => JsonReader::class,
        'model' => Sample::class,
    ];

    public function run(): void
    {
        collect($this->data())
            ->map(function ($fields): void {
                $item = app(static::CONFIG['model']);
                $item->data = 'Custom ' . $fields['data'];
                $item->custom = isset($fields['custom'])
                    ? Carbon::parse($fields['custom'])->addDay()
                    : null;
                $item->save();
            });
    }
}
