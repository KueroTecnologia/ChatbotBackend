<?php

declare(strict_types=1);

namespace KED\Module\Order\Middleware\Edit;

use function KED\get_js_file_url;
use KED\Module\Graphql\Services\GraphqlExecutor;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\MiddlewareAbstract;

class ItemsMiddleware extends MiddlewareAbstract
{
    /**
     * @param Request $request
     * @param Response $response
     * @param null $delegate
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $this->getContainer()
            ->get(GraphqlExecutor::class)
            ->waitToExecute([
                "query"=>"{
                    orderItems: order (id: {$request->attributes->get('id')}) {
                        items {
                            item_id: order_item_id
                            product_id
                            product_sku
                            product_name
                            product_price
                            qty
                            final_price
                            options {
                                option_id
                                option_name
                                values {
                                    value_id
                                    value_text
                                    extra_price
                                }
                            }
                            total
                        }
                    }
                }"
            ])
            ->then(function ($result) use ($response) {
                /**@var \GraphQL\Executor\ExecutionResult $result */
                if (empty($result->errors)) {
                    $response->addWidget(
                        'order_items',
                        'order_edit_left',
                        15,
                        get_js_file_url("production/order/edit/items.js", true),
                        [
                            'items'=> $result->data['orderItems']['items']
                        ]
                    );
                }
            });

        return $delegate;
    }
}
