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

    public function update_file_config(array $data_upgrade, $conf_user_folder)
    {
        $content = array( 
            'user' => array(
                'active_bloc_info' => isset($data_upgrade['blocinfo']) ? true:false,
                'user_directory' => $this->directory
            ),
            'nav' => array(
                'url_rutorrent' => $this->rutorrentUrl,
                'active_cakebox' => $this->cakeboxActiveUrl,
                'url_cakebox' => $this->cakeboxUrl
            ),
            'ftp' => array(
                'active_ftp' => isset($data_upgrade['blocftp']) ? true:false,
                'port_ftp' => $this->portFtp,
                'port_sftp' => $this->portSftp
            ),
            'rtorrent' => array(
                'active_reboot' => isset($data_upgrade['blocrtorrent']) ? true:false
            ),
            'support' => array(
                'active_support' => isset($data_upgrade['blocsupport']) ? true:false,
                'adresse_mail' => $this->supportMail
            ),
            'logout' => array(
                'realm' => $this->realmWebServer,
                'url_redirect' => $data_upgrade['url_redirect']
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
