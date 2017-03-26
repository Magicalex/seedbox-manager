<?php

namespace App\Seedbox;

class Users
{
    public $username;
    public $logoutUrl;
    public $directory;
    public $navbarLinks;
    public $portFtp;
    public $portSftp;
    public $scgi_folder;
    public $supportMail;
    public $enableInfo;
    public $enableRtorrent;
    public $enableFtp;
    public $isAdmin;
    public $theme;
    public $language;

    protected $currentPath;

    public function __construct($file_ini, $user)
    {
        $setting_user_array = parse_ini_file($file_ini, true, INI_SCANNER_TYPED);
        $this->username = $user;
        $this->hydrate($setting_user_array);
    }

    private function hydrate(array $array)
    {
        $this->navbarLinks = (string) $array['nav']['data_link'];
        $this->enableInfo = (bool) $array['user']['active_bloc_info'];
        $this->isAdmin = (bool) $array['user']['admin'];
        $this->enableFtp = (bool) $array['ftp']['active_ftp'];
        $this->enableRtorrent = (bool) $array['rtorrent']['active_reboot'];
        $this->directory = (string) $array['user']['user_directory'];
        $this->scgi_folder = (string) $array['user']['scgi_folder'];
        $this->theme = (string) $array['user']['theme'];
        $this->language = (string) $array['user']['language'];
        $this->supportMail = (string) $array['support']['adresse_mail'];
        $this->logoutUrl = (string) $array['logout']['url'];
        $this->portFtp = (int) $array['ftp']['port_ftp'];
        $this->portSftp = (int) $array['ftp']['port_sftp'];
        $this->currentPath = getcwd();
    }

    private static function convertFileSize($octets)
    {
        $unit = [
            'octet',
            'kilo_octet',
            'mega_octet',
            'giga_octet',
            'tera_octet',
            'peta_octet'
        ];
        for ($i = 0; $octets >= 1024; $i++) {
            $octets = $octets / 1024;
        }

        return [
            'octets' => round($octets, 2),
            'unit' => $unit[$i]
        ];
    }

    public function userdisk()
    {
        $total_disk = disk_total_space($this->directory);
        $used_disk = $total_disk - disk_free_space($this->directory);
        $percentage_used = round(($used_disk * 100) / $total_disk, 2);
        $free_disk = self::convertFileSize($total_disk - $used_disk);
        $used_disk = self::convertFileSize($used_disk);
        $total_disk = self::convertFileSize($total_disk);

        return [
            'used_disk' => $used_disk,
            'free_disk' => $free_disk,
            'total_disk' => $total_disk,
            'percentage_used' => $percentage_used
        ];
    }

    public function rebootRtorrent($irssi = false)
    {
        if ($irssi === true) {
            $command = "{$this->currentPath}/reboot-rtorrent {$this->username} irssi 2>&1";
        } else {
            $command = "{$this->currentPath}/reboot-rtorrent {$this->username} 2>&1";
        }
        exec($command, $log, $status);
        file_put_contents("{$this->currentPath}/conf/users/{$this->username}/data_reboot.txt", time());

        return [
            'command' => $command,
            'logReboot' => $log,
            'statusReboot' => $status
        ];
    }

    public function get_all_links()
    {
        $data_links = $this->navbarLinks;
        $data_links = preg_split("/\n/", $data_links);
        for ($i = 0; isset($data_links[$i]); $i++) {
            $array_link[] = preg_split("/\, /", $data_links[$i]);
            foreach ($array_link[$i] as $value) {
                $match_url = (bool) preg_match('#url =#', $value);
                $match_name = (bool) preg_match('#name =#', $value);
                if ($match_url === true) {
                    $value = preg_replace('#url =#', '', $value);
                    $value = trim($value);
                    $data_url = ['url' => $value];
                }
                if ($match_name === true) {
                    $value = preg_replace('#name =#', '', $value);
                    $value = trim($value);
                    $data_name = ['name' => $value];
                }
            }
            if (is_array(@$data_name) && is_array(@$data_url)) {
                $all_links[] = array_merge($data_name, $data_url);
                unset($data_name, $data_url);
            }
        }

        return isset($all_links) ? $all_links : null;
    }
}
