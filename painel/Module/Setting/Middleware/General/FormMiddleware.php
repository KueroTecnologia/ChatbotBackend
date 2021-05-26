<?php


declare(strict_types=1);

namespace KED\Module\Setting\Middleware\General;

use function KED\_mysql;
use function KED\generate_url;
use function KED\get_js_file_url;
use KED\Services\Helmet;
use KED\Services\Http\Request;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Response;
use KED\Services\Routing\Router;

class FormMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($request->getMethod() == 'POST')
            return $delegate;

        $this->getContainer()->get(Helmet::class)->setTitle('General setting');
        $stm = _mysql()
            ->executeQuery("SELECT * FROM `setting` WHERE `name` LIKE 'general_%'");

        $data = [];
        while ($row = $stm->fetch()) {
            if ($row['json'] == 1)
                $data[$row['name']] = json_decode($row['value'], true);
            else
                $data[$row['name']] = $row['value'];
        }

        $response->addWidget(
            'general_setting_form',
            'content',
            10,
            get_js_file_url("production/setting/general/form.js", true),
            [
                "action" =>$this->getContainer()->get(Router::class)->generateUrl('setting.general'),
                "data" => $data,
                "dashboardUrl" => generate_url("dashboard"),
                "cancelUrl" => generate_url("setting.general")
            ]
        );

        return $delegate;
    }
}