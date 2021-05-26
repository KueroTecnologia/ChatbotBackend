<?php

declare(strict_types=1);

namespace KED\Module\Customer\Services\Type;


use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\Type;
use function KED\dispatch_event;
use KED\Services\Di\Container;

class CustomerInputType extends InputObjectType
{
    public function __construct(Container $container)
    {
        $config = [
            'name'=> 'CustomerInput',
            'fields' => function () use ($container) {
                $fields = [
                    'customer_id' => Type::int(),
                    'full_name' => Type::nonNull(Type::string()),
                    'email' => Type::nonNull(Type::string()),
                    'status' => Type::int(),
                    'group_id' => Type::int(),
                    'password' => Type::string()
                ];
                dispatch_event('filter.customer.input', [&$fields]);

                return $fields;
            }
        ];
        parent::__construct($config);
    }
}