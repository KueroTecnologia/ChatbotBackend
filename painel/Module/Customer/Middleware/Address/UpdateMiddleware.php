<?php


declare(strict_types=1);

namespace KED\Module\Customer\Middleware\Address;


use function KED\dispatch_event;
use KED\Middleware\MiddlewareAbstract;
use KED\Module\Graphql\Services\GraphqlExecutor;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class UpdateMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $variables = $request->get('variables', []);
        $variables['id'] = $request->attributes->get('id');

        $query = "mutation UpdateCustomerAddress(\$address: AddressInput! \$id: Int!) { updateCustomerAddress (address: \$address id: \$id) {status message address {customer_address_id full_name telephone address_1 address_2 postcode city province country is_default update_url delete_url}}}";

        dispatch_event("filter_update_customer_address_query", [&$query]);

        $response->notNewPage();
        $promise = $this->getContainer()
            ->get(GraphqlExecutor::class)
            ->waitToExecute([
                "query" => $query,
                "variables" => $variables
            ]);

        $promise->then(function ($result) use ($request, $response) {
            $response->addData('addressUpdate', $result->data['updateCustomerAddress']);
        });

        $promise->otherwise(function ($reason) use ($request, $response) {
            // TODO: Support development mode and show real message
            $response->addData('addressUpdate', ['status'=> false, 'message'=> $reason[0]->message]);
        });

        return $promise;
    }
}