<?php


declare(strict_types=1);

namespace KED\Module\Order\Middleware\Dashboard;


use function KED\generate_url;
use function KED\get_js_file_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Module\Graphql\Services\GraphqlExecutor;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class BestSellersMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, $delegate = null)
    {

        $promise = $this->getContainer()
            ->get(GraphqlExecutor::class)
            ->waitToExecute([
                "query"=>"{
                        bestSellers(limit: 10){
                            name
                            sku
                            price
                            qty
                            editUrl
                        }
                    }"
            ]);
        $promise->then(function ($result) use ($response) {
                /**@var \GraphQL\Executor\ExecutionResult $result */
                if (isset($result->data['bestSellers'])) {
                    $response->addWidget(
                        'best_sellers',
                        'admin_dashboard_middle_left',
                        20,
                        get_js_file_url("production/order/dashboard/best_sellers.js", true),
                        [
                            'products' => $result->data['bestSellers'],
                            'listUrl' => generate_url("product.grid")
                        ]
                    );
                }
                return $result;
            });

        return $delegate;
    }
}