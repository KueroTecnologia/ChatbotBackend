<?php


declare(strict_types=1);

namespace KED\Module\Catalog\Middleware\Product\View;

use function KED\_mysql;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\MiddlewareAbstract;


class InitMiddleware extends MiddlewareAbstract
{
    /**
     * @param Request $request
     * @param Response $response
     * @param null $delegate
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $conn = _mysql();
        $product = null;
        if ($request->attributes->get('slug')) {
            $product = $conn->getTable('product')
                ->leftJoin('product_description')
                ->where('seo_key', '=', $request->attributes->get('slug'))
                ->fetchOneAssoc();
        } else
            $product = $conn->getTable('product')
                ->leftJoin('product_description')
                ->where('product_id', '=', $request->attributes->get('id'))
                ->fetchOneAssoc();

        if (!$product) {
            $response->setStatusCode(404);
            $request->attributes->set('_matched_route', 'not.found');
            return $delegate;
        }

        $request->attributes->set('id', $product['product_id']);

        $response->addState('product', [
            'id' => $product['product_id'],
            'regularPrice' => $product['price'],
            'sku' => $product['sku'],
            'weight' => $product['weight'],
            'isInStock' => $product['manage_stock'] == 0 || ($product['qty'] > 0 && $product['stock_availability'] == 1)
        ]);

        return $product;
    }
}