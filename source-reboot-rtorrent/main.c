///Librairies
#include <stdlib.h>
#include <stdio.h>
#include <sys/types.h>
#include <unistd.h>

///Librairies Perso
#include "reboot.h"
#include "suppression.h"
#include "kill.h"

/* Mise en place des droits d'accès root: 
chown root:root nom_du_fichier
chmod +s nom_du_fichier
*/


int main (int argc, char* argv[])
{
    ///Déclarations
    char nickname[50]; //Chaine recevant le pseudo de l'utilisateur

    // On vérifie la presénce d'un argument pour éviter l'erreur de segmentation
    if (argc <= 1){
	    printf("ERREUR : Vous n'avez pas rentré de nom d'utilisateur en paramètre du programme.\n"
			"Le programme va quitter\n");
	    exit(0);
    }

    // setuid pour les droits root
    setuid(0);
    perror("setuid");

    //Récupération du pseudo de l'utilisateur
    strcpy (nickname, argv[1]); //On récupère le pseudo

    //Arret de rtorrent
    rtorrent_kill(nickname);
    
    //Arret de screen
    screen_kill(nickname);

    //Suppression du rtorrent.lock
    supprLock (nickname);

    //Appel de la fonction pour reboot rtorrent
    reboot (nickname);

    return 0;
}
