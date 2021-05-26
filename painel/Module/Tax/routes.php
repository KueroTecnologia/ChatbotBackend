<?php



declare(strict_types=1);

/** @var \KED\Services\Routing\Router $router */

$router->addAdminRoute('tax.class.list', 'GET', '/taxes', [
    \KED\Module\Tax\Middleware\Edit\TaxClassMiddleware::class
]);

$router->addAdminRoute('tax.class.save', "POST", '/tax/save', [
    \KED\Module\Tax\Middleware\Save\SaveMiddleware::class
]);

$router->addAdminRoute('tax.install', ["POST", "GET"], '/tax/migrate/install', [
    \KED\Module\Tax\Middleware\Migrate\Install\InstallMiddleware::class
]);