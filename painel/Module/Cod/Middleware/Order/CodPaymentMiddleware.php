<?php


declare(strict_types=1);

namespace KED\Module\Cod\Middleware\Order;


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
        $response->addWidget(
            'cod_payment_action',
            'order_payment_block_info',
            30,
            get_js_file_url("production/cod/order/cod_action.js", true),
            [
                'orderEditUrl' => generate_url('order.edit', ['id'=>$request->attributes->get('id')]),
                'payOfflineUrl' => generate_url('order.offline.pay', ['id'=>$request->attributes->get('id')]),
                'refundOfflineUrl' => generate_url('order.offline.refund', ['id'=>$request->attributes->get('id')])
            ]
        );

        return $delegate;
    }
}