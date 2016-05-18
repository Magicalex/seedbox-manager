#include "kill_irssi.h"

void screen_irssi_kill(char nickname[])
{
    // Déclarations
    char chaine[100] = {0};

    snprintf(chaine, 100, "su --command='screen -S irc_logger -X quit' %s\n", nickname);
    printf("%s", chaine);
    system(chaine);
}
