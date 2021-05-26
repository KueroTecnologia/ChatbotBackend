<?php


declare(strict_types=1);

namespace KED\Module\Setting\Middleware\Payment;

use function KED\_mysql;
use function KED\get_js_file_url;
use KED\Services\Http\Request;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Response;
use KED\Services\Routing\Router;
// TODO: move this middleware to COD module
class CODFormMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($request->getMethod() == 'POST')
            return $delegate;

        $stm = _mysql()
            ->executeQuery("SELECT * FROM `setting` WHERE `name` LIKE 'payment_cod_%'");

        $data = [];
        while ($row = $stm->fetch()) {
            if ($row['json'] == 1)
                $data[$row['name']] = json_decode($row['value'], true);
            else
                $data[$row['name']] = $row['value'];
        }

        $response->addWidget(
            'cod_payment_form',
            'payment-setting',
            10,
            get_js_file_url("production/cod/setting/cod_form.js", true),
            array_merge($data, [
                "action"=>$this->getContainer()->get(Router::class)->generateUrl('setting.payment', ['method'=>'cod']),
            ])
        );

        return $delegate;
    }
}