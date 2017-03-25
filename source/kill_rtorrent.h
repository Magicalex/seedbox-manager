#ifndef KILL_RTORRENT_H
#define KILL_RTORRENT_H

#include <stdio.h>
#include <stdlib.h>
#include <string.h>

// Prototypes
void screen_rtorrent_kill(char nickname[]);
void remove_lock_file(char nickname[]);

#endif
