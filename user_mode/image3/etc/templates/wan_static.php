<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

$ip_inuse=query("/runtime/ip_inuse");
$router = query("/runtime/router/enable");
if ($router == 1)	{ $wanif = query("/runtime/layout/wanif"); }
else				{ $wanif = query("/runtime/layout/lanif"); }

if ($generate_start == 1)
{
	anchor("/wan/rg/inf:1/static");
	$ipaddr  = query("ip");
	$netmask = query("netmask");
	$gateway = query("gateway");
	$mtu     = query("mtu");
	if ($mtu=="" || $mtu=="0") { $mtu="1500"; }
	
	anchor("/dnsrelay/server");
	$pri_dns = query("primarydns");
	$sec_dns = query("secondarydns");

	if ($router == 1)
	{
		$orig_wanmac= query("/runtime/layout/wanmac");
		$curr_wanmac= query("/runtime/wan/inf:1/mac");
		$clonemac	= query("/wan/rg/inf:1/static/clonemac");
		require($template_root."/clone_wanmac.php");
	}

	set("/runtime/wan/inf:1/connecttype", "1");
	anchor("/runtime/wan/inf:1");
	set("connectstatus", "connected");
	set("ip", $ipaddr);
	set("netmask", $netmask);
	set("gateway", $gateway);
	set("primarydns", $pri_dns);
	set("secondarydns", $sec_dns);
	set("interface", $wanif);
	set("mtu", $mtu);

	//traveller add for trap 16 ip change
	if($ip_inuse != $ipaddr){
		echo "sendtrapbin [SNMP-TRAP][Specific=16]";
		echo "\n";
	}
	//traveller end
	$param="";
	if ($netmask != "" && $netmask != "0.0.0.0")	{ $param=$param." netmask ".$netmask; }
	if ($mtu != "" && $mtu != "0")					{ $param=$param." mtu ".$mtu; }
	echo "ifconfig ".$wanif." ".$ipaddr.$param."\n";
	echo "echo \"Start WAN(".$wanif."),".$ipaddr."/".$netmask." ...\" > /dev/console\n";

	if ($gateway != "" && $gateway != "0.0.0.0")	{ echo "route add default gw ".$gateway." dev ".$wanif."\n"; }

	/* set Broadcast D class ip routes to go via "wanif" */
	echo "route add -net 224.0.0.0 netmask 240.0.0.0 dev ".$wanif." \n";

	echo "echo -n > /etc/resolv.conf\n";
	if ($pri_dns != "" && $pri_dns != "0.0.0.0")	{ echo "echo \"nameserver ".$pri_dns."\" >> /etc/resolv.conf\n"; }
	if ($sec_dns != "" && $sec_dns != "0.0.0.0")	{ echo "echo \"nameserver ".$sec_dns."\" >> /etc/resolv.conf\n"; }

	echo $template_root."/wanup.sh > /dev/console\n";
}

if ($generate_start==0)
{
	echo "echo \"Stop WAN ...\" > /dev/console\n";
	echo "[ -f ".$template_root."/wandown.sh ] && ".$template_root."/wandown.sh > /dev/console\n";
	echo "ifconfig ".$wanif." 0.0.0.0 > /dev/console 2>&1\n";
	echo "route del default gw 0.0.0.0 dev ".$wanif." > /dev/console\n";
	echo "rgdb -i -s /runtime/wan/inf:1/connectstatus disconnected\n";
	echo "rgdb -i -s /runtime/wan/inf:1/ip \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:1/netmask \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:1/gateway \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:1/primarydns \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:1/secondarydns \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:1/interface \"\"\n";
	echo "rgdb -s -s /runtime/wan/inf:1/mtu \"\"\n";
}

?>
