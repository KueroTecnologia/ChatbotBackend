<?php


declare(strict_types=1);

namespace KED\Module\User\Middleware;


use function KED\_mysql;
use function KED\generate_url;
use function KED\get_config;
use function KED\get_js_file_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class GreetingMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if (
            $response->hasWidget('admin_user_greeting') ||
            !$request->isAdmin() ||
            $request->attributes->get('_matched_route') == 'admin.login' ||
            $request->attributes->get('_matched_route') == 'admin.authenticate'
        )
            return $delegate;

        $user = _mysql()->getTable('admin_user')->load($request->getSession()->get('user_id'));
        $response->addWidget(
            'admin_user_greeting',
            'header',
            20,
            get_js_file_url("production/user/greeting.js", true),
            [
                "fullName" => $user['full_name'],
                "logoutUrl" => generate_url('admin.logout'),
                "time" => date("F j, Y, g:i a"),
            ]
        );

        return $delegate;
    }
}