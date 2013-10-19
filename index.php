<?php

// autoload des class php
function chargerClasse($classe) { require_once('php/'.$classe.'.class.php'); }
spl_autoload_register('chargerClasse');

if ( isset($_SERVER['REMOTE_USER']) || isset($_SERVER['PHP_AUTH_USER']) )
    $userName = isset($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER']:$_SERVER['PHP_AUTH_USER'];
else
    die('Le script n\'est pas prot&eacute;g&eacute; par une authentification.<br>V&eacute;rifiez la configuration de votre serveur web.');

if ( file_exists('./conf/users/'.$userName.'/config.ini') )
    $file_user_ini = './conf/users/'.$userName.'/config.ini';
else
    $file_user_ini = './conf/config.ini';

// initialisation des objets
$user = new Users($file_user_ini, $userName );
$serveur = new Server($file_user_ini, $userName);
$update = new Edit($file_user_ini, $userName);

// passe les fonctions dans des variables
$host = $_SERVER['HTTP_HOST'];
$current_path = $user->currentPath();
$data_disk = $user->userdisk();
$load_server = Server::load_average();
$read_data_reboot = $user->readFileDataReboot('./conf/users/'.$userName.'/data_reboot.txt');

// check chmod ./reboot-rtorrent & ./conf/users/.$userName
$chmod_reboot_rtorrent = Server::getChmod('./reboot-rtorrent', 4);
$chmod_folder_user = file_exists('./conf/users/'.$userName) ? Server::getChmod('./conf/users/'.$userName, 3):null;
$chmodRebootRtorrent = $chmod_reboot_rtorrent == 4755 ? true:false;
$chmodFolderUser = $chmod_folder_user == 777 ? true:false;
// indique si le user à son dossier de config
$bad_config_user = $chmod_folder_user == null ? true:false;

/* REQUEST POST AND GET */
if ( isset($_GET['logout']) )
    $serveur->logout();

if ( isset($_POST['reboot']) )
    $rebootRtorrent = $user->rebootRtorrent();

if ( isset($_POST['submit']) )
{
    $uplog = $update->update_file_config($_POST, './conf/users/'.$userName);
    echo '<pre>';
    print_r($uplog);
    echo '</pre>';
}

//inclusion html
include_once('html/header.php');

// par defaut body sinon edit.php
if ( isset($_GET['edit']) )
    include_once 'html/edit.php';
else
    include_once('html/body.php');

include_once('html/modal.html');
