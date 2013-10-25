#!/bin/sh

# script pour compiler le programme c reboot-rtorrent
# Auteur du programme c : backtoback

gcc -v *.c -o reboot-rtorrent
chown -c root:root reboot-rtorrent
chmod -v 4755 reboot-rtorrent
mv -v reboot-rtorrent ..
