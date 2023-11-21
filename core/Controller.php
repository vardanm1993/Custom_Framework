<?php

namespace Core;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionException;

abstract class Controller
{
    /**
     * @param string $view
     * @param array $data
     * @param int $code
     * @return mixed
     * @throws Exception\ContainerException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function view(string $view, array $data = [], int $code = 200): mixed
    {
        $view = App::$container->get(View::class)->renderView($view,$data);

        $response = App::$container->get(Response::class);
        $response->setStatusCode($code);
        $response->setHeader('Content-Type','text/html');

        return $response->send($view);
    }
}