<?php


declare(strict_types=1);

namespace KED\Module\Order\Middleware\Edit;

use function KED\create_mutable_var;
use function KED\generate_url;
use function KED\get_js_file_url;
use KED\Module\Graphql\Services\GraphqlExecutor;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\MiddlewareAbstract;

class PaymentMiddleware extends MiddlewareAbstract
{
    /**
     * @param Request $request
     * @param Response $response
     * @param null $delegate
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        // Loading data by using GraphQL
        $this->getContainer()
            ->get(GraphqlExecutor::class)
            ->waitToExecute([
                "query"=> create_mutable_var("order_edit_payment_query", "{
                    payment : order (id: {$request->attributes->get('id')}) {
                        orderId: order_id
                        currency
                        status: payment_status
                        method: payment_method
                        methodName: payment_method_name
                        grandTotal: grand_total
                        payment_transactions {
                            id
                            transaction_id
                            transaction_type
                            amount
                            parent_transaction_id
                            payment_action
                            additional_information
                            created_at
                        }
                    }
                }")
            ])
            ->then(function ($result) use ($request, $response) {
                /**@var \GraphQL\Executor\ExecutionResult $result */
                if (isset($result->data['payment'])) {
                    $response->addWidget(
                        'order_payment',
                        'order_edit_left',
                        20,
                        get_js_file_url("production/order/edit/payment.js", true),
                        $result->data['payment']
                    );
                }
            });

        return $delegate;
    }
}
