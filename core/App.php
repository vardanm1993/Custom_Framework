<?php

namespace Core;

use Core\Exception\NotFoundException;
use Core\Route\Route;
use Core\Route\RouteDispatcher;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class App
{
    public static Container $container;

    public function __construct()
    {
        self::$container = new Container();
    }


    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws NotFoundException
     */
    public function run()
    {
        $request = self::$container->get(Request::class);

        foreach (Route::getRoutes()[$request->getMethod()] as $routeConfig) {
            $routeDispatcher = new RouteDispatcher($routeConfig, $request);
            $routeDispatcher->run();
        }
    }
}