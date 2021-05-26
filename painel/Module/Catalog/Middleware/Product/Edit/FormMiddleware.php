<?php


declare(strict_types=1);

namespace KED\Module\Catalog\Middleware\Product\Edit;

use function KED\generate_url;
use function KED\get_js_file_url;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Routing\Router;

class FormMiddleware extends MiddlewareAbstract
{
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $response->addWidget(
            'product-edit-form',
            'content',
            10,
            get_js_file_url("production/catalog/product/edit/product_edit_form.js", true),
            [
                "id"=> 'product-edit-form',
                "action" => $this->getContainer()->get(Router::class)->generateUrl("product.save", ['id'=>$request->attributes->get('id', null)], $request->query->get('language', null) != null ? ['language' => $request->query->get('language')] : null),
                "listUrl" => generate_url('product.grid'),
                "cancelUrl" => $request->attributes->get('id') ? generate_url('product.edit', ['id' => $request->attributes->get('id')]) : generate_url('product.create')
            ]
        );

        return $delegate;
    }
}
