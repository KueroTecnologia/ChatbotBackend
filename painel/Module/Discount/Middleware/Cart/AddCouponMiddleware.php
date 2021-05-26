<?php


declare(strict_types=1);

namespace KED\Module\Discount\Middleware\Cart;


use GuzzleHttp\Promise\RejectedPromise;
use KED\Middleware\MiddlewareAbstract;
use KED\Module\Checkout\Services\Cart\Cart;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Services\Routing\Router;

class AddCouponMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $coupon = $request->request->get('coupon');
        if (!$coupon)
            $promise = new RejectedPromise("Invalid coupon");
        else {
            $cart = $this->getContainer()->get(Cart::class);
            $promise = $cart->setData('coupon', $coupon);
        }

        $promise->then(function ($coupon) use ($request, $response) {
            $response->redirect($this->getContainer()->get(Router::class)->generateUrl('checkout.cart'));
        });

        $promise->otherwise(function ($reason) use ($response) {
            $response->addAlert('coupon_apply_error', 'error', "Invalid coupon")->notNewPage();
        });
    }
}