#!/bin/sh
echo [$0] $1 ... > /dev/console
TROOT="/etc/templates"
[ ! -f $TROOT/arpspoofing.php ] && exit 0
case "$1" in
start|restart)
	[ -f /var/run/arpspoofing_stop.sh ] && sh /var/run/arpspoofing_stop.sh > /dev/console
	xmldbc -A $TROOT/arpspoofing.php -V generate_start=1 > /var/run/arpspoofing_start.sh
	xmldbc -A $TROOT/arpspoofing.php -V generate_start=0 > /var/run/arpspoofing_stop.sh
	sh /var/run/arpspoofing_start.sh > /dev/console
	;;
stop)
	if [ -f /var/run/arpspoofing_stop.sh ]; then
		sh /var/run/arpspoofing_stop.sh > /dev/console
		rm -f /var/run/arpspoofing_stop.sh
	fi
	;;
*)
	echo "usage: arpspoofing.sh {start|stop|restart}"
	;;
esac
