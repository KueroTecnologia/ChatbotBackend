<?php


declare(strict_types=1);

namespace KED\Module\Catalog\Middleware\Product\Delete;


use function KED\_mysql;
use function KED\generate_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class DeleteMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        try {
            $conn = _mysql();
            $product = $conn->getTable('product')->load($request->attributes->get('id'));
            if ($product == false)
                throw new \Exception("Requested product does not exist");
            $conn->getTable('product')->where('product_id', '=', $request->attributes->get('id'))->delete();
            $response->addAlert("product_delete_success", "success", "Product deleted");
            $response->redirect(generate_url('product.grid'));

            return $product;
        } catch (\Exception $e) {
            $response->addAlert("product_delete_error", "error", $e->getMessage())->notNewPage();

            return false;
        }
    }
}