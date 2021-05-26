<?php


declare(strict_types=1);

namespace KED\Module\Customer\Middleware\Auth;


use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Services\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AuthMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($request->getCustomer()->isLoggedIn()) {
            $redirect = new RedirectResponse($this->getContainer()->get(Router::class)->generateUrl('homepage'));
            return $redirect->send();
        }

        $email = $request->request->get('email');
        $password = $request->request->get('password');
        try {
            $request->getCustomer()->login($email, $password);
            $response->redirect($this->getContainer()->get(Router::class)->generateUrl('homepage'));
        } catch (\Exception $e) {
            $response->addAlert('customer_login_error', 'error', $e->getMessage())->notNewPage();
        }

        return $delegate;
    }
}