<?php


declare(strict_types=1);

namespace KED\Module\Cms\Middleware\Widget\Delete;


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
            _mysql()->getTable('cms_widget')->where('cms_widget_id', '=', $id)->delete();
            $response->addAlert("cms_widget_id_delete_success", "success", "Widget deleted");
            $response->redirect(generate_url('widget.grid'));
        } catch (\Exception $e) {
            $response->addAlert("cms_widget_id_delete_error", "error", $e->getMessage())->notNewPage();
        }

        return $delegate;
    }
}