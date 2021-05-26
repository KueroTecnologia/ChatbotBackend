<?php


declare(strict_types=1);

namespace KED\Module\Setting\Middleware\Payment;

use function KED\get_js_file_url;
use KED\Services\Helmet;
use KED\Services\Http\Request;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Response;

class PaymentSettingMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($request->getMethod() == 'POST')
            return $delegate;
        $this->getContainer()->get(Helmet::class)->setTitle('Payment setting');
        $response->addWidget(
            'payment_setting',
            'content',
            10,
            get_js_file_url("production/setting/payment/payment.js", true)
        );

        return $delegate;
    }
}