<?php


declare(strict_types=1);

namespace KED\Middleware;

use KED\Services\Di\Container;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Services\MiddlewareManager;

abstract class MiddlewareAbstract
{
    /**@var Container $container */
    private $container;

    public function setContainer(Container $container)
    {
        $this->container = $container;
    }

    abstract public function __invoke(Request $request, Response $response);

    /**
     * @return Container
     */
    public function getContainer(): Container
    {
        return $this->container;
    }


    public function getDelegate($className, $defaultValue = null)
    {
        return MiddlewareManager::getDelegate($className, $defaultValue);
    }

    public function hasDelegate($className)
    {
        return MiddlewareManager::hasDelegate($className);
    }
}