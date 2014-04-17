<?php

require_once './../app/manager.php';

use app\Lib\Users;
use app\Lib\Server;
use app\Lib\Support;
use app\Lib\UpdateFileIni;

/* REQUEST GET */
if ( isset($_GET['logout']) )
{
    $serveur = new Server($file_user_ini, $userName);
    $serveur->logout();
}

if ( isset($_GET['admin']))
{
    if (empty($GET['user']))
        new Users('./../conf/users/'.$userName.'/config.ini', $userName );
    else
        new Users('./../conf/users/'.$_GET['user'].'/config.ini', $_GET['user'] );
}

/* REQUEST POST */
if ( isset($_POST['reboot']) )
{
    $user = new Users($file_user_ini, $userName);
    $rebootRtorrent = $user->rebootRtorrent();
}

if ( isset($_POST['simple_conf_user']) )
{
    $update = new UpdateFileIni($file_user_ini, $userName);
    $update_ini_file_log = $update->update_file_config($_POST, './../conf/users/'.$userName);
}

if ( isset($_POST['owner_change_config']) )
{
    $update = new UpdateFileIni('./../conf/users/'.$_POST['user'].'/config.ini', $_POST['user']);
    $update_ini_file_log_owner = $update->update_file_config($_POST, './../conf/users/'.$_POST['user']);
}

if ( isset($_POST['delete-userName']) )
{
    $user = new Users($file_user_ini, $userName);
    $log_delete_user = Users::delete_config_old_user('./../conf/users/'.$_POST['delete-userName']);
}

if ( isset($_POST['support']) && isset($_POST['message']) )
{
    $support = new Support($file_user_ini, $userName);
    $LogSupport = $support->sendTicket( $_POST['message'], $_POST['user']);
}

if ( isset($_POST['cloture']) && isset($_POST['user']))
{
    $support = new Support($file_user_ini, $userName);
    $cloture = $support->cloture($_POST['user']);
}

/* init objet */
$user = new Users($file_user_ini, $userName);
$serveur = new Server($file_user_ini, $userName);
$support = new Support($file_user_ini, $userName);
$host = $_SERVER['HTTP_HOST'];
$current_path = $user->currentPath();
$data_disk = $user->userdisk();
$load_server = Server::load_average();
$read_data_reboot = $user->readFileDataReboot('./../conf/users/'.$userName.'/data_reboot.txt');


$loader = new Twig_Loader_Filesystem('./themes/default');
$twig = new Twig_Environment($loader, array(
    'cache' => false
));

echo $twig->render(
    'index.html', array(
        'post' => $_POST,
        'get' => $_GET,
        'userName' => $userName,
        'is_owner' => $user->is_owner(),
        'userRutorrentActiveUrl' => $user->rutorrentActiveUrl(),
        'rutorrentUrl' => $user->rutorrentUrl(),
        'userCakeboxActiveUrl' => $user->cakeboxActiveUrl(),
        'userCakeboxUrl' => $user->cakeboxUrl(),
        'rebootRtorrent' => @$rebootRtorrent,
        'supportTicketSend' => @$LogSupport,
        'cloture' => @$cloture,
        'userBlocInfo' => $user->blocInfo(),
        'ipUser' => $_SERVER['REMOTE_ADDR'],
        'data_disk' => $data_disk,
        'uptime' => Server::getUptime(),
        'load_server' => $load_server,
        'userBlocFtp' => $user->blocFtp(),
        'host' => $host,
        'portFtp' => $user->portFtp(),
        'portSftp' => $user->portSftp(),
        'scgi_folder' => $user->scgi_folder(),
        'userBlocRtorrent' => $user->blocRtorrent(),
        'read_data_reboot' => $read_data_reboot,
        'userBlocSupport' => $user->blocSupport(),
        'userSupportMail' => $user->supportMail(),
        'ticket_list' => $support->ReadTicket(),

        // get option
        'updateIniFileLogUser' => @$update_ini_file_log,
        'urlRedirect' => $user->url_redirect(),

        // get admin
        'updateIniFileLogOwner' => @$update_ini_file_log_owner,
        'LogDeleteUser' => @$log_delete_user,
        'updateOwner' => ''

    )
);
