<?php


declare(strict_types=1);

return [
    [
        'event'    => 'after_add_routed_middleware',
        'callback' => function (\KED\Middleware\HandlerMiddleware $handler, $callable, $next) {
            if ($callable instanceof \KED\Module\Checkout\Middleware\Checkout\Orderbk\CreateOrderMiddleware) {
                $handler->addMiddleware(new \KED\Module\PaypalExpress\Middleware\Pay\CreatePaymentMiddleware(), null);
                $handler->addMiddleware(new \KED\Module\PaypalExpress\Middleware\Pay\PaymentInitMiddleware(), null);
            }
            // Online capture
            if ($callable instanceof \KED\Module\Sale\Middleware\Order\Update\Payment\OfflineCaptureMiddleware) {
                $handler->addMiddleware(new KED\Module\PaypalExpress\Middleware\Capture\CaptureMiddleware(), null);
            }
        },
        'priority' => 0
    ]
];