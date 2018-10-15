#!/bin/sh
echo [$0] ... > /dev/console
TROOT=`rgdb -i -g /runtime/template_root`
[ "$TROOT" = "" ] && TROOT="/etc/templates"
case "$1" in
SHDWN_TO_SHDWN)
	sh /etc/scripts/misc/profile.sh reset > /dev/console
	rgdb -s /sys/wtp/enable 1 > /dev/console
	rgdb -s /wlan/inf:1/enable 0 > /dev/console
	sh /etc/scripts/misc/profile.sh put > /dev/console
	sh /etc/templates/wan.sh restart > /dev/console
	sh /etc/templates/snmp.sh start > /dev/console
	sh /etc/templates/wlan.sh restart > /dev/console
	;;
*)
	echo "usage: wtpfunc.sh {SHDWN_TO_SHDWN}"
	;;
esac
