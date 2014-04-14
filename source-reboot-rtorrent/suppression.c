#include "Bib.h"

void supprLock (char nickname[])
{
    //Déclarations
    char chaine [100] = {0};

    snprintf(chaine, 100, "rm /home/%s/.session/rtorrent.lock\n", nickname);
    printf("%s", chaine);
    system (chaine);
}
