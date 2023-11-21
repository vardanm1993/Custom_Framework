<?php

use Core\App;
use Core\Request;
use Core\Response;
use Core\View;

App::$container->bind(Request::class,fn() => new Request($_SERVER,$_GET,$_POST,$_COOKIE,$_FILES));
App::$container->bind(Response::class,fn() => new Response());
App::$container->bind(View::class,fn() => new View());