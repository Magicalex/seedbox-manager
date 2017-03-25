<?php

use \App\Middleware\Admin as isAdmin;
use \App\Middleware\Auth as isAuth;
use \App\Middleware\Installed as checkInstall;

$app->add(new isAuth());

$app->group('/', function () {
    $this->get('', '\App\Controller\HomeController:index')->setName('home');
    $this->get('settings', '\App\Controller\HomeController:settings')->setName('setting');
    $this->get('admin', '\App\Controller\AdminController:index')->add(new isAdmin())->setname('admin');
    $this->get('admin/{username:[a-z]+}', '\App\Controller\AdminController:user')->add(new isAdmin())->setname('adminProfil');
    $this->get('download/{file:[a-z]+}', '\App\Controller\DownloadController:download')->setName('download');
    $this->post('reboot', '\App\Controller\HomeController:reboot')->setName('reboot');
    $this->post('settings/update', '\App\Controller\HomeController:update')->setName('updateSettingUser');
    $this->post('admin/update/{username:[a-z]+}', '\App\Controller\AdminController:update')->add(new isAdmin())->setName('updateAdminUser');
    $this->post('admin/delete', '\App\Controller\AdminController:delete')->add(new isAdmin())->setName('updateDeleteUser');
})->add(new checkInstall($container->get('router')));

$app->get('/install', '\App\Controller\InstallController:index')->setName('install');
