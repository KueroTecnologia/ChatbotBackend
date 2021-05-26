<?php


declare(strict_types=1);

/** @var \KED\Services\Routing\Router $router */
$router->addSiteRoute('graphql.api', ['GET', 'POST'], '/api/graphql', [
    \KED\Module\Graphql\Middleware\Graphql\GraphqlQLMiddleware::class,
]);

$router->addAdminRoute('admin.graphql.api', ['GET', 'POST'], '/api/graphql', [
    \KED\Module\Graphql\Middleware\Graphql\GraphqlQLMiddleware::class
]);