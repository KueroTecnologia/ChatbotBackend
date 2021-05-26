<?php


declare(strict_types=1);

/**@var \KED\Services\Di\Container $container */


$container[\KED\Module\Customer\Services\Type\CustomerType::class] = function () use ($container){
    return new \KED\Module\Customer\Services\Type\CustomerType($container);
};

$container[\KED\Module\Customer\Services\Type\CustomerInputType::class] = function () use ($container){
    return new \KED\Module\Customer\Services\Type\CustomerInputType($container);
};

$container[\KED\Module\Customer\Services\Type\CustomerGroupType::class] = function () use ($container){
    return new \KED\Module\Customer\Services\Type\CustomerGroupType($container);
};

$container[\KED\Module\Customer\Services\Type\AddressType::class] = function () use ($container){
    return new \KED\Module\Customer\Services\Type\AddressType($container);
};

$container[\KED\Module\Customer\Services\Type\AddressInputType::class] = function () use ($container){
    return new \KED\Module\Customer\Services\Type\AddressInputType($container);
};

$container[\KED\Module\Customer\Services\CustomerCollection::class] = function () use ($container){
    return new \KED\Module\Customer\Services\CustomerCollection($container);
};

$container[\KED\Module\Customer\Services\Type\CustomerCollectionType::class] = function () use ($container){
    return new \KED\Module\Customer\Services\Type\CustomerCollectionType($container);
};
