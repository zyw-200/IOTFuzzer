#!/bin/sh
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");
?>
[ -z "$1" ] && echo "Error: should be called from udhcpc" > /dev/console && exit 1
echo [$0] $1 ... > /dev/console
case "$1" in
renew|bound)
	[ -n "$broadcast" ] && BROADCAST="broadcast $broadcast"
	[ -n "$subnet" ] && NETMASK="netmask $subnet"
	ifconfig $interface $ip $BROADCAST $NETMASK
	echo -n > /etc/resolv.conf
	for i in $dns ; do
		echo adding dns $i ... > /dev/console
		echo nameserver $i >> /etc/resolv.conf
		if [ $i != $router ]; then
			route add -host $i gw $router dev $interface
		fi
	done
	SERVER=`gethostip -d <?=$server?>`
	if [ "$SERVER" != "" ]; then
		sh <?=$template_root?>/wan_ppp.sh start $SERVER > /dev/console
	else
		echo "Can not find server (<?=$server?>) : $SERVER" > /dev/console
	fi
	;;
deconfig)
	<?=$template_root?>/wan_ppp.sh stop > /dev/console
	ifconfig $interface 0.0.0.0 > /dev/console
	;;
esac
exit 0
