<?php


declare(strict_types=1);

return [
    [
        'method'   => 'GET',
        'path'     => '/paypal_express/pay',
        'middleware' => [
            \KED\Module\Checkout\Middleware\Cart\View\DataMiddleware::class,
            \KED\Module\Checkout\Middleware\Cart\View\ResponseMiddleware::class
        ],
        'is_admin' => false
    ],
    [
        'method'   => 'GET',
        'path'     => '/paypal_express/success',
        'middleware' => [
            \KED\Module\PaypalExpress\Middleware\Pay\PaymentInitMiddleware::class,
            \KED\Module\PaypalExpress\Middleware\Success\ExecutePaymentMiddleware::class
        ],
        'is_admin' => false
    ],
    [
        'method'   => 'GET',
        'path'     => '/paypal_express/failure',
        'middleware' => [
            \KED\Module\Checkout\Middleware\Cart\Edit\EditMiddleware::class,
        ],
        'is_admin' => false
    ]
];