#!/bin/sh

ART_MODULE=art.ko
APPLICATION=mdk_client.out

wlan_unload()
{
    rmmod ath_pci
    rmmod ath
    rmmod ath_dfs
    sleep 1
    rmmod wlan_scan_ap
    rmmod wlan_wep
    rmmod wlan_tkip
    rmmod wlan_ccmp
    rmmod wlan_xauth
    rmmod wlan_acl
    rmmod wlan
    rmmod ath_rate_atheros
    rmmod asc
    rmmod ath_hal
    #
    # Let's try WLAN one more time to be sure
    #
    sleep 1
    rmmod wlan
}
if [ $# != 1 ]; then
	echo "USAGES: $0 tftp server IP"
	exit
fi

echo '****** Killing all applications.'
ps | grep sysmonitor.sh | awk '{print $1}' | xargs kill -9
killall configd syslogd snmpd hostapd wpa_supplicant dropbear telnetd udhcpc dnsmasq reset_detect nmbd lighttpd php ntpdate klogd sleep pal.netgear
ps | grep ntpdate-wrapper | awk '{print $1}' | xargs kill -9
sleep 1

echo '****** Killing wlan driver.'
wlan_unload

cd /tmp

echo "*** Getting file $ART_MODULE and $APPLICATION using tftp server $1"
tftp $1 -g -r $ART_MODULE
if [ $? != 0 ]; then
	echo "TFTP get Failed for $ART_MODULE."
	exit
fi
tftp $1 -g -r $APPLICATION
if [ $? != 0 ]; then
	echo "TFTP get Failed for $APPLICATION."
	exit
fi
insmod /tmp/$ART_MODULE
if [ $? != 0 ]; then
	echo 'Module Insertion Failed.'
	exit
fi
chmod +x /tmp/$APPLICATION
/tmp/$APPLICATION
