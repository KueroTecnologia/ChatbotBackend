<?php


declare(strict_types=1);

namespace KED\Module\Cms\Middleware\Page\View;

use KED\Services\Helmet;
use KED\Services\Http\Response;
use KED\Services\Http\Request;
use KED\Middleware\MiddlewareAbstract;

class NotFoundPageMiddleware extends MiddlewareAbstract
{

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($response->getStatusCode()!== 404)
            return $delegate;

        $this->getContainer()->get(Helmet::class)->setTitle('Page not found');
        return $delegate;
    }
}