<?php


declare(strict_types=1);

namespace KED\Module\Checkout\Middleware\Checkout\Success;


use function KED\generate_url;
use function KED\get_js_file_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Helmet;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Services\Routing\Router;

class MessageMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if (!$request->getSession()->get('orderId')) {
            $response->redirect($this->getContainer()->get(Router::class)->generateUrl('homepage'));

            return $response;
        };

        if ($request->isAjax())
            $request->getSession()->remove('orderId');

        $this->getContainer()->get(Helmet::class)->setTitle("Checkout success");
        $response->addWidget(
            'checkout_success_message',
            'content_center',
            10,
            get_js_file_url("production/checkout/checkout/success/message.js"),
            [
                "message" => "Thank you, we will send you a notification email",
                "homeUrl" => generate_url("homepage")
            ]
        );

        return $delegate;
    }
}