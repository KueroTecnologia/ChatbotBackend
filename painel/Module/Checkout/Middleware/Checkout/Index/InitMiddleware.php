<?php


declare(strict_types=1);

namespace KED\Module\Checkout\Middleware\Checkout\Index;


use function KED\get_js_file_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Module\Checkout\Services\Cart\Cart;
use KED\Services\Helmet;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Services\Routing\Router;

class InitMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $cart = $this->getContainer()->get(Cart::class);
        if ($cart->isEmpty())
            return $response->redirect($this->getContainer()->get(Router::class)->generateUrl('checkout.cart'));

        $items = $cart->getItems();
        foreach ($items as $item)
            if ($item->getError())
                return $response->redirect($this->getContainer()->get(Router::class)->generateUrl('checkout.cart'));
        $this->getContainer()->get(Helmet::class)->setTitle('Checkout page');

        $cart->setData("payment_method", null);
        $cart->setData("shipping_method", null);

        $response->addWidget(
            'checkout_page',
            'content_center',
            20,
            get_js_file_url("production/checkout/checkout/checkout_page.js")
        );

        return $delegate;
    }
}