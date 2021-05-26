<?php


declare(strict_types=1);

namespace KED\Module\Cms\Middleware\Page\View;

use function KED\get_config;
use KED\Services\Helmet;
use KED\Services\Http\Response;
use KED\Services\Http\Request;
use KED\Middleware\MiddlewareAbstract;

class HomepageMiddleware extends MiddlewareAbstract
{

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $this->getContainer()->get(Helmet::class)->setTitle(get_config('general_store_name', 'KED store'));

        return $delegate;
    }
}