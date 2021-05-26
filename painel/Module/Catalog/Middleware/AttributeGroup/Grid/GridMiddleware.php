<?php


declare(strict_types=1);

namespace KED\Module\Catalog\Middleware\AttributeGroup\Grid;

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
        if ($response->hasWidget('attribute-grid'))
            return $delegate;

        $this->getContainer()->get(Helmet::class)->setTitle("Attribute groups");
        $response->addWidget(
            'attribute_group_grid_container',
            'content',
            10,
            get_js_file_url("production/grid/grid.js", true),
            ['id'=>"attribute_group_grid_container"]
        );
        $response->addWidget(
            'attribute-group-grid',
            'attribute_group_grid_container',
            10,
            get_js_file_url("production/catalog/attribute_group/grid/grid.js", true),
            [
                "apiUrl" => generate_url('admin.graphql.api')
            ]
        );

        return $delegate;
    }
}