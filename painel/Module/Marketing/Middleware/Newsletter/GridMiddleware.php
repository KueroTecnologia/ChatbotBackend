<?php


declare(strict_types=1);

namespace KED\Module\Marketing\Middleware\Newsletter;


use function KED\_mysql;
use function KED\generate_url;
use function KED\get_js_file_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Helmet;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class GridMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($response->hasWidget('subscriber_grid'))
            return $delegate;

        $this->getContainer()->get(Helmet::class)->setTitle("Subscribers");
        $response->addWidget(
            'subscriber_grid_container',
            'content',
            0,
            get_js_file_url("production/grid/grid.js", true),
            ['id'=>"subscriber_grid_container"]
        );

        $response->addWidget(
            'subscriber_grid',
            'subscriber_grid_container',
            20,
            get_js_file_url("production/marketing/newsletter/grid.js", true),
            [
                "apiUrl" => generate_url('admin.graphql.api')
            ]
        );

        return $delegate;
    }
}