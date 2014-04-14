<?php

require_once '../app/manager.php';

/* REQUEST POST AND GET */
if ( isset($_GET['logout']) )
{
    $serveur = new Server($file_user_ini, $userName);
    $serveur->logout();
}

if ( isset($_POST['reboot']) )
{
    $user = new Users($file_user_ini, $userName);
    $rebootRtorrent = $user->rebootRtorrent();
}

if ( isset($_POST['simple_conf_user']) )
{
    $update = new UpdateFileIni($file_user_ini, $userName);
    $update_ini_file_log = $update->update_file_config($_POST, './conf/users/'.$userName);
}

if ( isset($_POST['owner_change_config']) )
{
    $update = new UpdateFileIni('./conf/users/'.$_POST['user'].'/config.ini', $_POST['user']);
    $update_ini_file_log_owner = $update->update_file_config($_POST, './conf/users/'.$_POST['user']);
}

if ( isset($_POST['delete-userName']) )
{
    $user = new Users($file_user_ini, $userName);
    $log_delete_user = Users::delete_config_old_user('./conf/users/'.$_POST['delete-userName']);
}

if ( isset($_POST['support']) && isset($_POST['message']) )
{
    $message = $_POST['message'];
    $support = new Support($file_user_ini, $userName);
    $supportInfo = $support->sendTicket($message,$_POST['user']);
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
$read_data_reboot = $user->readFileDataReboot('./conf/users/'.$userName.'/data_reboot.txt');

/*

Pour les views charger le bon index.php en fonction du themes


*/

/* views */
require_once('themes/default/header.php');

if ( isset($_GET['option']) )
    require_once ('themes/default/option.php');
elseif ( isset($_GET['download']))
{
    require_once('lib/downloads.php');
    require_once('themes/default/body.php');
}
else
    require_once('themes/default/body.php');

require_once('themes/default/modal.php');
