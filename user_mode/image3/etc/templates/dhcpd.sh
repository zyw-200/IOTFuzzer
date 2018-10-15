#!/bin/sh
echo [$0] ... > /dev/console
TROOT="/etc/templates"

mi=`rgdb -g /lan/dhcp/server/multiinstances`

if [ "$mi" != "1" ]; then
	[ -f /var/run/udhcpd-br0.conf ] && rm -f /var/run/udhcpd-br0.conf
	rgdb -A $TROOT/dhcp/dhcpd_restart.php > /var/run/dhcpd_restart.sh
	sh /var/run/dhcpd_restart.sh
else
	if [ "$1" = "" ]; then
		flag=start
	else
		flag=$1
	fi

	case $flag in
	start)
		rgdb -A $TROOT/dhcp/mudhcpd.php -V generate_start=1 > /var/run/mudhcpd-start.sh
		sh /var/run/mudhcpd-start.sh
		;;

	restart)
		[ -f /var/run/mudhcpd-stop.sh ] || rgdb -A $TROOT/dhcp/mudhcpd.php -V generate_start=0 > /var/run/mudhcpd-stop.sh
		sh /var/run/mudhcpd-stop.sh
		sh /var/run/mudhcpd-start.sh
		;;

	stop)
		[ -f /var/run/mudhcpd-stop.sh ] || rgdb -A $TROOT/dhcp/mudhcpd.php -V generate_start=0 > /var/run/mudhcpd-stop.sh
		sh /var/run/mudhcpd-stop.sh
		;;

	clean)
		[ -f /var/run/mudhcpd-stop.sh ] || rgdb -A $TROOT/dhcp/mudhcpd.php -V generate_start=0 > /var/run/mudhcpd-stop.sh
		sh /var/run/mudhcpd-stop.sh
		rm -f /var/run/mudhcpd*
		;;
	config)
		rgdb -A $TROOT/dhcp/mudhcpd.php -V generate_start=1 > /var/run/mudhcpd-start.sh
		rgdb -A $TROOT/dhcp/mudhcpd.php -V generate_start=0 > /var/run/mudhcpd-stop.sh
		;;
	*)
		echo "Usage:"
		echo "$0 {start|restart|stop|clean}"
		[ -f /var/run/mudhcpd-start.sh ] && rm -f /var/run/mudhcpd-start.sh
		[ -f /var/run/mudhcpd-stop.sh  ] && rm -f /var/run/mudhcpd-stop.sh
		;;
	esac
fi
