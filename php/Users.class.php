<?php

class Users
{
    private $_userName;
    private $_controlUser;
    private $_directory;
    private $_rutorrentUrl;
    private $_cakeboxActiveUrl;
    private $_cakeboxUrl;
    private $_portFtp;
    private $_portSftp;
    private $_blocSupport;
    private $_supportMail;
    private $_realmWebServer;
    private $_currentPath;

    public function __construct()
    {
        if ( isset($_SERVER['REMOTE_USER']) || isset($_SERVER['PHP_AUTH_USER']) )
        {
            $this->_userName = isset($_SERVER['REMOTE_USER']) ? $_SERVER['REMOTE_USER']:$_SERVER['PHP_AUTH_USER'];
            $this->_controlUser = true;
        }
        else
        {
            $this->_userName = 'Aucun user';
            $this->_controlUser = false;
        }

        if (file_exists('./conf/users/'.$this->_userName.'/config.ini'))
            $fileIni = './conf/users/'.$this->_userName.'/config.ini';
        elseif (file_exists('../conf/users/'.$this->_userName.'/config.ini'))
            $fileIni = '../conf/users/'.$this->_userName.'/config.ini';
        elseif (file_exists('./conf/config.ini'))
            $fileIni = './conf/config.ini';
        else
            $fileIni = '../conf/config.ini';

        $setting_array           = parse_ini_file($fileIni, true);
        $this->_directory        = $setting_array['user']['user_directory'];
        $this->_rutorrentUrl     = $setting_array['rutorrent']['url'];
        $this->_cakeboxActiveUrl = $setting_array['cakebox']['active_cakebox'];
        $this->_cakeboxUrl       = $setting_array['cakebox']['url'];
        $this->_portFtp          = $setting_array['ftp']['port_ftp'];
        $this->_portSftp         = $setting_array['ftp']['port_sftp'];
        $this->_blocSupport      = $setting_array['support']['active_support'];
        $this->_supportMail      = $setting_array['support']['adresse_mail'];
        $this->_realmWebServer   = $setting_array['logout']['realm'];
        $this->_currentPath      = getcwd();
    }

    private static function convertFileSize($octets)
    {
        $types = array('O','Ko','Mo','Go','To');
        for( $i = 0; $octets >= 1024 && $i < ( count( $types ) -1 ); $octets /= 1024, $i++ );

        return( round($octets, 2).' '.$types[$i] );
    }

    public function userdisk()
    {
        $total_disk = disk_total_space($this->_directory);
        $used_disk = $total_disk - disk_free_space($this->_directory);
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
        exec( $this->_currentPath.'/reboot-rtorrent '.$this->_userName, $log, $status);
        $date_updated = date('d/m/y \à H\hi');
        $enter_data_reboot = fopen('./conf/users/'.$this->_userName.'/data_reboot.txt', 'a+');
        ftruncate($enter_data_reboot,0);
        fputs($enter_data_reboot, $date_updated);
        fclose($enter_data_reboot);

        return array( 'logReboot' => $log,
                      'statusReboot' => $status );
    }

    public function readFileDataReboot()
    {
        $data_reboot = fopen('./conf/users/'.$this->_userName.'/data_reboot.txt', 'r');
        $date_reboot_rtorrent = fgets($data_reboot);
        fclose($data_reboot);
        $exist_reboot_rtorrent = true;

        return array( 'read_file' => $date_reboot_rtorrent,
                      'file_exist' => $exist_reboot_rtorrent );
    }

    public function userName() { return $this->_userName; }
    public function controlUser() { return $this->_controlUser; }
    public function rutorrentUrl() { return $this->_rutorrentUrl; }
    public function cakeboxActiveUrl() { return $this->_cakeboxActiveUrl; }
    public function cakeboxUrl() { return $this->_cakeboxUrl; }
    public function portFtp() { return $this->_portFtp; }
    public function portSftp() { return $this->_portSftp; }
    public function blocSupport() { return $this->_blocSupport; }
    public function supportMail() { return $this->_supportMail; }
    public function realmWebServer() { return $this->_realmWebServer; }
    public function currentPath() { return $this->_currentPath; }
}