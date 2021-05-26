<?php


declare(strict_types=1);

namespace KED\Module\Migration\Middleware\Module\Install;


use function KED\_mysql;
use function KED\generate_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class ValidateMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        try {
            if (!file_exists(CONFIG_PATH . DS . 'config.php') and !file_exists(CONFIG_PATH . DS . 'config.tmp.php'))
                throw new \Exception("You need to install the app first");

            $module = $request->attributes->get("module");
            if (!preg_match('/^[A-Za-z0-9_]+$/', $module))
                throw new \Exception("Invalid module name");
            if (!file_exists(COMMUNITY_MODULE_PATH . DS . $module) && !file_exists(MODULE_PATH . DS . $module))
                throw new \Exception("Module folder could not be found");
            $conn = _mysql();
            if ($conn->getTable("migration")->where("module", "LIKE", $module)->fetchOneAssoc())
                throw new \Exception(sprintf("Module %s is already installed", $module));

            return $delegate;
        } catch (\Exception $e) {
            $response->redirect(generate_url('extensions.grid'));
            $response->addAlert("module_install_error", "error", $e->getMessage())->notNewPage();
            return $response;
        }
    }
}