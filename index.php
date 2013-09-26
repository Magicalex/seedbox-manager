<?php

function chargerClasse($classe) { require_once('php/'.$classe.'.class.php'); }
spl_autoload_register('chargerClasse');
// initialisation des objets
$user = new Users;
$serveur = new Server;

// passe les fonctions dans des variables
$controleUser = $user->controlUser();
$userName = $user->userName();
$host = $_SERVER['HTTP_HOST'];
$current_path = $user->currentPath();
$data_disk = $user->userdisk();
$load_server = Server::load_average();

// check chmod ./reboot-rtorrent & ./conf/users/.$userName
$chmod_reboot_rtorrent = Server::getChmod('./reboot-rtorrent', -4);
$chmod_folder_user = file_exists('./conf/users/'.$userName) ? Server::getChmod('./conf/users/'.$userName, -3):null;
$chmodRebootRtorrent = $chmod_reboot_rtorrent == 4755 ? true:false;
$chmodFolderUser = $chmod_folder_user == 777 ? true:false;

/* REQUEST POST AND GET */
if ( isset($_GET['logout']) )
    Server::logout($user->realmWebServer());

if ( $controleUser && isset($_POST['reboot']) )
    $rebootRtorrent = $user->rebootRtorrent();

if ( file_exists('./conf/users/'.$userName.'/data_reboot.txt') )
    $read_data_reboot = $user->readFileDataReboot();
else
    $read_data_reboot['file_exist'] = false;

//inclusion html
include_once('html/header.php');
include_once('html/body.php');
include_once('html/modal.html');
