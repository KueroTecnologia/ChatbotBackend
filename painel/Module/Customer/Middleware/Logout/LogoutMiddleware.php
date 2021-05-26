<?php

declare(strict_types=1);

namespace KED\Module\Customer\Middleware\Logout;


use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Services\Routing\Router;

class LogoutMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if (!$request->getCustomer()->isLoggedIn()) {
            $response->redirect($this->getContainer()->get(Router::class)->generateUrl('homepage'));
            return $response;
        }

        $request->getCustomer()->logOut();
        $response->redirect($this->getContainer()->get(Router::class)->generateUrl('homepage'));

        return $delegate;
    }
}