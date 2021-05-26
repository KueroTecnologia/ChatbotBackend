<?php


declare(strict_types=1);

namespace KED\Module\Marketing\Services\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use function KED\dispatch_event;
use KED\Services\Di\Container;
use GraphQL\Type\Definition\Type;

class SubscriberCollectionType extends ObjectType
{
    public function __construct(Container $container)
    {
        $config = [
            'name' => 'SubscriberCollection',
            'fields' => function () use ($container){
                $fields = [
                    'subscribers' => [
                        'type' => Type::listOf($container->get(SubscriberType::class))
                    ],
                    'total' => [
                        'type' => Type::nonNull(Type::int())
                    ],
                    'currentFilter' => Type::string()
                ];

                dispatch_event('filter.subscriberCollection.type', [&$fields]);

                return $fields;
            },
            'resolveField' => function ($value, $args, Container $container, ResolveInfo $info) {
                return isset($value[$info->fieldName]) ? $value[$info->fieldName] : null;
            }
        ];

        parent::__construct($config);
    }
}
