<?php

namespace Core;

use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private array $bindings = [];

    private array $resolved = [];

    public function get(string $id)
    {
        if (!$this->has($id)) {

        }

        return $this->bindings[$id];
    }

    public function has(string $id): bool
    {
        return $this->bindings[$id];
    }

    public function bind(string $id, callable $concrete)
    {
        $this->bindings[$id] = $concrete($this);
    }
}