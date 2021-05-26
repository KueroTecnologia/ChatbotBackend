<?php


declare(strict_types=1);

namespace KED\Module\Catalog\Middleware\Product\Edit;

use function KED\get_js_file_url;
use KED\Module\Graphql\Services\GraphqlExecutor;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\MiddlewareAbstract;

class PriceMiddleware extends MiddlewareAbstract
{
    const FORM_ID = 'product-edit-form';

    /**
     * @param Request $request
     * @param Response $response
     * @param null $delegate
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($response->hasWidget('product_edit_advanced_price'))
            return $delegate;
        if ($request->attributes->get('_matched_route') == 'product.edit')
            $query = <<< QUERY
                    {
                        productTierPrice (productId: {$request->attributes->getInt('id')}) {
                                product_price_id
                                product_id
                                price: tier_price
                                customer_group_id       
                                qty
                                active_from
                                active_to
                        }
                        customerGroups {
                            value: customer_group_id
                            text: group_name
                        }
                    }
QUERY;
        else
            $query = <<< QUERY
                    {
                        customerGroups {
                            value: customer_group_id
                            text: group_name
                        }
                    }
QUERY;
        $this->getContainer()
            ->get(GraphqlExecutor::class)
            ->waitToExecute([
                "query"=> $query
            ])
            ->then(function ($result) use ($response) {
                $tierPrice = [];
                $customerGroups = [['value'=> '999', 'text'=> 'All']];
                /**@var \GraphQL\Executor\ExecutionResult $result */
                if (isset($result->data['productTierPrice'])) {
                    $tierPrice = $result->data['productTierPrice'];
                }
                if (isset($result->data['customerGroups'])) {
                    $customerGroups = array_merge($customerGroups, $result->data['customerGroups']);
                }
                $response->addWidget(
                    'product_edit_advanced_price',
                    'admin_product_edit_inner_left',
                    30,
                    get_js_file_url("production/catalog/product/edit/advanced_price.js", true),
                    ['formId'=> self::FORM_ID, "prices"=> $tierPrice, 'customerGroups'=>$customerGroups]
                );
            });

        return $delegate;
    }
}
