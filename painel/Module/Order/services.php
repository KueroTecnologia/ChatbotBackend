<?php


declare(strict_types=1);

/**@var \KED\Services\Di\Container $container */

$container[\KED\Module\Order\Services\Type\OrderType::class] = function () use ($container){
    return new \KED\Module\Order\Services\Type\OrderType($container);
};

$container[\KED\Module\Order\Services\Type\OrderItemType::class] = function () use ($container){
    return new \KED\Module\Order\Services\Type\OrderItemType($container);
};

$container[\KED\Module\Order\Services\Type\PaymentTransactionType::class] = function () use ($container){
    return new \KED\Module\Order\Services\Type\PaymentTransactionType($container);
};

$container[\KED\Module\Order\Services\Type\OrderActivityType::class] = function () use ($container){
    return new \KED\Module\Order\Services\Type\OrderActivityType($container);
};

$container[\KED\Module\Order\Services\Type\OrderCollectionFilterType::class] = function () use ($container){
    return new \KED\Module\Order\Services\Type\OrderCollectionFilterType($container);
};

$container[\KED\Module\Order\Services\Type\OrderCollectionType::class] = function () use ($container){
    return new \KED\Module\Order\Services\Type\OrderCollectionType($container);
};

$container[\KED\Module\Order\Services\OrderLoader::class] = function () use ($container){
    return new \KED\Module\Order\Services\OrderLoader();
};

$container[\KED\Module\Order\Services\OrderCollection::class] = function () use ($container){
    return new \KED\Module\Order\Services\OrderCollection($container);
};

