<?php

namespace App\Resources\Organisation;

use App\Models\Organisation\Setting;
use stdClass;

class SettingResource
{

    public function getData(string $param): false|stdClass
    {
        return Setting::where('param', $param)->select('data')->first()->data ?? false;
    }

    public function update(string $param, array $data): bool
    {
        return (bool)Setting::updateOrCreate(
            ['param' => $param],
            ['data' => $data]
        );
    }

    public function delete(string $param): bool
    {
        return Setting::where(['param' => $param])->delete();
    }
}
