<?php

class Update
{
    private $url_redirect;
    private $realmWebServer;
    private $directory;
    private $rutorrentUrl;
    private $cakeboxActiveUrl;
    private $cakeboxUrl;
    private $portFtp;
    private $portSftp;
    private $blocSupport;
    private $supportMail;
    private $currentPath;
    private $blocInfo;
    private $blocRtorrent;
    private $blocFtp;
    private $is_owner;

    public function __construct($file_ini)
    {
        $setting_user_array = parse_ini_file($file_ini, true);
        $this->hydrate($setting_user_array);
    }

    public function hydrate(array $array)
    {
        $this->cakeboxActiveUrl = (bool) $array['nav']['active_cakebox'];
        $this->blocInfo         = (bool) $array['user']['active_bloc_info'];
        $this->is_owner         = (bool) $array['user']['owner'];
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
    }

    public function update_file_config(array $data_upgrade, $conf_user_folder)
    {

        if ( isset($data_upgrade['simple_conf_user']) )
        {
            if ( isset($data_upgrade['active_bloc_info']) )
                $this->blocInfo = true;
            else
                $this->blocInfo = false;
        }

        if ( isset($data_upgrade['owner_change_config']) )
            $this->directory = $data_upgrade['user_directory'];

        if ( isset($data_upgrade['owner_change_config']) )
            $this->rutorrentUrl = $data_upgrade['url_rutorrent'];

        if ( isset($data_upgrade['owner_change_config']) )
        {
            if ( isset($data_upgrade['active_cakebox']) )
                $this->cakeboxActiveUrl = true;
            else
                $this->cakeboxActiveUrl = false;
        }

        if ( isset($data_upgrade['owner_change_config']) )
            $this->cakeboxUrl = $data_upgrade['url_cakebox'];

        if ( isset($data_upgrade['simple_conf_user']) )
        {
            if ( isset($data_upgrade['active_ftp']) )
                $this->blocFtp = true;
            else
                $this->blocFtp = false;
        }

        if ( isset($data_upgrade['owner_change_config']) )
            $this->portFtp = (int) $data_upgrade['port_ftp'];

        if ( isset($data_upgrade['owner_change_config']) )
            $this->portSftp = (int) $data_upgrade['port_sftp'];

        if ( isset($data_upgrade['simple_conf_user']) )
        {
            if ( isset($data_upgrade['active_reboot']) )
                $this->blocRtorrent = true;
            else
                $this->blocRtorrent = false;
        }

        if ( isset($data_upgrade['simple_conf_user']) )
        {
            if ( isset($data_upgrade['active_support']) )
                $this->blocSupport = true;
            else
                $this->blocSupport = false;
        }

        if ( isset($data_upgrade['owner_change_config']) )
            $this->supportMail = $data_upgrade['adresse_mail'];

        if ( isset($data_upgrade['owner_change_config']) )
            $this->realmWebServer = $data_upgrade['realm'];

        if ( isset($data_upgrade['simple_conf_user']) )
            $this->url_redirect = $data_upgrade['url_redirect'];

        $content = array( 
            'user' => array(
                'active_bloc_info' => $this->blocInfo,
                'user_directory' => $this->directory,
                'owner' => $this->is_owner
            ),
            'nav' => array(
                'url_rutorrent' => $this->rutorrentUrl,
                'active_cakebox' => $this->cakeboxActiveUrl,
                'url_cakebox' => $this->cakeboxUrl
            ),
            'ftp' => array(
                'active_ftp' => $this->blocFtp,
                'port_ftp' => $this->portFtp,
                'port_sftp' => $this->portSftp
            ),
            'rtorrent' => array(
                'active_reboot' => $this->blocRtorrent
            ),
            'support' => array(
                'active_support' => $this->blocSupport,
                'adresse_mail' => $this->supportMail
            ),
            'logout' => array(
                'realm' => $this->realmWebServer,
                'url_redirect' => $this->url_redirect
            )
        );

        $log = array();   
        $log[0] = self::write_ini_file($content, $conf_user_folder.'/config.ini');
        if (empty($log[0])) unset($log[0]);

        return $log;
    }

    private static function write_ini_file(array $data_array, $file_path)
    {
        $file_content = '';
        $error = '';

        foreach($data_array as $key => $groupe_n)
        {
            $file_content .= '['.$key."]\n";
            foreach($groupe_n as $key => $item_n)
            {
                if ( $item_n == 1 )
                    $item_n = 'yes';
                elseif ( empty($item_n) )
                    $item_n = 'no';
                else
                    $item_n = '"'.$item_n.'"';
                $file_content .= $key.' = '.$item_n."\n";
            }
        }

        if ( false === @file_put_contents($file_path , $file_content) )
            $error = 'Une erreur est survenue lors de l\'écriture dans le fichier config.ini';

        return $error;
    }
}
