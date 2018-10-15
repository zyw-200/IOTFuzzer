#!/bin/sh
echo [$0] ... > /dev/console
TROOT=`rgdb -i -g /runtime/template_root`
[ "$TROOT" = "" ] && TROOT="/etc/templates"
case "$1" in
start|restart)
rgdb -A /etc/templates/wlan_servd.php > /var/run/wlan_servd_start.sh
if [ "`rgdb -i -g /time/syncwith`" = "2" ] && [ "`rgdb -i -g /schedule/enable`" = "1" ]; then
	sleep 1
	if [ "`rgdb -i -g /runtime/time/ntp/state`" = "1" ] || [ "`rgdb -i -g /runtime/time/ntp/ntp_connect_counter`" = "5" ]; then
		if [ "`rgdb -i -g /runtime/time/ntp/state`" != "1" ] && [ "`rgdb -i -g /runtime/time/ntp/ntp_connect_counter`" = "5" ]; then
			/etc/templates/ntp.sh > /dev/console
			
		fi
		sleep 1
		rgdb -i -s /runtime/time/ntp/ntp_connect_counter 0
	else
		xmldbc -k ntp
		rgdb -i -s /runtime/time/ntp/state ""
		rgdb -i -s /runtime/timeset ""
		if [ -f /var/run/schedule_ntp.sh ]; then
			rm -f /var/run/schedule_ntp.sh
		fi
		rgdb -A $TROOT/schedule_ntp_run.php -V generate_start=1 > /var/run/schedule_ntp.sh
		sh /var/run/schedule_ntp.sh > /dev/console
	fi
fi
service WLAN restart
	;;
stop)
service WLAN stop
	;;
*)
	echo "usage: wlan.sh {start|stop|restart}" > /dev/console
	echo "       if option "a" or "g" are not give," > /dev/console
	echo "       both wlan would be start|stop|restart." > /dev/console
	;;
esac
