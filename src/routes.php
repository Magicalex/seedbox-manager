<?php
// Routes

use Seedbox\Users;
use Seedbox\Server;

$app->get('/', function ($request, $response) use ($file_user_ini, $userName) {

    $user = new Users($file_user_ini, $userName);
    $serveur = new Server($file_user_ini, $userName);
    $read_data_reboot = $user->readFileDataReboot(__DIR__.'/../conf/users/'.$userName.'/data_reboot.txt');

    $messages = $this->flash->getMessages();

    echo "<pre>";
    print_r($messages);
    echo "</pre>";

    return $this->view->render($response, 'index.twig.html', [
        'userName' => $userName,
        'host' => $_SERVER['HTTP_HOST'],
        'ipUser' => $_SERVER['REMOTE_ADDR'],

        // init objet
        'user' => $user,
        'serveur' => $serveur,

        // var index
        'rebootRtorrent' => @$rebootRtorrent,
        'read_data_reboot' => $read_data_reboot,

        // get option
        'updateIniFileLogUser' => @$update_ini_file_log,

        // get admin
        'updateIniFileLogOwner' => @$update_ini_file_log_owner,
        'LogDeleteUser' => @$log_delete_user,
        'UpdateOwner' => @$loader_file_ini_user,
        'ClearCache' => @$ClearCache,

        'messages' => $messages
    ]);
});

$app->get('/settings', function ($request, $response) use ($file_user_ini, $userName) {
    $user = new Users($file_user_ini, $userName);
    $serveur = new Server($file_user_ini, $userName);

    return $this->view->render($response, 'settings.twig.html', [
        'userName' => $userName,
        'user' => $user
    ]);
});

$app->get('/admin', function ($request, $response) use ($file_user_ini, $userName) {
    $user = new Users($file_user_ini, $userName);
    $serveur = new Server($file_user_ini, $userName);

    return $this->view->render($response, 'admin.twig.html', [
        'userName' => $userName,
        'user' => $user,
        'server' => $serveur
    ]);
});

$app->post('/reboot-rtorrent', function ($request, $response) use ($file_user_ini, $userName) {
    $param = $request->getParsedBody();
    $option = (isset($param['irssi'])) ? true : false;
    $user = new Users($file_user_ini, $userName);
    $reboot_rtorrent = $user->rebootRtorrent($option);
    $this->flash->addMessage('reboot-rtorrent', $reboot_rtorrent);

    return $response->withStatus(302)->withHeader('Location', '/');
});
