<?php

require __DIR__.'/../vendor/autoload.php';

session_start();
$settings = require __DIR__.'/../src/settings.php';
$app = new \Slim\App($settings);

require __DIR__.'/../src/dependencies.php';
require __DIR__.'/../src/middleware.php';
require __DIR__.'/../src/routes.php';

$app->run();

/*
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;

if (isset($_GET['download'])) {
    require '../app/downloads.php';
}

// init translation
$lang = 'fr';
$translator = new Translator($lang, new MessageSelector());
$translator->addLoader('yaml', new YamlFileLoader());
$translator->addResource('yaml', '../locale/core.fr.yml', 'fr');
*/
