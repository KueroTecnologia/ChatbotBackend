<?php


declare(strict_types=1);
/** @var \KED\Services\Routing\Router $router */

$router->addAdminRoute('admin.login', 'GET', '/login', [
    \KED\Module\User\Middleware\Login\ValidateMiddleware::class,
    \KED\Module\User\Middleware\Login\FormMiddleware::class
]);

$router->addAdminRoute('admin.authenticate', 'POST', '/authenticate', [
    \KED\Module\User\Middleware\Authenticate\AuthenticateMiddleware::class
]);

$router->addAdminRoute('admin.logout', 'GET', '/logout', [
    \KED\Module\User\Middleware\Authenticate\LogoutMiddleware::class
]);