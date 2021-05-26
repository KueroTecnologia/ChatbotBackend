<?php


declare(strict_types=1);

namespace KED\Module\Graphql\Services;


use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;
use function KED\dispatch_event;
use KED\Services\Di\Container;

class FilterFieldType extends InputObjectType
{
    public function __construct(Container $container)
    {
        $config = [
            'name'=> 'FilterFieldType',
            'fields' => function () use ($container) {
                $fields = [
                    'key' => Type::string(),
                    'operator' => Type::nonNull(Type::string()),
                    'value' => Type::nonNull(Type::string())
                ];

                return $fields;
            }
        ];
        parent::__construct($config);
    }
}