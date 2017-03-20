<?php
// DIC configuration

$container = $app->getContainer();

// monolog
/*
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new \Monolog\Logger($settings['name']);
    $logger->pushProcessor(new \Monolog\Processor\UidProcessor());
    $logger->pushHandler(new \Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};
*/

// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('../themes/default', [
        //'cache' => '../cache'
    ]);
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new \Slim\Views\TwigExtension($container['router'], $basePath));
    return $view;
};

// Register provider
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};
