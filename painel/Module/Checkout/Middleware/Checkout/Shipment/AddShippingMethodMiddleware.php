<?php


declare(strict_types=1);

namespace KED\Module\Checkout\Middleware\Checkout\Shipment;


use KED\Middleware\MiddlewareAbstract;
use KED\Module\Checkout\Services\Cart\Cart;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class AddShippingMethodMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $this->getContainer()->get(Cart::class)
            ->setData('shipping_method_name', $request->request->get('method_name'));
        $promise = $this->getContainer()->get(Cart::class)
            ->setData('shipping_method', $request->request->get('method_code'));

        $promise->then(function ($value) use ($response) {
            $response->addData('success', 1);
            $response->notNewPage();
        }, function ($reason) use ($response) {
            $response->addData('success', 0)->addData('message', $reason);
            $response->addAlert("checkout_shipping_method", "error", "Something wrong. Please try again.");
            $response->notNewPage();
        });

        return $delegate;
    }
}