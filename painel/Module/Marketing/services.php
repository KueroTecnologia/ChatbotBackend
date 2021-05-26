<?php

declare(strict_types=1);

/**@var \KED\Services\Di\Container $container */


$container[\KED\Module\Marketing\Services\Type\SubscriberType::class] = function () use ($container){
    return new \KED\Module\Marketing\Services\Type\SubscriberType($container);
};

$container[\KED\Module\Marketing\Services\SubscriberCollection::class] = function () use ($container){
    return new \KED\Module\Marketing\Services\SubscriberCollection($container);
};

$container[\KED\Module\Marketing\Services\Type\SubscriberCollectionType::class] = function () use ($container){
    return new \KED\Module\Marketing\Services\Type\SubscriberCollectionType($container);
};
