<?php


declare(strict_types=1);
/** @var \KED\Services\Routing\Router $router */

$router->addSiteRoute('migration.install.form', ["GET"], '/install', [
    \KED\Module\Migration\Middleware\Form\FormMiddleware::class
]);

$router->addSiteRoute('migration.install.post', 'POST', '/install/post', [
    \KED\Module\Migration\Middleware\Post\CreateConfigFileMiddleware::class,
    \KED\Module\Migration\Middleware\Post\CreateMigrationTableMiddleware::class,
    \KED\Module\Migration\Middleware\Post\CreateAdminUserMiddleware::class
]);

$router->addAdminRoute('migration.install.finish', 'POST', '/install/finish', [
    \KED\Module\Migration\Middleware\Finish\FinishMiddleware::class
]);

$router->addAdminRoute('migration.module.install', ["GET", "POST"], '/migration/module/install/{module}', [
    \KED\Module\Migration\Middleware\Module\Install\ValidateMiddleware::class,
    \KED\Module\Migration\Middleware\Module\Install\InstallMiddleware::class
]);

$router->addAdminRoute('migration.module.disable', ["GET", "POST"], '/migration/module/disable/{module}', [
    \KED\Module\Migration\Middleware\Module\Disable\ValidateMiddleware::class,
    \KED\Module\Migration\Middleware\Module\Disable\DisableMiddleware::class
]);

$router->addAdminRoute('migration.module.enable', ["GET", "POST"], '/migration/module/enable/{module}', [
    \KED\Module\Migration\Middleware\Module\Enable\ValidateMiddleware::class,
    \KED\Module\Migration\Middleware\Module\Enable\EnableMiddleware::class
]);

$router->addAdminRoute('extensions.grid', 'GET', '/extensions', [
    \KED\Module\Migration\Middleware\Module\Grid\GridMiddleware::class
]);