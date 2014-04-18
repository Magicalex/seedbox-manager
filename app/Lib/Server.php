<?php

namespace app\Lib;

class Server extends Users
{
    
    public static function getUptime()
    {
        $fd = fopen('/proc/uptime', 'r');
        $ar_buf = split(' ', fgets($fd, 4096));
        fclose($fd);
        $sys_ticks = trim($ar_buf[0]);

        $min   = $sys_ticks / 60;
        $hours = $min / 60;
        $days  = floor($hours / 24);
        $hours = floor($hours - ($days * 24));
        $min   = floor($min - ($days * 60 * 24) - ($hours * 60));

        $result = '';
        if ($days != 0) $result = $days.' jours et ';
        if ($hours != 0) $result .= $hours.' h ';
        $result .= $min.' min';

        return $result;
    }

    public static function load_average()
    {
        $load_average = sys_getloadavg();
        if ($load_average[0] < 5)
            $info_charge = '<em class="text-success">Charge faible, conditions optimales.</em>';
        elseif ($load_average[0] < 10)
            $info_charge = '<em class="text-warning">Charge élévée, risque de ralentissement sur le serveur.</em>';
        else
            $info_charge = '<em class="text-danger">Charge très élévée, risque de gros ralentissement sur le serveur.</em>';

        return array( 'load_average' => $load_average,
                      'info_charge' => $info_charge );
    }

    public function logout()
    {
        header('HTTP/1.1 401 Unauthorized');

        usleep(500000); /*sleep(1); */ /* usleep(500000) = 1/2 sec ~ 500 ms */
        echo '<script>document.location.href = \''.$this->url_redirect.'\'</script>';
        exit();
    }

    public function FileDownload($file_config_name, $conf_ext_prog)
    {
        file_put_contents('./conf/users/'.$this->userName.'/'.$file_config_name, $conf_ext_prog);

        set_time_limit(0);
        $path_file_name = './conf/users/'.$this->userName.'/'.$file_config_name;
        $file_name = $file_config_name;
        $file_size = filesize($path_file_name);

        ini_set('zlib.output_compression', 0);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$file_name.'"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: '.$file_size);
        ob_clean();
        flush();
        readfile($path_file_name);

        //delete file config (transdroid|filezilla) for security.
        unlink('./conf/users/'.$this->userName.'/'.$file_config_name);
        exit;
    }
}
