<?php

use Core\App;

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/routes/web.php';

$app = new App();

require dirname(__DIR__) . '/config/app.php';

$app->run();