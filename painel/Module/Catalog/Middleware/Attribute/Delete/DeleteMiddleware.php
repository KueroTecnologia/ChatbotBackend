<?php


declare(strict_types=1);

namespace KED\Module\Catalog\Middleware\Attribute\Delete;


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
            _mysql()->getTable('attribute')->where('attribute_id', '=', $id)->delete();
            $response->addAlert("attribute_delete_success", "success", "Attribute deleted");
            $response->redirect(generate_url('attribute.grid'));
        } catch (\Exception $e) {
            $response->addAlert("attribute_delete_error", "error", $e->getMessage())->notNewPage();
        }

        return $delegate;
    }
}