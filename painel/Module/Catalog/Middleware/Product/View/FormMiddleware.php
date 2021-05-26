<?php


declare(strict_types=1);

namespace KED\Module\Catalog\Middleware\Product\View;

use function KED\generate_url;
use function KED\get_js_file_url;
use KED\Module\Graphql\Services\GraphqlExecutor;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\MiddlewareAbstract;


class FormMiddleware extends MiddlewareAbstract
{
    /**
     * @param Request $request
     * @param Response $response
     * @param null $delegate
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($response->getStatusCode() == 404)
            return $delegate;

        $this->getContainer()
            ->get(GraphqlExecutor::class)
            ->waitToExecute([
                "query"=>"{
                    custom_options : product(id: {$request->attributes->get('id')})
                    {
                        options {
                            option_id: product_custom_option_id
                            option_name
                            option_type
                            is_required
                            values {
                                value_id: product_custom_option_value_id
                                extra_price
                                value
                            }
                        }
                    }
                }"
            ])
            ->then(function ($result) use ($request, $response) {
                /**@var \GraphQL\Executor\ExecutionResult $result */
                $options = [];
                if (isset($result->data['custom_options']) and $result->data['custom_options']) {
                    $options = $result->data['custom_options']['options'];
                }
                $response->addWidget(
                    'product_view_form',
                    'product_page_middle_right',
                    50,
                    get_js_file_url("production/catalog/product/view/form.js", false),
                    [
                        "customOptions" => $options,
                        "productId" => $request->attributes->get('id'),
                        "action" => generate_url("cart.add")
                    ]
                );
            });

        return $delegate;
    }
}