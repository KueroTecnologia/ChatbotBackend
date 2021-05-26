<?php

declare(strict_types=1);

namespace KED\Module\Catalog\Middleware\Category\View;

use function KED\get_js_file_url;
use KED\Module\Graphql\Services\GraphqlExecutor;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\MiddlewareAbstract;

class GeneralInfoMiddleware extends MiddlewareAbstract
{

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($response->hasWidget('category_view_general'))
            return $delegate;

        // Loading data by using GraphQL
        $this->getContainer()
            ->get(GraphqlExecutor::class)
            ->waitToExecute([
                "query"=>"{
                    general_info: category(id: {$request->get('id')})
                    {
                        category_id 
                        name 
                        description 
                    }
                }"
            ])
            ->then(function ($result) use ($response) {
                /**@var \GraphQL\Executor\ExecutionResult $result */
                if (isset($result->data['general_info']) and $result->data['general_info']) {
                    $response->addWidget(
                        'category_view_general',
                        'content_top',
                        10,
                        get_js_file_url("production/catalog/category/view/general.js", false),
                        $result->data['general_info']
                    );
                }
            });

        return $delegate;
    }
}