<?php

namespace OxfordRisk\Common\Helpers;

use Illuminate\Support\Facades\Http;

/**
 * Class DownloadFileHelper.
 */
class DownloadFileHelper
{
    public function get(string $url): string
    {
        if (app()->environment('production')) {
            $response = Http::get($url);
        } else {
            $response = Http::withoutVerifying()
                ->get($url);
        }

        if (!$contents = $response->body()) {
            return new \InvalidArgumentException("$url returned empty contents");
        }

        return $contents;
    }
}
