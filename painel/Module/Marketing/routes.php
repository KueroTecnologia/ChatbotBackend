<?php


declare(strict_types=1);

/** @var \KED\Services\Routing\Router $router */
$router->addAdminRoute('subscriber.grid', 'GET', '/subscribers', [
    \KED\Module\Marketing\Middleware\Newsletter\GridMiddleware::class
]);

$router->addSiteRoute('newsletter.subscribe', 'POST', '/newsletter/subscribe', [
    \KED\Module\Marketing\Middleware\Newsletter\SubscribeMiddleware::class
]);

$router->addSiteRoute('newsletter.unsubscribe', 'POST', '/newsletter/unsubscribe', [
    \KED\Module\Marketing\Middleware\Newsletter\UnsubscribeMiddleware::class
]);

$router->addAdminRoute('admin.newsletter.unsubscribe', 'POST', '/newsletter/unsubscribe', [
    \KED\Module\Marketing\Middleware\Newsletter\UnsubscribeMiddleware::class
]);