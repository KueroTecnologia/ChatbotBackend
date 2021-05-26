<?php


declare(strict_types=1);

namespace KED\Module\Catalog\Middleware\Attribute\Grid;

use function KED\generate_url;
use function KED\get_js_file_url;
use KED\Services\Helmet;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\MiddlewareAbstract;

class GridMiddleware extends MiddlewareAbstract
{
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($response->hasWidget('attribute-grid')) {
            return $delegate;
        }

        $this->getContainer()->get(Helmet::class)->setTitle("Product attributes");

        $response->addWidget(
            'attribute_grid_container',
            'content',
            0,
            get_js_file_url("production/grid/grid.js", true),
            ['id'=>"attribute_grid_container"]
        );

        $response->addWidget(
            'attribute_grid',
            'attribute_grid_container',
            10,
            get_js_file_url("production/catalog/attribute/grid/grid.js", true),
            [
                "apiUrl" => generate_url('admin.graphql.api')
            ]
        );

        return $delegate;
    }
}