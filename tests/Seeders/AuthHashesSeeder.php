<?php

namespace Tests\Seeders;

use App\Models\Organisation\AuthHash;
use App\Models\Organisation\User;
use Carbon\Carbon;
use OxfordRisk\Common\Tests\Readers\JsonReader;
use OxfordRisk\Common\Tests\Seeders\Contracts\ContractSeeder;
use OxfordRisk\Common\Tests\Seeders\Seeder;

class AuthHashesSeeder extends Seeder implements ContractSeeder
{
    protected const CONFIG = [
        self::FILE => __DIR__ . '/Seeds/data/auth_hashes.json',
        self::READER => JsonReader::class,
        self::MODEL => AuthHash::class,
    ];

    protected function data(): array
    {
        return collect(parent::data())
            ->map(function ($fields) {
                return collect($fields)->mapWithKeys(function ($value, $field) {
                    if ($field === 'user.uuid') {
                        $field = 'user_id';
                        $value = User::where('uuid', $value)->first()->id;
                    }

                    if ($field === 'expires' && is_numeric($value)) {
                        $value = Carbon::now()->addDays($value);
                    }

                    return [$field => $value];
                })
                    ->all();

            })
            ->toArray();
    }
}
