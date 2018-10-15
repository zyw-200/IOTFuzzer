#!/bin/sh
case "$1" in
start)
	echo "start Layout ..."	> /dev/console
	/etc/scripts/layout.sh start	> /dev/console
	echo "start LAN ..."		> /dev/console
	/etc/templates/lan.sh start	> /dev/console
	echo "start web server ..."                 
	/etc/templates/webs.sh start    > /dev/console
if [ "`rgdb -g  /runtime/nvram/mfc_phase`" = "0" ] || [ "`rgdb -g  /runtime/nvram/mfc_phase`" = "" ]; then
 echo "start eth0 vlan setup ..."       > /dev/console
 /etc/scripts/eth_vlan.sh start    > /dev/console
	sh /etc/templates/limitedadmin.sh	>	/dev/console
 sh /etc/templates/pingctl.sh    >   /dev/console
fi
	mkdir /var/servd	>	/dev/console
	servd&	>	/dev/console
	echo "start fresetd ..."	> /dev/console
	fresetd &
	echo "enable LAN ports ..."	> /dev/console
	/etc/scripts/enlan.sh		> /dev/console
	echo " Generate channel table according to the country code..."	> /dev/console
	genchanneltable		> /dev/console
	echo " Generate VLAN table according to the port..."	> /dev/console
	genVLANTableByPort	> /dev/console
	echo "start WAN ..."		> /dev/console
	/etc/scripts/misc/setwantype.sh	> /dev/console
	/etc/templates/wan.sh start	> /dev/console
	echo "captival_tar prep ..."		> /dev/console
	captival_tar prep > /dev/console
if [ "`rgdb -g  /runtime/nvram/mfc_phase`" = "0" ] || [ "`rgdb -g  /runtime/nvram/mfc_phase`" = "" ]; then
	echo "start stunnel ..."
	/etc/templates/stunnel.sh start > /dev/console
fi
 echo "start WLAN ..."     > /dev/console
 /etc/templates/wlan.sh start    > /dev/console
	echo "start telnet daemon ..." > /dev/console
	/etc/scripts/misc/telnetd.sh	> /dev/console
if [ "`rgdb -g  /runtime/nvram/mfc_phase`" = "0" ] || [ "`rgdb -g  /runtime/nvram/mfc_phase`" = "" ]; then
	echo "start SSHD daemon ..." > /dev/console
	/etc/templates/sshd.sh start	> /dev/console
	echo "start DHCP server"    > /dev/console
	/etc/templates/dhcpd.sh		> /dev/console
 echo "start SNMP ..."		> /dev/console
 /etc/templates/snmp.sh start   	> /dev/console
 echo "start cwmHelper ..."		> /dev/console
 cwmHelper &   	                > /dev/console
 echo "start NEAP ..."     > /dev/console
 /etc/templates/neaps.sh start    > /dev/console
 echo "start NEAPC ..."     > /dev/console
 /etc/templates/neapc.sh start    > /dev/console
 echo "start APNEAPS_V2 ..."     > /dev/console
 /etc/templates/apneaps_v2.sh start    > /dev/console
 echo "start AUTORFC ..."     > /dev/console
 /etc/templates/autorf.sh start    > /dev/console
 echo "start LOADBALANCE ..."     > /dev/console
 /etc/templates/loadbalance.sh start    > /dev/console
 echo "start Microsoft LLDP ..."     > /dev/console
 /etc/templates/lld2d.sh start    > /dev/console
echo "start Ethlink ..."     > /dev/console
ethlink &> /dev/console
echo "start Trap Monitor ..."     > /dev/console
trap_monitor &> /dev/console
 /etc/templates/arpspoofing.sh start   	> /dev/console
 sh /etc/templates/netfilter.sh     > /dev/console
echo "start DHCP multicast to unicast ..."                 
/etc/templates/dhcp_mc2uc.sh    > /dev/console
fi
	;;
stop)
 echo "stop SNMP ..."         	> /dev/console
 /etc/templates/snmp.sh stop    	> /dev/console
 echo "stop NEAP ..."          > /dev/console
 /etc/templates/neaps.sh stop     > /dev/console
 echo "stop NEAPC ..."          > /dev/console
 /etc/templates/neapc.sh stop     > /dev/console
 echo "stop APNEAPS_V2 ..."          > /dev/console
 /etc/templates/apneaps_v2.sh stop     > /dev/console
 echo "stop AUTORFC ..."          > /dev/console
 /etc/templates/autorf.sh stop     > /dev/console
 echo "stop LOADBALANCE ..."          > /dev/console
 /etc/templates/loadbalance.sh stop     > /dev/console
 echo "stop LLDP ..."          > /dev/console
 /etc/templates/lld2d.sh stop     > /dev/console
 /etc/templates/arpspoofing.sh stop    	> /dev/console
	echo "stop WAN ..."		> /dev/console
	/etc/templates/wan.sh stop	> /dev/console
	echo "stop fresetd ..."	> /dev/console
	killall fresetd
	echo "stop WLAN ..."		> /dev/console
	/etc/templates/wlan.sh stop	> /dev/console
	echo "stop LAN ..."		> /dev/console
	/etc/templates/lan.sh stop	> /dev/console
	echo "reset layout ..."	> /dev/console
	/etc/scripts/layout.sh	stop	> /dev/console
 echo "reset eth0 vlan ..."       > /dev/console
 /etc/scripts/eth_vlan.sh  stop    > /dev/console
	;;
restart)
	sleep 3
	$0 stop
	$0 start
	;;
*)
	echo "Usage: system.sh {start|stop|restart}"
	;;
esac
exit 0
