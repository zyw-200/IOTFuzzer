#!/bin/sh
echo [$0] ... > /dev/console
TROOT=`rgdb -i -g /runtime/template_root`
IPV6=`rgdb -g /inet/entry:1/ipv6/valid`
[ "$TROOT" = "" ] && TROOT="/etc/templates"
case "$1" in
config)
	if [ "$IPV6" = "1" ]
	then rgdb -A $TROOT/ssh/sshdconf6.php > /var/etc/sshd_config
	else rgdb -A $TROOT/ssh/sshdconf.php > /var/etc/sshd_config
	fi
	;;
start|restart)
	[ -f /var/run/sshd_stop.sh ] && sh /var/run/sshd_stop.sh > /dev/console
	rgdb -A $TROOT/ssh/sshd_run.php -V start=1 > /var/run/sshd_start.sh
	rgdb -A $TROOT/ssh/sshd_run.php -V start=0 > /var/run/sshd_stop.sh
	sleep 1
	sh /var/run/sshd_start.sh > /dev/console
	;;
stop)
	if [ -f /var/run/sshd_stop.sh ]; then
		sh /var/run/sshd_stop.sh > /dev/console
		rm -f /var/run/sshd_stop.sh
	fi
	;;
*)
	echo "usage: $0 {start|stop|restart|config}"
	;;
esac
