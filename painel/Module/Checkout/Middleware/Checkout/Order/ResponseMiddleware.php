<?php


declare(strict_types=1);

namespace KED\Module\Checkout\Middleware\Checkout\Order;


use GuzzleHttp\Promise\Promise;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Services\Routing\Router;

class ResponseMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        /* IF THERE IS NO REDIRECT PROVIDED, WE REDIRECT USE TO EITHER SUCCESS PAGE OR FAILURE PAGE */
        if (!$delegate instanceof Promise)
            return $delegate;

        $delegate->then(function ($orderId) use ($request, $response) {
            if (!$response->isRedirect()) {
                $response->redirect($this->getContainer()->get(Router::class)->generateUrl('checkout.success'));
            }
        })->otherwise(function ($reason) use ($response) {
            if (!$response->isRedirect()) {
                $response->redirect($this->getContainer()->get(Router::class)->generateUrl('checkout.failure'));
            }
        });

        return $delegate;
    }
}