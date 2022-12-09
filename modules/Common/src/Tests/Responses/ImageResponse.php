<?php

namespace OxfordRisk\Common\Tests\Responses;

use OxfordRisk\Common\Tests\Readers\RawReader;

class ImageResponse extends HttpResponse
{
    public const CONFIG = [
        'reader' => RawReader::class,
        'url' => 'https://test.co.uk/000001.jpg',
        'file' => __DIR__ . '/Data/000001.jpg',
        'status' => 200,
        'headers' => ['Headers'],
    ];
}
