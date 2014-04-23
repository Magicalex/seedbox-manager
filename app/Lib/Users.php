<?php

namespace app\Lib;

class Users
{
    protected $userName;
    protected $url_redirect;
    protected $directory;
    protected $rutorrentActiveUrl;
    protected $rutorrentUrl;
    protected $cakeboxActiveUrl;
    protected $cakeboxUrl;
    protected $portFtp;
    protected $portSftp;
    protected $blocSupport;
    protected $supportMail;
    protected $currentPath;
    protected $blocInfo;
    protected $blocRtorrent;
    protected $blocFtp;
    protected $is_owner;

    public function __construct($file_ini, $user)
    {
        $setting_user_array = parse_ini_file($file_ini, true);
        $this->userName = $user;
        $this->hydrate($setting_user_array);
    }

    private function hydrate(array $array)
    {
        $this->cakeboxActiveUrl   = (bool) $array['nav']['active_cakebox'];
        $this->rutorrentActiveUrl = (bool) $array['nav']['active_rutorrent'];
        $this->blocInfo           = (bool) $array['user']['active_bloc_info'];
        $this->is_owner           = (bool) $array['user']['owner'];
        $this->blocFtp            = (bool) $array['ftp']['active_ftp'];
        $this->blocRtorrent       = (bool) $array['rtorrent']['active_reboot'];
        $this->blocSupport        = (bool) $array['support']['active_support'];
        $this->directory          = (string) $array['user']['user_directory'];
        $this->scgi_folder        = (string) $array['user']['scgi_folder'];
        $this->theme              = (string) $array['user']['theme'];
        $this->rutorrentUrl       = (string) $array['nav']['url_rutorrent'];
        $this->cakeboxUrl         = (string) $array['nav']['url_cakebox'];
        $this->supportMail        = (string) $array['support']['adresse_mail'];
        $this->url_redirect       = (string) $array['logout']['url_redirect'];
        $this->portFtp            = (int) $array['ftp']['port_ftp'];
        $this->portSftp           = (int) $array['ftp']['port_sftp'];
        $this->currentPath        = getcwd();
    }

    private static function convertFileSize($octets)
    {
        $unit = array('O','Ko','Mo','Go','To','Po','Eo');
        for ($i=0; $octets >= 1024; $i++) {
            $octets = $octets / 1024;
        }
        $result = round($octets, 2).' '.$unit[$i];
        return $result;
    }

    public function userdisk()
    {
        $total_disk = disk_total_space($this->directory);
        $used_disk = $total_disk - disk_free_space($this->directory);
        $percentage_used = round(($used_disk*100)/$total_disk, 2);
        $used_disk = self::convertFileSize($used_disk);
        $total_disk = self::convertFileSize($total_disk);
        if ( $percentage_used < 85 )
            $progressBarColor = null;
        elseif ( $percentage_used < 95 )
            $progressBarColor = 'progress-bar-warning';
        elseif ( $percentage_used >= 95 )
            $progressBarColor = 'progress-bar-danger';

        return array( 'used_disk' => $used_disk,
                      'total_disk' => $total_disk,
                      'percentage_used' => $percentage_used,
                      'progessBarColor' => $progressBarColor );
    }

    public function rebootRtorrent()
    {
        exec( $this->currentPath.'/../reboot-rtorrent '.$this->userName.' 2>&1', $log, $status);
        $date_updated = date('d/m/y \à H\hi');
        file_put_contents('./../conf/users/'.$this->userName.'/data_reboot.txt', $date_updated);

        return array( 'logReboot' => $log,
                      'statusReboot' => $status );
    }

    public static function readFileDataReboot($file)
    {
        if (file_exists($file))
        {
            $date_reboot_rtorrent = file_get_contents($file);
            $exist_reboot_rtorrent = true;
        }
        else
        {
            $exist_reboot_rtorrent = false;
            $date_reboot_rtorrent = null;
        }

        return array( 'read_file' => $date_reboot_rtorrent,
                      'file_exist' => $exist_reboot_rtorrent );
    }

    /*
        Retourne la liste de tous les users.
    */

    public static function get_all_users()
    {
        $scan = scandir('./../conf/users/');
        foreach ($scan as $file_name)
        {
            if ($file_name != '.' && $file_name != '..' && is_dir('./../conf/users/'.$file_name))
                $all_users[] = $file_name;
        }

        return $all_users;
    }

    /*
        Méthode pour supprimer le dossier et ses fichiers du user voulu par l'admin.
    */

    public static function delete_config_old_user($path_conf_user)
    {
        $scan = scandir($path_conf_user);
        foreach ($scan as $file_name)
        {
            if ($file_name != '.' && $file_name != '..')
            {
                $delete_file_log = @unlink($path_conf_user.'/'.$file_name);
                if ($delete_file_log === true)
                    $log[] = 'Le fichier '.$file_name.' a été supprimé.';
                else
                    $error[] = 'Impossible de supprimer le fichier '.$file_name.'.';
            }
        }

        $delete_folder_log = @rmdir($path_conf_user);
        if ($delete_folder_log === true)
            $log[] = 'Le dossier '.$path_conf_user.'/ a été supprimé.';
        else
            $error[] = 'Impossible de supprimer le dossier '.$path_conf_user.'.';

        return array( 'log' => @$log, 'error' => @$error);
    }

    /* retourne la liste de tous les thèmes */

    public static function get_all_themes()
    {
        $scan = scandir('./themes/');
        foreach ($scan as $folder_name)
        {
            if ($folder_name != '.' && $folder_name != '..' && is_dir('./themes/'.$folder_name))
                $all_themes[] = $folder_name;
        }

        return $all_themes;
    }

    public function url_redirect() { return $this->url_redirect; }
    public function rutorrentActiveUrl() { return $this->rutorrentActiveUrl; }
    public function rutorrentUrl() { return $this->rutorrentUrl; }
    public function cakeboxActiveUrl() { return $this->cakeboxActiveUrl; }
    public function cakeboxUrl() { return $this->cakeboxUrl; }
    public function portFtp() { return $this->portFtp; }
    public function portSftp() { return $this->portSftp; }
    public function blocSupport() { return $this->blocSupport; }
    public function supportMail() { return $this->supportMail; }
    public function currentPath() { return $this->currentPath; }
    public function blocInfo() { return $this->blocInfo; }
    public function blocFtp() { return $this->blocFtp; }
    public function blocRtorrent() { return $this->blocRtorrent; }
    public function is_owner() { return $this->is_owner; }
    public function user_directory() { return $this->directory; }
    public function scgi_folder() { return $this->scgi_folder; }
    public function theme() { return $this->theme; }
}
