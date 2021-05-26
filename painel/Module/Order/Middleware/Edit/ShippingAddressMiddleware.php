<?php


declare(strict_types=1);

namespace KED\Module\Order\Middleware\Edit;

use GraphQL\Type\Schema;
use function KED\dirty_output_query;
use function KED\get_js_file_url;
use KED\Module\Graphql\Services\GraphqlExecutor;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\MiddlewareAbstract;

class ShippingAddressMiddleware extends MiddlewareAbstract
{
    /**
     * @param Request $request
     * @param Response $response
     * @param null $delegate
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $outPut = dirty_output_query($this->getContainer()->get(Schema::class), 'CustomerAddress');
        // Loading data by using GraphQL
        $this->getContainer()
            ->get(GraphqlExecutor::class)
            ->waitToExecute([
                "query"=>"{
                    shipping_address : order (id: {$request->attributes->get('id')}) {
                        shipping_address $outPut
                    }
                }"
            ])
            ->then(function ($result) use ($response) {
                /**@var \GraphQL\Executor\ExecutionResult $result */
                if (isset($result->data['shipping_address'])) {
                    $response->addWidget(
                        'shipping_address',
                        'order_edit_right',
                        20,
                        get_js_file_url("production/order/edit/shipping_address.js", true),
                        [
                            'address' => $result->data['shipping_address']['shipping_address']
                        ]
                    );
                }
            });

        return $delegate;
    }
}
