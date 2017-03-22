<?php

use \Seedbox\Install;

// check authentication
if (isset($_SERVER['REMOTE_USER']) || isset($_SERVER['PHP_AUTH_USER'])) {
    $username = isset($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER'] : $_SERVER['PHP_AUTH_USER'];
} else {
    die('Le script n\'est pas prot&eacute;g&eacute; par une authentification.<br>
        V&eacute;rifiez la configuration de votre serveur web.');
}

// check install
if (false === is_writable(__DIR__.'/../conf/users')) {
    require __DIR__.'/../public/install/installation.php';
    exit(1);
} elseif (file_exists(__DIR__."/../conf/users/{$username}/config.ini")) {
    $file_user_ini = __DIR__."/../conf/users/{$username}/config.ini";
} else {
    Install::create_new_user($username);
    $file_user_ini = __DIR__."/../conf/users/{$username}/config.ini";
}

// check language
$lang = parse_ini_file($file_user_ini, true);
$lang = $lang['user']['language'];
