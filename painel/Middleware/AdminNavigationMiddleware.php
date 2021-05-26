<?php


declare(strict_types=1);

namespace KED\Middleware;


use function KED\create_mutable_var;
use function KED\generate_url;
use function KED\get_config;
use function KED\get_js_file_url;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class AdminNavigationMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if (!$request->isAdmin() || $request->attributes->get('_matched_route') == 'admin.login') {
            return $delegate;
        }

        $response->addWidget(
            'admin_navigation',
            'menu',
            0,
            get_js_file_url("production/cms/dashboard/navigation.js", true),
            [
                "adminUrl" => generate_url('dashboard'),
                "logoUrl" => \KED\get_base_url_scheme_less() . '/public/theme/admin/default/image/',
                "storeName" => get_config('general_store_name', 'KED store admin'),
                "items"=> create_mutable_var("admin_menu", [
                    [
                        "id" => "quick_links",
                        "sort_order" => 0,
                        "url" => null,
                        "title" => "Quick links",
                        "parent_id" => null
                    ],
                    [
                        "id" => "dashboard",
                        "sort_order" => 5,
                        "url" => generate_url("dashboard"),
                        "title" => "Dashboard",
                        "icon" => "home",
                        "parent_id" => "quick_links"
                    ]
                ])
            ]
        );

        return $delegate;
    }
}