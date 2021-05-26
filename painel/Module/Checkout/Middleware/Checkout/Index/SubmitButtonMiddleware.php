<?php


declare(strict_types=1);

namespace KED\Module\Checkout\Middleware\Checkout\Index;

use GraphQL\Type\Schema;
use function KED\dirty_output_query;
use function KED\generate_url;
use function KED\get_config;
use function KED\get_js_file_url;
use KED\Module\Checkout\Services\Cart\Cart;
use KED\Module\Graphql\Services\GraphqlExecutor;
use KED\Services\Http\Request;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Response;

class SubmitButtonMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $response->addWidget(
            'checkout_button',
            'checkout_summary_cart',
            80,
            get_js_file_url("production/checkout/checkout/checkout_button.js"),
            [
                "action" => generate_url('checkout.order'),
                "cartId" => $this->getContainer()->get(Cart::class)->getData('cart_id')
            ]
        );

        return $delegate;
    }
}