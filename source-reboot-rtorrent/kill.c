#include "Bib.h"

void rtorrent_kill (char nickname[])
{
    //Déclarations
    char chaine [100] = {0};

    snprintf(chaine, 100, "killall --user %s rtorrent\n", nickname);
    printf("%s", chaine);
    system (chaine);
}

void screen_kill (char nickname[])
{
    //Déclarations
    char chaine [100] = {0};

    snprintf(chaine, 100, "killall --user %s screen\n", nickname);
    printf("%s", chaine);
    system (chaine);
}
