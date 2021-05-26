<?php


declare(strict_types=1);

namespace KED\Module\Customer\Middleware\Register;


use function KED\get_base_url;
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
        if ($request->getCustomer()->isLoggedIn() == true) {
            $response->redirect($this->getContainer()->get(Router::class)->generateUrl('homepage'));
            return $response;
        }

        $this->getContainer()->get(Helmet::class)->setTitle("Register for an account");

        $response->addWidget(
            'customer_registration_form',
            'content_center',
            10,
            get_js_file_url("production/customer/registration_form.js", false),
            [
                'action' => $this->getContainer()->get(Router::class)->generateUrl('customer.register.post'),
                'redirectUrl' => get_base_url(false)
            ]
        );

        return $delegate;
    }
}