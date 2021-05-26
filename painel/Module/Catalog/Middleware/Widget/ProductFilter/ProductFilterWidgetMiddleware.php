<?php


declare(strict_types=1);

namespace KED\Module\Catalog\Middleware\Widget\ProductFilter;


use function KED\array_find;
use function KED\get_config;
use function KED\get_js_file_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Module\Graphql\Services\GraphqlExecutor;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class ProductFilterWidgetMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($request->isAdmin() == true)
            return $delegate;

        $this->getContainer()
            ->get(GraphqlExecutor::class)
            ->waitToExecute([
                "query"=>"{productFilter : widgetCollection (filters : [{key: \"type\" operator : \"=\" value: \"product_filter\"}]) {widgets { cms_widget_id name setting {key value} displaySetting {key value} sort_order }}}"
            ])->then(function ($result) use ($request, $response) {
                /**@var \GraphQL\Executor\ExecutionResult $result */
                if (isset($result->data['productFilter'])) {
                    $matchedRoute = $request->attributes->get('_matched_route');
                    $widgets = array_filter($result->data['productFilter']['widgets'], function ($v) use ($matchedRoute) {
                        $layouts = array_find($v['displaySetting'], function ($value, $key) {
                            if ($value['key'] == 'layout')
                                return json_decode($value['value'], true);
                            return null;
                        }, []);

                        $match = false;
                        foreach ($layouts as $layout) {
                            if ($matchedRoute == $layout || $layout == "all") {
                                $match = true;
                                break;
                            }
                        }
                        return $match;
                    }, ARRAY_FILTER_USE_BOTH);

                    foreach ($widgets as $widget) {
                        $title = array_find($widget['setting'], function ($value, $key) {
                            if ($value['key'] == 'title')
                                return $value['value'];
                            return null;
                        });

                        $areas = [];
                        foreach ($widget['displaySetting'] as $key => $value) {
                            if ($value['key'] == 'area')
                                $areas = array_merge($areas, json_decode($value['value'], true));
                            if ($value['key'] == 'area_manual_input')
                                $areas = array_merge($areas, explode(",", $value['value']));
                        }

                        foreach ($areas as $area)
                            $response->addWidget(
                                $widget['cms_widget_id'] . '-product-filter-widget',
                                trim($area),
                                (int)$widget['sort_order'],
                                get_js_file_url("production/catalog/widgets/filter.js", false),
                                [
                                    "title" => $title,
                                    "price_max_step" => get_config("catalog_product_filter_price_max_step", 5),
                                    "price_min_range" => get_config("catalog_product_filter_price_min_range", 50)
                                ]
                            );
                    }
                }
            }, function ($reason) {var_dump($reason);});

        return $delegate;
    }
}