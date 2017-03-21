<?php
// Routes

use Seedbox\Users;
use Seedbox\Server;
use WriteiniFile\WriteiniFile;

// request get
$app->get('/', function ($request, $response) use ($file_user_ini, $userName) {

    $user = new Users($file_user_ini, $userName);
    $serveur = new Server($file_user_ini, $userName);
    $read_data_reboot = $user->readFileDataReboot(__DIR__.'/../conf/users/'.$userName.'/data_reboot.txt');

    $notifications = $this->flash->getMessages();

    return $this->view->render($response, 'index.twig.html', [
        'userName' => $userName,
        'host' => $_SERVER['HTTP_HOST'],
        'ipUser' => $_SERVER['REMOTE_ADDR'],
        'user' => $user,
        'serveur' => $serveur,
        'read_data_reboot' => $read_data_reboot,
        'notifications' => $notifications

        /*
        // get admin
        'updateIniFileLogOwner' => @$update_ini_file_log_owner,
        'LogDeleteUser' => @$log_delete_user,
        'UpdateOwner' => @$loader_file_ini_user,
        'ClearCache' => @$ClearCache
        */
    ]);
});

$app->get('/settings', function ($request, $response) use ($file_user_ini, $userName) {
    $user = new Users($file_user_ini, $userName);
    $serveur = new Server($file_user_ini, $userName);

    $notifications = $this->flash->getMessages();

    return $this->view->render($response, 'settings.twig.html', [
        'userName' => $userName,
        'user' => $user,
        'notifications' => $notifications
    ]);
});

$app->get('/admin', function ($request, $response) use ($file_user_ini, $userName) {
    $param = $request->getParsedBody();
    $user = new Users($file_user_ini, $userName);
    $serveur = new Server($file_user_ini, $userName);

    return $this->view->render($response, 'admin.twig.html', [
        'userName' => $userName,
        'name' => $userName,
        'user' => $user,
        'server' => $serveur,

        //'updateIniFileLogOwner' => @$update_ini_file_log_owner,
        //'LogDeleteUser' => @$log_delete_user
    ]);
})->add($isAdmin);

// request post
$app->post('/reboot-rtorrent', function ($request, $response) use ($file_user_ini, $userName) {
    $param = $request->getParsedBody();
    $option = (isset($param['irssi'])) ? true : false;
    $user = new Users($file_user_ini, $userName);
    $reboot_rtorrent = $user->rebootRtorrent($option);
    $this->flash->addMessage('rtorrent', $reboot_rtorrent);

    return $response->withStatus(302)->withHeader('Location', '/');
});

$app->post('/settings/update', function ($request, $response) use ($file_user_ini, $userName) {
    $param = $request->getParsedBody();

    if (isset($param['conf_user'])) {
        $update = new WriteIniFile($file_user_ini);
        $update->update([
            'user' => ['active_bloc_info' => $param['active_bloc_info'], 'theme' => $param['theme']],
            'ftp' => ['active_ftp' => $param['active_ftp']],
            'rtorrent' => ['active_reboot' => $param['active_reboot']],
            'support' => ['active_support' => $param['active_support']],
            'logout' => ['url_redirect' => $param['url_redirect']]
        ]);
        $update_ini_file_log = $update->write();
    }

    $var = $this->flash->addMessage('update_ini_file', $update_ini_file_log);

    print_r($var);

    //return $response->withStatus(302)->withHeader('Location', '/settings');
});
