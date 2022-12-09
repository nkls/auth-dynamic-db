<?php

namespace OxfordRisk\Common\Tests\Seeders;

use OxfordRisk\Common\Tests\Readers\Contracts\ContractReader;
use OxfordRisk\Common\Tests\Seeders\Contracts\ContractSeeder;

class Seeder implements ContractSeeder
{
    public const FILE = 'file';
    public const READER = 'reader';
    public const MODEL = 'model';

    protected const CONFIG = [];

    /**
     * array key:
     * self::FILE => __DIR__ . '/Seeds/name.json',
     * self::READER => Reader::class,
     * self::MODEL => Model::class,
     */
    protected array $config = [];

    protected ContractReader $reader;
    protected ?string $file;

    public function __construct(array $config = [])
    {
        $this->config = array_merge(static::CONFIG, $config);
        $this->reader = app($this->config[self::READER]);
        $this->file = $this->config[self::FILE] ?? null;
    }

    public function run(): void
    {
        collect($this->data())
            ->map(function ($fields): void {
                $item = app($this->config[self::MODEL]);
                collect($fields)->map(function ($value, $field) use ($item): void {
                    $item->$field = $value;
                });
                $item->save();
            });
    }

    protected function data(): array
    {
        if (empty($this->file)) {
            return [];
        }

        return $this->reader->content($this->file);
    }
}
