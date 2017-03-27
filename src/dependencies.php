<?php

use \Slim\Flash\Messages;
use \Slim\Views\Twig;
use \Slim\Views\TwigExtension;
use \Symfony\Bridge\Twig\Extension\TranslationExtension;
use \Symfony\Component\Translation\Loader\YamlFileLoader;
use \Symfony\Component\Translation\MessageSelector;
use \Symfony\Component\Translation\Translator;

$container = $app->getContainer();

$container['view'] = function ($container) {
    $view = new Twig(__DIR__.'/../view', ['cache' => __DIR__.'/../cache']);
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $translator = $container->get('translate');
    $view->addExtension(new TwigExtension($container['router'], $basePath));
    $view->addExtension(new TranslationExtension($translator));

    return $view;
};

$container['flash'] = function () {
    return new Messages();
};

$container['translate'] = function () {
    $translator = new Translator('fr', new MessageSelector());
    $translator->addLoader('yaml', new YamlFileLoader());
    $files = glob(__DIR__.'/../locale/*.yml');

    foreach ($files as $file) {
        $info = pathinfo($file);
        $lang = explode('.', $info['filename']);
        $lang = $lang[1];

        $translator->addResource('yaml', $file, $lang);
    }

    return $translator;
};

$container['\App\Controller\HomeController'] = function ($container) {
    $view = $container->get('view');
    $flash = $container->get('flash');
    $translator = $container->get('translate');
    $router = $container->get('router');

    return new \App\Controller\HomeController($view, $flash, $translator, $router);
};

$container['\App\Controller\AdminController'] = function ($container) {
    $view = $container->get('view');
    $flash = $container->get('flash');
    $translator = $container->get('translate');
    $router = $container->get('router');

    return new \App\Controller\AdminController($view, $flash, $translator, $router);
};

$container['\App\Controller\InstallController'] = function ($container) {
    $view = $container->get('view');
    $router = $container->get('router');

    return new \App\Controller\InstallController($view, $router);
};

$container['\App\Controller\DownloadController'] = function () {
    return new \App\Controller\DownloadController();
};
