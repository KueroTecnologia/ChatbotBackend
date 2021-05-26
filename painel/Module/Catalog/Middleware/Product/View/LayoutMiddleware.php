<?php


declare(strict_types=1);

namespace KED\Module\Catalog\Middleware\Product\View;

use function KED\get_js_file_url;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\MiddlewareAbstract;


class LayoutMiddleware extends MiddlewareAbstract
{
    /**
     * @param Request $request
     * @param Response $response
     * @param null $delegate
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $response->addWidget(
            'product_page_layout',
            'content_center',
            0,
            get_js_file_url("production/catalog/product/view/layout.js", false)
        );

        return $delegate;
    }
}