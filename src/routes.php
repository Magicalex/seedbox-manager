<?php

use \Seedbox\Users;
use \Seedbox\Server;
use \WriteiniFile\WriteiniFile;

$user = new Users($file_user_ini, $username);
$server = new Server($file_user_ini, $username);

// request GET
$app->get('/', function ($request, $response) use ($user, $server) {

    $read_data_reboot = $user->readFileDataReboot(__DIR__."/../conf/users/{$user->name()}/data_reboot.txt");

    $notifications = $this->flash->getMessages();

    return $this->view->render($response, 'index.twig.html', [
        'host' => $_SERVER['HTTP_HOST'],
        'ipUser' => $_SERVER['REMOTE_ADDR'],
        'user' => $user,
        'server' => $server,
        'read_data_reboot' => $read_data_reboot,
        'notifications' => $notifications
    ]);
});

$app->get('/settings', function ($request, $response) use ($user, $server) {

    $notifications = $this->flash->getMessages();

    return $this->view->render($response, 'settings.twig.html', [
        'user' => $user,
        'server' => $server,
        'notifications' => $notifications
    ]);
});

$app->get('/admin', function ($request, $response) use ($user, $server) {

    $notifications = $this->flash->getMessages();

    return $this->view->render($response, 'admin.twig.html', [
        'user' => $user,
        'member' => $user,
        'server' => $server,
        'notifications' => $notifications
    ]);
})->add($isAdmin);

$app->get('/admin/{username}', function ($request, $response, $args) use ($user) {

    $username = $args['username'];
    $member = new Users(__DIR__."/../conf/users/{$username}/config.ini", $username);
    $server = new Server(__DIR__."/../conf/users/{$username}/config.ini", $username);

    $notifications = $this->flash->getMessages();

    return $this->view->render($response, 'admin.twig.html', [
        'user' => $user,
        'member' => $member,
        'server' => $server,
        'notifications' => $notifications
    ]);
})->add($isAdmin);

$app->get('/download/{file}', function ($request, $response, $args) use ($user) {

    $file = $args['file'];
    //if (isset($_GET['download'])) {
    //    require '../app/downloads.php';
    //}
    //return;
});

// request POST
$app->post('/reboot-rtorrent', function ($request, $response) use ($user) {

    $param = $request->getParsedBody();
    $option = (isset($param['irssi'])) ? true : false;

    $reboot_rtorrent = $user->rebootRtorrent($option);
    $this->flash->addMessage('rtorrent', $reboot_rtorrent);

    return $response->withStatus(302)->withHeader('Location', '/');
});

$app->post('/settings/update', function ($request, $response) use ($file_user_ini) {

    $param = $request->getParsedBody();
    $update = new WriteiniFile($file_user_ini);
    $update->update([
        'user' => [
            'active_bloc_info' => isset($param['active_bloc_info']) ? true : false,
            'theme' => $param['theme']
        ],
        'ftp' => [
            'active_ftp' => isset($param['active_ftp']) ? true : false
        ],
        'rtorrent' => [
            'active_reboot' => isset($param['active_reboot']) ? true : false
        ],
        'logout' => [
            'url_redirect' => $param['url_redirect']
        ]
    ]);
    $logs = $update->write();

    $this->flash->addMessage('update_ini_file', $logs);

    return $response->withStatus(302)->withHeader('Location', '/settings');
});

$app->post('/admin/update/{username}', function ($request, $response, $args) {

    $param = $request->getParsedBody();
    $username = $args['username'];

    $update = new WriteiniFile(__DIR__."/../conf/users/{$username}/config.ini");
    $update->update([
        'user' => [
            'user_directory' => $param['user_directory'],
            'scgi_folder' => $param['scgi_folder']
        ],
        'nav' => [
            'data_link' => $param['data_link']
        ],
        'ftp' => [
            'port_ftp' => $param['port_ftp'],
            'port_sftp' => $param['port_sftp']
        ],
        'support' => [
            'adresse_mail' => $param['adresse_mail']
        ]
    ]);
    $logs = $update->write();

    $this->flash->addMessage('admin_update_ini', $logs);

    return $response->withStatus(302)->withHeader('Location', "/admin/{$username}");
})->add($isAdmin);

$app->post('/admin/delete', function ($request, $response) {
    $param = $request->getParsedBody();
    $username = $param['deleteUserName'];

    $logs = Users::delete_config_old_user(__DIR__."/../conf/users/{$username}");

    $this->flash->addMessage('admin_delete_user', $logs);
    $this->flash->addMessage('admin_delete_user', $username);

    return $response->withStatus(302)->withHeader('Location', '/admin');
})->add($isAdmin);
