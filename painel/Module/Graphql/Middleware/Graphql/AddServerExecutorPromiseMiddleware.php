<?php


declare(strict_types=1);

namespace KED\Module\Graphql\Middleware\Graphql;


use KED\Middleware\MiddlewareAbstract;
use KED\Module\Graphql\Services\GraphqlExecutor;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Services\PromiseWaiter;

class AddServerExecutorPromiseMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($this->getContainer()->get(GraphqlExecutor::class)->getOperationParams() and
            $request->attributes->get('_matched_route') != 'graphql.api' and
            $request->attributes->get('_matched_route') != 'admin.graphql.api'
        ) {
            $this->getContainer()->get(PromiseWaiter::class)->addPromise(
                'serverGraphqlExecutor',
                $this->getContainer()->get(GraphqlExecutor::class)
            );
        }

        return $delegate;
    }
}