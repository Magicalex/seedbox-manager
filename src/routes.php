<?php

use Seedbox\Users;
use Seedbox\Server;

// Routes

$container = $app->getContainer();

// Register component on container
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig('../themes/default', [
        'cache' => '../cache'
    ]);

    // Instantiate and add Slim specific extension
    $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
    $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

    return $view;
};

$app->get('/', function ($request, $response) use ($file_user_ini, $userName) {

    // init objet
    $user = new Users($file_user_ini, $userName);
    $serveur = new Server($file_user_ini, $userName);
    $read_data_reboot = $user->readFileDataReboot('../conf/users/'.$userName.'/data_reboot.txt');

    return $this->view->render($response, 'index.twig.php', [
        'userName' => $userName,
        'post' => $_POST,
        'get' => $_GET,
        'host' => $_SERVER['HTTP_HOST'],
        'ipUser' => $_SERVER['REMOTE_ADDR'],
        // init objet
        'user' => $user,
        'serveur' => $serveur,
        // var index
        'rebootRtorrent' => @$rebootRtorrent,
        'supportTicketSend' => @$LogSupport,
        'supportTicketClose' => @$LogCloture,
        'read_data_reboot' => $read_data_reboot,
        // get option
        'updateIniFileLogUser' => @$update_ini_file_log,
        // get admin
        'updateIniFileLogOwner' => @$update_ini_file_log_owner,
        'LogDeleteUser' => @$log_delete_user,
        'UpdateOwner' => @$loader_file_ini_user,
        'ClearCache' => @$ClearCache
    ]);
})->setName('index');

$app->get('/settings', function ($request, $response, $args) {
    return $this->view->render($response, 'settings.twig.html', [
    ]);
})->setName('profile');

$app->get('/admin', function ($request, $response, $args) {
    return $this->view->render($response, 'index.twig.html', [
    ]);
})->setName('admin');
