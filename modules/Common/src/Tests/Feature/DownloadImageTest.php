<?php

namespace OxfordRisk\Common\Tests\Feature;

use Illuminate\Support\Facades\Http;
use OxfordRisk\Common\Tests\Responses\ImageResponse;
use OxfordRisk\Common\Helpers\DownloadFileHelper;
use Tests\TestCase;

class DownloadImageTest extends TestCase
{
    public function testGetImage()
    {
        Http::fake(
            $this->mockResponse([ImageResponse::class])
        );

        $fileInfo = new \finfo(FILEINFO_MIME_TYPE);

        $this->assertEquals(
            'image/jpeg',
            $fileInfo->buffer(
                app(DownloadFileHelper::class)->get('https://test.co.uk/000001.jpg')
            )
        );
    }
}
