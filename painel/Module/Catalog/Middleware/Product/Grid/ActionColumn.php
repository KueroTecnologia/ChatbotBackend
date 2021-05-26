<?php


declare(strict_types=1);

namespace KED\Module\Catalog\Middleware\Product\Grid;

use function KED\generate_url;
use function KED\get_js_file_url;
use KED\Services\Helmet;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\MiddlewareAbstract;

class ActionColumn extends MiddlewareAbstract
{
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $response->addWidget(
            'product-grid-action-column-header',
            'product_grid_header',
            60, get_js_file_url("production/catalog/product/grid/actionColumnHeader.js", true)
        );

        $response->addWidget(
            'product-grid-action-column-row',
            'product_grid_row',
            60, get_js_file_url("production/catalog/product/grid/actionColumnRow.js", true),
            [
                'deleteUrl' => generate_url('admin.graphql.api', ['type'=>'deleteProduct']),
            ]
        );

        return $delegate;
    }
}