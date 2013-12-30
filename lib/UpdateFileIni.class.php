<?php

class UpdateFileIni extends Users
{
    public function update_file_config(array $data_upgrade, $conf_user_folder)
    {
        $content = array( 
            'user' => array(
                'active_bloc_info' => !isset($data_upgrade['simple_conf_user']) ? $this->blocInfo:
                                       isset($data_upgrade['active_bloc_info']) ? true:false,
                'user_directory' => isset($data_upgrade['owner_change_config']) ? $data_upgrade['user_directory']:$this->directory,
                'owner' => $this->is_owner
            ),
            'nav' => array(
                'active_rutorrent' => !isset($data_upgrade['owner_change_config']) ? $this->rutorrentActiveUrl:
                                       isset($data_upgrade['active_rutorrent']) ? true:false,
                'url_rutorrent' => isset($data_upgrade['owner_change_config']) ? $data_upgrade['url_rutorrent']:$this->rutorrentUrl,
                'active_cakebox' => !isset($data_upgrade['owner_change_config']) ? $this->cakeboxActiveUrl:
                                     isset($data_upgrade['active_cakebox']) ? true:false,
                'url_cakebox' => isset($data_upgrade['owner_change_config']) ? $data_upgrade['url_cakebox']:$this->cakeboxUrl
            ),
            'ftp' => array(
                'active_ftp' => !isset($data_upgrade['simple_conf_user']) ? $this->blocFtp:
                                 isset($data_upgrade['active_ftp']) ? true:false,
                'port_ftp' => isset($data_upgrade['owner_change_config']) ? (int) $data_upgrade['port_ftp']:$this->portFtp,
                'port_sftp' => isset($data_upgrade['owner_change_config']) ? (int) $data_upgrade['port_sftp']:$this->portSftp
            ),
            'rtorrent' => array(
                'active_reboot' => !isset($data_upgrade['simple_conf_user']) ? $this->blocRtorrent:
                                    isset($data_upgrade['active_reboot']) ? true:false
            ),
            'support' => array(
                'active_support' => !isset($data_upgrade['simple_conf_user']) ? $this->blocSupport:
                                     isset($data_upgrade['active_support']) ? true:false,
                'adresse_mail' => isset($data_upgrade['owner_change_config']) ? $data_upgrade['adresse_mail']:$this->supportMail
            ),
            'logout' => array(
                'url_redirect' => isset($data_upgrade['simple_conf_user']) ? $data_upgrade['url_redirect']:$this->url_redirect
            )
        );

        $log[0] = self::write_ini_file($content, $conf_user_folder.'/config.ini');
        if (empty($log[0]))
            unset($log[0]);

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
