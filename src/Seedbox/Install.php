<?php

namespace App\Seedbox;

class Install
{
    public static function get_user_php()
    {
        $uid_user_php = posix_geteuid();
        $name_user_php = posix_getpwuid($uid_user_php);

        return ['name' => $name_user_php['name'], 'num_uid' => $uid_user_php];
    }

    public static function check_uid_file($path_file)
    {
        if (false === file_exists($path_file)) {
            return false;
        } else {
            $uid = fileowner($path_file);

            return $uid;
        }
    }

    public static function getChmod($file, $precision)
    {
        if (false === file_exists($file)) {
            return false;
        } else {
            $precision = $precision * -1;
            $chmod = substr(sprintf('%o', fileperms($file)), $precision);

            return $chmod;
        }
    }

    public static function create_new_user($username)
    {
        $result = mkdir(__DIR__."/../../conf/users/{$username}", 0755, false);

        if (file_exists(__DIR__."/../../conf/users/{$username}")) {
            copy(__DIR__.'/../../conf/config.ini', __DIR__."/../../conf/users/{$username}/config.ini");
        }

        return $result;
    }
}
