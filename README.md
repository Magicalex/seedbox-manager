#Description du seedbox-manager

L'application web seedbox manager est une interface pour redémarrer une session rtorrent d'un utilisateur unix.
On n'y trouve aussi :

 * des liens vers rutorrent et cakebox dans la navbar.
 * un rappel des ids ftp et sftp de l'utilisateur.
 * un module de support mail désactivable.
 * des statistiques serveurs (load average, uptime).
 * des informations utilisateurs (espace disque, adresse ip visiteur).
 * un outil pour générer des fichiers de configuration filezilla et transdroid.
 * Un espace administrateur pour gérer facilement la configuration de vos utilisateurs
 * Une page paramètre pour désactiver les blocs que vous n'utilisez pas.

**Auteur :** Backtoback (c) & Magicalex (php) & hydrog3n (php).
Nous contacter : <magicalex14000@gmail.com>

#Installation

note : pour installer l'interface il faut les droits root ou avoir la possiblité d'utiliser sudo.

```
cd /var/www
wget http://XX.XXX.XX.XX/seedbox-manager.zip
unzip seedbox-manager.zip && rm seedbox-manager.zip
mv seedbox-manager manager
chown www-data:www-data ./manager/
cd ./manager/source-rtorrent/
chmod +x install.sh && ./install.sh
```

##Configuration du serveur web

1. Il faut protéger l'interface via une authentification basic ou digest.
Je vous conseille d'étendre la protection de rutorrent à cette interface.

2. Il faut protéger le dossier conf récursivement via votre serveur web.

Pour l'exemple l'url sera égale à : http://www.domaine.fr/conf/
Rajoutez dans le fichier de configuration de votre serveur wev ceci.
pour lighttpd :
```
$http["url"] =~ "^/conf/"
{
	url.access-deny = ("")
}
```
pour nginx :
```
location ^~ /conf/
{
	deny all;
}
```
pour apache :
```
<Location ~ "^/conf/">
    Order deny,allow
    Deny from all
</Location>
```
Puis redémarrer votre serveur web préféré via service
```
# pour recharger la config nginx
service nginx restart
# pour recharger la config lighttpd
service lighttpd restart
# pour recharger la config apache
service apache2 restart
```
note : vérifiez si vous avez bien une erreur 403 si vous tentez d'accèder à cette url :
http://www.domaine.fr/conf/config.ini


##Première connexion

Se connecter à l'interface avec ses identifiants rutorrent.
Cela va générer des fichiers de configuration pour l'utilisateur dans le dossier conf/users/<utilisateur>/config.ini

note : à chaque fois qu'un nouvel utilisateur se connecte ses fichiers de configuration sont automatiquement généré à partir du fichier ./conf/config.ini

pour obtenir les droits administrateurs
```
vim /var/www/manager/conf/users/<utilisateur>/config.ini
```
puis modifier à la ligne ## (admin = no par admin = yes)

Après avoir récupéré les droits administrateurs vous pouvez configurer tous les utilisateurs
