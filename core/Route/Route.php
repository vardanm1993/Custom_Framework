<?php

namespace Core\Route;

class Route
{
    private static array $routes = [];

    /**
     * @return array
     */
    public static function getRoutes(): array
    {
        return self::$routes;
    }

    /**
     * @param string $method
     * @param string $route
     * @param array|\Closure|string $callback
     * @return RouteConfig
     */
    public static function add(string $method, string $route, array|\Closure|string $callback): RouteConfig
    {
        $routeConfig = new RouteConfig($route, $callback);
        self::$routes[$method][RouteDispatcher::clean($route)] = $routeConfig;

        return $routeConfig;
    }

    /**
     * @param string $route
     * @param array|\Closure|string $callback
     * @return RouteConfig
     */
    public static function get(string $route, array|\Closure|string $callback): RouteConfig
    {
        return self::add('get', $route, $callback);
    }

    /**
     * @param string $route
     * @param array|\Closure|string $callback
     * @return RouteConfig
     */
    public static function post(string $route, array|\Closure|string $callback): RouteConfig
    {
        return self::add('post', $route, $callback);
    }

    /**
     * @param string $route
     * @param array|\Closure|string $callback
     * @return RouteConfig
     */
    public static function put(string $route, array|\Closure|string $callback): RouteConfig
    {
        return self::add('put', $route, $callback);
    }

    /**
     * @param string $route
     * @param array|\Closure|string $callback
     * @return RouteConfig
     */
    public static function patch(string $route, array|\Closure|string $callback): RouteConfig
    {
        return self::add('patch', $route, $callback);
    }

    /**
     * @param string $route
     * @param array|\Closure|string $callback
     * @return RouteConfig
     */
    public static function delete(string $route, array|\Closure|string $callback): RouteConfig
    {
        return self::add('delete', $route, $callback);
    }

}