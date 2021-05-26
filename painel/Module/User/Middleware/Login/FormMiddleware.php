<?php


declare(strict_types=1);

namespace KED\Module\User\Middleware\Login;

use function KED\get_js_file_url;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Routing\Router;

class FormMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $response->addWidget(
            'admin_login_form',
            'content',
            10,
            get_js_file_url("production/user/login/admin_login_form.js", true),
            [
                "id"=> "admin_login_form",
                "action"=> $this->getContainer()->get(Router::class)->generateUrl("admin.authenticate"),
                "logoUrl" => \KED\get_base_url_scheme_less() . '/public/theme/admin/default/image/logo.png',
            ]
        );
        
        return $delegate;
    }
}