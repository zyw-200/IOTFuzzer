#!/bin/sh
echo [$0] $1 $2... > /dev/console
TROOT=`rgdb -i -g /runtime/template_root`
[ "$TROOT" = "" ] && TROOT="/etc/templates"
case "$1" in
start|restart)
	case "$2" in
		a)
		iwlist ath16 scanning ch_env > /dev/console
#		iwpriv ath16 reset 1 > /dev/console
		;;
		g)
		iwpriv ath0 sitesurvey  1 > /dev/console
		iwlist ath0 scanning ch_env > /dev/console
		iwpriv ath0 sitesurvey 0 > /dev/console
#		iwpriv ath0 reset 1 > /dev/console
		;;
	esac
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
