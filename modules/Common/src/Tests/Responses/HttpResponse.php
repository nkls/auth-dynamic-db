<?php

namespace OxfordRisk\Common\Tests\Responses;

use OxfordRisk\Common\Tests\Readers\Contracts\ContractReader;
use Illuminate\Support\Facades\Http;

class HttpResponse
{
    public const CONFIG = [];
    protected ContractReader $reader;

    public function __construct()
    {
        $this->reader = app(static::CONFIG['reader']);
    }

    public function get(?string $url = null): array
    {
        $key = $url ?? static::CONFIG['url'];

        return [
            $key => Http::response(
                $this->response(),
                static::CONFIG['status'],
                static::CONFIG['headers']
            )
        ];
    }

    protected function response()
    {
        return $this->reader->content(static::CONFIG['file']);
    }
}
