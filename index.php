<?php

/* autoload class php */
function chargerClasse($classe) { require_once('php/'.$classe.'.class.php'); }
spl_autoload_register('chargerClasse');

/* recup user */
if ( isset($_SERVER['REMOTE_USER']) || isset($_SERVER['PHP_AUTH_USER']) )
    $userName = isset($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER']:$_SERVER['PHP_AUTH_USER'];
else
    $userName = 'magicalex';//die('Le script n\'est pas prot&eacute;g&eacute; par une authentification.<br>V&eacute;rifiez la configuration de votre serveur web.');

// check conf + create new user
$install = new Install;
$uid_reboot_rtorrent = Install::check_uid_file('./reboot-rtorrent');
$get_chmod_reboot_rtorrent = Install::getChmod('./reboot-rtorrent', 4);
if (file_exists('./reboot-rtorrent') && $uid_reboot_rtorrent == 0 && $get_chmod_reboot_rtorrent == 4755)
{
    $uid_folder_users = Install::check_uid_file('./conf/users/');
    $uid_user_php = Install::get_user_php();
    if ( $uid_folder_users != $uid_user_php['num_uid'] )
    {
        require_once('./html/installation.php');
        exit();
    }
    else
    {
        if (file_exists('./conf/users/'.$userName.'/config.ini'))
            $file_user_ini = './conf/users/'.$userName.'/config.ini';
        else
        {
            Install::create_new_user($userName);
            $file_user_ini = './conf/users/'.$userName.'/config.ini';
        }
    }
}
else
{
    require_once('./html/installation.php');
    exit();
}

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

if ( isset($_POST['submit']) )
{
    $update = new Update($file_user_ini, $userName);
    $update_ini_file_log = $update->update_file_config($_POST, './conf/users/'.$userName);
}

// init objet
$user = new Users($file_user_ini, $userName);
$serveur = new Server($file_user_ini, $userName);
$host = $_SERVER['HTTP_HOST'];
$current_path = $user->currentPath();
$data_disk = $user->userdisk();
$load_server = Server::load_average();
$read_data_reboot = $user->readFileDataReboot('./conf/users/'.$userName.'/data_reboot.txt');

/* views */
require_once('html/header.php');

if ( isset($_GET['edit']) )
    require_once ('html/edit.php');
else
    require_once('html/body.php');

require_once('html/modal.html');
