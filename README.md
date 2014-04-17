#Description du seedbox-manager

L'application web seedbox manager est une interface pour redémarrer une session rtorrent d'un utilisateur unix.
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
git clone https://depot.git
chown www-data:www-data ./manager/
cd ./seedbox-manager/source-rtorrent/
chmod +x install.sh && ./install.sh
```

##Configuration du serveur web

...

##Première connexion

Se connecter à l'interface avec ses identifiants rutorrent.  
Cela va générer des fichiers de configuration pour l'utilisateur dans le dossier conf/users/<utilisateur>/config.ini

note : à chaque fois qu'un nouvel utilisateur se connecte ses fichiers de configuration sont automatiquement généré à partir du fichier ./conf/config.ini

pour obtenir les droits administrateurs :
```
nano /var/www/manager/conf/users/<utilisateur>/config.ini
```
puis modifier à la ligne ## (owner = no par owner = yes)

Après avoir récupéré les droits administrateurs vous pouvez configurer tous les utilisateurs.
