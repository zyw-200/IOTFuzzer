#!/bin/sh
<? /* vi: set sw=4 ts=4: */
$mode=query("/wan/rg/inf:1/mode");
if($mode==1){
  anchor("/wan/rg/inf:1/static");
  }
else{
   anchor("/runtime/rg/wan/inf:1")
  }
$lanip	= query("ip");
$mask	= query("/lan/dhcp/server/pool:1/netmask");

echo "# DHCP server ...\n";
echo "IP=`chnet ".query("/lan/dhcp/server/pool:1/startip")." ".$lanip." ".$mask."`\n";
echo "xmldbc -s /lan/dhcp/server/pool:1/startip \"$IP\"\n";
echo "IP=`chnet ".query("/lan/dhcp/server/pool:1/endip")." ".$lanip." ".$mask."`\n";
echo "xmldbc -s /lan/dhcp/server/pool:1/endip \"$IP\"\n";
/* Setting this node will tell DHCP server to disable and reanble the LAN ports
 * when restarting DHCP server.			David Hsieh */
set("/runtime/dhcpd/disable_lan", "1");

echo "# Virtual Server ...\n";
for ("/nat/vrtsrv/entry")
{
	$priv_ip = query("internal/ip");
	if ($priv_ip!="0.0.0.0" && $priv_ip!="")
	{
		echo "IP=`chnet ".$priv_ip." ".$lanip." ".$mask."`\n";
		echo "xmldbc -s /nat/vrtsrv/entry:".$@."/internal/ip \"$IP\"\n";
	}
}

echo "# Static DHCP ...\n";
for ("/lan/dhcp/server/pool:1/staticdhcp/entry")
{
	$ipaddr = query("ip");
	if ($ipaddr!="0.0.0.0" && $ipaddr!="")
	{
		echo "IP=`chnet ".$ipaddr." ".$lanip." ".$mask."`\n";
		echo "xmldbc -s /lan/dhcp/server/pool:1/staticdhcp/entry:".$@."/ip \"$IP\"\n";
	}
}

echo "# DMZ ...\n";
$ipaddr = query("/nat/dmzsrv/ip");
if ($ipaddr!="0.0.0.0" && $ipaddr!="")
{
	echo "IP=`chnet ".query("/nat/dmzsrv/ip")." ".$lanip." ".$mask."`\n";
	echo "xmldbc -s /nat/dmzsrv/ip \"$IP\"\n";
}

echo "# Firewall rules...\n";
for("/security/firewall/entry")
{
	if (query("src/inf")=="1")	/* the infterface is LAN */
	{
		$ipaddr = query("src/startip");
		if ($ipaddr!="0.0.0.0" && $ipaddr!="")
		{
			echo "IP=`chnet ".$ipaddr." ".$lanip." ".$mask."`\n";
			echo "xmldbc -s /security/firewall/entry:".$@."/src/startip \"$IP\"\n";
		}
		$ipaddr = query("src/endip");
		if ($ipaddr!="0.0.0.0" && $ipaddr!="")
		{
			echo "IP=`chnet ".$ipaddr." ".$lanip." ".$mask."`\n";
			echo "xmldbc -s /security/firewall/entry:".$@."/src/endip \"$IP\"\n";
		}
	}
	if (query("dst/inf")=="1")	/* the infterface is LAN */
	{
		$ipaddr = query("dst/startip");
		if ($ipaddr!="0.0.0.0" && $ipaddr!="")
		{
			echo "IP=`chnet ".$ipaddr." ".$lanip." ".$mask."`\n";
			echo "xmldbc -s /security/firewall/entry:".$@."/dst/startip \"$IP\"\n";
		}
		$ipaddr = query("dst/endip");
		if ($ipaddr!="0.0.0.0" && $ipaddr!="")
		{
			echo "IP=`chnet ".$ipaddr." ".$lanip." ".$mask."`\n";
			echo "xmldbc -s /security/firewall/entry:".$@."/dst/endip \"$IP\"\n";
		}
	}
}

echo "# IP filter rules....\n";
for("/security/ipfilter/entry")
{
	if (query("src/inf")=="1")	/* the interface is LAN */
	{
		$ipaddr = query("src/startip");
		if ($ipaddr!="0.0.0.0" && $ipaddr!="")
		{
			echo "IP=`chnet ".$ipaddr." ".$lanip." ".$mask."`\n";
			echo "xmldbc -s /security/ipfilter/entry:".$@."/src/startip \"$IP\"\n";
		}
		$ipaddr = query("src/endip");
		if ($ipaddr!="0.0.0.0" && $ipaddr!="")
		{
			echo "IP=`chnet ".$ipaddr." ".$lanip." ".$mask."`\n";
			echo "xmldbc -s /security/ipfilter/entry:".$@."/src/endip \"$IP\"\n";
		}
	}
	if (query("dst/inf")=="1")	/* the interface is LAN */
	{
		$ipaddr = query("dst/startip");
		if ($ipaddr!="0.0.0.0" && $ipaddr!="")
		{
			echo "IP=`chnet ".$ipaddr." ".$lanip." ".$mask."`\n";
			echo "xmldbc -s /security/ipfilter/entry:".$@."/dst/startip \"$IP\"\n";
		}
		$ipaddr = query("dst/endip");
		if ($ipaddr!="0.0.0.0" && $ipaddr!="")
		{
			echo "IP=`chnet ".$ipaddr." ".$lanip." ".$mask."`\n";
			echo "xmldbc -s /security/ipfilter/entry:".$@."/dst/endip \"$IP\"\n";
		}
	}
}

/* remove all the session records */
require("/etc/templates/httpd/killallses.php");

?>
