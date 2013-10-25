#Description du seedbox-manager

L'application web seedbox manager est une interface pour redémarrer une session rtorrent d'un utilisateur unix.
On n'y trouve aussi :

 * des liens vers rutorrent et cakebox dans la navbar.
 * un rappel des ids ftp et sftp de l'utilisateur.
 * un module de support mail désactivable.
 * des statistiques serveurs (load average, uptime).
 * des informations utilisateurs (espace disque, adresse ip visiteur).
 * un outil pour générer des fichiers de configuration filezilla et transdroid.

**Auteur :** Backtoback & Magicalex (magicalex14000@gmail.com)

#Installation

```
cd /var/www
wget http://XX.XXX.XX.XX/seedbox-manager.zip
unzip seedbox-manager.zip && rm seedbox-manager.zip
mv seedbox-manager manager
chown www-data:www-data ./manager/
```

##Configuration du serveur web

attention protéger le dossier conf récursivement etc
 -> config nginx + config lighttpd + config apache (htaccess -Index)
 ->
possiblité de faire un alias?

type d'authantification supporté basic et digest -> protéger de la même manière cette interface que rutorrent.

##Première connexion

Se connecter à l'interface puis on termine.

```
cd ./manager/prog/
chmod +x install.sh && ./install.sh
```

Se donner les droits admin.
Configurer les users depuis l'interface etc
