<?php


declare(strict_types=1);

/** @var \KED\Services\Routing\Router $router */

$router->addAdminRoute('order.grid', 'GET', '/orders', [
    \KED\Module\Order\Middleware\Grid\GridMiddleware::class,
    //\KED\Module\Order\Middleware\Grid\ButtonMiddleware::class,
]);

/** @var \KED\Services\Routing\Router $router */
$router->addAdminRoute('order.edit', 'GET', '/order/edit/{id:\d+}', [
    \KED\Module\Order\Middleware\Edit\InitMiddleware::class,
    \KED\Module\Order\Middleware\Edit\InfoMiddleware::class,
    \KED\Module\Order\Middleware\Edit\ItemsMiddleware::class,
    \KED\Module\Order\Middleware\Edit\ShippingAddressMiddleware::class,
    \KED\Module\Order\Middleware\Edit\BillingAddressMiddleware::class,
    \KED\Module\Order\Middleware\Edit\PaymentMiddleware::class,
    \KED\Module\Order\Middleware\Edit\ShipmentMiddleware::class,
    \KED\Module\Order\Middleware\Edit\ActivityMiddleware::class,
    \KED\Module\Order\Middleware\Edit\SummaryMiddleware::class,
]);

/** @var \KED\Services\Routing\Router $router */
$router->addAdminRoute('order.offline.pay', 'GET', '/order/pay/offline/{id:\d+}', [
    \KED\Module\Order\Middleware\Update\InitPromiseMiddleware::class,
    \KED\Module\Order\Middleware\Update\Payment\PayOfflineMiddleware::class
]);

$router->addAdminRoute('order.offline.refund', 'GET', '/order/refund/offline/{id:\d+}', [
    \KED\Module\Order\Middleware\Update\InitPromiseMiddleware::class,
    \KED\Module\Order\Middleware\Update\Payment\RefundOfflineMiddleware::class
]);

/** @var \KED\Services\Routing\Router $router */
$router->addAdminRoute('order.ship.start', 'GET', '/order/ship/start/{id:\d+}', [
    \KED\Module\Order\Middleware\Update\InitPromiseMiddleware::class,
    \KED\Module\Order\Middleware\Update\Shipment\StartShipmentMiddleware::class
]);
/** @var \KED\Services\Routing\Router $router */
$router->addAdminRoute('order.ship.complete', 'GET', '/order/ship/complete/{id:\d+}', [
    \KED\Module\Order\Middleware\Update\InitPromiseMiddleware::class,
    \KED\Module\Order\Middleware\Update\Shipment\CompleteShipmentMiddleware::class
]);
////////////////////////////////////////////
///            SITE ROUTERS           //////
////////////////////////////////////////////

