<?php


declare(strict_types=1);

/** @var \KED\Services\Routing\Router $router */

$router->addAdminRoute('customer.grid', 'GET', '/customers', [
    \KED\Module\Customer\Middleware\Grid\GridMiddleware::class,
]);

$router->addAdminRoute('customer.edit', 'GET', '/customer/edit/{id:\d+}', [
    \KED\Module\Customer\Middleware\Edit\CustomerInfoMiddleware::class,
]);

$router->addAdminRoute('customer.install', ["POST", "GET"], '/customer/migrate/install', [
    \KED\Module\Customer\Middleware\Migrate\Install\InstallMiddleware::class
]);

////////////////////////////////////////////
///            SITE ROUTERS           //////
////////////////////////////////////////////

$router->addSiteRoute('customer.register', 'GET', '/customer/register', [
    \KED\Module\Customer\Middleware\Register\FormMiddleware::class,
]);

$router->addSiteRoute('customer.register.post', 'POST', '/customer/register', [
    \KED\Module\Customer\Middleware\Create\CreateAccountMiddleware::class,
    \KED\Module\Customer\Middleware\Create\LoginMiddleware::class
]);

$router->addSiteRoute('customer.login', 'GET', '/customer/login', [
    \KED\Module\Customer\Middleware\Login\FormMiddleware::class,
]);

$router->addSiteRoute('customer.dashboard', 'GET', '/customer/dashboard', [
    \KED\Module\Customer\Middleware\Dashboard\LayoutMiddleware::class,
    \KED\Module\Customer\Middleware\Dashboard\InfoMiddleware::class,
    \KED\Module\Customer\Middleware\Dashboard\AddressMiddleware::class,
    \KED\Module\Customer\Middleware\Dashboard\OrderMiddleware::class,
]);

$router->addSiteRoute('customer.auth', 'POST', '/customer/auth', [
    \KED\Module\Customer\Middleware\Auth\AuthMiddleware::class,
]);

$router->addSiteRoute('customer.update', 'POST', '/customer/update/{id:\d+}', [
    \KED\Module\Customer\Middleware\Update\UpdateAccountMiddleware::class,
]);

$router->addSiteRoute('customer.logout', ["GET"], '/customer/logout', [
    \KED\Module\Customer\Middleware\Logout\LogoutMiddleware::class,
]);

// ADDRESS

$router->addSiteRoute('customer.address.create', 'POST', '/customer/address/create', [
    \KED\Module\Customer\Middleware\Address\CreateMiddleware::class,
]);

$router->addSiteRoute('customer.address.update', 'POST', '/customer/address/update/{id:\d+}', [
    \KED\Module\Customer\Middleware\Address\UpdateMiddleware::class,
]);

$router->addSiteRoute('customer.address.delete', 'POST', '/customer/address/delete/{id:\d+}', [
    \KED\Module\Customer\Middleware\Address\DeleteMiddleware::class,
]);