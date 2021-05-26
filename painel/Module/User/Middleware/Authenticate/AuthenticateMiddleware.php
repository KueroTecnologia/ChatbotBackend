<?php


declare(strict_types=1);

namespace KED\Module\User\Middleware\Authenticate;

use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Module\User\Services\Authenticator;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Routing\Router;

class AuthenticateMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response)
    {
        try {
            $email = $request->get('email', null);
            $password = $request->get('password', null);
            $this->getContainer()->get(Authenticator::class)->login($email, $password);
            $response->addData('success', 1)->redirect($this->getContainer()->get(Router::class)->generateUrl('dashboard'))->notNewPage();

            return $response;
        } catch (\Exception $e) {
            $response->addAlert('login_error', 'error', $e->getMessage())->notNewPage();

            return $response;
        }
    }
}