<?php


declare(strict_types=1);

namespace KED\Module\Checkout\Services\Type;


use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use function KED\dispatch_event;
use KED\Services\Di\Container;

class ItemCustomOptionValueType extends ObjectType
{
    public function __construct(Container $container)
    {
        $config = [
            'name' => 'CartItemOptionValue',
            'fields' => function () use ($container) {
                $fields = [
                    'value_id' => [
                        'type' => Type::nonNull(Type::int())
                    ],
                    'value_text' => [
                        'type' => Type::nonNull(Type::string())
                    ],
                    'extra_price' => [
                        'type' => Type::nonNull(Type::string())
                    ]
                ];

                dispatch_event('filter.cartItemCustomOptionValue.type', [&$fields]);

                return $fields;
            },
            'resolveField' => function ($value, $args, Container $container, ResolveInfo $info) {
                return isset($value[$info->fieldName]) ? $value[$info->fieldName] : null;
            }
        ];
        parent::__construct($config);
    }
}