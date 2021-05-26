<?php


declare(strict_types=1);

namespace KED\Module\Customer\Middleware\Grid;

use function KED\_mysql;
use function KED\generate_url;
use function KED\get_js_file_url;
use KED\Services\Helmet;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\MiddlewareAbstract;

class GridMiddleware extends MiddlewareAbstract
{
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($response->hasWidget('customer_grid'))
            return $delegate;

        $this->getContainer()->get(Helmet::class)->setTitle("Customers");
        $response->addWidget(
            'coupon_grid_container',
            'content',
            0,
            get_js_file_url("production/grid/grid.js", true),
            ['id'=>"customer_grid_container"]
        );

        $groups = _mysql()->getTable('customer_group')->where('customer_group_id', '<', 999)->fetchAllAssoc();
        $response->addWidget(
            'customer_grid',
            'customer_grid_container',
            20, get_js_file_url("production/customer/grid/grid.js", true),
            [
                "apiUrl" => generate_url('admin.graphql.api'),
                "groups" => $groups
            ]
        );

        return $delegate;
    }
}