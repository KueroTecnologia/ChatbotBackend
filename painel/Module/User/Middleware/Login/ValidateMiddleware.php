<?php


declare(strict_types=1);

namespace KED\Module\User\Middleware\Login;

use KED\Services\Helmet;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Routing\Router;

class ValidateMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($request->getUser()) {
            $response->addData('success', 1)->redirect($this->getContainer()->get(Router::class)->generateUrl('dashboard'));
            return $response;
        }
        $this->getContainer()->get(Helmet::class)->setTitle("Admin login");

        return $delegate;
    }
}