<?php

require __DIR__.'/../vendor/autoload.php';

session_start();
$settings = require __DIR__.'/../config.php';
$app = new \Slim\App($settings);

//require __DIR__.'/../src/init.php';
require __DIR__.'/../src/dependencies.php';
require __DIR__.'/../src/routes.php';

$app->run();
