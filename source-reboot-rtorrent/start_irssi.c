#include "start_irssi.h"

void start_irssi (char nickname[])
{
    // Déclarations
    char chaine[100] = {0};

    snprintf(chaine, 100, "su --command='screen -dmS irc_logger irssi' %s\n", nickname);
    printf("%s", chaine);
    system (chaine);
}
