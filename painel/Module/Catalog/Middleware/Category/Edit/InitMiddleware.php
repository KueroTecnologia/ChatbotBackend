<?php

declare(strict_types=1);

namespace KED\Module\Catalog\Middleware\Category\Edit;

use KED\Services\Db\Processor;
use KED\Services\Helmet;
use KED\Services\Http\Response;
use KED\Services\Http\Request;
use KED\Middleware\MiddlewareAbstract;

class InitMiddleware extends MiddlewareAbstract
{
    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $id = (int) $request->attributes->get('id');
        if ($id) {
            if ($this->getContainer()->get(Processor::class)->getTable('category')->load($id) === false) {
                $response->addData('success', 0);
                $response->addData('message', 'Requested category does not exist');

                return $response;
            }
            $this->getContainer()->get(Helmet::class)->setTitle("Edit category");
        } else {
            $this->getContainer()->get(Helmet::class)->setTitle("Create new category");
        }

        return $delegate;
    }
}