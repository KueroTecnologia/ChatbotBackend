<?php


declare(strict_types=1);

namespace KED\Module\Customer\Middleware\Dashboard;

use function KED\get_js_file_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Helmet;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Services\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LayoutMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if (!$request->getCustomer()->isLoggedIn()) {
            $redirect = new RedirectResponse($this->getContainer()->get(Router::class)->generateUrl('customer.login'));
            return $redirect->send();
        }

        $this->getContainer()->get(Helmet::class)->setTitle("Account dashboard");
        $response->addWidget(
            'customer_dashboard_layout',
            'content_center',
            10,
            get_js_file_url("production/area.js", false),
            [
                'className'=> "row",
                'id'=> "customer_dashboard_layout"
            ]
        );

        return $delegate;
    }
}