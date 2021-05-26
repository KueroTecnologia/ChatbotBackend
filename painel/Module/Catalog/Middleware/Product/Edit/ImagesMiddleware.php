<?php

declare(strict_types=1);

namespace KED\Module\Catalog\Middleware\Product\Edit;

use function KED\generate_url;
use function KED\get_js_file_url;
use KED\Module\Graphql\Services\GraphqlExecutor;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\MiddlewareAbstract;

class ImagesMiddleware extends MiddlewareAbstract
{
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($response->hasWidget('product_edit_images'))
            return $delegate;

        if ($request->attributes->get('_matched_route') == 'product.edit')
            $this->getContainer()
                ->get(GraphqlExecutor::class)
                ->waitToExecute([
                    "query"=>"{
                    productImages(productId: {$request->get('id')})
                    {
                        images {
                            path
                            url: image
                            isMain
                        }
                    }
                }"
                ])
                ->then(function ($result) use ($response) {
                    /**@var \GraphQL\Executor\ExecutionResult $result */
                    //var_dump($result);
                    if (isset($result->data['productImages']) and $result->data['productImages']) {
                        $response->addWidget(
                            'product_edit_images',
                            'admin_product_edit_inner_left',
                            20,
                            get_js_file_url("production/catalog/product/edit/images.js", true),
                            array_merge(
                                $result->data['productImages'],
                                [
                                    'uploadApi' =>generate_url('admin.graphql.api', ['type' => 'uploadMedia'])
                                ]
                                )
                        );
                    }
                });
        else
            $response->addWidget(
                'product_edit_images',
                'admin_product_edit_inner_left',
                20,
                get_js_file_url("production/catalog/product/edit/images.js", true),
                [
                    'uploadApi' =>generate_url('admin.graphql.api', ['type' => 'uploadMedia'])
                ]
            );
        return $delegate;
    }
}
