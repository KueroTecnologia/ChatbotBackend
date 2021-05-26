<?php


declare(strict_types=1);

namespace KED\Middleware;

use function KED\generate_url;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Services\Routing\Router;

class RoutingMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $code = $this->getContainer()->get(Router::class)->dispatch();
        if ($code == 404) {
            $response->setStatusCode(404);
        } elseif ($code == 405) {
            $response->setContent('Method Not Allowed')->setStatusCode(405);
        } else {
            $response->addState("currentRoute", $request->attributes->get("_matched_route"));
            $response->addState("currentRouteArgs", $request->attributes->get("_route_args"));
            $response->addState(
                "currentUrl",
                generate_url($request->attributes->get("_matched_route"), $request->attributes->get("_route_args"))
            );
        }

        return $delegate;
    }
}