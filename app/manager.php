<?php

if ( isset($_SERVER['REMOTE_USER']) || isset($_SERVER['PHP_AUTH_USER']) )
    $userName = isset($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER']:$_SERVER['PHP_AUTH_USER'];
else
    die('Le script n\'est pas prot&eacute;g&eacute; par une authentification.<br>V&eacute;rifiez la configuration de votre serveur web.');

// autoload des class php via composer
require_once './../vendor/autoload.php';

/*check config app */
use app\Lib\Install;

if ( Install::check_uid_file('./../reboot-rtorrent') == 0
     && Install::getChmod('./../reboot-rtorrent', 4) == 4755 )
{
    $uid_folder_users = Install::check_uid_file('./../conf/users/');
    $uid_user_php = Install::get_user_php();
    if ( $uid_folder_users != $uid_user_php['num_uid'] )
    {
        require_once('./install/installation.php');
        exit(0);
    }
    else
    {
        if (file_exists('./../conf/users/'.$userName.'/config.ini'))
            $file_user_ini = './../conf/users/'.$userName.'/config.ini';
        else
        {
            Install::create_new_user($userName);
            $file_user_ini = './../conf/users/'.$userName.'/config.ini';
        }
    }
}
else
{
    require_once('./install/installation.php');
    exit(0);
}
