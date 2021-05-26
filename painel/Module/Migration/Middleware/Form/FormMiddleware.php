<?php


declare(strict_types=1);

namespace KED\Module\Migration\Middleware\Form;


use function KED\generate_url;
use function KED\get_js_file_url;
use KED\Middleware\MiddlewareAbstract;
use KED\Services\Helmet;
use KED\Services\Http\Request;
use KED\Services\Http\Response;

class FormMiddleware extends MiddlewareAbstract
{

    public function __invoke(Request $request, Response $response)
    {
        if (file_exists(CONFIG_PATH . DS . 'config.php'))
            $response->redirect(generate_url('homepage'));

        $this->getContainer()->get(Helmet::class)->setTitle('KED installation');
        $response->addWidget(
            'installation_form',
            'content_center',
            0,
            get_js_file_url("production/migration/install/form/installation_form.js", true),
            [
                'action'=>generate_url('migration.install.post')
            ]
        );
    }
}