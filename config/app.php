<?php

use Core\App;
use Core\Request;

App::$container->bind(Request::class,fn() => new Request($_SERVER,$_GET,$_POST,$_COOKIE,$_FILES));