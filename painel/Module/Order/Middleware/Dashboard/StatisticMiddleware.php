<?php


declare(strict_types=1);

namespace KED\Module\Order\Middleware\Dashboard;


use function KED\get_js_file_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class StatisticMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $response->addWidget(
            'saleStatistic',
            'admin_dashboard_middle_left',
            10,
            get_js_file_url("production/order/dashboard/statistic.js", true)
        );
    }
}