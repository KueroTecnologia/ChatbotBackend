<?php

declare(strict_types=1);

namespace KED\Module\Cms\Middleware\Widget\Edit;


use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Services\Routing\Router;

class GetLayoutsMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $siteRoutes = array_filter($this->getContainer()->get(Router::class)->getSiteRoutes(), function ($v, $k) {
            return is_string($v[0]) && strtoupper($v[0]) == "GET";
        }, ARRAY_FILTER_USE_BOTH );

        $response->addState("layouts", (function () use ($siteRoutes) {
            $a = [];
            foreach ($siteRoutes as $key => $route)
                $a[] = $key;
            return $a;
        })());
        return $delegate;
    }
}