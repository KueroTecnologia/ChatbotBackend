<?php


declare(strict_types=1);

namespace KED\Module\User\Middleware\Authenticate;

use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LogoutMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response)
    {
        if ($request->getUser()) {
            $request->getSession()->set('user_id', null);
        }

        $redirect = new RedirectResponse($this->getContainer()->get(Router::class)->generateUrl('admin.login'));
        return $redirect->send();
    }
}