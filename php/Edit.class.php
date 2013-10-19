<?php

class Edit extends Server
{
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
        $log['function_write_ini_file'] = '';
        $log['bad_chmod_user_folder'] = '';
        $log['not_acces_file_ini'] = '';
        $log['not_exist_conf_user_folder'] = '';

        if ( file_exists($conf_user_folder) )
        {
            if ( $this->getChmod($conf_user_folder, 3) == 777 )
            {
                if (file_exists($conf_user_folder.'/config.ini'))
                {
                    if(is_writable($conf_user_folder.'/config.ini'))
                        $log['function_write_ini_file'] = $this->write_ini_file($content, $conf_user_folder.'/config.ini');
                    else
                        $log['not_acces_file_ini'] = 'L\'interface n\'a pas les droits sur le fichier config.ini pour mettre à jour la configuration.';
                }
                else
                    $log['function_write_ini_file'] = $this->write_ini_file($content, $conf_user_folder.'/config.ini');
            }
            else
                $log['bad_chmod_user_folder'] = 'Le chmod sur le dossier de configuration de l\'utilisateur '.$this->userName.' n\'est pas correcte.';
        }
        else
            $log['not_acces_file_ini'] = 'Le dossier de configuration de l\'utilisateur '.$this->userName.' n\'éxiste pas. Merci de le créer.';

        // destruction des éléments du tableau qui sont vide
        if ( empty($log['function_write_ini_file']) ) unset($log['function_write_ini_file']);
        if ( empty($log['bad_chmod_user_folder']) ) unset($log['bad_chmod_user_folder']);
        if ( empty($log['not_acces_file_ini']) ) unset($log['not_acces_file_ini']);
        if ( empty($log['not_exist_conf_user_folder']) ) unset($log['not_exist_conf_user_folder']);

        return $log;
    }

    public function write_ini_file(array $data_array, $file_path)
    {
        $file_content = '';
        $write_error = '';
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
            $write_error = 'Une erreur est survenue lors de l\'écriture dans le fichier config.ini : cause inconnu.';

        return $write_error;
    }
}
