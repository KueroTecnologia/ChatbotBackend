<?php


declare(strict_types=1);

/** @var \KED\Services\Event\EventDispatcher $eventDispatcher */

$eventDispatcher->addListener('register.setting.general.middleware', function (\KED\Services\MiddlewareManager $mm) {
    $mm->registerMiddlewareBefore(\KED\Module\Setting\Middleware\General\FormMiddleware::class, \KED\Module\GoogleAnalytics\Middleware\Setting\FormMiddleware::class);
});

$eventDispatcher->addListener('register.core.middleware', function (\KED\Services\MiddlewareManager $mm) {
    $mm->registerMiddlewareBefore(\KED\Middleware\ResponseMiddleware::class, \KED\Module\GoogleAnalytics\Middleware\GoogleAnalyticsTrackingMiddleware::class);
});
