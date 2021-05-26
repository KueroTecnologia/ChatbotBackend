<?php


declare(strict_types=1);

namespace KED\Module\Customer\Middleware\Checkout;


use function KED\generate_url;
use function KED\get_js_file_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class AccountMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response)
    {
        $response->addWidget(
            'checkout.login.form',
            'checkout_page',
            5,
            get_js_file_url("production/customer/checkout/contact_form.js"),
            [
                "loginUrl" => generate_url('customer.auth'),
                "setContactUrl" => generate_url('checkout.set.contact')
            ]
        );
    }
}