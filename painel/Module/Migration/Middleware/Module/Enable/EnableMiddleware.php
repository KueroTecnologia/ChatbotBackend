<?php


declare(strict_types=1);

namespace KED\Module\Migration\Middleware\Module\Enable;


use function KED\_mysql;
use function KED\generate_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class EnableMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $conn = _mysql();
        $conn->startTransaction();
        try {
            $module = $request->attributes->get("module");
            $conn->getTable("migration")->where("module", "=", $module)->update(["status" => 1]);
            $response->addAlert("module_enabled_success", "success", sprintf("Module %s is enabled", $module))
                ->redirect(generate_url("extensions.grid"));

            $conn->commit();
            return $response;
        } catch (\Exception $e) {
            $conn->rollback();
            $response->addAlert("module_enabled_error", "error", $e->getMessage())->notNewPage();
            return $response;
        }
    }
}