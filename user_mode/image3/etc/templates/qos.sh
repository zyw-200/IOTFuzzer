#!/bin/sh
echo [$0] $1 ... > /dev/console
TROOT="/etc/templates"
[ ! -f $TROOT/qos_run.php ] && exit 0
case "$1" in
start|restart)
	[ -f /var/run/qos_stop_g.sh ] && sh /var/run/qos_stop_g.sh > /dev/console
	[ -f /var/run/qos_stop_a.sh ] && sh /var/run/qos_stop_a.sh > /dev/console
	xmldbc -A $TROOT/qos_run.php -V generate_start=1 -V band_type=0 > /var/run/qos_start_g.sh
	xmldbc -A $TROOT/qos_run.php -V generate_start=1 -V band_type=1 > /var/run/qos_start_a.sh
	xmldbc -A $TROOT/qos_run.php -V generate_start=0 -V band_type=0 > /var/run/qos_stop_g.sh
	xmldbc -A $TROOT/qos_run.php -V generate_start=0 -V band_type=1 > /var/run/qos_stop_a.sh
	sleep 2
	sh /var/run/qos_start_g.sh > /dev/console
	sh /var/run/qos_start_a.sh > /dev/console
	;;
stop)
	if [ -f /var/run/qos_stop_g.sh ]; then
		sh /var/run/qos_stop_g.sh > /dev/console
		rm -f /var/run/qos_stop_g.sh
	fi
	if [ -f /var/run/qos_stop_a.sh ]; then
		sh /var/run/qos_stop_a.sh > /dev/console
		rm -f /var/run/qos_stop_a.sh
	fi
	;;
*)
	echo "usage: qos.sh {start|stop|restart}"
	;;
esac
