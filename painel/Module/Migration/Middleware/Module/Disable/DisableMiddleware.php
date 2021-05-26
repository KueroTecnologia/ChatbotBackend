<?php


declare(strict_types=1);

namespace KED\Module\Migration\Middleware\Module\Disable;


use function KED\_mysql;
use function KED\generate_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Db\Processor;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class DisableMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $conn = _mysql();
        $conn->startTransaction();
        try {
            $module = $request->attributes->get("module");
            $conn->getTable("migration")->where("module", "=", $module)->update(["status" => 0]);
            $response->addAlert("module_disable_success", "success", sprintf("Module %s is disabled", $module))
                ->redirect(generate_url("extensions.grid"));

            $conn->commit();
            return $response;
        } catch (\Exception $e) {
            $conn->rollback();
            $response->addAlert("module_disable_error", "error", $e->getMessage())->notNewPage();
            return $response;
        }
    }
}