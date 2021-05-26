<?php


declare(strict_types=1);

namespace KED\Module\Discount\Middleware\Edit;

use function KED\_mysql;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\MiddlewareAbstract;

class LoadCustomerGroupMiddleware extends MiddlewareAbstract
{
    /**
     * @param Request $request
     * @param Response $response
     * @param null $delegate
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $response->addState('customerGroups',
            _mysql()->getTable('customer_group')
                ->addFieldToSelect('customer_group_id', 'value')
                ->addFieldToSelect('group_name', 'text')
                ->where('customer_group_id', '<>', 1000)
                ->fetchAllAssoc());

        return $delegate;
    }
}
