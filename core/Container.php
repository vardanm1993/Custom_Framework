<?php

namespace Core;

use Core\Exception\ContainerException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use ReflectionClass;
use ReflectionException;

class Container implements ContainerInterface
{
    private array $bindings = [];

    /**
     * @param string $id
     * @return mixed
     * @throws ContainerException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    public function get(string $id): mixed
    {
        if ($this->has($id) === false) {
            return $this->resolve($id);
        }

        return Session::get('resolved')[$id];
    }

    public function has(string $id): bool
    {
        return isset(Session::get('resolved')[$id]);
    }

    public function bind(string $id, callable $concrete): void
    {
        $this->bindings[$id] = $concrete($this);
        Session::set('resolved', $this->bindings);
    }

    /**
     * @param string $id
     * @return mixed
     * @throws ContainerException
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     * @throws ReflectionException
     */
    private function resolve(string $id): mixed
    {
        $reflectionClass = new ReflectionClass($id);
        if (!$reflectionClass->isInstantiable()) {
            throw new ContainerException("Class " . $id . " is not instantiable");
        }

        $constructor = $reflectionClass->getConstructor();

        if (!$constructor || !$constructor->getParameters()) {

            $this->bindings[$id] = new $id;
            Session::set('resolved', $this->bindings);

            return $_SESSION['resolved'][$id];
        }

        $parameters = $constructor->getParameters();

        $dependencies = array_map(function (\ReflectionParameter $param) use ($id) {
            $name = $param->getName();
            $type = $param->getType();

            if (!$type) {
                throw new ContainerException(
                    'Failed to resolve class ' . $id . ' because param ' . $name . ' is missing a type hint'
                );
            }

            if ($type instanceof \ReflectionUnionType) {
                throw new ContainerException(
                    'Failed to resolve class ' . $id . ' because of union type for param ' . $name
                );
            }

            if ($type instanceof \ReflectionNamedType && !$type->isBuiltin()) {
                return $this->get($type->getName());
            }

            throw new ContainerException(
                'Failed to resolve class ' . $id . ' because invalid param ' . $name
            );


        }, $parameters);

        $this->bindings[$id] = $reflectionClass->newInstanceArgs($dependencies);
        Session::set('resolved', $this->bindings);

        return $reflectionClass->newInstanceArgs($dependencies);
    }


}