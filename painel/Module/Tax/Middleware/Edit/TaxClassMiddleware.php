<?php

declare(strict_types=1);

namespace KED\Module\Tax\Middleware\Edit;

use function KED\get_js_file_url;
use KED\Module\Graphql\Services\GraphqlExecutor;
use KED\Services\Helmet;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Routing\Router;

class TaxClassMiddleware extends MiddlewareAbstract
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
                "query"=>"{
                    taxClasses {
                        tax_class_id
                        name
                        rates {
                            id:tax_rate_id
                            name
                            country
                            province
                            postcode
                            rate
                            is_compound
                            priority
                        }
                    }
                }"
            ])
            ->then(function ($result) use ($response) {
                /**@var \GraphQL\Executor\ExecutionResult $result */
                if (isset($result->data['taxClasses'])) {
                    array_walk($result->data['taxClasses'], function (&$tax) {
                        $tax = array_merge($tax, [
                            'formId'=> 'tax_class_edit_' . $tax['tax_class_id']
                        ]);
                    });
                    $response->addWidget(
                        'tax_setting',
                        'content',
                        10,
                        get_js_file_url("production/tax/form.js", true),
                        [
                            'classes' => $result->data['taxClasses'],
                            'saveAction'=> $this->getContainer()->get(Router::class)->generateUrl('tax.class.save')
                        ]
                    );
                }
            });
        $this->getContainer()->get(Helmet::class)->setTitle('Tax setting');
        return $delegate;
    }
}
