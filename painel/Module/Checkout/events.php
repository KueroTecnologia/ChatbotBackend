<?php


declare(strict_types=1);

/** @var \KED\Services\Event\EventDispatcher $eventDispatcher */

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use function KED\_mysql;
use KED\Services\Di\Container;
use KED\Services\Http\Request;

$eventDispatcher->addListener(
    'register.core.middleware',
    function (\KED\Services\MiddlewareManager $middlewareManager) {
        $middlewareManager->registerMiddleware(\KED\Module\Checkout\Middleware\Core\MiniCartMiddleware::class, 71);
    },
    0
);

$eventDispatcher->addListener(
    'register.checkout.index.middleware',
    function (\KED\Services\MiddlewareManager $middlewareManager) {
        $middlewareManager->registerMiddleware(\KED\Module\Customer\Middleware\Checkout\AccountMiddleware::class, 31);
    },
    0
);

$eventDispatcher->addListener(
    'register.customer.logout.middleware',
    function (\KED\Services\MiddlewareManager $middlewareManager) {
        $middlewareManager->registerMiddlewareAfter(\KED\Module\Customer\Middleware\Logout\LogoutMiddleware::class, \KED\Module\Checkout\Middleware\Logout\ClearCartMiddleware::class);
    },
    0
);
//
//$eventDispatcher->addListener(
//    'register.core.middleware',
//    function (\KED\Services\MiddlewareManager $middlewareManager) {
//        $middlewareManager->registerMiddlewareBefore(\KED\Middleware\ResponseMiddleware::class, \KED\Module\Checkout\Middleware\Cart\Add\ButtonMiddleware::class);
//    },
//    0
//);
$eventDispatcher->addListener(
    'filter.mutation.type',
    function (&$fields, Container $container) {
        $fields['addShippingAddress'] = [
            'args' => [
                'address' => Type::nonNull($container->get(\KED\Module\Customer\Services\Type\AddressInputType::class)),
                'cartId' => Type::nonNull(Type::int())
            ],
            'type' => new ObjectType([
                'name'=> 'addShippingAddressOutput',
                'fields' => [
                    'status' => Type::nonNull(Type::boolean()),
                    'message'=> Type::string(),
                    'address' => $container->get(\KED\Module\Customer\Services\Type\AddressType::class)
                ]
            ]),
            'resolve' => function ($rootValue, $args, Container $container, ResolveInfo $info) {
                $conn = _mysql();
                $address = $args['address'];
                $provinces = \KED\Services\Locale\Province::listStateV3();
                // TODO: Verify allow countries
                if ($container->get(KED\Module\Checkout\Services\Cart\Cart::class)->isEmpty())
                    return ['status'=> false, 'address' => null, 'message' => 'Your shopping cart is empty'];

                if (
                    $container->get(Request::class)->getSession()->get('cart_id') != $args['cartId']
                )
                    return ['status'=> false, 'address' => null, 'message' => 'Permission denied'];

                if (
                    !isset($provinces[$address['country']]) ||
                    (
                        isset($address['province']) &&
                        !\KED\array_find($provinces[$address['country']], function ($value) use ($address) {
                            if ($value['value'] == $address['province'])
                                return $value;
                            else
                                return null;
                        })
                    )
                )
                    return ['status'=> false, 'address' => null, 'message' => 'Country or Province is invalid'];


                $conn->getTable('cart_address')->insert($address);
                $id = $conn->getLastID();
                $container->get(\KED\Module\Checkout\Services\Cart\Cart::class)->setData('shipping_address_id', $id);

                return ['status'=> true, 'address' => $conn->getTable('cart_address')->load($id)];
            }
        ];

        $fields['addBillingAddress'] = [
            'args' => [
                'address' => $container->get(\KED\Module\Customer\Services\Type\AddressInputType::class),
                'cartId' => Type::nonNull(Type::int())
            ],
            'type' => new ObjectType([
                'name'=> 'addBillingAddressOutput',
                'fields' => [
                    'status' => Type::nonNull(Type::boolean()),
                    'message'=> Type::string(),
                    'address' => $container->get(\KED\Module\Customer\Services\Type\AddressType::class)
                ]
            ]),
            'resolve' => function ($rootValue, $args, Container $container, ResolveInfo $info) {
                $conn = _mysql();
                $address = $args['address'];
                $provinces = \KED\Services\Locale\Province::listStateV3();
                // TODO: Verify allow countries
                if ($container->get(KED\Module\Checkout\Services\Cart\Cart::class)->isEmpty())
                    return ['status'=> false, 'address' => null, 'message' => 'Your shopping cart is empty'];

                if (
                    $container->get(Request::class)->getSession()->get('cart_id') != $args['cartId']
                )
                    return ['status'=> false, 'address' => null, 'message' => 'Permission denied'];

                if ($address == null) {
                    $container->get(\KED\Module\Checkout\Services\Cart\Cart::class)->setData('billing_address_id', null);
                    return ['status'=> true, 'address' => null];
                }
                if (
                    !isset($provinces[$address['country']]) ||
                    (
                        isset($address['province']) &&
                        !\KED\array_find($provinces[$address['country']], function ($value) use ($address) {
                            if ($value['value'] == $address['province'])
                                return $value;
                            else
                                return null;
                        })
                    )
                )
                    return ['status'=> false, 'address' => null, 'message' => 'Country or Province is invalid'];

                $conn->getTable('cart_address')->insert($address);
                $id = $conn->getLastID();
                $container->get(\KED\Module\Checkout\Services\Cart\Cart::class)->setData('billing_address_id', $id);

                return ['status'=> true, 'address' => $conn->getTable('cart_address')->load($id)];
            }
        ];
    },
    5
);

$eventDispatcher->addListener('breadcrumbs_items', function (array $items) {
    $container = \KED\the_container();
    if (in_array($container->get(Request::class)->get("_matched_route"), ["checkout.cart"])) {
        $items[] = ["sort_order"=> 1, "title"=> "Shopping cart", "link"=> null];
    }
    if (in_array($container->get(Request::class)->get("_matched_route"), ["checkout.index"])) {
        $items[] = ["sort_order"=> 1, "title"=> "Checkout", "link"=> null];
    }
    return $items;
});