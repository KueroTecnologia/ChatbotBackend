<?php


declare(strict_types=1);

/**@var \KED\Services\Di\Container $container */
$container[\KED\Module\Checkout\Services\Cart\Cart::class] =  function () use ($container) {
    return new \KED\Module\Checkout\Services\Cart\Cart($container->get(\KED\Services\Http\Request::class));
};

$container->set(\KED\Module\Checkout\Services\PriceHelper::class, function () use ($container) {
    return new \KED\Module\Checkout\Services\PriceHelper($container->get(\KED\Services\Db\Processor::class));
});

$container[\KED\Module\Checkout\Services\Type\CartType::class] =  function () use ($container) {
    return new \KED\Module\Checkout\Services\Type\CartType($container);
};

$container[\KED\Module\Checkout\Services\Type\CartItemType::class] =  function () use ($container) {
    return new \KED\Module\Checkout\Services\Type\CartItemType($container);
};

$container[\KED\Module\Checkout\Services\Type\ItemCustomOptionType::class] =  function () use ($container) {
    return new \KED\Module\Checkout\Services\Type\ItemCustomOptionType($container);
};

$container[\KED\Module\Checkout\Services\Type\ItemCustomOptionValueType::class] =  function () use ($container) {
    return new \KED\Module\Checkout\Services\Type\ItemCustomOptionValueType($container);
};

$container[\KED\Module\Checkout\Services\Type\ItemVariantOptionType::class] =  function () use ($container) {
    return new \KED\Module\Checkout\Services\Type\ItemVariantOptionType($container);
};