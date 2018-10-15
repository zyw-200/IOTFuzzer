#!/bin/sh
#This script is a workaround for issue 
#  : 5G disabled, 2.4G can't connect. 
#So we bring 5G module up in INIT. Remember that WLAN-2 is 5G.

echo [$0]: $1 ... > /dev/console
xmldbc -P /etc/services/WIFI/rtcfg.php -V PHY_UID="WLAN-2" > /var/run/RT2860.dat
insmod /lib/modules/rt2860v2_ap.ko
ifconfig ra0 up; ifconfig ra0 down
rmmod rt2860v2_ap.ko

exit 0
