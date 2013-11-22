<?php

class Users
{
    protected $userName;
    protected $url_redirect;
    protected $realmWebServer;
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

    public function hydrate(array $array)
    {
        $this->cakeboxActiveUrl   = (bool) $array['nav']['active_cakebox'];
        $this->rutorrentActiveUrl = (bool) $array['nav']['active_rutorrent'];
        $this->blocInfo           = (bool) $array['user']['active_bloc_info'];
        $this->is_owner           = (bool) $array['user']['owner'];
        $this->blocFtp            = (bool) $array['ftp']['active_ftp'];
        $this->blocRtorrent       = (bool) $array['rtorrent']['active_reboot'];
        $this->blocSupport        = (bool) $array['support']['active_support'];
        $this->directory          = (string) $array['user']['user_directory'];
        $this->rutorrentUrl       = (string) $array['nav']['url_rutorrent'];
        $this->cakeboxUrl         = (string) $array['nav']['url_cakebox'];
        $this->supportMail        = (string) $array['support']['adresse_mail'];
        $this->realmWebServer     = (string) $array['logout']['realm'];
        $this->url_redirect       = (string) $array['logout']['url_redirect'];
        $this->portFtp            = (int) $array['ftp']['port_ftp'];
        $this->portSftp           = (int) $array['ftp']['port_sftp'];
        $this->currentPath        = getcwd();
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

    public static function get_all_users()
    {
        $scan = scandir('./conf/users/');
        $i = 0;
        $all_users = array();
        foreach ($scan as $i => $file_name)
        {
            if ($file_name != '.' && $file_name != '..' && is_dir('./conf/users/'.$file_name))
                $all_users[$i] = $file_name;
        }

        return $all_users;
    }

    public static function delete_config_old_user($path_conf_user)
    {
        $scan = scandir($path_conf_user);
        $i=0;
        $log = array();
        foreach ($scan as $file_name)
        {
            if ($file_name != '.' && $file_name != '..')
            {
                $t = unlink($path_conf_user.'/'.$file_name);
                if ($t === true)
                    $log[] = 'Le fichier '.$file_name.' a été supprimé.';
                else
                    $log[] = 'Impossible de supprimer le fichier '.$file_name.'.';
            }
        }

        $s = rmdir($path_conf_user);
        if ( $s === true )
            $log[] = 'Le dossier '.$path_conf_user.'/ a été supprimé.';
        else
            $log[] = 'Impossible de supprimer le dossier '.$path_conf_user.'.';

        return $log;
    }
    
    public function support($message) 
    {
        $name = $this->userName;
        $message = htmlspecialchars($message);            
        $date = date("d/m/Y \à H:i:s");       
        
        if (file_exists('./conf/users/'.$this->userName.'/support.json'))
        {
            $supjson = './conf/users/'.$this->userName.'/support.json';
            $json = json_decode(file_get_contents($supjson));

            $json[] = array( 'datas' => array('user' => $name, 'date' => $date, 'message' => $message));
            $jsonencod = json_encode($json);
            file_put_contents('./conf/users/'.$this->userName.'/support.json', $jsonencod.PHP_EOL);       
        }
        else
        {
            $json = array ( array ('datas' => array( 'user' => $name, 'date' => $date, 'message' => $message)));
            $jsonencod = json_encode($json);
            file_put_contents('./conf/users/'.$this->userName.'/support.json', $jsonencod.PHP_EOL, FILE_APPEND);
        }
        
        if ( file_exists('./conf/users/'.$this->userName.'/support.json'))
        {
            return array( 'file_exist' => true);
        }
        else
        {
            return array( 'file_exist' => false);
        }
    }
    
    public function ticketList()
    {
        if ($this->is_owner() === true)
        {
            $scan = scandir('./conf/users/');
            $i = 0;
            $all_ticket = array();
            foreach ($scan as $i => $userDir)
            {
                if ($userDir != '.' && $userDir != '..' && is_dir('./conf/users/'.$userDir))
                {
                    $userConf = scandir('./conf/users/'.$userDir);
                    foreach ($userConf as $j => $numTicket) 
                    {
                        if ($numTicket != '.' && $numTicket != '..' && $numTicket != 'config.ini' && is_file('./conf/users/'.$userDir.'/'.$numTicket))
                        {
                            $all_ticket[$j] = './conf/users/'.$userDir.'/'.$numTicket;
                        }
                    }
                }
            }
            return $all_ticket; 
        }
        else
        {
        $scan = scandir('./conf/users/'.$this->userName);
        $i = 0;
        $all_ticket = array();
        foreach ($scan as $i => $ticket_number)
        {
            if ($ticket_number != '.' && $ticket_number != '..' && $ticket_number != 'config.ini' && is_file('./conf/users/'.$this->userName.'/'.$ticket_number))
                $all_ticket[$i] = './conf/users/'.$this->userName.'/'.$ticket_number;
        }
        return $all_ticket; 
        }

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
    public function realmWebServer() { return $this->realmWebServer; }
}
