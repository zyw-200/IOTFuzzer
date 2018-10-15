# wan_ip_setup >>>
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

$dhcpc_ppp_pid = "/var/run/udhcpc-ppp.pid";
$wanif = query("/runtime/layout/wanif");
$wanmode = query("/wan/rg/inf:1/mode");
if		($wanmode == "4")	{ anchor("/wan/rg/inf:1/pptp"); $PPPMODE="PPTP"; }
else if	($wanmode == "5")	{ anchor("/wan/rg/inf:1/l2tp"); $PPPMODE="L2TP"; }
else
{
	echo "echo \"Not in PPTP/L2TP mode ...\" > /dev/console\n";
	exit;
}

/* read configuration */
$dhcp_mode	= query("mode");
$ipaddr		= query("ip");
$subnet		= query("netmask");
$gateway	= query("gateway");
$dns		= query("dns");
$serverip	= query("serverip");

if ($generate_start == 1)
{
	if ($PPPMODE == "PPTP")	{ set("/runtime/wan/inf:1/connecttype", "4"); }
	else					{ set("/runtime/wan/inf:1/connecttype", "5"); }
	set("/runtime/wan/inf:1/ip", "");
	set("/runtime/wan/inf:1/netmask", "");
	set("/runtime/wan/inf:1/gateway", "");
	set("/runtime/wan/inf:1/primarydns", "");
	set("/runtime/wan/inf:1/secondarydns", "");
	set("/runtime/wan/inf:1/mtu", "");

	if ($dhcp_mode == 2)
	{
		echo "echo \"Setup DHCP for ".$PPPMODE." ...\" > /dev/console\n";
		echo "rgdb -A ".$template_root."/dhcp/dhcpc_ppp.php -V server=".$serverip." > /var/run/dhcpc_ppp.sh\n";
		echo "chmod +x /var/run/dhcpc_ppp.sh\n";
		echo "udhcpc -i ".$wanif." -p ".$dhcpc_ppp_pid." -H ".query("/sys/modelname");
		echo " -s /var/run/dhcpc_ppp.sh -D2 -R5 -S300 &\n";
	}
	else
	{
		echo "echo \"Setup Static IP for ".$PPPMODE." ...\" > /dev/console\n";
		echo "ifconfig ".$wanif." ".query("ip");
		if ($subnet != "" && $subnet != "0.0.0.0") { echo " netmask ".$subnet; }
		echo "\n";
		if ($dns != "" && $dns != "0.0.0.0")
		{
			echo "echo \"nameserver ".$dns."\" > /etc/resolv.conf\n";
			if ($dns != $gateway) { echo "route add -host ".$dns." gw ".$gateway." dev ".$wanif."\n"; }
		}
		echo "SERVER=`gethostip -d ".$serverip."`\n";
		echo "if [ \"$SERVER\" != \"\" ]; then\n";
		echo "	sh ".$template_root."/wan_ppp.sh start $SERVER > /dev/console\n";
		echo "else\n";
		echo "	echo \"Can not find server (".$serverip.") : $SERVER\" > /dev/console\n";
		echo "fi\n";
	}
}
else
{
	echo $template_root."/wan_ppp.sh stop > /dev/console\n";
	if ($dhcp_mode == 2)
	{
		echo "echo \"Stop DHCP of ".$PPPMODE." ...\" > /dev/console\n";
		echo "if [ -f ".$dhcpc_ppp_pid." ]; then\n";
		echo "	PID=`cat ".$dhcpc_ppp_pid."`\n";
		echo "	if [ $PID != 0 ]; then\n";
		echo "		kill $PID > /dev/console 2>&1\n";
		echo "	fi\n";
		echo "	rm -f ".$dhcpc_ppp_pid."\n";
		echo "fi\n";
	}
	else
	{
		echo "echo \"Stop Static IP of ".$PPPMODE." ...\" > /dev/console\n";
	}
	echo "ifconfig ".$wanif." 0.0.0.0 > /dev/console 2>&1\n";
}
?>
#wan_ip_setup <<<
