#!/bin/sh
echo "--- Start of tspc configuration script. ---" > /dev/console
echo "Script: " `basename $0` > /dev/console

ifconfig=/sbin/ifconfig
route=/sbin/route
ipconfig=/bin/ip
tspcradvd=/usr/sbin/radvd
sysctl=/sbin/sysctl
tspcradvdconfigfilename=tspcradvd.conf
tspcradvdconfigfile=$TSP_HOME_DIR/$tspcradvdconfigfilename

if [ -z $TSP_HOME_DIR ]; then
	echo "TSP_HOME_DIR variable not specified!;" > /dev/console
	exit 1
fi

if [ ! -d $TSP_HOME_DIR ]; then
	echo "Error : directory $TSP_HOME_DIR does not exist" > /dev/console
	exit 1
fi

if [ -z $TSP_HOST_TYPE ]; then
	echo "Error: TSP_HOST_TYPE not defined." > /dev/console
	exit 1
fi

if [ X"${TSP_HOST_TYPE}" = X"host" ] || [ X"${TSP_HOST_TYPE}" = X"router" ]; then
	#
	# Configured tunnel config (IPv6) 
	echo "$TSP_TUNNEL_INTERFACE setup" > /dev/console
	if [ X"${TSP_TUNNEL_MODE}" = X"v6v4" ]; then
		echo "Setting up link to $TSP_SERVER_ADDRESS_IPV4" > /dev/console
		if [ -x $ipconfig ]; then
			$ipconfig tunnel del $TSP_TUNNEL_INTERFACE
			$ipconfig tunnel add $TSP_TUNNEL_INTERFACE mode sit ttl 64 remote $TSP_SERVER_ADDRESS_IPV4
		else
			$ifconfig $TSP_TUNNEL_INTERFACE tunnel ::$TSP_SERVER_ADDRESS_IPV4
		fi
	fi
	
	$ifconfig $TSP_TUNNEL_INTERFACE up
	echo 0 > /proc/sys/net/ipv6/conf/"${TSP_TUNNEL_INTERFACE}"/disable_ipv6
	
	PREF=`echo $TSP_CLIENT_ADDRESS_IPV6 | sed "s/:0*/:/g" |cut -d : -f1-2`
	OLDADDR=`$ifconfig $TSP_TUNNEL_INTERFACE | grep "inet6.* $PREF" | sed -e "s/^.*inet6 addr: //" -e "s/ Scope.*\$//"`
	if [ ! -z $OLDADDR ]; then
		echo "Removing old IPv6 address $OLDADDR" > /dev/console
		$ifconfig $TSP_TUNNEL_INTERFACE inet6 del $OLDADDR
	fi
	echo "This host is: $TSP_CLIENT_ADDRESS_IPV6/$TSP_TUNNEL_PREFIXLEN" > /dev/console
	$ifconfig $TSP_TUNNEL_INTERFACE add $TSP_CLIENT_ADDRESS_IPV6/$TSP_TUNNEL_PREFIXLEN
	$ifconfig $TSP_TUNNEL_INTERFACE mtu 1280
	xmldbc -s <?=$STSP?>/inet/ipv6/ipaddr $TSP_CLIENT_ADDRESS_IPV6
	xmldbc -s <?=$STSP?>/inet/ipv6/prefix $TSP_TUNNEL_PREFIXLEN
	# 
	# Default route  
	echo "Adding default route" > /dev/console
	$route -A inet6 del 2000::/3 2>/dev/null  # delete old gw route
	$route -A inet6 add 2000::/3 dev $TSP_TUNNEL_INTERFACE
fi

# Router configuration if required
if [ X"${TSP_HOST_TYPE}" = X"router" ]; then
	echo "Router configuration" > /dev/console
	echo "Kernel setup" > /dev/console
	if [ X"${TSP_PREFIXLEN}" != X"64" ]; then
		#Better way on linux to avoid loop with the remaining /48?
		$route -A inet6 del $TSP_PREFIX::/$TSP_PREFIXLEN dev $TSP_HOME_INTERFACE 2>/dev/null
		$route -A inet6 add $TSP_PREFIX::/$TSP_PREFIXLEN dev $TSP_HOME_INTERFACE
	fi
	$sysctl -q -w net.ipv6.conf.all.forwarding=1 # ipv6_forwarding enabled
	echo "Adding prefix to $TSP_HOME_INTERFACE" > /dev/console
	OLDADDR=`$ifconfig $TSP_HOME_INTERFACE | grep "inet6.* $PREF" | sed -e "s/^.*inet6 addr: //" -e "s/ Scope.*\$//"`
	if [ ! -z $OLDADDR ]; then
		echo "Removing old IPv6 address $OLDADDR" > /dev/console
		$ifconfig $TSP_HOME_INTERFACE inet6 del $OLDADDR
	fi
	$ifconfig $TSP_HOME_INTERFACE add $TSP_PREFIX::1/64
	xmldbc -s <?=$LANSTSP?>/inet/ipv6/ipaddr $TSP_PREFIX::1
	xmldbc -s <?=$LANSTSP?>/inet/ipv6/prefix 64
	# Router advertisement configuration 
	echo "Create new $tspcradvdconfigfile"
	echo "##### tspcradvd.conf made by TSP ####" > "$tspcradvdconfigfile"
	echo "interface $TSP_HOME_INTERFACE" >> "$tspcradvdconfigfile"
	echo "{" >> "$tspcradvdconfigfile"
	echo " AdvSendAdvert on;" >> "$tspcradvdconfigfile"
	echo " prefix $TSP_PREFIX::/64" >> "$tspcradvdconfigfile"
	echo " {" >> "$tspcradvdconfigfile"
	echo " AdvOnLink on;" >> "$tspcradvdconfigfile"
	echo " AdvAutonomous on;" >> "$tspcradvdconfigfile"
	echo " };" >> "$tspcradvdconfigfile"
	echo "};" >> "$tspcradvdconfigfile"
	echo "" >> "$tspcradvdconfigfile"
	killall radvd
	if [ -f $tspcradvdconfigfile ]; then
		$tspcradvd -C $tspcradvdconfigfile
		echo "Starting radvd: $tspcradvd -C $tspcradvdconfigfile"
	else
		echo "Error : file $tspcradvdconfigfile not found"
		exit 1
	fi
	xmldbc -s <?=$STSP?>/inet/ipv6/ipv6in4/tsp/prefix    $TSP_PREFIX
	xmldbc -s <?=$STSP?>/inet/ipv6/ipv6in4/tsp/prefixlen $TSP_PREFIXLEN 
fi

echo "--- End of tspc configuration script. ---" > /dev/console

exit 0

