<?php


declare(strict_types=1);

namespace KED\Middleware;

use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Services\PromiseWaiter;

class PromiseWaiterMiddleware extends MiddlewareAbstract
{
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $this->getContainer()->get(PromiseWaiter::class)->wait();

        return $delegate;
    }
}