<?php

namespace App\Seedbox;

class Utils
{
    public static function getCurrentUser()
    {
        if (isset($_SERVER['REMOTE_USER']) || isset($_SERVER['PHP_AUTH_USER'])) {
            return $username = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : $_SERVER['REMOTE_USER'];
        }

        return 'alexandre';
    }

    public static function getFileini($username)
    {
        return __DIR__."/../../conf/users/{$username}/config.ini";
    }
}
