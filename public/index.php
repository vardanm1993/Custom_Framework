<?php

use Core\App;

$root = dirname(__DIR__);

require $root . '/vendor/autoload.php';
require $root . '/routes/web.php';

$app = new App($root);

require $root . '/config/app.php';

$app->run();