<?php

class Update
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
    protected $is_owner;

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
        $this->currentPath      = getcwd();
    }

    public function update_file_config(array $data_upgrade, $conf_user_folder)
    {
        
        // mettre en place un système édition admin
        /* choisir en admin : 8 champs/inputs
        
        1. 'user_directory' => $this->directory
        2. 'url_rutorrent' => $this->rutorrentUrl
        3. 'active_cakebox' => $this->cakeboxActiveUrl
        4. 'url_cakebox' => $this->cakeboxUrl
        5. 'port_ftp' => $this->portFtp
        6. 'port_sftp' => $this->portSftp
        7. 'adresse_mail' => $this->supportMail
        8. 'realm' => $this->realmWebServer

        */

        $content = array( 
            'user' => array(
                'active_bloc_info' => isset($data_upgrade['simple_conf_user']) ? isset($data_upgrade['active_bloc_info']) ? true:false:$this->blocInfo,
                'user_directory' => $this->directory,
                'owner' => $this->is_owner
            ),
            'nav' => array(
                'url_rutorrent' => $this->rutorrentUrl,
                'active_cakebox' => $this->cakeboxActiveUrl,
                'url_cakebox' => $this->cakeboxUrl
            ),
            'ftp' => array(
                'active_ftp' => isset($data_upgrade['simple_conf_user']) ? isset($data_upgrade['active_ftp']) ? true:false:$this->blocFtp,
                'port_ftp' => $this->portFtp,
                'port_sftp' => $this->portSftp
            ),
            'rtorrent' => array(
                'active_reboot' => isset($data_upgrade['simple_conf_user']) ? isset($data_upgrade['active_reboot']) ? true:false:$this->blocRtorrent
            ),
            'support' => array(
                'active_support' => isset($data_upgrade['simple_conf_user']) ? isset($data_upgrade['active_support']) ? true:false:$this->blocSupport,
                'adresse_mail' => $this->supportMail
            ),
            'logout' => array(
                'realm' => $this->realmWebServer,
                'url_redirect' => isset($data_upgrade['simple_conf_user']) ? $data_upgrade['url_redirect']:$this->url_redirect
            )
        );

        $log = array();
   
        $log[0] = $this->write_ini_file($content, $conf_user_folder.'/config.ini');
        if (empty($log[0])) unset($log[0]);

        return $log;
    }

    public function write_ini_file(array $data_array, $file_path)
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

        if ( false === @file_put_contents ($file_path , $file_content) )
            $error = 'Une erreur est survenue lors de l\'écriture dans le fichier config.ini';

        return $error;
    }
}
