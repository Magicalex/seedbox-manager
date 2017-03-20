<?php

require '../vendor/autoload.php';

// Instantiate the app
$settings = require __DIR__.'/../src/settings.php';

$app = new \Slim\App($settings);

require __DIR__.'/../src/dependencies.php';

require __DIR__.'/../src/middleware.php';

require __DIR__.'/../src/routes.php';

// Run app
$app->run();

/*
use Seedbox\Users;
use Seedbox\Server;
use Seedbox\Install;
use WriteiniFile\WriteiniFile;

use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;

// REQUEST POST
if (isset($_POST['reboot'])) {
    $option = (isset($_POST['irssi'])) ? true : false;
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

// REQUEST GET
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



// init translation
$lang = 'fr';
$translator = new Translator($lang, new MessageSelector());
$translator->addLoader('yaml', new YamlFileLoader());
$translator->addResource('yaml', '../locale/core.fr.yml', 'fr');



*/
