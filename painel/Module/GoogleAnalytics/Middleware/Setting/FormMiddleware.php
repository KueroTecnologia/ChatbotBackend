<?php


declare(strict_types=1);

namespace KED\Module\GoogleAnalytics\Middleware\Setting;

use function KED\get_js_file_url;
use KED\Services\Http\Request;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Response;

class FormMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($request->getMethod() == 'POST')
            return $delegate;

        $response->addWidget(
            'general_google_analytics',
            'general_setting_form_web',
            50,
            get_js_file_url("production/google_analytics/setting_code.js", true)
        );

        return $delegate;
    }
}