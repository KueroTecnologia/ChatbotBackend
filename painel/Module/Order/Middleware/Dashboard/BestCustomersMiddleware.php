<?php


declare(strict_types=1);

namespace KED\Module\Order\Middleware\Dashboard;


use function KED\_mysql;
use function KED\generate_url;
use function KED\get_js_file_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Module\Graphql\Services\GraphqlExecutor;
use KED\Services\Db\Processor;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class BestCustomersMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $conn = $this->getContainer()->get(Processor::class);
        $customers = $conn->executeQuery("SELECT `customer`.customer_id, `customer`.full_name, COUNT(`order`.order_id) as orders, SUM(`order`.grand_total) as `total`
        FROM `customer`
        INNER JOIN `order`
        ON `customer`.customer_id = `order`.customer_id
        GROUP BY `customer`.customer_id
        ORDER BY `orders` DESC
        LIMIT 0, 10
        ")->fetchAll(\PDO::FETCH_ASSOC);

        array_walk($customers, function (&$c) {
            $c["editUrl"] = generate_url("customer.edit", ["id"=> $c['customer_id']]);
        });

        $response->addWidget(
            'best_customers',
            'admin_dashboard_middle_right',
            30,
            get_js_file_url("production/order/dashboard/best_customers.js", true),
            [
                'customers' => $customers,
                'listUrl' => generate_url("customer.grid")
            ]
        );

        return $delegate;
    }
}