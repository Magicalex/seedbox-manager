#include "Bib.h"

void reboot (char nick1, char nick2, char nick3)
{
    //Déclarations
    char chaine [30] = {0};

    snprintf(chaine, 30, "/etc/init.d/%c%c%c.rtord start\n", nick1, nick2, nick3);
    printf("%s", chaine);
    system (chaine);
}
