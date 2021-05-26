<?php

declare(strict_types=1);

namespace KED\Module\Cms\Middleware\Dashboard;


use KED\Middleware\MiddlewareAbstract;
use KED\Services\Helmet;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class TitleMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($response->hasWidget('dashboard_title'))
            return $delegate;

        $this->getContainer()->get(Helmet::class)->setTitle("Dashboard");

        return $delegate;
    }
}