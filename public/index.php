<?php

require '../vendor/autoload.php';

session_start();
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

if (isset($_GET['download'])) {
    require '../app/downloads.php';
}

// init translation
$lang = 'fr';
$translator = new Translator($lang, new MessageSelector());
$translator->addLoader('yaml', new YamlFileLoader());
$translator->addResource('yaml', '../locale/core.fr.yml', 'fr');
*/
