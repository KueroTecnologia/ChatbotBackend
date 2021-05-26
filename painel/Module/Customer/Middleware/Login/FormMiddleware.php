<?php


declare(strict_types=1);

namespace KED\Module\Customer\Middleware\Login;


use function KED\get_js_file_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Helmet;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Services\Routing\Router;

class FormMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($request->getCustomer()->isLoggedIn()) {
            $response->redirect($this->getContainer()->get(Router::class)->generateUrl('homepage'));
            return $response;
        }
        $response->addWidget(
            'customer_registration_form',
            'content_center',
            10,
            get_js_file_url("production/customer/login_form.js", false),
            [
                'action' => $this->getContainer()->get(Router::class)->generateUrl('customer.auth'),
                'registerUrl' => $this->getContainer()->get(Router::class)->generateUrl('customer.register')
            ]
        );
        $this->getContainer()->get(Helmet::class)->setTitle('Login');
        return $delegate;
    }
}