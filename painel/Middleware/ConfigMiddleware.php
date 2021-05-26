<?php


declare(strict_types=1);

namespace KED\Middleware;

use function KED\get_config;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class ConfigMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        // Set time zone
        @date_default_timezone_set(get_config('general_timezone', 'Europe/London'));

        return $delegate;
    }
}