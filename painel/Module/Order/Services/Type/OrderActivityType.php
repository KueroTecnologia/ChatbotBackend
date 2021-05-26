<?php


declare(strict_types=1);

namespace KED\Module\Order\Services\Type;


use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use function KED\dispatch_event;
use KED\Services\Di\Container;

class OrderActivityType extends ObjectType
{
    public function __construct(Container $container)
    {
        $config = [
            'name' => 'Order activity',
            'fields' => function () use ($container) {
                $fields = [
                    'id' => [
                        'type' => Type::nonNull(Type::id()),
                        'resolve' => function ($value, $args, Container $container, ResolveInfo $info) {
                            return isset($value['order_activity_id']) ? $value['order_activity_id'] : null;
                        }
                    ],
                    'order_id' => [
                        'type' => Type::nonNull(Type::int()),
                        'resolve' => function ($value, $args, Container $container, ResolveInfo $info) {
                            return isset($value['order_activity_order_id']) ? $value['order_activity_order_id'] : null;
                        }
                    ],
                    'comment' => [
                        'type' => Type::string()
                    ],
                    'customer_notified' => [
                        'type' => Type::int()
                    ],
                    'created_at' => [
                        'type' => Type::string()
                    ]
                ];

                dispatch_event('filter.order_activity.type', [&$fields]);

                return $fields;
            },
            'resolveField' => function ($value, $args, Container $container, ResolveInfo $info) {
                return isset($value[$info->fieldName]) ? $value[$info->fieldName] : null;
            }
        ];
        parent::__construct($config);
    }
}