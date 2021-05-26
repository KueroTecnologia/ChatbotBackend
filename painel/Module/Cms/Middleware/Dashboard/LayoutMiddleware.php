<?php


declare(strict_types=1);

namespace KED\Module\Cms\Middleware\Dashboard;


use function KED\get_js_file_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class LayoutMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($response->hasWidget('dashboard_layout'))
            return $delegate;

        $response->addWidget(
            'dashboard_layout',
            'content',
            10,
            get_js_file_url("production/cms/dashboard/layout.js", true)
        );

        return $delegate;
    }
}