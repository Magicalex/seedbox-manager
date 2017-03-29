<?php

namespace App\Seedbox;

class Server
{
    const VERSION = '3.0.1';

    public static function version()
    {
        return self::VERSION;
    }

    public static function getUptime()
    {
        $data_uptime = @file_get_contents('/proc/uptime');
        $data_uptime = explode(' ', $data_uptime);
        $data_uptime = trim($data_uptime[0]);

        $time = [];
        $time['min'] = $data_uptime / 60;
        $time['hours'] = $time['min'] / 60;
        $time['days'] = floor($time['hours'] / 24);
        $time['hours'] = floor($time['hours'] - $time['days'] * 24);
        $time['min'] = floor($time['min'] - $time['days'] * 60 * 24 - $time['hours'] * 60);

        return [
            'days' => $time['days'],
            'hours' => $time['hours'],
            'minutes' => $time['min']
        ];
    }

    public static function load_average()
    {
        $load_average = sys_getloadavg();
        for ($i = 0; isset($load_average[$i]); $i++) {
            $load_average[$i] = round($load_average[$i], 2);
        }

        return $load_average;
    }

    public static function CheckUpdate()
    {
        $lifetime_cookie = time() + 3600 * 24;
        if (!isset($_COOKIE['seedbox-manager'])) {
            setcookie('seedbox-manager', 'check-update', $lifetime_cookie, '/', null, false, true);
            $url_repository = 'https://raw.githubusercontent.com/Magicalex/seedbox-manager/master/version.json';
            $remote = json_decode(file_get_contents($url_repository));
            if (self::VERSION !== $remote->version) {
                return $remote;
            }
        }

        return false;
    }
}
