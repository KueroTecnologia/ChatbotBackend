<?php


declare(strict_types=1);

namespace KED\Module\Catalog\Middleware\Category\Delete;


use function KED\_mysql;
use function KED\generate_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class DeleteMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $id = $request->attributes->get('id');
        try {
            _mysql()->getTable('category')->where('category_id', '=', $id)->delete();
            $response->addAlert("category_delete_success", "success", "Category deleted");
            $response->redirect(generate_url('category.grid'));
        } catch (\Exception $e) {
            $response->addAlert("category_delete_error", "error", $e->getMessage())->notNewPage();
        }

        return $delegate;
    }
}