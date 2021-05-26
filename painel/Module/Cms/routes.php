<?php

declare(strict_types=1);

/** @var \KED\Services\Routing\Router $router */
$router->addAdminRoute('dashboard', 'GET', '/', [
    \KED\Module\Cms\Middleware\Dashboard\TitleMiddleware::class,
    \KED\Module\Cms\Middleware\Dashboard\LayoutMiddleware::class
]);

$router->addAdminRoute('page.grid', 'GET', '/pages', [
    \KED\Module\Cms\Middleware\Page\Grid\AddNewButtonMiddleware::class,
    \KED\Module\Cms\Middleware\Page\Grid\GridMiddleware::class
]);

$router->addAdminRoute('page.create', 'GET', '/page/create', [
    \KED\Module\Cms\Middleware\Page\Edit\InitMiddleware::class,
    \KED\Module\Cms\Middleware\Page\Edit\FormMiddleware::class
]);

$router->addAdminRoute('page.edit', 'GET', '/page/edit/{id:\d+}', [
    \KED\Module\Cms\Middleware\Page\Edit\InitMiddleware::class,
    \KED\Module\Cms\Middleware\Page\Edit\FormMiddleware::class
]);

$router->addAdminRoute('page.delete', 'GET', '/page/delete/{id:\d+}', [
    \KED\Module\Cms\Middleware\Page\Delete\DeleteMiddleware::class,
]);

$router->addAdminRoute('page.save', 'POST', '/page/save[/{id:\d+}]', [
    \KED\Module\Cms\Middleware\Page\Edit\InitMiddleware::class,
    \KED\Module\Cms\Middleware\Page\Edit\FormMiddleware::class
]);

$router->addAdminRoute('widget.grid', 'GET', '/widgets', [
    \KED\Module\Cms\Middleware\Widget\Grid\GridMiddleware::class,
    \KED\Module\Cms\Middleware\Widget\Grid\AddNewButtonMiddleware::class
]);

$router->addAdminRoute('widget.create', 'GET', '/widget/create', [
    \KED\Module\Cms\Middleware\Widget\Edit\EditMiddleware::class,
    \KED\Module\Cms\Middleware\Widget\Edit\GetLayoutsMiddleware::class
]);

$router->addAdminRoute('widget.edit', 'GET', '/widget/edit/{type}/{id:\d+}', [
    \KED\Module\Cms\Middleware\Widget\Edit\EditMiddleware::class,
    \KED\Module\Cms\Middleware\Widget\Edit\GetLayoutsMiddleware::class
]);

$router->addAdminRoute('widget.delete', 'GET', '/widget/edit/{id:\d+}', [
    \KED\Module\Cms\Middleware\Widget\Delete\DeleteMiddleware::class
]);

$router->addAdminRoute('cms.install', ["POST", "GET"], '/cms/migrate/install', [
    \KED\Module\Cms\Middleware\Migrate\Install\InstallMiddleware::class
]);

////////////////////////////////////////////
///            SITE ROUTERS           //////
////////////////////////////////////////////

$pageViewMiddleware = [
    \KED\Module\Cms\Middleware\Page\View\ViewMiddleware::class
];
$router->addSiteRoute('page.view', 'GET', '/page/id/{id:\d+}', $pageViewMiddleware);

// Pretty url
$router->addSiteRoute('page.view.pretty', 'GET', '/page/{slug}', $pageViewMiddleware);

$router->addSiteRoute('homepage', 'GET', '/', [
    \KED\Module\Cms\Middleware\Page\View\HomepageMiddleware::class
]);