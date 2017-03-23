# Description of seedbox-manager

seedbox-manager is web app for manage your seedbox.

 * reboot of rtorrent
 * custom links in navbar.
 * statistic server (load average, uptime).
 * download file config of filezilla et transdroid.
 * admin area

**Auteur :** Backtoback (c) & Magicalex (php) & hydrog3n (php).  

## Installation

*note : root privilege is required*

```bash
cd /var/www
git clone https://github.com/Magicalex/seedbox-manager.git
chown -R www-data:www-data seedbox-manager
cd seedbox-manager
composer install
cd source-reboot-rtorrent
chmod +x install.sh && ./install.sh
```

## Configuration

*example : web server nginx*

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

## First connection

Connect you to the interface with your rutorrent id.
This will automatically generate configuration files for the current user. `./seedbox-manager/conf/users/{utilisateur}/config.ini`

To obtain the admin rights :
```bash
vi /var/www/seedbox-manager/conf/users/{utilisateur}/config.ini
```
and replace (owner = no by owner = yes)

## developement

```bash
echo "127.0.0.1 sbm.dev" >> /etc/hosts
cd seedbox-manager/public
php -S sbm.dev:8080
```
