#include "kill_rtorrent.h"

void remove_lock_file(char nickname[])
{
    // Déclarations
    char chaine[100] = {0};

    snprintf(chaine, 100, "rm /home/%s/.session/rtorrent.lock\n", nickname);
    printf("%s", chaine);
    system(chaine);
}

void screen_rtorrent_kill(char nickname[])
{
    // Déclarations
    char chaine[100] = {0};

    snprintf(chaine, 100, "su --command='screen -S %s-rtorrent -X quit' %s\n", nickname, nickname);
    printf("%s", chaine);
    system(chaine);
}
