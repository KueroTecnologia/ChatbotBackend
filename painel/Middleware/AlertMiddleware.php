<?php


declare(strict_types=1);

namespace KED\Middleware;

use function KED\get_js_file_url;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class AlertMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $alerts = $request->getSession()->getFlashBag()->all();
        foreach ($alerts as $type => $message) {
            $response->addAlert('session_alert', $type, $message);
        }
        $response->addWidget(
            'alert',
            'content',
            5,
            get_js_file_url("production/alert.js", $request->isAdmin()),
            [
                "alerts"=>[]
            ]
        );

        return $delegate;
    }
}