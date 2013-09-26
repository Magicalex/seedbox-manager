<?php

function chargerClasse($classe) { require_once($classe.'.class.php'); }
spl_autoload_register('chargerClasse');

$user = new Users;
$serveur = new Server;

$userName = $user->userName();
$host = $_SERVER['HTTP_HOST'];
$chmod_folder_user = file_exists('../conf/users/'.$userName) ? Server::getChmod('../conf/users/'.$userName, -3):null;
$chmodFolderUser = $chmod_folder_user == 777 ? true:false;
$folder_scgi = '/'.strtoupper(substr($userName,0,3)).'0';
$title_seedbox = 'Seedbox '.$userName;
$passwd = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW']:null;

// setting transdroid
$tableau_conf_trandroid = array(
    'ui_swipe_labels' => false,
    'alarm_vibrate' => false,
    'alarm_enabled' => false,
    'alarm_check_rss_feeds' => false,
    'websites' => array(),
    'ui_refresh_interval' => '60',
    'ui_hide_refresh' => false,
    'search_sort_by' => 'sort_seeders',
    'alarm_play_sound' => false,
    'ui_enable_ads' => true,
    'ui_only_show_transferring' => false,
    'search_num_results' => '25',
    'servers' => array(array(
        'port' => '443',
        'host' => $host,
        'ssl' => true,
        'type' => 'daemon_rtorrent',
        'password' => $passwd,
        'os_type' => 'type_linux',
        'folder' => $folder_scgi,
        'username' => $userName,
        'use_auth' => true,
        'name' => $title_seedbox,
        'base_ftp_url' => 'ftp://'.$userName.'@'.$host.'/torrents/',
        'download_alarm' => true,
        'new_torrent_alarm' => true,
        'ssl_accept_all' => true
        )),

    'alarm_interval' => '600000',
    'rssfeeds' => array(),
    'ui_ask_before_remove' => true
);

$conf_json_trandroid = json_encode($tableau_conf_trandroid);

// setting filezilla
$conf_xml_filezilla =
'<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>'.
'<FileZilla3>'.
    '<Servers>'.
        '<Server>'.
            '<Host>'.$host.'</Host>'.
            '<Port>'.$user->portFtp().'</Port>'.
            '<Protocol>0</Protocol>'.
            '<Type>0</Type>'.
            '<User>'.$userName.'</User>'.
            '<Pass>'.$passwd.'</Pass>'.
            '<Logontype>1</Logontype>'.
            '<TimezoneOffset>0</TimezoneOffset>'.
            '<PasvMode>MODE_DEFAULT</PasvMode>'.
            '<MaximumMultipleConnections>0</MaximumMultipleConnections>'.
            '<EncodingType>UTF-8</EncodingType>'.
            '<BypassProxy>0</BypassProxy>'.
            '<Name>'.$title_seedbox.' ftp</Name>'.
            '<Comments />'.
            '<LocalDir />'.
            '<RemoteDir />'.
            '<SyncBrowsing>0</SyncBrowsing>'.$title_seedbox.' ftp&#x0A;'.
        '</Server>'.
        '<Server>'.
            '<Host>'.$host.'</Host>'.
            '<Port>'.$user->portSftp().'</Port>'.
            '<Protocol>1</Protocol>'.
            '<Type>0</Type>'.
            '<User>'.$userName.'</User>'.
            '<Pass>'.$passwd.'</Pass>'.
            '<Logontype>1</Logontype>'.
            '<TimezoneOffset>0</TimezoneOffset>'.
            '<PasvMode>MODE_DEFAULT</PasvMode>'.
            '<MaximumMultipleConnections>0</MaximumMultipleConnections>'.
            '<EncodingType>Auto</EncodingType>'.
            '<BypassProxy>0</BypassProxy>'.
            '<Name>'.$title_seedbox.' sftp</Name>'.
            '<Comments />'.
            '<LocalDir />'.
            '<RemoteDir />'.
            '<SyncBrowsing>0</SyncBrowsing>'.$title_seedbox.' sftp&#x0A;'.
        '</Server>'.
    '</Servers>'.
'</FileZilla3>';

if ( $chmodFolderUser && isset($_GET['file']) && $_GET['file'] == 'transdroid' )
    Server::FileDownload('settings.json', $conf_json_trandroid, $userName);
elseif ( $chmodFolderUser && isset($_GET['file']) && $_GET['file'] == 'filezilla' )
    Server::FileDownload('filezilla.xml', $conf_xml_filezilla, $userName);
else
    echo 'Le fichier demand&eacute; n\'existe pas.';
