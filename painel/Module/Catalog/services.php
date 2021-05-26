<?php


declare(strict_types=1);

/**@var \KED\Services\Di\Container $container */

$container[\KED\Module\Catalog\Services\Type\Price::class] =  function () {
    return new \KED\Module\Catalog\Services\Type\Price();
};

$container[\KED\Module\Catalog\Services\Type\ProductTierPriceType::class] =  function () use ($container) {
    return new \KED\Module\Catalog\Services\Type\ProductTierPriceType($container);
};

$container[\KED\Module\Catalog\Services\Type\CategoryType::class] =  function () use ($container) {
    return new \KED\Module\Catalog\Services\Type\CategoryType($container);
};

$container[\KED\Module\Catalog\Services\Type\ProductType::class] =  function () use ($container) {
    return new \KED\Module\Catalog\Services\Type\ProductType($container);
};

$container[\KED\Module\Catalog\Services\Type\ProductImageType::class] =  function () use ($container) {
    return new \KED\Module\Catalog\Services\Type\ProductImageType($container);
};

$container[\KED\Module\Catalog\Services\Type\AttributeGroupType::class] =  function () use ($container) {
    return new \KED\Module\Catalog\Services\Type\AttributeGroupType($container);
};

$container[\KED\Module\Catalog\Services\Type\AttributeType::class] =  function () use ($container) {
    return new \KED\Module\Catalog\Services\Type\AttributeType($container);
};

$container[\KED\Module\Catalog\Services\Type\AttributeOptionType::class] =  function () use ($container) {
    return new \KED\Module\Catalog\Services\Type\AttributeOptionType($container);
};

$container[\KED\Module\Catalog\Services\Type\AttributeCollectionType::class] =  function () use ($container) {
    return new \KED\Module\Catalog\Services\Type\AttributeCollectionType($container);
};

$container[\KED\Module\Catalog\Services\AttributeCollection::class] =  function () use ($container) {
    return new \KED\Module\Catalog\Services\AttributeCollection($container);
};

$container[\KED\Module\Catalog\Services\Type\AttributeGroupCollectionType::class] =  function () use ($container) {
    return new \KED\Module\Catalog\Services\Type\AttributeGroupCollectionType($container);
};

$container[\KED\Module\Catalog\Services\AttributeGroupCollection::class] =  function () use ($container) {
    return new \KED\Module\Catalog\Services\AttributeGroupCollection($container);
};

$container[\KED\Module\Catalog\Services\Type\ProductAttributeIndex::class] =  function () use ($container) {
    return new \KED\Module\Catalog\Services\Type\ProductAttributeIndex($container);
};

$container[\KED\Module\Catalog\Services\Type\CustomOptionType::class] =  function () use ($container) {
    return new \KED\Module\Catalog\Services\Type\CustomOptionType($container);
};

$container[\KED\Module\Catalog\Services\Type\CustomOptionValueType::class] =  function () use ($container) {
    return new \KED\Module\Catalog\Services\Type\CustomOptionValueType($container);
};

$container[\KED\Module\Catalog\Services\ProductMutator::class] =  function () use ($container) {
    return new \KED\Module\Catalog\Services\ProductMutator();
};

$container[\KED\Module\Catalog\Services\Type\ProductCollectionType::class] =  function () use ($container) {
    return new \KED\Module\Catalog\Services\Type\ProductCollectionType($container);
};

$container[\KED\Module\Catalog\Services\ProductCollection::class] = $container->factory(function () use ($container) {
    return new \KED\Module\Catalog\Services\ProductCollection($container);
});

$container[\KED\Module\Catalog\Services\Type\CategoryCollectionType::class] =  function () use ($container) {
    return new \KED\Module\Catalog\Services\Type\CategoryCollectionType($container);
};

$container[\KED\Module\Catalog\Services\CategoryCollection::class] =  function () use ($container) {
    return new \KED\Module\Catalog\Services\CategoryCollection($container);
};

$container[\KED\Module\Catalog\Services\CategoryMutator::class] =  function () use ($container) {
    return new \KED\Module\Catalog\Services\CategoryMutator($container[\KED\Services\Db\Processor::class]);
};

// Filter Tool

$container[\KED\Module\Catalog\Services\Type\ProductFilterToolType::class] = function () use ($container) {
    return new \KED\Module\Catalog\Services\Type\ProductFilterToolType($container);
};

$container[\KED\Module\Catalog\Services\Type\FilterTool\PriceFilterType::class] = function () use ($container) {
    return new \KED\Module\Catalog\Services\Type\FilterTool\PriceFilterType($container);
};

$container[\KED\Module\Catalog\Services\Type\FilterTool\CategoryFilterType::class] = function () use ($container) {
    return new \KED\Module\Catalog\Services\Type\FilterTool\CategoryFilterType($container);
};

$container[\KED\Module\Catalog\Services\Type\FilterTool\AttributeFilterType::class] = function () use ($container) {
    return new \KED\Module\Catalog\Services\Type\FilterTool\AttributeFilterType($container);
};