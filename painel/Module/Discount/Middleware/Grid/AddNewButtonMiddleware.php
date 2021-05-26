<?php


declare(strict_types=1);

namespace KED\Module\Discount\Middleware\Grid;

use function KED\generate_url;
use function KED\get_js_file_url;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\MiddlewareAbstract;

class AddNewButtonMiddleware extends MiddlewareAbstract
{
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $response->addWidget(
            'coupon-grid-add-new',
            'coupon_grid_container',
            5,
            get_js_file_url("production/discount/grid/add_new_button.js", true),
            [
                "url" => generate_url('coupon.create')
            ]
        );

        return $delegate;
    }
}