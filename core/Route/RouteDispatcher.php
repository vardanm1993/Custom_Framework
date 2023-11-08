<?php

namespace Core\Route;

use Core\Exception\NotFoundException;
use Core\Request;

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
     * @throws NotFoundException
     */
    public function run()
    {
        $this->saveRequestUri();
        $this->setParams();
        $this->makeRegexpRequest();
        $this->process();
    }


    private function saveRequestUri()
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


    private function setParams()
    {
        $routeArray = explode('/', $this->routeConfig->route);

        foreach ($routeArray as $routeKey => $routeValue) {

            if (preg_match('/{.*}/', $routeValue)) {
                $this->routeParams[$routeKey] = preg_replace('/(^{)|(}$)/', '', $routeValue);
            }
        }
    }

    private function makeRegexpRequest()
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

    private function prepareRegexp()
    {
        $this->requestUri = str_replace('/', '\/', $this->requestUri);

    }

    /**
     * @throws NotFoundException
     */
    private function process()
    {
        if (preg_match("/$this->requestUri/", $this->routeConfig->route)) {
            $this->render();
        }

        throw new \Exception('404');
    }

    /**
     * @throws NotFoundException
     */
    private function render()
    {
        $method = $this->request->getMethod();
        $callback = Route::getRoutes()[$method][$this->routeConfig->route]->callback ?? false;

        if (!$callback) {
            throw new NotFoundException('404');
        }

        if (is_string($callback)) {
            return $callback;
        }

        if (is_array($callback)) {
            $callback[0] = new $callback[0];
        }

        echo call_user_func($callback, ...$this->requestParams);
        die();
    }


}