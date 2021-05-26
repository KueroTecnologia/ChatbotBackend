<?php

declare(strict_types=1);

namespace KED\Module\Checkout\Middleware\Logout;


use KED\Middleware\MiddlewareAbstract;
use KED\Module\Checkout\Services\Cart\Cart;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class ClearCartMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($request->getCustomer()->isLoggedIn() == true)
            return $delegate;

        $this->getContainer()->get(Cart::class)->destroy();
        $request->getSession()->remove('cart_id');

        return $delegate;
    }
}