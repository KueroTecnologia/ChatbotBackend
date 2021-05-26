<?php


declare(strict_types=1);

namespace KED\Module\Cms\Services\Type;


use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;
use function KED\dispatch_event;
use KED\Module\Graphql\Services\KeyValuePairFieldTypeInput;
use KED\Services\Di\Container;

class WidgetInputType extends InputObjectType
{
    public function __construct(Container $container)
    {
        $config = [
            'name'=> 'WidgetInput',
            'fields' => function () use ($container) {
                $fields = [
                    'id' => Type::int(),
                    'type' => Type::nonNull(Type::string()),
                    'name' => Type::nonNull(Type::string()),
                    'status' => Type::nonNull(Type::int()),
                    'setting' => Type::listOf($container->get(KeyValuePairFieldTypeInput::class)),
                    'display_setting' => Type::listOf($container->get(KeyValuePairFieldTypeInput::class)),
                    'sort_order' => Type::string()
                ];
                dispatch_event('filter.widgetInput.input', [&$fields]);

                return $fields;
            }
        ];
        parent::__construct($config);
    }
}