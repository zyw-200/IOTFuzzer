#!/bin/sh
echo [$0] $1 ... > /dev/console
case "$1" in
start)
	[ -f /var/run/eth0_vlan_stop.sh ] && sh /var/run/eth0_vlan_stop.sh > /dev/console
	rgdb -A /etc/scripts/eth0_vlan.php -V generate_start=1 > /var/run/eth0_vlan_start.sh
        rgdb -A /etc/scripts/eth0_vlan.php -V generate_start=0 > /var/run/eth0_vlan_stop.sh
	sh /var/run/eth0_vlan_start.sh
	;;
stop)
	[ -f /var/run/eth0_vlan_stop.sh ] && sh /var/run/eth0_vlan_stop.sh > /dev/console
        rm -f /var/run/eth0_vlan_stop.sh

	;;
*)
	echo "Usage: $0 {start|stop}"
	;;
esac
