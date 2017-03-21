<?php

use \Slim\Views\Twig;
use \Slim\Views\TwigExtension;
use \Slim\Flash\Messages;
use \Symfony\Component\Translation\Translator;
use \Symfony\Component\Translation\MessageSelector;
use \Symfony\Component\Translation\Loader\YamlFileLoader;
use \Symfony\Bridge\Twig\Extension\TranslationExtension;

$translator = new Translator($lang, new MessageSelector());
$translator->addLoader('yaml', new YamlFileLoader());
$translator->addResource('yaml', __DIR__.'/../locale/core.fr.yml', 'fr');
$translator->addResource('yaml', __DIR__.'/../locale/core.en.yml', 'en');

$container = $app->getContainer();

$container['view'] = function ($container) use ($translator) {
    $view = new Twig(__DIR__.'/../view', [
        //'cache' => '../cache' // à réactiver
    ]);
    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new TwigExtension($container['router'], $basePath));
    $view->addExtension(new TranslationExtension($translator));

    return $view;
};

$container['flash'] = function () {
    return new Messages();
};
