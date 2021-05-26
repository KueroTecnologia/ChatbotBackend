<?php


declare(strict_types=1);

namespace KED\Module\Customer\Middleware\Create;


use GuzzleHttp\Promise\Promise;
use function KED\get_config;
use KED\Middleware\MiddlewareAbstract;
use KED\Module\SendGrid\Services\SendGrid;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class LoginMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, Promise $promise = null)
    {
        if (!$promise instanceof Promise)
            return $promise;

        $promise->then(function ($result) {
            if (isset($result->data['createCustomer']['status']) and $result->data['createCustomer']['status'] == true) {
                if (isset($result->data['createCustomer']['status']) and $result->data['createCustomer']['status'] == true)
                    $this->getContainer()->get(Request::class)->getCustomer()->forceLogin($result->data['createCustomer']['customer']['email']);
            }
        });

        return $promise;
    }
}