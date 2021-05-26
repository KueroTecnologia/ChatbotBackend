<?php

declare(strict_types=1);

/**@var \KED\Services\Di\Container $container */

$container[\KED\Module\User\Services\Authenticator::class] = function () use ($container) {
    return new \KED\Module\User\Services\Authenticator($container[\KED\Services\Http\Request::class]);
};