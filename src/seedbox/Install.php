<?php

namespace Seedbox;

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
        $log = mkdir('../conf/users/' . $username, 0755, false);
        if (true === $log) {
            $log = 'Dossier de configuration crée avec succès';
        }
        if (file_exists('../conf/users/' . $username)) {
            copy('../conf/config.ini', '../conf/users/' . $username . '/config.ini');
        }
        return $log;
    }
}
