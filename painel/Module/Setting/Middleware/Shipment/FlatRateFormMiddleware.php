<?php


declare(strict_types=1);

namespace KED\Module\Setting\Middleware\Shipment;

use function KED\_mysql;
use function KED\get_js_file_url;
use KED\Services\Http\Request;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Response;
use KED\Services\Routing\Router;
// TODO: move this middleware to Flatrate module
class FlatRateFormMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($request->getMethod() == 'POST')
            return $delegate;

        $stm = _mysql()
            ->executeQuery("SELECT * FROM `setting` WHERE `name` LIKE 'shipment_flat_rate_%'");

        $data = [];
        while ($row = $stm->fetch()) {
            if ($row['json'] == 1)
                $data[$row['name']] = json_decode($row['value'], true);
            else
                $data[$row['name']] = $row['value'];
        }

        $response->addWidget(
            'flat_rate_shipment_form',
            'shipment-setting',
            10,
            get_js_file_url("production/flat_rate/setting/flat_rate_form.js", true),
            array_merge($data, [
                "action"=>$this->getContainer()->get(Router::class)->generateUrl('setting.shipment', ['method'=>'flat_rate']),
            ])
        );

        return $delegate;
    }
}