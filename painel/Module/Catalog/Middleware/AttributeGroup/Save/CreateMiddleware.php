<?php


declare(strict_types=1);

namespace KED\Module\Catalog\Middleware\AttributeGroup\Save;

use function KED\_mysql;
use KED\Services\Db\Processor;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Routing\Router;

class CreateMiddleware extends MiddlewareAbstract
{
    /**@var Processor $conn*/
    protected $conn;
    /**
     * @param Request $request
     * @param Response $response
     * @param array $data
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($request->attributes->get('id', null) != null)
            return $delegate;

        $this->conn = _mysql();
        try {
            $conn = _mysql();
            $conn->getTable('attribute_group')
                ->insert($request->request->all());
            $id = $conn->getLastID();

            if ($attributes = $request->request->get('attributes'))
                foreach ($attributes as $attribute) {
                    if ($conn->getTable('attribute')->load($attribute))
                        $conn->getTable('attribute_group_link')->insert(['attribute_id'=>$attribute, 'group_id'=>$id]);
                }

            $response->addAlert('attribute_group_save_success', 'success', 'Attribute group saved')
                ->redirect($this->getContainer()->get(Router::class)->generateUrl('attribute.group.grid'));

            return $response;
        } catch(\Exception $e) {
            $response->addAlert('attribute_group_save_error', 'error', $e->getMessage());

            return $response;
        }
    }
}