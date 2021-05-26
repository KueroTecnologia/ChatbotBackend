<?php


declare(strict_types=1);

/**@var \KED\Services\Di\Container $container */
$container->set(\KED\Module\Discount\Services\Validator::class, function () use ($container) {
    return new \KED\Module\Discount\Services\Validator();
});

$container->set(\KED\Module\Discount\Services\Calculator::class, function () use ($container) {
    return new \KED\Module\Discount\Services\Calculator($container->get(\KED\Module\Discount\Services\Validator::class));
});

$container->set(\KED\Module\Discount\Services\CouponHelper::class, function () use ($container) {
    return new \KED\Module\Discount\Services\CouponHelper(
        $container->get(\KED\Module\Checkout\Services\Cart\Cart::class),
        $container->get(\KED\Module\Discount\Services\Validator::class),
        $container->get(\KED\Module\Discount\Services\Calculator::class)
    );
});

$container->set(\KED\Module\Discount\Services\Type\CouponType::class, function () use ($container) {
    return new \KED\Module\Discount\Services\Type\CouponType($container);
});

$container->set(\KED\Module\Discount\Services\Type\CouponCollectionType::class, function () use ($container) {
    return new \KED\Module\Discount\Services\Type\CouponCollectionType($container);
});

$container->set(\KED\Module\Discount\Services\Type\CouponCollectionFilterType::class, function () use ($container) {
    return new \KED\Module\Discount\Services\Type\CouponCollectionFilterType($container);
});

$container->set(\KED\Module\Discount\Services\CouponCollection::class, function () use ($container) {
    return new \KED\Module\Discount\Services\CouponCollection($container);
});

