#include "start_rtorrent.h"

void start_rtorrent (char nickname[])
{
    // Déclarations
    char chaine[100] = {0};

    snprintf(chaine, 100, "su --command='screen -dmS %s-rtorrent rtorrent' %s\n", nickname, nickname);
    printf("%s", chaine);
    system (chaine);
}
