<?php


declare(strict_types=1);

namespace KED\Module\Catalog\Middleware\Category\Edit;

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
     * @param null $delegate
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $response->addWidget(
            'category-edit-form',
            'content',
            10,
            get_js_file_url("production/catalog/category/edit/category_edit_form.js", true),
            [
                "id"=> 'category-edit-form',
                "action" => $this->getContainer()->get(Router::class)->generateUrl("category.save", ['id'=>$request->attributes->get('id', null)], $request->query->get('language', null) != null ? ['language' => $request->query->get('language')] : null),
                "listUrl" => generate_url('category.grid'),
                "cancelUrl" => $request->attributes->get('id') ? generate_url('category.edit', ['id' => $request->attributes->get('id')]) : generate_url('category.create')
            ]
        );

        return $delegate;
    }
}
