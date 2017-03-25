<?php

namespace App\Seedbox;

class Utils
{
    public static function getCurrentUser()
    {
        if (isset($_SERVER['REMOTE_USER']) || isset($_SERVER['PHP_AUTH_USER'])) {
            return isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : $_SERVER['REMOTE_USER'];
        } else {
            return 'username';
        }
    }

    public static function getFileini($username)
    {
        return __DIR__."/../../conf/users/{$username}/config.ini";
    }

    public static function readFileDataReboot($file)
    {
        if (file_exists($file)) {
            $date_reboot_rtorrent = file_get_contents($file);
            $exist_reboot_rtorrent = true;
        } else {
            $exist_reboot_rtorrent = false;
            $date_reboot_rtorrent = null;
        }

        return [
            'read_file' => $date_reboot_rtorrent,
            'file_exist' => $exist_reboot_rtorrent
        ];
    }

    public static function get_all_users()
    {
        $scan = scandir(__DIR__.'/../../conf/users/');
        foreach ($scan as $file_name) {
            if ($file_name != '.' && $file_name != '..' && is_dir(__DIR__."/../../conf/users/{$file_name}")) {
                $all_users[] = $file_name;
            }
        }

        return $all_users;
    }

    public static function delete_config_old_user($path_conf_user)
    {
        $scan = scandir($path_conf_user);
        foreach ($scan as $file_name) {
            if ($file_name != '.' && $file_name != '..') {
                $delete_file_log = @unlink($path_conf_user.'/'.$file_name);
                if ($delete_file_log === false) {
                    return false;
                }
            }
        }

        $delete_folder_log = @rmdir($path_conf_user);
        if ($delete_folder_log === false) {
            return false;
        }

        return true;
    }

    public static function get_all_themes()
    {
        $scan = scandir(__DIR__.'/../../themes');
        foreach ($scan as $folder_name) {
            if ($folder_name != '.' && $folder_name != '..' && is_dir(__DIR__."/../../themes/{$folder_name}")) {
                $all_themes[] = $folder_name;
            }
        }

        return $all_themes;
    }

    public static function get_all_languages()
    {
        $scan = scandir(__DIR__.'/../../locale');
        foreach ($scan as $file_name) {
            if ($file_name != '.' && $file_name != '..' && is_file(__DIR__."/../../locale/{$file_name}")) {
                $var = explode('.', $file_name);
                $languages[] = $var[1];
            }
        }

        return $languages;
    }
}
