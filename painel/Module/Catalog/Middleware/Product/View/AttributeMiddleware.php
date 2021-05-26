<?php


declare(strict_types=1);

namespace KED\Module\Catalog\Middleware\Product\View;

use function KED\get_js_file_url;
use KED\Module\Graphql\Services\GraphqlExecutor;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\MiddlewareAbstract;


class AttributeMiddleware extends MiddlewareAbstract
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

        $promise = $this->getContainer()
            ->get(GraphqlExecutor::class)
            ->waitToExecute([
                "query"=>"{
                    productAttributeIndex(product_id: {$request->get('id')})
                    {
                        attribute_name
                        attribute_id
                        option_id
                        attribute_value_text
                    }
                }"
            ]);

        $promise->then(function ($result) use ($response) {
                /**@var \GraphQL\Executor\ExecutionResult $result */
                if (isset($result->data['productAttributeIndex']) and $result->data['productAttributeIndex']) {
                    $response->addWidget(
                        'product_view_attribute',
                        'product_single_tabs',
                        20,
                        get_js_file_url("production/catalog/product/view/attributes.js", false),
                        [
                            "attributes"=>$result->data['productAttributeIndex']
                        ]
                    );
                }
            });

        return $delegate;
    }
}