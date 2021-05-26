<?php

declare(strict_types=1);

namespace KED\Module\Setting\Middleware\Catalog;

use function KED\_mysql;
use function KED\create_mutable_var;
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

        $this->getContainer()->get(Helmet::class)->setTitle('Catalog setting');
        $stm = _mysql()
            ->executeQuery("SELECT * FROM `setting` WHERE `name` LIKE 'catalog_%'");

        $data = [];
        while ($row = $stm->fetch()) {
            if ($row['json'] == 1)
                $data[$row['name']] = json_decode($row['value'], true);
            else
                $data[$row['name']] = $row['value'];
        }

        $response->addWidget(
            'catalog_setting_form',
            'content',
            10,
            get_js_file_url("production/setting/catalog/form.js", true),
            [
                "action" => $this->getContainer()->get(Router::class)->generateUrl('setting.catalog'),
                "data" => $data,
                "sorting_options" => create_mutable_var("sorting_options", []),
                "dashboardUrl" => generate_url("dashboard"),
                "cancelUrl" => generate_url("setting.catalog")
            ]
        );

        return $delegate;
    }
}