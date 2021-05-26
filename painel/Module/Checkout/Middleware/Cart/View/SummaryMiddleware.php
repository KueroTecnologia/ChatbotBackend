<?php

declare(strict_types=1);

namespace KED\Module\Checkout\Middleware\Cart\View;

use function KED\generate_url;
use function KED\get_js_file_url;
use KED\Module\Checkout\Services\Cart\Cart;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\MiddlewareAbstract;

class SummaryMiddleware extends MiddlewareAbstract
{
    /**
     * @param Request $request
     * @param Response $response
     * @param null $delegate
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($this->getContainer()->get(Cart::class)->isEmpty())
            return $delegate;

        $response->addWidget(
            'shopping_cart_summary',
            'shopping-cart-page',
            20,
            get_js_file_url("production/checkout/cart/summary.js"),
            [
                "checkoutUrl"=> generate_url('checkout.index'),
            ]
        );

        return $delegate;
    }
}