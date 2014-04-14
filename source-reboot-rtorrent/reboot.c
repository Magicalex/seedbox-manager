#include "Bib.h"

void reboot (char nickname[])
{
    //Déclarations
    char chaine [100] = {0};

    snprintf(chaine, 100, "service %s-rtorrent start\n", nickname);
    printf("%s", chaine);
    system (chaine);
}
