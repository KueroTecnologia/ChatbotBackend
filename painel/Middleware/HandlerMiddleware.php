<?php


declare(strict_types=1);

namespace KED\Middleware;

use KED\Module\Cms\Middleware\Page\View\NotFoundPageMiddleware;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Services\MiddlewareManager;

class HandlerMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($response->getStatusCode() == 405) {
            return $response;
        }

        $routedMiddleware = $request->attributes->get('_routed_middleware');

        if ($response->getStatusCode() == 404) {
            $routedMiddleware = [NotFoundPageMiddleware::class];
        }

        $mm = new MiddlewareManager($this->getContainer(), $routedMiddleware);

        return $mm->run();
    }
}