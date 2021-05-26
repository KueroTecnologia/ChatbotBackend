<?php


declare(strict_types=1);

namespace KED\Module\Catalog\Services\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use function KED\dispatch_event;
use KED\Services\Di\Container;
use GraphQL\Type\Definition\Type;

class ProductCollectionType extends ObjectType
{
    public function __construct(Container $container)
    {
        $config = [
            'name' => 'ProductCollection',
            'fields' => function () use ($container){
                $fields = [
                    'products' => [
                        'type' => Type::listOf($container->get(ProductType::class))
                    ],
                    'total' => [
                        'type' => Type::nonNull(Type::int())
                    ],
                    'currentFilter' => Type::string()
                ];

                dispatch_event('filter.productCollection.type', [&$fields]);

                return $fields;
            },
            'resolveField' => function ($value, $args, Container $container, ResolveInfo $info) {
                return isset($value[$info->fieldName]) ? $value[$info->fieldName] : null;
            }
        ];

        parent::__construct($config);
    }
}
