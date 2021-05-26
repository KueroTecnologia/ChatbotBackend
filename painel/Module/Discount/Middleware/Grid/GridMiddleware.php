<?php


declare(strict_types=1);

namespace KED\Module\Discount\Middleware\Grid;

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
        if ($response->hasWidget('coupon-grid'))
            return $delegate;

        $this->getContainer()->get(Helmet::class)->setTitle("Coupons");

        $response->addWidget(
            'coupon_grid_container',
            'content',
            0,
            get_js_file_url("production/grid/grid.js", true),
            ['id'=>"coupon_grid_container"]
        );

        $response->addWidget(
            'coupon-grid',
            'coupon_grid_container',
            20,
            get_js_file_url("production/discount/grid/grid.js", true),
            [
                "apiUrl" => generate_url('admin.graphql.api')
            ]
        );

        return $delegate;
    }
}