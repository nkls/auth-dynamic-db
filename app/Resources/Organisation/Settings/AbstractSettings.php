<?php

namespace App\Resources\Organisation\Settings;

use App\Resources\Organisation\SettingResource;
use stdClass;

abstract class AbstractSettings
{
    protected ?stdClass $data = null;
    protected stdClass $default;
    protected static array $class;

    public function __construct()
    {
        $this->setDefault();
    }

    public static function init(): static
    {
        if (!isset(static::$class[static::class])) {
            static::$class[static::class] = new static();
        }

        return static::$class[static::class];
    }

    public static function get(string $name = null): mixed
    {
        if ($name) {
            return static::init()->all()->{$name} ?? static::init()->getDefault($name);
        }

        return static::init()->all();
    }

    public function all(): stdClass
    {
        if (is_null($this->data)) {
            $this->data = (object)(app(SettingResource::class)->getData(static::PARAM)
                ?: $this->getDefault());
        }

        return $this->data;
    }

    public function update(array $data): bool
    {
        $this->resetData();

        return app(SettingResource::class)->update(static::PARAM, $data);
    }

    public function delete(): bool
    {
        $this->resetData();

        return app(SettingResource::class)->delete(static::PARAM);
    }

    protected function resetData(): void
    {
        $this->data = null;
    }

    protected function setDefault(): void
    {
        $this->default = (object)[];
    }


    protected function getDefault(string $name = null): mixed
    {
        if ($name) {
            return $this->default->{$name} ?? null;
        }

        return $this->default;
    }
}
