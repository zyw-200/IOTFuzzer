#!/bin/sh
echo [$0] ... > /dev/console
TROOT=`rgdb -i -g /runtime/template_root`
[ "$TROOT" = "" ] && TROOT="/etc/templates"
case "$1" in
start|restart)
	xmldbc -A $TROOT/autorekey.php -V generate_start=1 -V band_type=0> /var/run/autorekey_start_g.sh
	xmldbc -A $TROOT/autorekey.php -V generate_start=1 -V band_type=1> /var/run/autorekey_start_a.sh
	
	xmldbc -A $TROOT/autorekey.php -V generate_start=0 > /var/run/autorekey_stop.sh
	sh /var/run/autorekey_start_g.sh &> /dev/console
	sh /var/run/autorekey_start_a.sh &> /dev/console
	
	;;
stop)
	if [ -f /var/run/autorekey_stop.sh ]; then
		sh /var/run/autorekey_stop.sh > /dev/console
		rm -f /var/run/autorekey_stop.sh
		rm -f /var/run/autorekey_start_g.sh
		rm -f /var/run/autorekey_start_a.sh
		
	fi
	;;
*)
	echo "usage: autorekey.sh {start|stop|restart}"
	;;
esac
