<?php


declare(strict_types=1);

namespace KED\Module\Cms\Middleware\Widget\Edit;


use function KED\create_mutable_var;
use function KED\get_js_file_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Helmet;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class EditMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($request->attributes->get('id')) {
            $this->getContainer()->get(Helmet::class)->setTitle("Edit a widget");
        } else {
            $this->getContainer()->get(Helmet::class)->setTitle("Create a widget");
        }

        $response->addWidget(
            'widget_edit_form_wrapper',
            'content',
            10,
            get_js_file_url("production/cms/widget/edit/widget_edit.js", true),
            [
                "widgetId"=> $request->attributes->get('id'),
                "id"=> "widget_edit_form_wrapper",
                "types"=> create_mutable_var("widget_types", []),
                "selectedType"=> $request->attributes->get('type', "")
            ]
        );

        return $delegate;
    }
}