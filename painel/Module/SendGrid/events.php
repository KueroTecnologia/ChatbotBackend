<?php


declare(strict_types=1);

/** @var \KED\Services\Event\EventDispatcher $eventDispatcher */

use KED\Services\Routing\Router;

$eventDispatcher->addListener(
    "admin_menu",
    function (array $items) {
        return array_merge($items, [
            [
                "id" => "setting_sendgrid",
                "sort_order" => 50,
                "url" => \KED\generate_url("setting.sendgrid"),
                "title" => "SendGrid",
                "icon" => "mail-bulk",
                "parent_id" => "setting"
            ]
        ]);
    },
    0
);

$eventDispatcher->addListener('register.customer.register.post.middleware', function (\KED\Services\MiddlewareManager $mm) {
    $mm->registerMiddlewareAfter(\KED\Module\Customer\Middleware\Create\CreateAccountMiddleware::class, \KED\Module\SendGrid\Middleware\Customer\SendWelcomeEmailMiddleware::class);
});

$eventDispatcher->addListener('register.checkout.order.middleware', function (\KED\Services\MiddlewareManager $mm) {
    $mm->registerMiddlewareAfter(\KED\Module\Checkout\Middleware\Checkout\Order\CreateOrderMiddleware::class, \KED\Module\SendGrid\Middleware\Order\SendConfirmationEmailMiddleware::class);
});
