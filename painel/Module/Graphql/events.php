<?php


declare(strict_types=1);

/** @var \KED\Services\Event\EventDispatcher $eventDispatcher */

$eventDispatcher->addListener(
    'register.core.middleware',
    function (\KED\Services\MiddlewareManager $middlewareManager) {
        $middlewareManager->registerMiddleware(\KED\Module\Graphql\Middleware\Graphql\AddApiUrlStateMiddleware::class, 51);
    },
    0
);

$eventDispatcher->addListener(
    'register.core.middleware',
    function (\KED\Services\MiddlewareManager $middlewareManager) {
        $middlewareManager->registerMiddlewareBefore(\KED\Middleware\PromiseWaiterMiddleware::class, \KED\Module\Graphql\Middleware\Graphql\AddServerExecutorPromiseMiddleware::class);
    },
    0
);