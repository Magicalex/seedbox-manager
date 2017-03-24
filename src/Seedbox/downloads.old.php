<?php

use \Seedbox\Users;
use \Seedbox\Server;

$title_seedbox = 'seedbox-'.$userName;
$user = new Users($file_user_ini, $userName);
$serveur = new Server($file_user_ini, $userName);
$passwd = isset($_SERVER['PHP_AUTH_PW']) ? $_SERVER['PHP_AUTH_PW']:null;
$passwd = htmlspecialchars($passwd, ENT_NOQUOTES);

// setting transdroid
$tableau_conf_trandroid = [
    'ui_swipe_labels' => false,
    'alarm_vibrate' => false,
    'alarm_enabled' => false,
    'alarm_check_rss_feeds' => false,
    'websites' => [],
    'ui_refresh_interval' => '60',
    'ui_hide_refresh' => false,
    'search_sort_by' => 'sort_seeders',
    'alarm_play_sound' => false,
    'ui_enable_ads' => true,
    'ui_only_show_transferring' => false,
    'search_num_results' => '25',
    'servers' => [[
        'port' => '443',
        'host' => $_SERVER['HTTP_HOST'],
        'ssl' => true,
        'type' => 'daemon_rtorrent',
        'password' => $passwd,
        'os_type' => 'type_linux',
        'folder' => $user->scgi_folder(),
        'username' => $userName,
        'use_auth' => true,
        'name' => $title_seedbox,
        'base_ftp_url' => 'ftp://'.$userName.'@'.$_SERVER['HTTP_HOST'].'/torrents/',
        'download_alarm' => true,
        'new_torrent_alarm' => true,
        'ssl_accept_all' => true
    ]],
    'alarm_interval' => '600000',
    'rssfeeds' => [],
    'ui_ask_before_remove' => true
];

$conf_json_trandroid = json_encode($tableau_conf_trandroid, JSON_PRETTY_PRINT);

// setting filezilla
$conf_xml_filezilla = '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>'."\n".
'<FileZilla3>'."\n".
    '<Servers>'."\n".
        '<Server>'."\n".
            '<Host>'.$_SERVER['HTTP_HOST'].'</Host>'."\n".
            '<Port>'.$user->portFtp().'</Port>'."\n".
            '<Protocol>0</Protocol>'."\n".
            '<Type>0</Type>'."\n".
            '<User>'.$userName.'</User>'."\n".
            '<Pass>'.$passwd.'</Pass>'."\n".
            '<Logontype>1</Logontype>'."\n".
            '<TimezoneOffset>0</TimezoneOffset>'."\n".
            '<PasvMode>MODE_DEFAULT</PasvMode>'."\n".
            '<MaximumMultipleConnections>0</MaximumMultipleConnections>'."\n".
            '<EncodingType>UTF-8</EncodingType>'."\n".
            '<BypassProxy>0</BypassProxy>'."\n".
            '<Name>'.$title_seedbox.'-ftp</Name>'."\n".
            '<Comments />'."\n".
            '<LocalDir />'."\n".
            '<RemoteDir />'."\n".
            '<SyncBrowsing>0</SyncBrowsing>'.$title_seedbox.'-ftp&#x0A;'."\n".
        '</Server>'."\n".
        '<Server>'."\n".
            '<Host>'.$_SERVER['HTTP_HOST'].'</Host>'."\n".
            '<Port>'.$user->portSftp().'</Port>'."\n".
            '<Protocol>1</Protocol>'."\n".
            '<Type>0</Type>'."\n".
            '<User>'.$userName.'</User>'."\n".
            '<Pass>'.$passwd.'</Pass>'."\n".
            '<Logontype>1</Logontype>'."\n".
            '<TimezoneOffset>0</TimezoneOffset>'."\n".
            '<PasvMode>MODE_DEFAULT</PasvMode>'."\n".
            '<MaximumMultipleConnections>0</MaximumMultipleConnections>'."\n".
            '<EncodingType>Auto</EncodingType>'."\n".
            '<BypassProxy>0</BypassProxy>'."\n".
            '<Name>'.$title_seedbox.'-sftp</Name>'."\n".
            '<Comments />'."\n".
            '<LocalDir />'."\n".
            '<RemoteDir />'."\n".
            '<SyncBrowsing>0</SyncBrowsing>'.$title_seedbox.'-sftp&#x0A;'."\n".
        '</Server>'."\n".
    '</Servers>'."\n".
'</FileZilla3>';

if ($_GET['file'] == 'transdroid') {
    $serveur->FileDownload('settings.json', $conf_json_trandroid);
} elseif ($_GET['file'] == 'filezilla') {
    $serveur->FileDownload('filezilla.xml', $conf_xml_filezilla);
}
