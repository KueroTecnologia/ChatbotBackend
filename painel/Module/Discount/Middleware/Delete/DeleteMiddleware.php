<?php

declare(strict_types=1);

namespace KED\Module\Discount\Middleware\Delete;


use function KED\_mysql;
use function KED\generate_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class DeleteMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $id = $request->attributes->get('id');
        try {
            _mysql()->getTable('coupon')->where('coupon_id', '=', $id)->delete();
            $response->addAlert("coupon_delete_success", "success", "Coupon deleted");
            $response->redirect(generate_url('coupon.grid'));
        } catch (\Exception $e) {
            $response->addAlert("coupon_delete_error", "error", $e->getMessage())->notNewPage();
        }

        return $delegate;
    }
}