<?php

declare(strict_types=1);

namespace KED\Module\Order\Middleware\Dashboard;


use function KED\get_js_file_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Helmet;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class AddRechartsMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($request->isAdmin() == true)
            $this->getContainer()->get(Helmet::class)->addScript(['src'=>get_js_file_url('production/recharts.min.js'), 'type'=>'text/javascript', 'defer'=> "true"], 6);

        return $delegate;
    }
}