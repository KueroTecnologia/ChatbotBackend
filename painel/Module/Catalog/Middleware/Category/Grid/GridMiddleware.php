<?php


declare(strict_types=1);

namespace KED\Module\Catalog\Middleware\Category\Grid;

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
        if ($response->hasWidget('category-grid'))
            return $delegate;

        $this->getContainer()->get(Helmet::class)->setTitle("Categories");

        $response->addWidget(
            'category_grid_container',
            'content',
            0, get_js_file_url("production/grid/grid.js", true),
            ['id'=>"category_grid_container"]
        );

        $response->addWidget(
            'category-grid',
            'category_grid_container',
            20, get_js_file_url("production/catalog/category/grid/grid.js", true),
            [
                "apiUrl" => generate_url('admin.graphql.api')
            ]
        );

        return $delegate;
    }
}