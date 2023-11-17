<?php

namespace Core;

use Core\Exception\NotFoundException;
use Core\Route\Route;
use Core\Route\RouteDispatcher;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

class App
{
    public static Container $container;

    public function __construct()
    {
        self::$container = new Container();
    }


    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws Exception\ContainerException
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function run(): void
    {
        $request = self::$container->get(Request::class);

        foreach (Route::getRoutes()[$request->getMethod()] as $routeConfig) {
            $routeDispatcher = new RouteDispatcher($routeConfig, $request);
            $routeDispatcher->run();
        }
    }
}