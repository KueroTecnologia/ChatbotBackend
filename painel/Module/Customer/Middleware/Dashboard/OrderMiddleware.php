<?php


declare(strict_types=1);

namespace KED\Module\Customer\Middleware\Dashboard;


use function KED\create_mutable_var;
use function KED\get_js_file_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class OrderMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $query = create_mutable_var("my_orders_query", "{
                    customer (id: {$request->getCustomer()->getData('customer_id')}) {
                        orders {
                            order_number 
                            payment_status
                            shipment_status
                            created_at 
                            tax_amount
                            discount_amount 
                            coupon
                            grand_total
                            items {
                                order_item_id
                                product_id
                                product_sku
                                product_name
                                product_price
                                qty
                                final_price
                                total
                            }
                        }
                    }
                }");

        $response->addWidget(
            'orders',
            'customer_dashboard_layout',
            30,
            get_js_file_url("production/customer/orders.js", false),
            [
                'query' => $query
            ]
        );
        return $delegate;
    }
}