#!/bin/sh
echo [$0] ... > /dev/console
TROOT=`rgdb -i -g /runtime/template_root`
SNMPD=`rgdb -g /sys/snmpd/status`
[ "$TROOT" = "" ] && TROOT="/etc/templates"
[ ! -f $TROOT/snmp_run.php ] && exit 0
#syslog_2007_04_16_4 , Jordan
case "$SNMPD" in
1)
	if [ "$SNMPD" = "1" ]; then
		[ -f /var/run/snmp_stop.sh ] && sh /var/run/snmp_stop.sh > /dev/console
		[ -f /var/net-snmp/snmpd.conf ] && rm -f /var/net-snmp/snmpd.conf > /dev/console
		rgdb -A $TROOT/snmp_run.php -V generate_start=1 > /var/run/snmp_start.sh
		rgdb -A $TROOT/snmp_run.php -V generate_start=0 > /var/run/snmp_stop.sh
		sh /var/run/snmp_start.sh > /dev/console
	fi
	;;
	
0)
	if [ "$SNMPD" = "0" ]; then
		if [ -f /var/run/snmp_stop.sh ]; then
			sh /var/run/snmp_stop.sh > /dev/console
			rm -f /var/run/snmp_stop.sh
		fi
	fi	
	;;
*)
	echo "usage: snmp.sh {start|stop|restart}"
	;;
esac
