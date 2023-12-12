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
    public function view(string $view, array $data = [], int $code = 200, array $headers = ['Content-Type' => 'text/html']): mixed
    {
        $view = App::$container->get(View::class)->renderView($view,$data);

        $response = App::$container->get(Response::class);
        $response->setContent($view);
        $response->setStatusCode($code);
        $response->setHeaders($headers);

        return $response->send($view);
    }
}