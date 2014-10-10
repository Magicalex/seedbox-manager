<?php

namespace app\Lib;

class Install
{
    public static function get_user_php()
    {
        $uid_user_php = posix_geteuid();
        $name_user_php = posix_getpwuid($uid_user_php);

        return array ( 'name' => $name_user_php['name'],
                       'num_uid' => $uid_user_php );
    }

    public static function check_uid_file($path_file)
    {
        if ( file_exists($path_file) === false )
            return false;
        else
        {
            $uid = fileowner($path_file);
            return $uid;
        }
    }

    public static function getChmod($file, $precision)
    {
        if ( file_exists($file) === false )
            return false;
        else
        {
            $precision = $precision * -1;
            $chmod = substr(sprintf('%o', fileperms($file)), $precision);
            return $chmod;
        }

    }

    public static function create_new_user($userName)
    {
        $log = mkdir('./../conf/users/'.$userName ,0755 ,false);
        if ($log === true) $log = 'Dossier de confifuration crée avec succès';

        if (file_exists('./../conf/users/'.$userName))
            copy('./../conf/config.ini' ,'./../conf/users/'.$userName.'/config.ini');

        return $log;
    }
}
