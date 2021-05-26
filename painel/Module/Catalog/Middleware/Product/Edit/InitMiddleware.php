<?php


declare(strict_types=1);

namespace KED\Module\Catalog\Middleware\Product\Edit;

use function KED\_mysql;
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
        if ($id) {
            $product = _mysql()->getTable('product')->load($id);
            if ($product === false) {
                $response->addData('success', 0);
                $response->addData('message', 'Requested product does not exist');

                return $response;
            }
            $this->getContainer()->get(Helmet::class)->setTitle('Edit product');
        }
        $this->getContainer()->get(Helmet::class)->setTitle('Create new product');

        return $delegate;
    }
}