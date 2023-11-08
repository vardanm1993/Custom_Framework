<?php

namespace Core;

class App
{
    public static Container $container;

    public function __construct()
    {
        static::$container = new Container();
    }

    public function run()
    {

    }
}