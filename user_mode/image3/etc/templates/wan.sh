#!/bin/sh
echo [$0] $1 ... > /dev/console
TROOT=`rgdb -i -g /runtime/template_root`
[ "$TROOT" = "" ] && TROOT="/etc/templates"
case "$1" in
start|restart)
        rgdb -A /etc/scripts/eth0_vlan.php -V generate_start=1 > /var/run/eth0_vlan_start.sh
        rgdb -A /etc/scripts/eth0_vlan.php -V generate_start=0 > /var/run/eth0_vlan_stop.sh
        sh /var/run/eth0_vlan_start.sh

	[ -f /var/run/wan_stop.sh ] && sh /var/run/wan_stop.sh > /dev/console
	rgdb -A $TROOT/wan_start.php > /var/run/wan_start.sh
	rgdb -A $TROOT/wan_stop.php > /var/run/wan_stop.sh
	sh /var/run/wan_start.sh > /dev/console
	;;
stop)
	if [ -f /var/run/wan_stop.sh ]; then
		sh /var/run/wan_stop.sh > /dev/console
		rm -f /var/run/wan_stop.sh
	fi
	;;
*)
	echo "usage: $0 {start|stop|restart}"
	;;
esac
