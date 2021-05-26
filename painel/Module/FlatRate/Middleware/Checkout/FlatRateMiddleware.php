<?php


declare(strict_types=1);

namespace KED\Module\FlatRate\Middleware\Checkout;


use function KED\generate_url;
use function KED\get_config;
use function KED\get_js_file_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class FlatRateMiddleware extends MiddlewareAbstract
{
    /**
     * @param Request $request
     * @param Response $response
     * @param null $delegate
     * @return null
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if (
            get_config('shipment_flat_rate_status') != 1
        )
            return $delegate;

        $response->addWidget(
            'flat_rate_shipment',
            'checkout_shipping_method_block',
            (int) get_config('shipment_flat_rate_sort_order', 10),
            get_js_file_url("production/flat_rate/flat_rate.js"),
            [
                "label" => get_config("shipment_flat_rate_title", "Flat rate"),
                "fee" => get_config("shipment_flat_rate_fee", 0),
                "countries" => get_config("shipment_flat_rate_countries", []),
                "apiUrl" => generate_url("checkout.set.shipment")
            ]
        );

        return $delegate;
    }
}