<?php

namespace App\Seedbox;

class FileConfiguration
{
    public static function filezilla(Users $user, $host)
    {
        $filezilla_xml = '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>'."\n".
        '<FileZilla3>'."\n".
            '<Servers>'."\n".
                '<Server>'."\n".
                    '<Host>'.$host.'</Host>'."\n".
                    '<Port>'.$user->portFtp.'</Port>'."\n".
                    '<Protocol>0</Protocol>'."\n".
                    '<Type>0</Type>'."\n".
                    '<User>'.$user->username.'</User>'."\n".
                    '<Pass></Pass>'."\n".
                    '<Logontype>1</Logontype>'."\n".
                    '<TimezoneOffset>0</TimezoneOffset>'."\n".
                    '<PasvMode>MODE_DEFAULT</PasvMode>'."\n".
                    '<MaximumMultipleConnections>0</MaximumMultipleConnections>'."\n".
                    '<EncodingType>UTF-8</EncodingType>'."\n".
                    '<BypassProxy>0</BypassProxy>'."\n".
                    '<Name>seedbox-'.$user->username.'-ftp</Name>'."\n".
                    '<Comments />'."\n".
                    '<LocalDir />'."\n".
                    '<RemoteDir />'."\n".
                    '<SyncBrowsing>0</SyncBrowsing>seedbox-'.$user->username.'-ftp&#x0A;'."\n".
                '</Server>'."\n".
                '<Server>'."\n".
                    '<Host>'.$host.'</Host>'."\n".
                    '<Port>'.$user->portSftp.'</Port>'."\n".
                    '<Protocol>1</Protocol>'."\n".
                    '<Type>0</Type>'."\n".
                    '<User>'.$user->username.'</User>'."\n".
                    '<Pass></Pass>'."\n".
                    '<Logontype>1</Logontype>'."\n".
                    '<TimezoneOffset>0</TimezoneOffset>'."\n".
                    '<PasvMode>MODE_DEFAULT</PasvMode>'."\n".
                    '<MaximumMultipleConnections>0</MaximumMultipleConnections>'."\n".
                    '<EncodingType>Auto</EncodingType>'."\n".
                    '<BypassProxy>0</BypassProxy>'."\n".
                    '<Name>seedbox-'.$user->username.'-sftp</Name>'."\n".
                    '<Comments />'."\n".
                    '<LocalDir />'."\n".
                    '<RemoteDir />'."\n".
                    '<SyncBrowsing>0</SyncBrowsing>seedbox-'.$user->username.'-sftp&#x0A;'."\n".
                '</Server>'."\n".
            '</Servers>'."\n".
        '</FileZilla3>';

        return $filezilla_xml;
    }

    public static function transdroid(Users $user, $host)
    {
        $trandroid_data = [
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
                'host' => $host,
                'ssl' => true,
                'type' => 'daemon_rtorrent',
                'password' => '',
                'os_type' => 'type_linux',
                'folder' => $user->scgi_folder,
                'username' => $user->username,
                'use_auth' => true,
                'name' => 'seedbox-'.$user->username,
                'base_ftp_url' => 'ftp://'.$user->username.'@'.$host.'/torrents/',
                'download_alarm' => true,
                'new_torrent_alarm' => true,
                'ssl_accept_all' => true
            ]],
            'alarm_interval' => '600000',
            'rssfeeds' => [],
            'ui_ask_before_remove' => true
        ];

        return json_encode($trandroid_data, JSON_PRETTY_PRINT);
    }
}
