# Description of seedbox-manager

[![StyleCI](https://styleci.io/repos/18575839/shield?branch=master)](https://styleci.io/repos/18575839)

seedbox-manager is web app for manage your seedbox.

 * reboot rtorrent session
 * custom links in navbar.
 * statistic server (load average, uptime)
 * download file config of filezilla and transdroid
 * admin area
 * logout for http basic authentication

## Installation

*note : root privilege is required*

```bash
cd /var/www
git clone https://github.com/Magicalex/seedbox-manager.git
cd seedbox-manager
composer install
chown -R www-data: /var/www/seedbox-manager
cd source
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
    auth_basic_user_file "/etc/nginx/passwd/password";

    root /var/www;

    location / {
        try_files /seedbox-manager/$uri /seedbox-manager/index.php$is_args$args;
    }

    location ^~ /assets {
        alias /var/www/seedbox-manager/assets;
    }

    location ~ \.php$ {
        fastcgi_index index.php;
        include /etc/nginx/fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_pass unix:/run/php/php7.0-fpm.sock;
    }
}
```

In a uri like http://domain.tld/seedbox-manager

```nginx
server {
    listen 80 default_server;
    server_name _;

    charset utf-8;
    index index.html index.php;

    auth_basic "seedbox";
    auth_basic_user_file "/etc/nginx/passwd/password";

    root /var/www;

    location /seedbox-manager {
        try_files /seedbox-manager/$uri /seedbox-manager/index.php$is_args$args;
    }

    location ~ \.php$ {
        fastcgi_index index.php;
        include /etc/nginx/fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_pass unix:/run/php/php7.0-fpm.sock;
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
and replace `admin = no` by `admin = yes`

## developement

```bash
echo "127.0.0.1 sbm.dev" >> /etc/hosts
php -S sbm.dev:8080
```
