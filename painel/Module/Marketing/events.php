<?php


declare(strict_types=1);

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use KED\Module\Graphql\Services\FilterFieldType;
use KED\Services\Di\Container;

/** @var \KED\Services\Event\EventDispatcher $eventDispatcher */
$eventDispatcher->addListener('register.customer.dashboard.middleware', function (\KED\Services\MiddlewareManager $mm) {
    $mm->registerMiddlewareBefore(\KED\Module\Customer\Middleware\Dashboard\OrderMiddleware::class, \KED\Module\Marketing\Middleware\Newsletter\CustomerAccountMiddleware::class);
});


$eventDispatcher->addListener(
    "admin_menu",
    function (array $items) {
        return array_merge($items, [
            [
                "id" => "marketing",
                "sort_order" => 60,
                "url" => null,
                "title" => "Marketing",
                "parent_id" => null
            ],
            [
                "id" => "marketing_subscribers",
                "sort_order" => 10,
                "url" => \KED\generate_url("subscriber.grid"),
                "title" => "Subscribers",
                "icon" => "mail-bulk",
                "parent_id" => "marketing"
            ]
        ]);
    },
    0
);

$eventDispatcher->addListener(
    'filter.query.type',
    function (&$fields, Container $container) {
        $fields += [
            'subscriberCollection' => [
                'type' => $container->get(\KED\Module\Marketing\Services\Type\SubscriberCollectionType::class),
                'description' => "Return list of subscriber and total count",
                'args' => [
                    'filters' =>  Type::listOf($container->get(FilterFieldType::class))
                ],
                'resolve' => function ($rootValue, $args, Container $container, ResolveInfo $info) {
                    if ($container->get(\KED\Services\Http\Request::class)->isAdmin() == false)
                        return [];
                    $collection = new \KED\Module\Marketing\Services\SubscriberCollection($container);
                    return $collection->getData($rootValue, $args, $container, $info);
                }
            ]
        ];
    },
    5
);

$eventDispatcher->addListener(
    'widget_types',
    function ($types) {
        $types[] = ['code' => 'newsletter_form', 'name' => 'Newsletter form widget'];

        return $types;
    },
    0
);


$eventDispatcher->addListener('register.widget.create.middleware', function (\KED\Services\MiddlewareManager $mm) {
    $mm->registerMiddleware(\KED\Module\Marketing\Middleware\SubscribeFormWidget\FormMiddleware::class, 0);
});

$eventDispatcher->addListener('register.widget.edit.middleware', function (\KED\Services\MiddlewareManager $mm) {
    $mm->registerMiddleware(\KED\Module\Marketing\Middleware\SubscribeFormWidget\FormMiddleware::class, 0);
});

$eventDispatcher->addListener(
    'register.core.middleware',
    function (\KED\Services\MiddlewareManager $middlewareManager) {
        $middlewareManager->registerMiddleware(\KED\Module\Marketing\Middleware\SubscribeFormWidget\NewsletterFormWidgetMiddleware::class, 21);
    },
    5
);