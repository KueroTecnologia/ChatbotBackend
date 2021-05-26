<?php


declare(strict_types=1);

/** @var \KED\Services\Routing\Router $router */

$router->addAdminRoute('coupon.grid', 'GET', '/coupons', [
    \KED\Module\Discount\Middleware\Grid\GridMiddleware::class,
    \KED\Module\Discount\Middleware\Grid\AddNewButtonMiddleware::class,
]);

$router->addAdminRoute('coupon.create', 'GET', '/coupon/create', [
    \KED\Module\Discount\Middleware\Edit\LoadCustomerGroupMiddleware::class,
    \KED\Module\Discount\Middleware\Edit\FormMiddleware::class
]);

$router->addAdminRoute('coupon.edit', 'GET', '/coupon/edit/{id:\d+}', [
    \KED\Module\Discount\Middleware\Edit\LoadCustomerGroupMiddleware::class,
    \KED\Module\Discount\Middleware\Edit\FormMiddleware::class
]);

$router->addAdminRoute('coupon.delete', 'GET', '/coupon/delete/{id:\d+}', [
    \KED\Module\Discount\Middleware\Delete\DeleteMiddleware::class,
]);

$router->addAdminRoute('coupon.save', "POST", '/coupon/save[/{id:\d+}]', [
    \KED\Module\Discount\Middleware\Save\SaveMiddleware::class
]);

$router->addAdminRoute('discount.install', ["POST", "GET"], '/discount/migrate/install', [
    \KED\Module\Discount\Middleware\Migrate\Install\InstallMiddleware::class
]);

$router->addSiteRoute('coupon.add', 'POST', '/cart/coupon/add', [
    \KED\Module\Discount\Middleware\Cart\AddCouponMiddleware::class
]);