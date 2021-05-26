<?php


declare(strict_types=1);

namespace KED\Module\Cms\Middleware\Widget\Grid;


use function KED\create_mutable_var;
use function KED\generate_url;
use function KED\get_js_file_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Helmet;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class GridMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $this->getContainer()->get(Helmet::class)->setTitle("Widgets");

        $response->addWidget(
            'cms_widget_grid_container',
            'content',
            0,
            get_js_file_url("production/grid/grid.js", true),
            [
                "id"=>"cms_widget_grid_container"
            ]
        );

        $response->addWidget(
            'widgets',
            'cms_widget_grid_container',
            20,
            get_js_file_url("production/cms/widget/grid/grid.js", true),
            [
                "apiUrl" => generate_url('admin.graphql.api'),
                "types" => create_mutable_var("widget_types", [])
            ]
        );

        return $delegate;
    }
}