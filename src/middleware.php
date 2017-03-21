<?php
// Application middleware

use Seedbox\Install;
use Seedbox\Users;

$dev = true;

// check authentication
if (isset($_SERVER['REMOTE_USER']) || isset($_SERVER['PHP_AUTH_USER'])) {
    $userName = isset($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER']:$_SERVER['PHP_AUTH_USER'];
} elseif ($dev) {
    $userName = 'max';
} else {
    die('Le script n\'est pas prot&eacute;g&eacute; par une authentification.<br>
         V&eacute;rifiez la configuration de votre serveur web.');
}

// check install
if (false === is_writable(__DIR__.'/../conf/users')) {
    require __DIR__.'/../public/install/installation.php';
    exit(1);
} elseif (file_exists(__DIR__.'/../conf/users/'.$userName.'/config.ini')) {
    $file_user_ini =  __DIR__.'/../conf/users/'.$userName.'/config.ini';
} else {
    Install::create_new_user($userName);
    $file_user_ini =  __DIR__.'/../conf/users/'.$userName.'/config.ini';
}

$isAdmin = function ($request, $response, $next) use ($file_user_ini, $userName) {
    $user = new Users($file_user_ini, $userName);

    if ($user->is_owner() === false){
        return $response->withStatus(403);
    }

    return $next($request, $response);
};
