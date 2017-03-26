<?php

require __DIR__.'/vendor/autoload.php';

session_start();

$settings = require __DIR__.'/src/config.php';
$app = new \Slim\App($settings);

require __DIR__.'/src/dependencies.php';
require __DIR__.'/src/routes.php';

$app->run();
