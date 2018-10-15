#!/bin/sh
image_sign=`cat /etc/config/image_sign`

case "$1" in
start)
	echo "Mounting proc and var ..."
	mount -t proc   none   /proc
	mount -t sysfs  none   /sys
	mount -t ramfs  ramfs  /var
        mount -t tmpfs  mdev   /dev
        mkdir -p /var/etc /var/log /var/run /var/state /var/tmp /var/etc/ppp /var/etc/config /dev/pts
        mount -t devpts devpts /dev/pts
        echo /sbin/mdev > /proc/sys/kernel/hotplug
        /sbin/mdev -s

        mkdir -p /dev/mtdblock;
        for node in /dev/mtdblock?*; do
                num=`echo $node | sed -e 's,/dev/mtdblock\(.*\),\1,'`;
                ln -s $node /dev/mtdblock/$num
                echo "SymbLink /dev/mtdblock/$num";
        done

	echo -n > /var/etc/resolv.conf
	echo -n > /var/TZ

	touch /var/log/messages

	echo "2" > /proc/sys/net/ipv6/conf/all/accept_dad
	echo "2" > /proc/sys/net/ipv6/conf/default/accept_dad
	echo "0" > /proc/sys/net/ipv6/conf/all/disable_ipv6
	echo "0" > /proc/sys/net/ipv6/conf/default/disable_ipv6
	
	echo "Inserting modules ..." > /dev/console
	echo "Inserting Rebootm ..." > /dev/console
	insmod /lib/modules/rebootm.ko
	
	echo "Inserting atheros ethernet ..." > /dev/console
	insmod /lib/modules/athrs_gmac.ko
	echo "1" > /proc/sys/net/ipv6/conf/eth0/disable_ipv6
	
	echo "Inserting gpio ..." > /dev/console
	insmod /lib/modules/gpio.ko
	[ "$?" = "0" ] && mknod /dev/gpio c 101 0 && echo "done."

	# get the country code for madwifi, default is fcc.
	ccode=`devdata get -e countrycode`
	[ "$ccode" = "" ] && ccode="840"

	env_wlan=`devdata get -e wlanmac`
	[ "$env_wlan" = "" ] && env_wlan="00:15:e9:2c:75:00"

	# prepare db...
	echo "Start xmldb ..." > /dev/console
	xmldb -n $image_sign -t > /dev/console &
	sleep 1
	/etc/scripts/misc/profile.sh getnew
	/etc/templates/timezone.sh set
	/etc/templates/logs.sh

    # fresh led status
    echo "gpio set led status" > /dev/console
    /etc/templates/powerled.sh
	# bring up network devices
	env_wan=`devdata get -e wanmac`
	[ "$env_wan" = "" ] && env_wan="00:15:e9:2c:75:00"
	ifconfig eth0 hw ether $env_wan up
	sleep 3
	rgdb -i -s /runtime/wan/inf:1/mac "$env_wan"

#traveller add for add ssid mac when system start begin
mac=`expr substr $env_wan 16 2`
prefix=`expr substr $env_wan 1 15`
mac=0x$mac
mac=`printf "%d" $mac` 
#mac for ssid 1
nmac=`expr $mac + 1`
emac=`printf "%x" $nmac`
if [ $nmac -lt 16 ]
then
	emac=0$emac
fi
rgdb -i -s /runtime/wan/inf:1/macstart "$prefix$emac"
#mac for ssid 7
nmac=`expr $mac + 7`
emac=`printf "%x" $nmac`
if [ $nmac -lt 16 ]
then
	emac=0$emac
fi
rgdb -i -s /runtime/wan/inf:1/macend "$prefix$emac"
#mac for 5ghz
nmac=`expr $mac + 8`
emac=`printf "%x" $nmac`
if [ $nmac -lt 16 ]
then
	emac=0$emac
fi
#traveller add for add ssid mac when system start end

	# bring up loopback interface
	ifconfig lo up

	brctl addbr br0 	> /dev/console
	brctl stp br0 off	> /dev/console
	brctl setfd br0 0	> /dev/console
	echo "1" > /proc/sys/net/ipv6/conf/br0/disable_ipv6
	
	;;
stop)
	umount /tmp
	umount /proc
	umount /var
	;;
esac

