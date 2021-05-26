<?php


declare(strict_types=1);

namespace KED\Module\Catalog\Middleware\AttributeGroup\Delete;


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
            _mysql()->getTable('attribute_group')->where('attribute_group_id', '=', $id)->delete();
            $response->addAlert("attribute_group_delete_success", "success", "Attribute group deleted");
            $response->redirect(generate_url('attribute.group.grid'));
        } catch (\Exception $e) {
            $response->addAlert("attribute_group_delete_error", "error", $e->getMessage())->notNewPage();
        }

        return $delegate;
    }
}