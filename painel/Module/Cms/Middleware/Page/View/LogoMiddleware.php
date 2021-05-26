<?php


declare(strict_types=1);

namespace KED\Module\Cms\Middleware\Page\View;

use function KED\generate_url;
use function KED\get_config;
use function KED\get_js_file_url;
use KED\Services\Http\Response;
use KED\Services\Http\Request;
use KED\Middleware\MiddlewareAbstract;

class LogoMiddleware extends MiddlewareAbstract
{

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($response->hasWidget('site_logo') || $request->isAdmin())
            return $delegate;

        $response->addWidget(
            'site_logo',
            'header_center',
            10,
            get_js_file_url("production/cms/page/logo.js", false),
            [
                "homeUrl" => generate_url('homepage'),
                "logoUrl" => get_config('general_logo') ? \KED\get_base_url_scheme_less() . '/public/media/' . get_config('general_logo') : null,
                "storeName" => get_config('general_store_name'),
                "logoWidth" => get_config('general_logo_width', 50),
                "logoHeight" => get_config('general_logo_height', 50)
            ]
        );

        return $delegate;
    }
}