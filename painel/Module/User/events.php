<?php


declare(strict_types=1);

/** @var \KED\Services\Event\EventDispatcher $eventDispatcher */

$eventDispatcher->addListener(
    'register.core.middleware',
    function (\KED\Services\MiddlewareManager $middlewareManager) {
        $middlewareManager->registerMiddlewareBefore(\KED\Middleware\ResponseMiddleware::class, \KED\Module\User\Middleware\GreetingMiddleware::class);
    },
    5
);