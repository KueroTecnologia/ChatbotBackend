<?php


declare(strict_types=1);

/**@var \KED\Services\Di\Container $container */


$container[\KED\Module\Tax\Services\Type\TaxClassType::class] = function () use ($container){
    return new \KED\Module\Tax\Services\Type\TaxClassType($container);
};

$container[\KED\Module\Tax\Services\Type\TaxRateType::class] = function () use ($container){
    return new \KED\Module\Tax\Services\Type\TaxRateType($container);
};


