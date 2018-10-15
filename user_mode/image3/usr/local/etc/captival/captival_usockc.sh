#!/bin/sh
echo "[$0] $1" > /dev/console

case "$1" in
deconfig)
	echo "udhcpc deconfig $interface" > /dev/console
	;;

renew|bound)
	echo "udhcpc config $interface" > /dev/console
	captival_usockc br_ip $interface $ip $subnet $router $dns
	;;
esac
