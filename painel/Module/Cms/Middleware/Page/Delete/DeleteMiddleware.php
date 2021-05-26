<?php


declare(strict_types=1);

namespace KED\Module\Cms\Middleware\Page\Delete;


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
            _mysql()->getTable('cms_page')->where('cms_page_id', '=', $id)->delete();
            $response->addAlert("cms_page_id_delete_success", "success", "Cms page deleted");
            $response->redirect(generate_url('page.grid'));
        } catch (\Exception $e) {
            $response->addAlert("cms_page_id_delete_error", "error", $e->getMessage())->notNewPage();
        }

        return $delegate;
    }
}