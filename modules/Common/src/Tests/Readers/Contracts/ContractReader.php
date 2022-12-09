<?php

namespace OxfordRisk\Common\Tests\Readers\Contracts;

interface ContractReader
{
    public function content(string $path);
}
