<?php

declare(strict_types=1);

namespace KED\Module\Checkout\Middleware\Core;


use function KED\generate_url;
use function KED\get_js_file_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class MiniCartMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($request->isAdmin() == true || $request->attributes->get('_matched_route') == 'graphql.api')
            return $delegate;

        $response->addWidget(
            'minicart',
            'header_right',
            20,
            get_js_file_url("production/checkout/minicart/container.js"),
            [
                'cartUrl'=> generate_url('checkout.cart'),
                'checkoutUrl'=> generate_url('checkout.index')
            ]
        );

        return $delegate;
    }
}