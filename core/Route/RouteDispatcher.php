<?php

namespace Core\Route;

use Core\App;
use Core\Exception\ContainerException;
use Core\Exception\NotFoundException;
use Core\Request;
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
     */
    public function run(): void
    {
        $this->saveRequestUri();
        $this->setParams();
        $this->makeRegexpRequest();
        try {
            $this->process();
        } catch (NotFoundException $e) {
        }
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
        if (preg_match("/$this->requestUri/", $this->routeConfig->route)) {
            $this->render();
        }

        throw new NotFoundException('404');
    }

    /**
     * @return never
     * @throws ContainerExceptionInterface
     * @throws NotFoundException
     * @throws NotFoundExceptionInterface
     */
    private function render(): never
    {
        $method = $this->request->getMethod();
        $callback = Route::getRoutes()[$method][$this->routeConfig->route]->callback ?? false;

        if (!$callback) {
            throw new NotFoundException('404');
        }

        if (is_array($callback)) {
            try {
                $callback[0] = App::$container->get($callback[0]);
            } catch (ContainerException|\ReflectionException $e) {
            }
        }

        echo call_user_func($callback, ...$this->requestParams);
        die();
    }


}