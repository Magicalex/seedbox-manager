#Description du seedbox-manager

L'application web seedbox-manager est une interface pour redémarrer une session rtorrent d'un utilisateur unix.  
On n'y trouve aussi :

 * des liens vers rutorrent et cakebox dans la navbar configurable.
 * un rappel des ids ftp et sftp de l'utilisateur.
 * un module de support avec ticket désactivable.
 * des statistiques serveurs (load average, uptime).
 * des informations utilisateurs (espace disque, adresse ip visiteur).
 * un outil pour générer des fichiers de configuration filezilla et transdroid.
 * Un espace administrateur pour gérer facilement la configuration de vos utilisateurs
 * Une page paramètre pour désactiver les blocs que vous n'utilisez pas.

**Auteur :** Backtoback (c) & Magicalex (php) & hydrog3n (php).  

#Installation

note : pour installer l'interface il faut les droits root ou avoir la possiblité d'utiliser sudo.

```
cd /var/www
git clone https://github.com/Magicalex/seedbox-manager.git
chown -R www-data:www-data ./seedbox-manager/
cd ./seedbox-manager/
composer install
bower install --allow-root
cd ./source-reboot-rtorrent/
chmod +x install.sh && ./install.sh
```

##Configuration du serveur web

ex : web server nginx
```nginx
server {
    listen 80 default_server;
    server_name _;

    charset utf-8;
    index index.php;

    access_log /var/log/nginx/seedbox-manager-access.log combined;
    error_log /var/log/nginx/seedbox-manager-error.log error;

    auth_basic "seedbox-manager";
    auth_basic_user_file "/var/www/seedbox-manager/.htpasswd";

    location / {
        root /var/www/seedbox-manager/public;
    }

    location ~ \.php$ {
        fastcgi_index index.php;
        fastcgi_pass unix:/var/run/php5-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include /etc/nginx/fastcgi_params;
    }
}
```
ex : web server apache2 (v2.4.*)
```apache
<VirtualHost _default_:80>
    DocumentRoot /var/www/seedbox-manager/public

        <Directory /var/www/seedbox-manager/public/>
            Options Indexes FollowSymLinks
            AllowOverride None
            Require all denied

            AuthType Basic
            AuthName "seedbox"
            AuthUserFile "/var/www/seedbox-manager/.htpasswd"
            Require valid-user
        </Directory>

</VirtualHost>

```

##Première connexion

Se connecter à l'interface avec ses identifiants rutorrent.  
Cela va générer automatiquement des fichiers de configuration pour l'utilisateur dans le dossier ./seedbox-manager/conf/users/{utilisateur}/config.ini

note : à chaque fois qu'un nouvel utilisateur se connecte ses fichiers de configuration sont automatiquement généré à partir du fichier ./conf/config.ini

pour obtenir les droits administrateurs :
```
nano /var/www/manager/conf/users/{utilisateur}/config.ini
```
puis modifier à la ligne ## (owner = no par owner = yes)

Après avoir récupéré les droits administrateurs vous pouvez configurer tous les utilisateurs.
