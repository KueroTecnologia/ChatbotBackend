<?php


declare(strict_types=1);

namespace KED\Module\Catalog\Services\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use function KED\dispatch_event;
use KED\Services\Di\Container;

class AttributeOptionType extends ObjectType
{
    public function __construct(Container $container)
    {
        $config = [
            'name' => 'AttributeOption',
            'fields' => function () use ($container) {
                $fields = [
                    'attribute_option_id' => [
                        'type' => Type::nonNull(Type::id())
                    ],
                    'attribute_id' => [
                        'type' => Type::nonNull(Type::int())
                    ],
                    'attribute_code' => [
                        'type' => Type::nonNull(Type::string())
                    ],
                    'option_text' => [
                        'type' => Type::string()
                    ]
                ];

                dispatch_event('filter.attributeOption.type', [&$fields]);

                return $fields;
            },
            'resolveField' => function ($value, $args, Container $container, ResolveInfo $info) {
                return isset($value[$info->fieldName]) ? $value[$info->fieldName] : null;
            }
        ];
        parent::__construct($config);
    }
}
