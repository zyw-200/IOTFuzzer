<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

$dhcpd_pidf ="/var/run/udhcpd-".$dhcpd_if.".pid";
$dhcpd_conf ="/var/run/udhcpd-".$dhcpd_if.".conf";
$dhcpd_lease="/var/run/udhcpd-".$dhcpd_if.".lease";
$dhcpd_patch="/var/run/dhcppatch-".$dhcpd_if.".pid";

if ($generate_start==1)
{
	echo "echo \"Start DHCP server (".$dhcpd_if.") ...\" > /dev/console\n";
//	if ($dhcpd_clearleases==1) { echo "echo -n > ".$dhcpd_lease."\n"; } /* dennis 2008-01-23 dhcp server */
	//if (query("/runtime/router/enable")!="1")
//	{
//		echo "echo Router function is off, DHCP server will not be enabled !!! > /dev/console\n";
//	}
/* dennis 2008-01-23 dhcp server start */
/* if dhcpc not get ip so dhcp server not enable start */
	if(query("/wan/rg/inf:1/mode") != "1")
	{
		if(query("/runtime/wan/inf:1/dhcpdenable") != "1" ){
			exit;
		}
	}
	$force_bcast = query("/lan/dhcp/server/force_bcast");//dhcp_force_broadcast
	if ($force_bcast!="")
        {
        	if($force_bcast=="1"){echo "brctl dhcp_force_broadcast_enable br0 1 > /dev/console\n";}
                else                 {echo "brctl dhcp_force_broadcast_enable br0 0 > /dev/console\n";}
        }
       $force_ucast = query("/lan/dhcp/server/force_ucast");
       if ($force_ucast!="")
       {
       if($force_ucast=="1"){echo "brctl dhcp_force_unicast_enable br0 1 > /dev/console\n";}
       else                 {echo "brctl dhcp_force_unicast_enable br0 0 > /dev/console\n";}
       }

/* dennis 2008-01-23 dhcp server end */
	if (query("/lan/dhcp/server/enable") != "1")
	{
		echo "echo DHCP server is disabled! > /dev/console\n";
		echo "brctl dhcp_server_enable br0 0 > /dev/console\n";/*dhcp server no provide to eth0's pc 2008-01-23 dennis */
	}	
	else
	{
		//$ipaddr=query("/lan/ethernet/ip");
		//$netmask=query("/lan/ethernet/netmask");
		anchor("/lan/dhcp/server/pool:1");
		$ipstr=query("startip");
		$ipend=query("endip");
		$dmain=query("domain");
		$wins0=query("primarywins");
		$wins1=query("secondarywins");
		$ltime=query("leasetime");
		$lanip = query("/wan/rg/inf:1/static/ip");/*AP's IP address*/
/* dennis 2008-01-23 dhcp server start */
        $dhcpdns1=query("dns1");
		$gateway=query("gateway");
		$netmask=query("netmask");
/* dennis 2008-01-23 dhcp server end */
		$force_bcast = query("/lan/dhcp/server/force_bcast");
       		$force_ucast = query("/lan/dhcp/server/force_ucast");
		if ($ltime == "") { $ltime="8640"; }
		if ($dmain == "") { $dmain=query("/runtime/wan/inf:1/domain"); }

		/* clear leases */
		if ($dhcpd_clearleases == 1)
		{
			if ($ipaddr != query("/runtime/dhcpserver/lan/ipaddr") ||
				$netmask!= query("/runtime/dhcpserver/lan/netmask")||
				$ipstr	!= query("/runtime/dhcpserver/lan/startip")||
				$ipend	!= query("/runtime/dhcpserver/lan/endip"))
			{
				echo "echo -n > ".$dhcpd_lease."\n";
				set("/runtime/dhcpserver/lan/ipaddr",	$ipaddr);
				set("/runtime/dhcpserver/lan/netmask",	$netmask);
				set("/runtime/dhcpserver/lan/startip",	$ipstr);
				set("/runtime/dhcpserver/lan/endip",	$ipend);
			}
		}

		fwrite2($dhcpd_conf, "start ".		$ipstr."\n");
		fwrite2($dhcpd_conf, "end ".		$ipend."\n");
		fwrite2($dhcpd_conf, "lan_ip ".		$lanip."\n");
		fwrite2($dhcpd_conf, "interface ".	$dhcpd_if."\n");
		fwrite2($dhcpd_conf, "lease_file ".	$dhcpd_lease."\n");
		fwrite2($dhcpd_conf, "pidfile ".	$dhcpd_pidf."\n");
		fwrite2($dhcpd_conf, "auto_time ".	"10\n");
		fwrite2($dhcpd_conf, "opt lease ".	$ltime."\n");
		fwrite2($dhcpd_conf, "opt dns ".    $dhcpdns1."\n");/* dennis 2008-01-23 dhcp server */
		if ($dmain!="")	{ fwrite2($dhcpd_conf, "opt domain ".	$dmain."\n"); }
		if ($netmask!="")	{ fwrite2($dhcpd_conf, "opt subnet ".$netmask."\n"); }
		if ($gateway!="")	{ fwrite2($dhcpd_conf, "opt router ".$gateway."\n"); }/* dennis 2008-01-23 dhcp server */
		if ($wins0!="")		{ fwrite2($dhcpd_conf, "opt wins ".$wins0."\n"); }
		if ($wins1!="")		{ fwrite2($dhcpd_conf, "opt wins ".$wins1."\n"); }
		if ($force_bcast!="")
		{
			if($force_bcast=="1"){fwrite2($dhcpd_conf, "force_bcast yes\n");}
			else		     {fwrite2($dhcpd_conf, "force_bcast no\n");}
		}
		if ($force_ucast!="")
		{
			if($force_ucast=="1"){fwrite2($dhcpd_conf, "force_ucast yes\n");}
			else		     {fwrite2($dhcpd_conf, "force_ucast no\n");}
		}
/* dennis 2008-01-23 dhcp server start */
//		if (query("/dnsrelay/mode") != "1")
//		{
//			fwrite2($dhcpd_conf, "opt dns ".$ipaddr."\n");
//		}
//		else
//		{
//			$dns = query("/runtime/wan/inf:1/primarydns");
//			if ($dns != "")	{ fwrite2($dhcpd_conf, "opt dns ".$dns."\n"); }
//			$dns=query("/runtime/wan/inf:1/secondarydns");
//			if ($dns != "")	{ fwrite2($dhcpd_conf, "opt dns ".$dns."\n"); }
//		}
/* dennis 2008-01-23 dhcp server end */
		if (query("staticdhcp/enable") == 1)
		{
			for ("/lan/dhcp/server/pool:1/staticdhcp/entry")
			{
				if (query("enable") == 1)
				{
				    $hostname=query("hostname");
					$ip=query("ip");
					$mac=query("mac");
					$dns=query("dns1");
					$domain=query("domain");
					$ipmask=query("netmask");
					$gway=query("gateway");
					$wins=query("primarywins");
					fwrite2($dhcpd_conf, "static ".$hostname." ".$ip." ".$mac." ");
					if($dns!="") {fwrite2($dhcpd_conf,"dns ".$dns." ");}
					else if($dhcpdns1!="") {fwrite2($dhcpd_conf,"dns ".$dhcpdns1." ");}
					if($domain!="") {fwrite2($dhcpd_conf,"domain ".$domain." ");}
					else if($dmain!="") {fwrite2($dhcpd_conf,"domain ".$dmain." ");}
					if($ipmask!="") {fwrite2($dhcpd_conf,"subnet ".$ipmask." ");}
					else if($netmask!="") {fwrite2($dhcpd_conf,"subnet ".$netmask." ");}
					if($gway!="") {fwrite2($dhcpd_conf,"router ".$gway." ");}
					else if($gateway!="") {fwrite2($dhcpd_conf,"router ".$gateway." ");}
					if($wins!="") {fwrite2($dhcpd_conf,"wins ".$wins." ");}
					else if($wins0!="") {fwrite2($dhcpd_conf,"wins ".$wins0." ");}
					fwrite2($dhcpd_conf,"\n");
				}
			}
		}
		
		echo "udhcpd ".$dhcpd_conf." &\n";
		echo "dhcpxmlpatch -f ".$dhcpd_lease." &\n";
		echo "echo $! > ".$dhcpd_patch."\n";
		echo "brctl dhcp_server_enable br0 1 > /dev/console\n";/*dhcp server no provide to eth0's pc 2008-01-23 dennis */
	}
}
else
{
	echo "echo \"Stop DHCP server (".$dhcpd_if.") ...\" > /dev/console\n";
	echo "brctl dhcp_server_enable br0 0 > /dev/console\n";/*dhcp server no provide to eth0's pc2008-01-23 dennis */
	echo "brctl dhcp_force_broadcast_enable br0 0 > /dev/console\n"; //dhcp_force_broadcast	
	 echo "brctl dhcp_force_unicast_enable br0 0 > /dev/console\n";
/* dennis 2008-01-23 dhcp server start */
//	if (query("/runtime/router/enable")!="1")
//	{
//		echo "echo DHCP server is not enabled ! > /dev/console\n";
//	}
//	else
//	{
/* dennis 2008-01-23 dhcp server end */
		echo "if [ -f ".$dhcpd_patch." ]; then\n";
		echo "	pid=`cat ".$dhcpd_patch."`\n";
		echo "	if [ $pid != 0 ]; then\n";
		echo "		kill $pid > /dev/console 2>&1\n";
		echo "	fi\n";
		echo "	rm -f ".$dhcpd_patch."\n";
		echo "fi\n";

		echo "if [ -f ".$dhcpd_pidf." ]; then\n";
		echo "	pid=`cat ".$dhcpd_pidf."`\n";
		echo "	if [ $pid != 0 ]; then\n";
		echo "		kill $pid > /dev/console 2>&1\n";
		echo "	fi\n";
		echo "	rm -f ".$dhcpd_pidf."\n";
		echo "fi\n";
//	}/* dennis 2008-01-23 dhcp server */
/* erial, fix dhcp server function bug */
        echo "if [ -f ".$dhcpd_lease." ]; then\n";
        echo "  rm -f ".$dhcpd_lease."\n";
        echo "fi\n";
/* erial, fix dhcp server function bug */
}
?>
