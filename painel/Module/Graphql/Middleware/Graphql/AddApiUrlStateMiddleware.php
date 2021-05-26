<?php

declare(strict_types=1);

namespace KED\Module\Graphql\Middleware\Graphql;


use function KED\generate_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class AddApiUrlStateMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($request->isAdmin())
            $response->addState('graphqlApi', generate_url('admin.graphql.api'));
        else
            $response->addState('graphqlApi', generate_url('graphql.api'));

        return $delegate;
    }
}