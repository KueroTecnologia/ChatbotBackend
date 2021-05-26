<?php


declare(strict_types=1);

namespace KED\Module\Checkout\Middleware\Cart\Add;

use KED\Services\Checkout\Cart\Cart;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\Delegate;
use KED\Middleware\MiddlewareAbstract;

class OptionMiddleware extends MiddlewareAbstract
{
    /**
     * @param Request $request
     * @param Response $response
     * @param callable $next
     * @param Delegate|null $delegate
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, callable $next, Delegate $delegate)
    {
        try {
            $added_item = $request->getApp()->get(Cart::class)->addProduct($request->get('id'), $request->get('qty', 1), $request->request->all());
            $delegate['added_item'] = $added_item;
            $response->addAlert('cart_add_success', 'success', sprintf('%s has been added to shopping cart successfully', $added_item->getName()));
            $delegate->stopAndResponse();
        } catch (\Exception $e) {
            $response->addAlert('cart_add_error', 'error', $e->getMessage());
            $delegate->stopAndResponse();
        }
        return $next($request, $response, $delegate);
    }
}