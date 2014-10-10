<?php

require_once './../app/manager.php';

use app\Lib\Users;
use app\Lib\Server;
use app\Lib\Support;
use app\Lib\UpdateFileIni;

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
if ( isset($_POST['deleteUserName']) )
    $log_delete_user = Users::delete_config_old_user('./../conf/users/'.$_POST['deleteUserName']);
if ( isset($_POST['support']) && isset($_POST['message']) )
{
    $support = new Support($file_user_ini, $userName);
    $LogSupport = $support->sendTicket( $_POST['message'], $_POST['user']);
}
if ( isset($_POST['cloture']) && isset($_POST['user']))
    $LogCloture = Support::ClotureTicket($_POST['user']);

/* REQUEST GET */
if (isset($_GET['admin']))
{
    if (empty($_GET['user']))
        $loader_file_ini_user = new Users('./../conf/users/'.$userName.'/config.ini', $userName );
    else
        $loader_file_ini_user = new Users('./../conf/users/'.$_GET['user'].'/config.ini', $_GET['user'] );
}
if (isset($_GET['download']))
    require_once './../app/downloads.php';

/* init objet */
$user = new Users($file_user_ini, $userName);
$serveur = new Server($file_user_ini, $userName);
$support = new Support($file_user_ini, $userName);
$read_data_reboot = $user->readFileDataReboot('./../conf/users/'.$userName.'/data_reboot.txt');

/* init twig */
$loader = new Twig_Loader_Filesystem('themes/' . $user->theme());
$twig = new Twig_Environment($loader);
echo $twig->render(
    'index.html', array(
        'userName' => $userName,
        'post' => $_POST,
        'get' => $_GET,
        'host' => $_SERVER['HTTP_HOST'],
        'ipUser' => $_SERVER['REMOTE_ADDR'],
        // init objet
        'user' => $user,
        'serveur' => $serveur,
        'support' => $support,
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
    )
);
