#!/bin/sh
if test -f "/proc/net/if_inet6" ;
then
	echo 2 > /proc/sys/net/ipv6/conf/eth2/accept_dad
	echo 1 > /proc/sys/net/ipv6/conf/eth2/disable_ipv6
fi
MACADDR=`devdata get -e wanmac`
[ "$MACADDR" != "" ] && ip link set eth2 addr $MACADDR
ip link set eth2 up
vconfig set_name_type DEV_PLUS_VID_NO_PAD
