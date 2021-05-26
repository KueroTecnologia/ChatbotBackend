<?php


declare(strict_types=1);

/**@var \KED\Services\Di\Container $container */


use function KED\get_config;

$container[\KED\Module\SendGrid\Services\SendGrid::class] = function () use ($container) {
    return new \KED\Module\SendGrid\Services\SendGrid(
        get_config('sendgrid_apiKey', null),
        get_config('sendgrid_sender_email', ''),
        get_config('sendgrid_sender_name', get_config('general_store_name', 'Online Store')),
        get_config('sendgrid_status', 1)
    );
};
