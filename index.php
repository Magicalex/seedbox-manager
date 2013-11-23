<?php

/* autoload class php */
function chargerClasse($classe) { require_once('lib/'.$classe.'.class.php'); }
spl_autoload_register('chargerClasse');

/* recuperation nom utilisateur connecte */
if ( isset($_SERVER['REMOTE_USER']) || isset($_SERVER['PHP_AUTH_USER']) )
    $userName = isset($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER']:$_SERVER['PHP_AUTH_USER'];
else
    die('Le script n\'est pas prot&eacute;g&eacute; par une authentification.<br>V&eacute;rifiez la configuration de votre serveur web.');

/*check config app */
$install = new Install;

    $uid_folder_users = Install::check_uid_file('./conf/users/');
    $uid_user_php = Install::get_user_php();
   /* if ( $uid_folder_users != $uid_user_php['num_uid'] )
    {
        require_once('./html/installation.php');
        exit();
    }*/

        if (file_exists('./conf/users/'.$userName.'/config.ini'))
            $file_user_ini = './conf/users/'.$userName.'/config.ini';
        else
        {
            Install::create_new_user($userName);
            $file_user_ini = './conf/users/'.$userName.'/config.ini';
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

if ( isset($_POST['simple_conf_user']) )
{
    $update = new Update($file_user_ini);
    $update_ini_file_log = $update->update_file_config($_POST, './conf/users/'.$userName);
}

if ( isset($_POST['owner_change_config']) )
{
    $update = new Update('./conf/users/'.$_POST['user'].'/config.ini');
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
    $user = new Users($file_user_ini, $userName);
    $support = $user->support($message,$_POST['user']);
}

if ( isset($_POST['cloture']) && isset($_POST['user']))
{
    $user = new Users($file_user_ini, $userName);
    $cloture = $user->cloture($_POST['user']);
}

/* init objet */
$user = new Users($file_user_ini, $userName);
$serveur = new Server($file_user_ini, $userName);
$host = $_SERVER['HTTP_HOST'];
$current_path = $user->currentPath();
$data_disk = $user->userdisk();
$load_server = Server::load_average();
$read_data_reboot = $user->readFileDataReboot('./conf/users/'.$userName.'/data_reboot.txt');

/* views */
require_once('html/header.php');

if ( isset($_GET['option']) )
    require_once ('html/option.php');
elseif ( isset($_GET['download']))
{
    require_once('lib/downloads.php');
    require_once('html/body.php');
}
else
    require_once('html/body.php');

require_once('html/modal.php');
