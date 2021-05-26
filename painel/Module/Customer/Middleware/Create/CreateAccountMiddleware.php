<?php

declare(strict_types=1);

namespace KED\Module\Customer\Middleware\Create;


use function KED\dispatch_event;
use KED\Middleware\MiddlewareAbstract;
use KED\Module\Graphql\Services\GraphqlExecutor;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Services\Routing\Router;

class CreateAccountMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($request->getCustomer()->isLoggedIn()) {
            $response->redirect($this->getContainer()->get(Router::class)->generateUrl('homepage'));
            return $response;
        }

        $variables = $request->get('variables', []);
        $query = "mutation CreateCustomer(\$customer: CustomerInput!) { createCustomer (customer: \$customer) {status message customer {customer_id group_id status full_name email}}}";
        dispatch_event("filter_create_customer_query", [&$query]);
        $response->notNewPage();
        $promise = $this->getContainer()
            ->get(GraphqlExecutor::class)
            ->waitToExecute([
                "query" => $query,
                "variables" => $variables
            ]);

        $promise->then(function ($result) use ($request, $response) {
            $response->addData('customerCreation', $result->data['createCustomer']);
        });

        $promise->otherwise(function ($reason) use ($request, $response) {
            $response->addData('customerCreation', ['status'=> false, 'message'=> "Internal server error"]);
        });

        return $promise;
    }
}