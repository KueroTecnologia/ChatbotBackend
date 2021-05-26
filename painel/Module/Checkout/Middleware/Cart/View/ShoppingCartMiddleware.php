<?php


declare(strict_types=1);

namespace KED\Module\Checkout\Middleware\Cart\View;

use function KED\get_js_file_url;
use KED\Module\Checkout\Services\Cart\Cart;
use KED\Services\Helmet;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Routing\Router;

class ShoppingCartMiddleware extends MiddlewareAbstract
{
    /**
     * @param Request $request
     * @param Response $response
     * @param null $delegate
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $this->getContainer()->get(Helmet::class)->setTitle('Shopping cart');
        $response->addWidget(
            'shopping_cart_page',
            'content_center',
            10,
            get_js_file_url("production/checkout/cart/shopping-cart.js"),
            [

            ]
        );

        return $delegate;
    }
}