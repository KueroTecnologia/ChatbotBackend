<?php


declare(strict_types=1);

namespace KED\Module\Catalog\Middleware\Category\View;

use GraphQL\Language\Parser;
use GraphQL\Language\Source;
use function KED\_mysql;
use function KED\array_find;
use function KED\create_mutable_var;
use function KED\generate_url;
use function KED\get_config;
use function KED\get_js_file_url;
use KED\Module\Catalog\Services\ProductCollection;
use KED\Module\Graphql\Services\GraphqlExecutor;
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
        if ($response->hasWidget('category_view_products'))
            return $delegate;

        $query = create_mutable_var("product_list_query", <<< QUERY
                    {
                        productCollection (filters: <FILTER>) {
                                products {
                                    product_id
                                    name
                                    price
                                    salePrice
                                    url
                                    image {
                                        list
                                    }
                                }
                                total
                                currentFilter
                        }
                    }
QUERY
        );

        $query = str_replace("<FILTER>", trim($request->attributes->get("filters", "")), $query);

        $promise = $this->getContainer()
            ->get(GraphqlExecutor::class)
            ->waitToExecute([
                "query"=> $query
            ]);

        $promise->then(function ($result) use ($request, $response, $query) {
                /**@var \GraphQL\Executor\ExecutionResult $result */
                if (isset($result->data['productCollection']['products'])) {
                    $products = $result->data['productCollection']['products'];
                    $response->addState('productCollectionFilter', (function () use ($result) {
                        $fs = [];
                        $filters = json_decode($result->data['productCollection']['currentFilter'], true);
                        foreach ($filters as $filter) {
                            if ($this->getContainer()->get(Request::class)->query->has($filter["key"]))
                                $fs[] = $filter;
                        }
                        return $fs;
                    })());
                    $response->addWidget(
                        'category_view_products',
                        'content_center',
                        10,
                        get_js_file_url("production/catalog/category/view/products.js", false),
                        [
                            "products" => $products,
                            "currentFilter" => (function () use ($result) {
                                $fs = [];
                                $filters = json_decode($result->data['productCollection']['currentFilter'], true);
                                foreach ($filters as $filter) {
                                    if ($this->getContainer()->get(Request::class)->query->has($filter["key"]))
                                        $fs[] = $filter;
                                }
                                return json_encode($fs, JSON_NUMERIC_CHECK);
                            })(),
                            "total" => $result->data['productCollection']['total'],
                            "limit" => (function () use ($result) {
                                $limit = array_find(json_decode($result->data['productCollection']['currentFilter'], true), function ($f) {
                                    if ($f["key"] == "limit")
                                        return $f;
                                    return null;
                                });
                                if ($limit == null)
                                    return get_config('catalog_product_list_limit', 20);
                                return $limit["value"];
                            })(),
                            "countPerRow" => get_config("catalog_product_list_number_per_row", 3),
                            "currentPage" => (function () use ($result) {
                                $page = array_find(json_decode($result->data['productCollection']['currentFilter'], true), function ($f) {
                                    if ($f["key"] == "page")
                                        return $f;
                                    return null;
                                });
                                if ($page == null)
                                    return 1;
                                return $page["value"];
                            })(),
                            "addItemApi" => generate_url('cart.add'),
                            "query" => $query,
                            "with_pagination" => create_mutable_var("with_pagination", true),
                            "with_sorting" => create_mutable_var("with_sorting", true),
                            "sorting_options" => create_mutable_var("sorting_options", []),
                            "currentSortOrder" => (function () use ($result) {
                                $sortOrder = array_find(json_decode($result->data['productCollection']['currentFilter'], true), function ($f) {
                                    if ($f["key"] == "sort-order")
                                        return $f;
                                    return null;
                                });
                                if ($sortOrder == null)
                                    return get_config('catalog_product_list_sort_order', 'DESC');

                                return $sortOrder["value"];
                            })(),
                            "currentSortBy" => (function () use ($result) {
                                $sortBy = array_find(json_decode($result->data['productCollection']['currentFilter'], true), function ($f) {
                                    if ($f["key"] == "sort-by")
                                        return $f;
                                    return null;
                                });
                                if ($sortBy == null)
                                    return get_config('catalog_product_list_sort_by', 'product.created_at');
                                return $sortBy["value"];
                            })(),
                        ]
                    );
                }
        }, function ($reason) { var_dump($reason);});

        return $promise;
    }
}