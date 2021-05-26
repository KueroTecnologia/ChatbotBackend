<?php


declare(strict_types=1);

/** @var \KED\Services\Routing\Router $router */

$router->addSiteRoute('checkout.cart', 'GET', '/cart', [
    \KED\Module\Checkout\Middleware\Cart\View\ShoppingCartMiddleware::class,
    \KED\Module\Checkout\Middleware\Cart\View\ItemsMiddleware::class,
    \KED\Module\Checkout\Middleware\Cart\View\SummaryMiddleware::class
]);

$router->addSiteRoute('cart.add', 'POST', '/cart/add', [
    \KED\Module\Checkout\Middleware\Cart\Add\AddProductMiddleware::class
]);

$router->addSiteRoute('cart.remove', ["POST", "GET"], '/cart/remove/{id:\d+}', [
    \KED\Module\Checkout\Middleware\Cart\Remove\RemoveItemMiddleware::class
]);

$router->addSiteRoute('checkout.index', 'GET', '/checkout', [
    \KED\Module\Checkout\Middleware\Checkout\Index\InitMiddleware::class,
    \KED\Module\Checkout\Middleware\Checkout\Index\AddressBookMiddleware::class,
    \KED\Module\Checkout\Middleware\Checkout\Index\AddressFormMiddleware::class,
    \KED\Module\Checkout\Middleware\Checkout\Index\SubmitButtonMiddleware::class,
]);

$router->addSiteRoute('checkout.set.contact', 'POST', '/checkout/contact/add', [
    \KED\Module\Checkout\Middleware\Checkout\ContactInfo\AddContactInfoMiddleware::class
]);

$router->addSiteRoute('checkout.set.payment', 'POST', '/checkout/payment/add', [
    \KED\Module\Checkout\Middleware\Checkout\Payment\AddPaymentMethodMiddleware::class
]);

$router->addSiteRoute('checkout.set.shipment', 'POST', '/checkout/shipment/add', [
    \KED\Module\Checkout\Middleware\Checkout\Shipment\AddShippingMethodMiddleware::class
]);

$router->addSiteRoute('checkout.set.billing.address', 'POST', '/checkout/payment/address/set', [
    \KED\Module\Checkout\Middleware\Checkout\Payment\AddBillingAddressMiddleware::class
]);

$router->addSiteRoute('checkout.set.shipping.address', 'POST', '/checkout/shipment/address/set', [
    \KED\Module\Checkout\Middleware\Checkout\Shipment\AddShippingAddressMiddleware::class
]);

$router->addSiteRoute('checkout.order', 'POST', '/checkout/order', [
    \KED\Module\Checkout\Middleware\Checkout\Order\CreateOrderMiddleware::class,
    \KED\Module\Checkout\Middleware\Checkout\Order\ResponseMiddleware::class
]);

$router->addSiteRoute('checkout.success', 'GET', '/checkout/success', [
    \KED\Module\Checkout\Middleware\Checkout\Success\MessageMiddleware::class
]);