<?php


declare(strict_types=1);

namespace KED\Module\Catalog\Middleware\Category\Edit;

use function KED\generate_url;
use function KED\get_config;
use function KED\get_js_file_url;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\MiddlewareAbstract;

class ProductsMiddleware extends MiddlewareAbstract
{
    /**
     * @param Request $request
     * @param Response $response
     * @param null $delegate
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($response->hasWidget('category_edit_products'))
            return $delegate;

        if ($request->attributes->get('_matched_route') == 'category.edit') {
            $response->addWidget(
                'product_grid_container',
                'admin_category_edit_inner_right',
                0, get_js_file_url("production/grid/grid.js", true),
                [
                    'id'=>"product_grid_container",
                    'defaultFilter'=> [
                        [
                            "key" => "category",
                            "operator" => "IN",
                            "value" => [$request->attributes->get('id')]
                        ]
                    ]
                ]
            );

            $response->addWidget(
                'product_grid',
                'product_grid_container',
                10, get_js_file_url("production/catalog/category/edit/products.js", true),
                [
                    "apiUrl" => generate_url('admin.graphql.api'),
                    "limit" => get_config('catalog_product_list_limit', 20)
                ]
            );
        }

        return $delegate;
    }
}
