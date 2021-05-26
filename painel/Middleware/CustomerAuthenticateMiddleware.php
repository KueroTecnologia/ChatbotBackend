<?php


declare(strict_types=1);

namespace KED\Middleware;

use function KED\create_mutable_var;
use KED\Module\Customer\Services\Customer;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class CustomerAuthenticateMiddleware extends MiddlewareAbstract
{
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($request->isAdmin()) {
            return $delegate;
        }

        
        $customer = new Customer($request->getSession());
        $request->setCustomer($customer);
        $response->addState('customer', create_mutable_var("customer_state", [
            'full_name' => $request->getCustomer()->getData('full_name'),
            'email' => $request->getCustomer()->getData('email')
        ], [$customer]));

        return $delegate;
    }
}