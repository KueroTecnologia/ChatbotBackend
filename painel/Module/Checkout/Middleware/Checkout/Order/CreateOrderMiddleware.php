<?php

declare(strict_types=1);

namespace KED\Module\Checkout\Middleware\Checkout\Order;


use KED\Middleware\MiddlewareAbstract;
use KED\Module\Checkout\Services\Cart\Cart;
use KED\Module\Order\Services\OrderUpdatePromise;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Services\PromiseWaiter;

class CreateOrderMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $cart = $this->getContainer()->get(Cart::class);
        $promise = $cart->createOrder();
        $promise->then(function ($orderId) {
            $this->getContainer()->get(PromiseWaiter::class)->addPromise('orderCreated', new OrderUpdatePromise($orderId));
        });
        $promise->then(function ($orderId) use ($request) {
            $request->getSession()->set('orderId', $orderId);
        } ,function (\Exception $e) use ($response) {
            $response->addAlert('create_order_error', 'error', $e->getMessage())->notNewPage();
        });

        return $promise;
    }
}