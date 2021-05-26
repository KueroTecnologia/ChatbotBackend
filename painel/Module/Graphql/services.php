<?php


declare(strict_types=1);

/**@var \KED\Services\Di\Container $container */


$container[\KED\Module\Graphql\Services\QueryType::class] = function () use ($container){
    return new \KED\Module\Graphql\Services\QueryType($container);
};

$container[\KED\Module\Graphql\Services\MutationType::class] = function () use ($container){
    return new \KED\Module\Graphql\Services\MutationType($container);
};

$container[\GraphQL\Type\Schema::class] =  function () use ($container) {
    return new \GraphQL\Type\Schema([
        'query'=> $container->get(\KED\Module\Graphql\Services\QueryType::class),
        'mutation' => $container->get(\KED\Module\Graphql\Services\MutationType::class),
    ]);
};

$container[\KED\Module\Graphql\Services\GraphqlExecutor::class] =  function () use ($container) {
    return new \KED\Module\Graphql\Services\GraphqlExecutor($container[\GraphQL\Type\Schema::class], $container);
};

$container[\KED\Module\Graphql\Services\FilterFieldType::class] = function () use ($container){
    return new \KED\Module\Graphql\Services\FilterFieldType($container);
};

$container[\KED\Module\Graphql\Services\FilterOperatorType::class] = function () use ($container){
    return new \KED\Module\Graphql\Services\FilterOperatorType();
};

$container[\KED\Module\Graphql\Services\KeyValuePairFieldTypeInput::class] = function () use ($container){
    return new \KED\Module\Graphql\Services\KeyValuePairFieldTypeInput($container);
};

$container[\KED\Module\Graphql\Services\KeyValuePairFieldType::class] = function () use ($container){
    return new \KED\Module\Graphql\Services\KeyValuePairFieldType($container);
};