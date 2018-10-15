#!/bin/sh
echo [$0] $1 ... > /dev/console
TROOT="/etc/templates"
case "$1" in
start|restart)
	[ -f /var/run/apneaps_v2_stop.sh ] && sh /var/run/apneaps_v2_stop.sh > /dev/console
	xmldbc -A $TROOT/apneap_v2/apneaps_v2_run.php -V generate_start=1 > /var/run/apneaps_v2_start.sh
	xmldbc -A $TROOT/apneap_v2/apneaps_v2_run.php -V generate_stop=1  > /var/run/apneaps_v2_stop.sh
	sh /var/run/apneaps_v2_start.sh > /dev/console
	;;
stop)
	if [ -f /var/run/apneaps_v2_stop.sh ]; then
		sh /var/run/apneaps_v2_stop.sh
		rm -f /var/run/apneaps_v2_stop.sh
	fi
	;;
*)
	echo "usage: apneaps_v2.sh {start|stop|restart}"
	;;
esac
