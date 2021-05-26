<?php


declare(strict_types=1);

namespace KED\Module\Marketing\Middleware\SubscribeFormWidget;


use function KED\get_js_file_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Module\Graphql\Services\GraphqlExecutor;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Services\Routing\Router;

class FormMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $id = $request->attributes->get('id', null);
        if ($request->attributes->get('type', null) != "newsletter_form" && $request->attributes->get('type', null) != null)
            return $delegate;

        if ($id)
            $this->getContainer()
                ->get(GraphqlExecutor::class)
                ->waitToExecute([
                    "query"=>"{
                            cmsWidget(id: {$id} )
                            {
                                id: cms_widget_id
                                name
                                status
                                setting {
                                    key
                                    value
                                }
                                displaySetting {
                                    key
                                    value
                                }
                                sort_order
                            }
                        }"
                ])->then(function ($result) use (&$fields, $response) {
                    /**@var \GraphQL\Executor\ExecutionResult $result */
                    if (isset($result->data['cmsWidget'])) {
                        $response->addWidget(
                            'newsletter_form_widget_edit_form',
                            'widget_edit_form',
                            10,
                            get_js_file_url("production/marketing/widget/newsletter_form/form.js", true),
                            array_merge($result->data['cmsWidget'], [
                                "formAction" => $this->getContainer()->get(Router::class)->generateUrl("admin.graphql.api"),
                                "redirect"=> $this->getContainer()->get(Router::class)->generateUrl("widget.grid")
                            ])
                        );
                    }
                });
        else
            $response->addWidget(
                'newsletter_form_widget_edit_form',
                'widget_edit_form',
                10,
                get_js_file_url("production/marketing/widget/newsletter_form/form.js", true),
                [
                    "formAction" => $this->getContainer()->get(Router::class)->generateUrl("admin.graphql.api"),
                    "redirect"=> $this->getContainer()->get(Router::class)->generateUrl("widget.grid")
                ]
            );

        return $delegate;
    }
}