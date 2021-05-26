<?php


declare(strict_types=1);

namespace KED\Module\Setting\Middleware\Shipment;

use function KED\get_js_file_url;
use KED\Services\Helmet;
use KED\Services\Http\Request;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Response;

class ShipmentSettingMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($request->getMethod() == 'POST')
            return $delegate;
        $this->getContainer()->get(Helmet::class)->setTitle('Shipment setting');
        $response->addWidget(
            'shipment_setting',
            'content',
            10,
            get_js_file_url("production/setting/shipment/shipment.js", true)
        );

        return $delegate;
    }
}