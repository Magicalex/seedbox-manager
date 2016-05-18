<?php

require '../vendor/autoload.php';

use \app\Lib\Users;
use \app\Lib\Server;
use \app\Lib\Support;
use \app\Lib\Install;
use \WriteiniFile\WriteiniFile;

/* check authentication */
if (isset($_SERVER['REMOTE_USER']) || isset($_SERVER['PHP_AUTH_USER'])) {
    $userName = isset($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER']:$_SERVER['PHP_AUTH_USER'];
} else {
    die('Le script n\'est pas prot&eacute;g&eacute; par une authentification.<br>
         V&eacute;rifiez la configuration de votre serveur web.');
}

/* check install */
if (false === is_writable('../conf/users')) {
    require 'install/installation.php';
    exit(1);
} elseif (file_exists('../conf/users/' . $userName . '/config.ini')) {
    $file_user_ini = '../conf/users/' . $userName . '/config.ini';
} else {
    Install::create_new_user($userName);
    $file_user_ini = '../conf/users/' . $userName . '/config.ini';
}

/* REQUEST POST */
if (isset($_POST['reboot'])) {
    $option = (isset($_POST['irssi'])) ? true:false;
    $user = new Users($file_user_ini, $userName);
    $rebootRtorrent = $user->rebootRtorrent($option);
}

if (isset($_POST['conf_user'])) {
    $post = $_POST;
    $update = new WriteIniFile($file_user_ini);
    $update->update([
        'user' => ['active_bloc_info' => @$post['active_bloc_info'], 'theme' => $post['theme']],
        'ftp' => ['active_ftp' => @$post['active_ftp']],
        'rtorrent' => ['active_reboot' => @$post['active_reboot']],
        'support' => ['active_support' => @$post['active_support']],
        'logout' => ['url_redirect' => $post['url_redirect']]
    ]);
    $update_ini_file_log = $update->write();
}

if (isset($_POST['config_admin'])) {
    $post = $_POST;
    $update = new WriteIniFile('../conf/users/' . $post['user'] . '/config.ini');
    $update->update([
        'user' => ['user_directory' => $post['user_directory'], 'scgi_folder' => $post['scgi_folder']],
        'nav' => ['data_link' => $post['data_link']],
        'ftp' => ['port_ftp' => $post['port_ftp'], 'port_sftp' => $post['port_sftp']],
        'support' => ['adresse_mail' => $post['adresse_mail']]
    ]);
    $update_ini_file_log_owner = $update->write();
}

if (isset($_POST['deleteUserName'])) {
    $log_delete_user = Users::delete_config_old_user('../conf/users/' . $_POST['deleteUserName']);
}

if (isset($_POST['support']) && isset($_POST['message'])) {
    $support = new Support($file_user_ini, $userName);
    $LogSupport = $support->sendTicket($_POST['message'], $_POST['user']);
}

if (isset($_POST['cloture']) && isset($_POST['user'])) {
    $LogCloture = Support::ClotureTicket($_POST['user']);
}

/* REQUEST GET */
if (isset($_GET['admin'])) {
    if (empty($_GET['user'])) {
        $loader_file_ini_user = new Users('../conf/users/' . $userName . '/config.ini', $userName);
    } else {
        $loader_file_ini_user = new Users('../conf/users/' . $_GET['user'] . '/config.ini', $_GET['user']);
    }
}

if (isset($_GET['download'])) {
    require '../app/downloads.php';
}

/* init objet */
$user = new Users($file_user_ini, $userName);
$serveur = new Server($file_user_ini, $userName);
$support = new Support($file_user_ini, $userName);
$read_data_reboot = $user->readFileDataReboot('../conf/users/' . $userName . '/data_reboot.txt');

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
