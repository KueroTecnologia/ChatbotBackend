<?php



declare(strict_types=1);

/** @var \KED\Services\Routing\Router $router */

$router->addAdminRoute('setting.general', ["POST", "GET"], '/setting/general', [
    \KED\Module\Setting\Middleware\General\FormMiddleware::class,
    \KED\Module\Setting\Middleware\General\SaveMiddleware::class
]);

$router->addAdminRoute('setting.catalog', ["POST", "GET"], '/setting/catalog', [
    \KED\Module\Setting\Middleware\Catalog\FormMiddleware::class,
    \KED\Module\Setting\Middleware\Catalog\SaveMiddleware::class
]);

$router->addAdminRoute('setting.payment', ["POST", "GET"], '/setting/payment[/{method}]', [
    \KED\Module\Setting\Middleware\Payment\PaymentSettingMiddleware::class,
    \KED\Module\Setting\Middleware\Payment\CODFormMiddleware::class,
    \KED\Module\Setting\Middleware\Payment\CODSaveMiddleware::class,
]);

$router->addAdminRoute('setting.shipment', ["POST", "GET"], '/setting/shipment[/{method}]', [
    \KED\Module\Setting\Middleware\Shipment\ShipmentSettingMiddleware::class,
    \KED\Module\Setting\Middleware\Shipment\FlatRateFormMiddleware::class,
    \KED\Module\Setting\Middleware\Shipment\FlatRateSaveMiddleware::class,
]);

/* MIGRATION */
$router->addAdminRoute('setting.install', ["POST", "GET"], '/setting/migrate/install', [
    \KED\Module\Setting\Middleware\Migrate\InstallMiddleware::class
]);