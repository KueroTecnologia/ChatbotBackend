<?php


declare(strict_types=1);

/**@var \KED\Services\Di\Container $container */


$container[\KED\Module\Cms\Services\Type\FileType::class] =  function () use ($container) {
    return new \KED\Module\Cms\Services\Type\FileType($container);
};

$container[\KED\Module\Cms\Services\Type\CmsPageType::class] =  function () use ($container) {
    return new \KED\Module\Cms\Services\Type\CmsPageType($container);
};

$container[\KED\Module\Cms\Services\Type\PageCollectionType::class] = function () use ($container){
    return new \KED\Module\Cms\Services\Type\PageCollectionType($container);
};

$container[\KED\Module\Cms\Services\Type\CmsPageInputType::class] = function () use ($container){
    return new \KED\Module\Cms\Services\Type\CmsPageInputType($container);
};

$container[\KED\Module\Cms\Services\Type\WidgetType::class] =  function () use ($container) {
    return new \KED\Module\Cms\Services\Type\WidgetType($container);
};

$container[\KED\Module\Cms\Services\Type\WidgetCollectionType::class] = function () use ($container){
    return new \KED\Module\Cms\Services\Type\WidgetCollectionType($container);
};

$container[\KED\Module\Cms\Services\Type\WidgetInputType::class] = function () use ($container){
    return new \KED\Module\Cms\Services\Type\WidgetInputType($container);
};