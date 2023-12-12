<?php

namespace config;

use Core\App;
use Core\Exception\ContainerException;
use Core\Exception\NotFoundException;
use Core\Request;
use Core\Response;
use Core\Route\Route;
use Core\Route\RouteConfig;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class RouteDispatcher
{
    private string $requestUri = '/';

    private array $routeParams = [];

    private array $requestParams = [];

    /**
     * @param RouteConfig $routeConfig
     * @param Request $request
     */
    public function __construct(protected RouteConfig $routeConfig, protected Request $request)
    {
        
    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws NotFoundException
     */
    public function run(): void
    {
        $this->saveRequestUri();
        $this->setParams();
        $this->makeRegexpRequest();
        $this->process();

    }


    private function saveRequestUri(): void
    {
        if ($this->request->getUri() !== '/') {
            $this->requestUri = self::clean($this->request->getUri());
            $this->routeConfig->route = self::clean($this->routeConfig->route);
        }
    }

    /**
     * @param string $str
     * @return array|string|null
     */
    public static function clean(string $str): array|string|null
    {
        return preg_replace('/(^\/)|(\/$)/', '', $str);
    }


    /**
     * @return void
     */
    private function setParams(): void
    {
        $routeArray = explode('/', $this->routeConfig->route);

        foreach ($routeArray as $routeKey => $routeValue) {

            if (preg_match('/{.*}/', $routeValue)) {
                $this->routeParams[$routeKey] = preg_replace('/(^{)|(}$)/', '', $routeValue);
            }
        }
    }

    /**
     * @return void
     */
    private function makeRegexpRequest(): void
    {
        $requestUriArray = explode('/', $this->requestUri);


        if (!empty($this->routeParams)) {

            foreach ($this->routeParams as $paramKey => $paramValue) {
                if (!isset($requestUriArray[$paramKey])) {
                    return;
                }

                $this->requestParams[$paramValue] = $requestUriArray[$paramKey];
                $requestUriArray[$paramKey] = '{.*}';
            }
        }


        $this->requestUri = implode('/', $requestUriArray);
        $this->prepareRegexp();

    }

    /**
     * @return void
     */
    private function prepareRegexp(): void
    {
        $this->requestUri = str_replace('/', '\/', $this->requestUri);

    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     */
    private function process(): void
    {

        $route = "/" . $this->routeConfig->route . "/";
        $request = "/" . $this->requestUri . "/";

        if (count($this->requestParams) > 0) {
            if (preg_match($request, $route)) {
                $this->requestUri = $route;
            }
        }

        $this->render();

    }

    /**
     * @return void
     * @throws ContainerExceptionInterface
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     */
    private function render(): void
    {
        $method = $this->request->getMethod();
        $routes = Route::getRoutes();

        if (isset($routes[$method])) {
            $cleanedUri = self::clean($this->requestUri);

            if (isset($routes[$method][$cleanedUri])) {
                $callback = $routes[$method][$cleanedUri]->callback ?? false;

                if ($callback) {
                    if (is_array($callback)) {
                        try {
                            $callback[0] = App::$container->get($callback[0]);
                        } catch (ContainerException|\ReflectionException $e) {
                        }
                    }


                    $response = call_user_func($callback, ...$this->requestParams);
                    // Check if the response is an instance of Response
                    if ($response instanceof Response) {
                        // Send the response
                        $response->send();
                    } else {
                        // Handle the response if it's not an instance of Response
                        echo "Unexpected response type";
                    }
                    die();
                }
            }
        }

        throw new NotFoundException('404');
    }
}