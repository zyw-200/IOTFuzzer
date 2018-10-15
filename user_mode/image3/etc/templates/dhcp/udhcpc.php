#!/bin/sh
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

$wanmode=query("/wan/rg/inf:1/mode");
if ($wanmode == 7)
{
	$autodns = 1;
}
else
{
	//$autodns=query("/wan/rg/inf:1/dhcp/autodns");
	if ($wanmode == 2)
        {
                $autodns = 1;
                set("/dnsrelay/server/primarydns", "");
                set("/dnsrelay/server/secondarydns", "");
        }
        else
        {
	$autodns = 1;
	$dns1=query("/dnsrelay/server/primarydns");
	$dns2=query("/dnsrelay/server/secondarydns");
	if ($dns1 != "" && $dns1 != "0.0.0.0") { $autodns=0; }
	if ($dns2 != "" && $dns2 != "0.0.0.0") { $autodns=0; }
	}
}

?>
echo "[$0] $1" > /dev/console
RESOLV_CONF="/etc/resolv.conf"

case "$1" in
deconfig)
	echo "deconfig $interface" > /dev/console
	if [ `rgdb -i -g /runtime/wan/inf:1/connectstatus` != "connected" ]; then
		echo "DHCP is not connected !" > /dev/console
		exit 0
	fi

	echo "deleting routers" > /dev/console
	while route del default gw 0.0.0.0 dev $interface ; do
		:
	done
	ifconfig $interface 0.0.0.0
	[ -f <?=$template_root?>/wandown.sh ] && <?=$template_root?>/wandown.sh > /dev/console &
	rgdb -i -s /runtime/wan/inf:1/connectstatus disconnceted
	rgdb -i -s /runtime/wan/inf:1/ip ""
	rgdb -i -s /runtime/wan/inf:1/netmask ""
	rgdb -i -s /runtime/wan/inf:1/gateway ""
	rgdb -i -s /runtime/wan/inf:1/primarydns ""
	rgdb -i -s /runtime/wan/inf:1/secondarydns ""
	rgdb -i -s /runtime/wan/inf:1/domain ""
	rgdb -i -s /runtime/wan/inf:1/interface ""
	rgdb -i -s /runtime/wan/inf:1/mtu ""
	rgdb -i -s /runtime/wan/inf:1/ac_ip "$ac_ip"
	;;

renew|bound)
	echo "config $interface $ip/$subnet/$broadcast ac:$ac_ip" > /dev/console
	rgdb -i -s /runtime/wan/inf:1/dhcpdenable 1;
	[ -n "$broadcast" ] && BROADCAST="broadcast $broadcast"
	[ -n "$subnet" ] && NETMASK="netmask $subnet"
	ifconfig $interface $ip $BROADCAST $NETMASK
	rgdb -i -s /runtime/wan/inf:1/ip "$ip"
	rgdb -i -s /runtime/wan/inf:1/netmask "$subnet"
	rgdb -i -s /runtime/wan/inf:1/interface "$interface"
	rgdb -i -s /runtime/wan/inf:1/ac_ip "$ac_ip"

	if [ -n "$router" ]; then
		for i in $router ; do
			route add default gw $i dev $interface
			rgdb -i -s /runtime/wan/inf:1/gateway "$i"
			echo "Add default gw $i" > /dev/console
		done
	fi

	echo -n > $RESOLV_CONF
<?
	if ($autodns==0)
	{
		if ($dns1 != "" && $dns1 != "0.0.0.0")
		{
			echo "	echo \"nameserver ".$dns1."\" >> $RESOLV_CONF\n";
			echo "	rgdb -i -s /runtime/wan/inf:1/primarydns \"".$dns1."\"\n";
		}
		if ($dns2 != "" && $dns2 != "0.0.0.0")
		{
			echo "	echo \"nameserver ".$dns2."\" >> $RESOLV_CONF\n";
			echo "	rgdb -i -s /runtime/wan/inf:1/secondarydns \"".$dns2."\"\n";
		}
	}
	else
	{
		echo "	PDNS=\"\"\n";
		echo "	for i in $dns ; do\n";
		echo "		echo \"adding dns $i...\" > /dev/console\n";
		echo "		echo \"nameserver $i\" >> $RESOLV_CONF\n";
		echo "		if [ \"$PDNS\" = \"\" ]; then\n";
		echo "			rgdb -i -s /runtime/wan/inf:1/primarydns \"$i\"\n";
		echo "			PDNS=\"$i\"\n";
		echo "		else\n";
		echo "			rgdb -i -s /runtime/wan/inf:1/secondarydns \"$i\"\n";
		echo "		fi\n";
		echo "	done\n";
	}
?>
	[ -n "$domain" ] && echo search $domain >> $RESOLV_CONF
	rgdb -i -s /runtime/wan/inf:1/domain "$domain"
	rgdb -i -s /runtime/wan/inf:1/lease "$lease"
	rgdb -i -s /runtime/wan/inf:1/connectstatus connected
	if [ "$1" = "renew" ]; then
		exit 0
	fi
	sh /etc/templates/dhcpd.sh
	[ -f <?=$template_root?>/wanup.sh ] && <?=$template_root?>/wanup.sh > /dev/console &
	[ -f <?=$template_root?>/wlan.sh ] && <?=$template_root?>/wlan.sh restart > /dev/console &
	;;
esac
exit 0
