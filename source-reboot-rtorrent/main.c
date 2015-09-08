#include <stdlib.h>
#include <stdio.h>
#include <sys/types.h>
#include <unistd.h>

// Librairies Perso
#include "start_rtorrent.h"
#include "kill_rtorrent.h"

int main (int argc, char* argv[])
{
    // Déclarations
    // Chaine recevant le pseudo de l'utilisateur
    char nickname[50];

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
    strcpy(nickname, argv[1]);

    // Arrêt de la session rtorrent screen
    screen_kill(nickname);

    // Suppression du fichier rtorrent.lock
    remove_lock_file(nickname);

    // Appel de la fonction pour reboot rtorrent
    start_rtorrent(nickname);

    return 0;
}
