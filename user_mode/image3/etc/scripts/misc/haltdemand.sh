#!/bin/sh
echo [$0] ... > /dev/console
# Stop WLAN
/etc/templates/wlan.sh stop	> /dev/console
sleep 60
# Stop WAN
/etc/templates/wan.sh stop	> /dev/console
# Stop UPNP ...
/etc/templates/upnpd.sh stop	> /dev/console
# Stop DNRD
#/etc/templates/dnrd.sh stop	> /dev/console
# Stop RG
#/etc/templates/rg.sh stop	> /dev/console
# Stop LAN
/etc/templates/lan.sh stop	> /dev/console
/etc/scripts/startburning.sh	> /dev/console
/etc/templates/webs.sh stop      > /dev/console
/etc/templates/neapc.sh stop 	> /dev/console
/etc/templates/neaps.sh stop 	> /dev/console
/etc/templates/loadbalance.sh stop > /dev/console
/etc/templates/autorf.sh stop   > /dev/console
/etc/templates/apneaps_v2.sh stop > /dev/console

echo "Start kill some process..." > /dev/console
killall syslogd
killall fresetd
killall klogd
killall telnetd
killall ethlink
killall trap_monitor
killall lld2d
killall snmpd
killall cwmHelper
killall captival_portal
killall dhcpxmlpatch
killall udhcpd
killall httpd
killall ntpclient
# sleep 30, to let wlan down!!!
#sleep 60
killall servd
killall wlxmlpatch
killall xmldb
if [ -f "/etc/config/warmstartblock" ] ; then
	warmstartblock=`cat /etc/config/warmstartblock`
	devdata set -e warm_start="1" -n $warmstartblock
fi
sleep 2
echo "End kill some process..." > /dev/console
echo "Done ! Start burning ..."	> /dev/console

