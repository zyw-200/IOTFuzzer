#!/bin/sh
echo [$0] $1 ... > /dev/console
TROOT="/etc/templates"
case "$1" in
start|restart)
	[ -f /var/run/loadbalance_stop.sh ] && sh /var/run/loadbalance_stop.sh > /dev/console
	xmldbc -A $TROOT/apneap_v2/loadbalance_run.php -V generate_start=1 > /var/run/loadbalance_start.sh
	xmldbc -A $TROOT/apneap_v2/loadbalance_run.php -V generate_stop=1  > /var/run/loadbalance_stop.sh
	sh /var/run/loadbalance_start.sh > /dev/console
	
	;;
stop)
	if [ -f /var/run/loadbalance_stop.sh ]; then
		sh /var/run/loadbalance_stop.sh
		rm -f /var/run/loadbalance_stop.sh
	fi
	;;
*)
	echo "usage: loadbalance.sh {start|stop|restart}"
	;;
esac
