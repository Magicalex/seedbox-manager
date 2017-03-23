<?php

use \App\Middleware\Auth as isAuth;
use \App\Middleware\Admin as isAdmin;
use \App\Middleware\Installed as checkInstall;

$app->group('/', function () {
    // get
    $this->get('', '\App\Controller\HomeController:index');
    $this->get('settings', '\App\Controller\HomeController:settings');
    $this->get('admin', '\App\Controller\AdminController:index')->add(new isAdmin);
    $this->get('admin/{username}', '\App\Controller\AdminController:user')->add(new isAdmin);

    // post
    $this->post('reboot-rtorrent', '\App\Controller\HomeController:reboot');
    $this->post('settings/update', '\App\Controller\HomeController:update');
    $this->post('admin/update/{username}', '\App\Controller\AdminController:update')->add(new isAdmin);
    $this->post('admin/delete', '\App\Controller\AdminController:delete')->add(new isAdmin);
})->add(new isAuth)->add(new checkInstall);

$app->get('/install', '\App\Controller\InstallController:index');


/*
$app->get('/download/{file}', function ($request, $response, $args) {
    $file = $args['file'];
    if (isset($_GET['download'])) {
        require '../app/downloads.php';
    }
    return;
});
*/
