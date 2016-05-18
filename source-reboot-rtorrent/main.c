#include <stdlib.h>
#include <stdio.h>
#include <sys/types.h>
#include <unistd.h>

// Librairies Perso
#include "start_rtorrent.h"
#include "kill_rtorrent.h"
#include "start_irssi.h"
#include "kill_irssi.h"

int main (int argc, char* argv[])
{
    // Déclarations
    // Chaine recevant le pseudo de l'utilisateur
    char nickname[20];

    // On vérifie la présence d'un argument pour éviter l'erreur de segmentation
    if (argc < 2){
        printf("ERREUR : Vous n'avez pas rentré de nom d'utilisateur en paramètre du programme.\n"
        "Usage : ./reboot-rtorrent <username>\n"
        "Le programme va quitter, bye.\n");
        return 101;
    }

    // setuid pour les droits root
    setuid(0);
    perror("setuid");

    // Récupération du pseudo de l'utilisateur
    strncpy(nickname, argv[1], sizeof(nickname));

    // Arrêt de la session rtorrent screen
    screen_rtorrent_kill(nickname);

    // Suppression du fichier rtorrent.lock
    remove_lock_file(nickname);

    // Appel de la fonction pour reboot rtorrent
    start_rtorrent(nickname);

    // Appel de la fonction pour lancer irssi si demander
    // Usage : ./reboot-rtorrent <username> irssi
    if (argc > 2 && strcmp(argv[2], "irssi") == 0) {
	    screen_irssi_kill (nickname);
	    start_irssi (nickname);
    }

    return 0;
}
