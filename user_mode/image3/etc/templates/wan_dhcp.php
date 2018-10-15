<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

$router = query("/runtime/router/enable");
if ($router==1)	{ $wanif=query("/runtime/layout/wanif"); }
else			{ $wanif=query("/runtime/layout/lanif"); }

$dhcpc_pid="/var/run/udhcpc-".$wanif.".pid";

if ($generate_start == 1)
{
	if ($mode == 7)
	{
		anchor("/wan/rg/inf:1/bigpond");
		$hostname	= query("/sys/hostname");
		$clonemac	= query("clonemac");
		$autodns	= "1";
		$mtu		= "1500";
	}
	else
	{
		anchor("/wan/rg/inf:1/dhcp");
		$hostname	= query("/sys/hostname");
		$clonemac	= query("clonemac");
		$autodns	= query("autodns");
		$mtu		= query("mtu");
	}
	if ($mtu == "" || $mtu == "0") { $mtu = "1500"; }

	if ($router == 1)
	{
		$orig_wanmac=query("/runtime/layout/wanmac");
		$curr_wanmac=query("/runtime/wan/inf:1/mac");
		require($template_root."/clone_wanmac.php");
	}

	set("/runtime/wan/inf:1/connecttype", $mode);
	anchor("/runtime/wan/inf:1");
	set("connectstatus", "connecting");
	set("ip", "");
	set("netmask", "");
	set("gateway", "");
	set("primarydns", "");
	set("secondarydns", "");
	set("mtu", $mtu);

	echo "echo \"DHCP client on WAN(".$wanif.") CloneMAC(".$clonemac.") ...\" > /dev/console\n";
	if ($mtu != "" && $mtu != "0") { echo "ifconfig ".$wanif." mtu ".$mtu."\n"; }
	if ($hostname != "") { $HOST=" -H \"".$hostname."\""; }
	echo "rgdb -A ".$template_root."/dhcp/udhcpc.php > /var/run/udhcpc.sh\n";
	echo "chmod +x /var/run/udhcpc.sh\n";
	echo "sleep 1\n"; // peter
	echo "udhcpc -i ".$wanif." -p ".$dhcpc_pid.$HOST." -s /var/run/udhcpc.sh -D 2 -R 5 -S 300 &\n";
}
else
{
	echo "echo \"Stop DHCP client on WAN(".$wanif.") ...\" > /dev/console\n";
	echo "if [ -f ".$dhcpc_pid." ]; then\n";
	echo "	PID=`cat ".$dhcpc_pid."`\n";
	echo "	if [ $PID != 0 ]; then\n";
	echo "		kill -9 $PID > /dev/console 2>&1\n";
	echo "	fi\n";
	echo "	rm -f ".$dhcpc_pid."\n";
	echo "fi\n";
	echo "ifconfig ".$wanif." 0.0.0.0 > /dev/console\n";
	echo "while route del default gw 0.0.0.0 dev ".$wanif." ; do\n";
	echo "	:\n";
	echo "done\n";
	echo "[ -f ".$template_root."/wandown.sh ] && ".$template_root."/wandown.sh\n";
	echo "rgdb -i -s /runtime/wan/inf:1/connectstatus disconnected\n";
	echo "rgdb -i -s /runtime/wan/inf:1/ip \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:1/netmask \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:1/gateway \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:1/primarydns \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:1/secondarydns \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:1/interface \"\"\n";
	echo "rgdb -i -s /runtime/wan/inf:1/ac_ip \"\"\n";
}
?>
