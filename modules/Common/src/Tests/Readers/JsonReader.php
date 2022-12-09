<?php

namespace OxfordRisk\Common\Tests\Readers;

use OxfordRisk\Common\Tests\Readers\Contracts\ContractReader;
use Illuminate\Filesystem\Filesystem;

class JsonReader implements ContractReader
{
    protected object $driver;

    public function __construct()
    {
        $this->driver = new Filesystem();
    }

    public function content(string $path): array
    {
        return json_decode($this->driver->get($path), true);
    }
}
