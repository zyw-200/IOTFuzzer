#!/bin/bash

. /etc/generic_include.sh

if [ "$PLATFORM" == "WNDAP660_620" ]; then 
    echo -e "FW Version "`uname -r | cut -d '-' -f3` > /tmp/versions.txt
else
    echo -e "FW Version "`uname -r | cut -d '-' -f2` > /tmp/versions.txt
fi
   echo -e "Config Version "`cat /var/config | grep configVersion | cut -d  ' ' -f2` >> /tmp/versions.txt

   echo -e `/usr/local/bin/pal.netgear -v` >> /tmp/versions.txt

   cat /tmp/versions.txt > /var/log/messages



