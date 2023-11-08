<?php

namespace Core\Route;

class RouteConfig
{
    public string $route = '/';

    public array|\Closure|string $callback;

    public string $name = '';

    public string $middleware = '';

    /**
     * @param string $route
     * @param array|\Closure|string $callback
     */
    public function __construct(string $route, array|\Closure|string $callback)
    {
        $this->route = $route;
        $this->callback = $callback;
    }

    /**
     * @param string $name
     * @return RouteConfig
     */
    public function name(string $name): RouteConfig
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $middleware
     * @return RouteConfig
     */
    public function middleware(string $middleware): RouteConfig
    {
        $this->middleware = $middleware;

        return $this;
    }

}