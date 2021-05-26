<?php



declare(strict_types=1);

/** @var \KED\Services\Routing\Router $router */

$router->addAdminRoute('setting.sendgrid', ["POST", "GET"], '/setting/sendgrid', [
    \KED\Module\SendGrid\Middleware\Setting\FormMiddleware::class,
    \KED\Module\SendGrid\Middleware\Setting\SaveMiddleware::class
]);
