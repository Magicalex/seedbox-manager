<?php

class Users
{
    protected $userName;
    protected $url_redirect;
    protected $realmWebServer;
    protected $directory;
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

    public function __construct($file_ini, $user)
    {
        $setting_user_array = parse_ini_file($file_ini, true);
        $this->userName = $user;
        $this->hydrate($setting_user_array);
    }

    public function hydrate(array $array)
    {
        $this->cakeboxActiveUrl = (bool) $array['nav']['active_cakebox'];
        $this->blocInfo         = (bool) $array['user']['active_bloc_info'];
        $this->blocFtp          = (bool) $array['ftp']['active_ftp'];
        $this->blocRtorrent     = (bool) $array['rtorrent']['active_reboot'];
        $this->blocSupport      = (bool) $array['support']['active_support'];

        $this->directory        = (string) $array['user']['user_directory'];
        $this->rutorrentUrl     = (string) $array['nav']['url_rutorrent'];
        $this->cakeboxUrl       = (string) $array['nav']['url_cakebox'];
        $this->supportMail      = (string) $array['support']['adresse_mail'];
        $this->realmWebServer   = (string) $array['logout']['realm'];
        $this->url_redirect     = (string) $array['logout']['url_redirect'];

        $this->portFtp          = (int) $array['ftp']['port_ftp'];
        $this->portSftp         = (int) $array['ftp']['port_sftp'];
        $this->currentPath      = getcwd();
    }

    private static function convertFileSize($octets)
    {
        $types = array('O','Ko','Mo','Go','To');
        for( $i = 0; $octets >= 1024 && $i < ( count( $types ) -1 ); $octets /= 1024, $i++ );

        return( round($octets, 2).' '.$types[$i] );
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
        exec( $this->currentPath.'/reboot-rtorrent '.$this->userName, $log, $status);
        $date_updated = date('d/m/y \à H\hi');
        file_put_contents('./conf/users/'.$this->userName.'/data_reboot.txt', $date_updated);

        return array( 'logReboot' => $log,
                      'statusReboot' => $status );
    }

    public function readFileDataReboot($file)
    {
        if (file_exists($file))
        {
            $data_reboot = fopen($file, 'r');
            $date_reboot_rtorrent = fgets($data_reboot);
            fclose($data_reboot);
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

    public function url_redirect() { return $this->url_redirect; }
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
}
