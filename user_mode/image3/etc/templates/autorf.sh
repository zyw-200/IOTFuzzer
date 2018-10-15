#!/bin/sh
echo [$0] $1 ... > /dev/console
TROOT="/etc/templates"
case "$1" in
start|restart)
	[ -f /var/run/autorf_stop.sh ] && sh /var/run/autorf_stop.sh > /dev/console
	xmldbc -A $TROOT/apneap_v2/autorf_run.php -V generate_start=1 > /var/run/autorf_start.sh
	xmldbc -A $TROOT/apneap_v2/autorf_run.php -V generate_stop=1  > /var/run/autorf_stop.sh
	sh /var/run/autorf_start.sh > /dev/console
	;;
stop)
	if [ -f /var/run/autorf_stop.sh ]; then
		sh /var/run/autorf_stop.sh
		rm -f /var/run/autorf_stop.sh
	fi
	;;
*)
	echo "usage: autorf.sh {start|stop|restart}"
	;;
esac
