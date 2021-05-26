<?php


declare(strict_types=1);

namespace KED\Module\Checkout\Middleware\Cart\Add;


use function KED\generate_url;
use function KED\get_js_file_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class ButtonMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($request->isAdmin() == false && $request->getMethod() == "GET")
            $response->addWidget(
                'add_to_cart_button',
                'product_item',
                50,
                get_js_file_url("production/checkout/cart/buy_button.js", false),
                [
                    "addItemApi" => generate_url('cart.add')
                ]
            );

        return $delegate;
    }
}