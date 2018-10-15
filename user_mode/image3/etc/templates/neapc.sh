#!/bin/sh
echo [$0] $1 ... > /dev/console
TROOT="/etc/templates"
case "$1" in
start|restart)
	[ -f /var/run/neapc_stop.sh ] && sh /var/run/neapc_stop.sh > /dev/console
	xmldbc -A $TROOT/neapc/neapc_run.php -V generate_start=1 > /var/run/neapc_start.sh
	xmldbc -A $TROOT/neapc/neapc_run.php -V generate_stop=1  > /var/run/neapc_stop.sh
        xmldbc -A $TROOT/neapc/neapc_conf.php > /var/run/neapc.conf
	sh /var/run/neapc_start.sh > /dev/console
	
	[ -f /var/run/apneaps_stop.sh ] && sh /var/run/apneaps_stop.sh > /dev/console
	xmldbc -A $TROOT/neapc/apneaps_run.php -V generate_start=1 > /var/run/apneaps_start.sh
	xmldbc -A $TROOT/neapc/apneaps_run.php -V generate_stop=1  > /var/run/apneaps_stop.sh
	sh /var/run/apneaps_start.sh > /dev/console
	;;
stop)
	if [ -f /var/run/neapc_stop.sh ]; then
		sh /var/run/neapc_stop.sh
		rm -f /var/run/neapc_stop.sh
	fi
	if [ -f /var/run/apneaps_stop.sh ]; then
		sh /var/run/apneaps_stop.sh
		rm -f /var/run/apneaps_stop.sh
	fi
	;;
*)
	echo "usage: lan.sh {start|stop|restart}"
	;;
esac
