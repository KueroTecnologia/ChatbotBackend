<?php


declare(strict_types=1);

/** @var \KED\Services\Routing\Router $router */
$router->addAdminRoute('product.grid', 'GET', '/products', [
    \KED\Module\Catalog\Middleware\Product\Grid\GridMiddleware::class,
    \KED\Module\Catalog\Middleware\Product\Grid\AddNewButtonMiddleware::class,
    \KED\Module\Catalog\Middleware\Product\Grid\ActionColumn::class,
]);

$productEditMiddleware = [
    \KED\Module\Catalog\Middleware\Product\Edit\InitMiddleware::class,
    \KED\Module\Catalog\Middleware\Product\Edit\FormMiddleware::class,
    \KED\Module\Catalog\Middleware\Product\Edit\GeneralInfoMiddleware::class,
    \KED\Module\Catalog\Middleware\Product\Edit\InventoryMiddleware::class,
    \KED\Module\Catalog\Middleware\Product\Edit\ImagesMiddleware::class,
    \KED\Module\Catalog\Middleware\Product\Edit\SeoMiddleware::class,
    \KED\Module\Catalog\Middleware\Product\Edit\VariantMiddleware::class,
    \KED\Module\Catalog\Middleware\Product\Edit\PriceMiddleware::class,
    \KED\Module\Catalog\Middleware\Product\Edit\CategoryMiddleware::class,
    \KED\Module\Catalog\Middleware\Product\Edit\AttributeMiddleware::class,
    \KED\Module\Catalog\Middleware\Product\Edit\CustomOptionMiddleware::class,
];
$router->addAdminRoute('product.create', 'GET', '/product/create', $productEditMiddleware);

$router->addAdminRoute('product.edit', 'GET', '/product/edit/{id:\d+}', $productEditMiddleware);

$router->addAdminRoute('product.save', 'POST', '/product/save[/{id:\d+}]', [
    \KED\Module\Catalog\Middleware\Product\Save\ValidateMiddleware::class,
    \KED\Module\Catalog\Middleware\Product\Save\UpdateMiddleware::class,
    \KED\Module\Catalog\Middleware\Product\Save\CreateMiddleware::class,
    \KED\Module\Catalog\Middleware\Product\Save\SaveVariantMiddleware::class
]);

$router->addAdminRoute('product.delete', ['GET', 'POST'], '/product/delete/{id:\d+}', [
    KED\Module\Catalog\Middleware\Product\Delete\DeleteMiddleware::class
]);

//////////////// CATEGORY ////////////////////

$router->addAdminRoute('category.grid', 'GET', '/categories', [
    \KED\Module\Catalog\Middleware\Category\Grid\GridMiddleware::class,
    \KED\Module\Catalog\Middleware\Category\Grid\AddNewButtonMiddleware::class,
]);

$categoryEditMiddleware = [
    \KED\Module\Catalog\Middleware\Category\Edit\InitMiddleware::class,
    \KED\Module\Catalog\Middleware\Category\Edit\FormMiddleware::class,
    \KED\Module\Catalog\Middleware\Category\Edit\GeneralInfoMiddleware::class,
    \KED\Module\Catalog\Middleware\Category\Edit\SeoMiddleware::class,
    \KED\Module\Catalog\Middleware\Category\Edit\ProductsMiddleware::class
];
$router->addAdminRoute('category.create', 'GET', '/category/create', $categoryEditMiddleware);

$router->addAdminRoute('category.edit', 'GET', '/category/edit/{id:\d+}', $categoryEditMiddleware);

$router->addAdminRoute('category.delete', ["POST", "GET"], '/category/delete/{id:\d+}', [
    \KED\Module\Catalog\Middleware\Category\Delete\DeleteMiddleware::class
]);

$router->addAdminRoute('category.save', 'POST', '/category/save[/{id:\d+}]', [
    \KED\Module\Catalog\Middleware\Category\Save\ValidateMiddleware::class,
    \KED\Module\Catalog\Middleware\Category\Save\UpdateMiddleware::class,
    \KED\Module\Catalog\Middleware\Category\Save\CreateMiddleware::class
]);

//////////////// ATTRIBUTE ////////////////////

$router->addAdminRoute('attribute.grid', 'GET', '/attributes', [
    \KED\Module\Catalog\Middleware\Attribute\Grid\GridMiddleware::class,
    \KED\Module\Catalog\Middleware\Attribute\Grid\AddNewButtonMiddleware::class,
]);

$attributeEditMiddleware = [
    \KED\Module\Catalog\Middleware\Attribute\Edit\InitMiddleware::class,
    \KED\Module\Catalog\Middleware\Attribute\Edit\EditFormMiddleware::class
];
$router->addAdminRoute('attribute.create', 'GET', '/attribute/create', $attributeEditMiddleware);

$router->addAdminRoute('attribute.edit', 'GET', '/attribute/edit/{id:\d+}', $attributeEditMiddleware);

$router->addAdminRoute('attribute.delete', 'GET', '/attribute/delete/{id:\d+}', [
    \KED\Module\Catalog\Middleware\Attribute\Delete\DeleteMiddleware::class
]);

$router->addAdminRoute('attribute.save', 'POST', '/attribute/save[/{id:\d+}]', [
    \KED\Module\Catalog\Middleware\Attribute\Save\UpdateMiddleware::class,
    \KED\Module\Catalog\Middleware\Attribute\Save\CreateMiddleware::class
]);

//////////////// ATTRIBUTE GROUP ////////////////////

$router->addAdminRoute('attribute.group.grid', 'GET', '/attribute/groups', [
    \KED\Module\Catalog\Middleware\AttributeGroup\Grid\GridMiddleware::class,
    \KED\Module\Catalog\Middleware\AttributeGroup\Grid\AddNewButtonMiddleware::class,
]);

$attributeGroupEditMiddleware = [
    \KED\Module\Catalog\Middleware\AttributeGroup\Edit\InitMiddleware::class,
    \KED\Module\Catalog\Middleware\AttributeGroup\Edit\EditFormMiddleware::class
];
$router->addAdminRoute('attribute.group.create', 'GET', '/attribute/group/create', $attributeGroupEditMiddleware);

$router->addAdminRoute('attribute.group.edit', 'GET', '/attribute/group/edit/{id:\d+}', $attributeGroupEditMiddleware);

$router->addAdminRoute('attribute.group.delete', 'GET', '/attribute/group/delete/{id:\d+}', [
    KED\Module\Catalog\Middleware\AttributeGroup\Delete\DeleteMiddleware::class
]);

$router->addAdminRoute('attribute.group.save', 'POST', '/attribute/group/save[/{id:\d+}]', [
    \KED\Module\Catalog\Middleware\AttributeGroup\Save\UpdateMiddleware::class,
    \KED\Module\Catalog\Middleware\AttributeGroup\Save\CreateMiddleware::class
]);

////////////////////////////////////////////
////            SITE ROUTERS           /////
////////////////////////////////////////////


$categoryViewMiddleware = [
    \KED\Module\Catalog\Middleware\Category\View\InitMiddleware::class,
    \KED\Module\Catalog\Middleware\Category\View\GeneralInfoMiddleware::class,
    \KED\Module\Catalog\Middleware\Widget\ProductFilter\SaveQueryToSessionMiddleware::class,
    \KED\Module\Catalog\Middleware\Category\View\QueryValidateMiddleware::class,
    \KED\Module\Catalog\Middleware\Category\View\ProductsMiddleware::class
];
$router->addSiteRoute('category.view', 'GET', '/catalog/id/{id:\d+}', $categoryViewMiddleware);

// Pretty url
$router->addSiteRoute('category.view.pretty', 'GET', '/catalog/{slug}', $categoryViewMiddleware);

$productViewMiddleware = [
    \KED\Module\Catalog\Middleware\Product\View\InitMiddleware::class,
    \KED\Module\Catalog\Middleware\Product\View\VariantMiddleware::class,
    \KED\Module\Catalog\Middleware\Product\View\VariantDetectMiddleware::class,
    \KED\Module\Catalog\Middleware\Product\View\LayoutMiddleware::class,
    \KED\Module\Catalog\Middleware\Product\View\PriceMiddleware::class,
    \KED\Module\Catalog\Middleware\Product\View\GeneralInfoMiddleware::class,
    \KED\Module\Catalog\Middleware\Product\View\ImagesMiddleware::class,
    \KED\Module\Catalog\Middleware\Product\View\DescriptionMiddleware::class,
    \KED\Module\Catalog\Middleware\Product\View\AttributeMiddleware::class,
    \KED\Module\Catalog\Middleware\Product\View\FormMiddleware::class,
];

$router->addSiteRoute('product.view', 'GET', '/product/id/{id:\d+}', $productViewMiddleware);

// Pretty url
$router->addSiteRoute('product.view.pretty', 'GET', '/product/{slug}', $productViewMiddleware);