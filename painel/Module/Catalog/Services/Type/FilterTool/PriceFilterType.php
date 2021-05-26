<?php


declare(strict_types=1);

namespace KED\Module\Catalog\Services\Type\FilterTool;


use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use KED\Services\Di\Container;

class  PriceFilterType extends ObjectType
{
    public function __construct(Container $container)
    {
        $config = [
            'name' => 'PriceFilterType',
            'fields' => function () use ($container){
                $fields = [
                    'maxPrice' => [
                        'type' => Type::nonNull(Type::float())
                    ],
                    'minPrice' => [
                        'type' => Type::nonNull(Type::float())
                    ]
                ];

                return $fields;
            },
            'resolveField' => function ($value, $args, Container $container, ResolveInfo $info) {
                return isset($value[$info->fieldName]) ? $value[$info->fieldName] : null;
            }
        ];

        parent::__construct($config);
    }
}