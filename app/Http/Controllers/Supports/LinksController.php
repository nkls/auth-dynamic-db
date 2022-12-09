<?php

namespace App\Http\Controllers\Supports;

use App\Helpers\DataBase;
use App\Helpers\Message;
use App\Helpers\Supports\Links;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\JsonResponse;

class LinksController extends Controller
{

    public function login(string $subdomain): JsonResponse
    {
        try {
            if (!DataBase::setDefaultBySubdomain($subdomain)) {
                throw new Exception('Cannot resolve database via subdomain.');
            }

            return response()->json(
                app(Links::class)->login()
            );
        } catch (Exception $e) {
            Message::get(400);
        }
    }
}
