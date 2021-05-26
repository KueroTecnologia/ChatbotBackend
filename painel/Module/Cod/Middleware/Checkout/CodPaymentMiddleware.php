<?php


declare(strict_types=1);

namespace KED\Module\Cod\Middleware\Checkout;


use function KED\generate_url;
use function KED\get_config;
use function KED\get_js_file_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Module\Checkout\Services\Cart\Cart;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class CodPaymentMiddleware extends MiddlewareAbstract
{
    /**
     * @param Request $request
     * @param Response $response
     * @param null $delegate
     * @return null
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $cart = $this->getContainer()->get(Cart::class);
        if (
            get_config('payment_cod_status') != 1 ||
            $cart->getData('grand_total') == 0
        )
            return $delegate;

        $response->addWidget(
            'cod_payment',
            'checkout_payment_method_block',
            (int) get_config('payment_cod_sort_order', 10),
            get_js_file_url("production/cod/cod.js"),
            [
                "label"=>get_config('payment_cod_name', "Cash on delivery"),
                "status"=>get_config('payment_cod_status', 1),
                "minTotal"=>get_config('payment_cod_minimum', 0),
                "maxTotal"=>get_config('payment_cod_maximum'),
                "apiUrl" => generate_url('checkout.set.payment')
            ]
        );

        return $delegate;
    }
}