<?php


declare(strict_types=1);

namespace KED\Module\Setting\Middleware\General;

use function KED\_mysql;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Routing\Router;

class SaveMiddleware extends MiddlewareAbstract
{
    /**
     * @param Request $request
     * @param Response $response
     * @param null $delegate
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        if ($request->getMethod() == 'GET')
            return $delegate;
        $processor = _mysql();
        $processor->startTransaction();
        try {
            $data = $request->request->all();
            foreach ($data as $name=> $value) {
                if (is_array($value))
                    $processor->getTable('setting')
                        ->insertOnUpdate([
                            'name'=>$name,
                            'value'=>json_encode($value, JSON_NUMERIC_CHECK),
                            'json'=>1
                        ]);
                else
                    $processor->getTable('setting')
                        ->insertOnUpdate([
                            'name'=>$name,
                            'value'=>$value,
                            'json'=>0
                        ]);
            }

            $processor->commit();
            $response->addAlert('general_setting_update_success', 'success', "Setting saved successfully");
            $response->redirect($this->getContainer()->get(Router::class)->generateUrl('setting.general'));
        } catch (\Exception $e) {
            $processor->rollback();
            $response->addAlert('general_setting_update_error', 'error', $e->getMessage());
        }

        return $response;
    }
}