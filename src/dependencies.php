<?php

use \Slim\Views\Twig;
use \Slim\Views\TwigExtension;
use \Slim\Flash\Messages;
use \Symfony\Component\Translation\Translator;
use \Symfony\Component\Translation\MessageSelector;
use \Symfony\Component\Translation\Loader\YamlFileLoader;
use \Symfony\Bridge\Twig\Extension\TranslationExtension;

$container = $app->getContainer();

$container['view'] = function ($container) {
    $view = new Twig(__DIR__.'/../view', [
        //'cache' => '../cache' // à réactiver
    ]);

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

    // add loader (parse directory)
    $translator->addResource('yaml', __DIR__.'/../locale/core.fr.yml', 'fr');
    $translator->addResource('yaml', __DIR__.'/../locale/core.en.yml', 'en');

    return $translator;
};

$container['\App\Controller\HomeController'] = function ($container) {
    $view = $container->get('view');
    $flash = $container->get('flash');
    $translator = $container->get('translate');

    return new \App\Controller\HomeController($view, $flash, $translator);
};

$container['\App\Controller\AdminController'] = function ($container) {
    $view = $container->get('view');
    $flash = $container->get('flash');
    $translator = $container->get('translate');

    return new \App\Controller\AdminController($view, $flash, $translator);
};

$container['\App\Controller\InstallController'] = function ($container) {
    $view = $container->get('view');

    return new \App\Controller\InstallController($view);
};

$container['\App\Controller\DownloadController'] = function () {
    return new \App\Controller\InstallController();
};
