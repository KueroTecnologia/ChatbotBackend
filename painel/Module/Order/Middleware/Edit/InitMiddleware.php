<?php


declare(strict_types=1);

namespace KED\Module\Order\Middleware\Edit;

use function KED\_mysql;
use function KED\get_js_file_url;
use KED\Module\Order\Services\OrderLoader;
use KED\Services\Helmet;
use KED\Services\Http\Response;
use KED\Services\Http\Request;
use KED\Middleware\MiddlewareAbstract;

class InitMiddleware extends MiddlewareAbstract
{
    /**
     * @param Request $request
     * @param Response $response
     * @param null $delegate
     * @return mixed
     * @internal param callable $next
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $id = (int) $request->attributes->get('id');
        $order = $this->getContainer()->get(OrderLoader::class)->load($id);
        if ($order === false) {
            $response->addData('success', 0);
            $response->addData('message', 'Requested order does not exist');
            $response->setStatusCode(404);
            return $response;
        }
        $this->getContainer()->get(Helmet::class)->setTitle("Order #{$order['order_number']}");
        $response->addState('orderData', $order);
        $response->addWidget(
            'order_information_container',
            'content',
            10,
            get_js_file_url("production/order/edit/order_edit.js", true)
        );
        return $delegate;
    }
}