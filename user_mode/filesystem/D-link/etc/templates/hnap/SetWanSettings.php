HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
include "/htdocs/phplib/xnode.php";
$path_inf_wan1 = XNODE_getpathbytarget("", "inf", "uid", "WAN-1", 0);
$path_inf_wan2 = XNODE_getpathbytarget("", "inf", "uid", "WAN-2", 0);
$path_run_inf_wan1 = XNODE_getpathbytarget("/runtime", "inf", "uid", "WAN-1", 0);
$wan1_inet = query($path_inf_wan1."/inet"); 
$wan1_phyinf = query($path_inf_wan1."/phyinf");
$wan2_inet = query($path_inf_wan2."/inet");
$path_wan1_inet = XNODE_getpathbytarget("/inet", "entry", "uid", $wan1_inet, 0);
$path_wan1_phyinf = XNODE_getpathbytarget("", "phyinf", "uid", $wan1_phyinf, 0);
$path_wan2_inet = XNODE_getpathbytarget("/inet", "entry", "uid", $wan2_inet, 0); 

$nodebase="/runtime/hnap/SetWanSettings/";
$mac="";
$rlt="OK";
$Type=query($nodebase."Type");
$MacAddress=query($nodebase."MacAddress");
$IPAddress=query($nodebase."IPAddress");
$SubnetMask=query($nodebase."SubnetMask");
$Gateway=query($nodebase."Gateway");
$MTU=query($nodebase."MTU");
$Username=query($nodebase."Username");
$Password=query($nodebase."Password");
$MaxIdleTime=query($nodebase."MaxIdleTime");
$ServiceName=query($nodebase."ServiceName");
$AutoReconnect=query($nodebase."AutoReconnect");
$PriDns = query($nodebase."DNS/Primary");
$SecDns = query($nodebase."DNS/Secondary");
$OpenDnsEnable = query($nodebase."OpenDNS/enable");
$DNScount=0;
//if( $PriDns != "" || $SecDns != "" )
//{


	if($OpenDnsEnable == "true")
	{
		set("/advdns/enable", 1);
	}
	else
	{
		set("/advdns/enable", 0);
	}
//}

set($path_inf_wan1."/active", "1");
if($Type == "Static")
{
	set($path_inf_wan2."/active", "0");
	set($path_wan1_inet."/addrtype", "ipv4");
	set($path_wan1_inet."/ipv4/static", "1");
	anchor($path_wan1_inet."/ipv4");
	set($path_wan1_phyinf."/macaddr", $MacAddress);
	set("ipaddr", $IPAddress);
	set("mask", ipv4mask2int($SubnetMask));
	set("gateway", $Gateway);
	if($MTU == "0")
	{
		//$rlt = "ERROR_AUTO_MTU_NOT_SUPPORTED";
		set("mtu", 1500);
	}
	else
	{
		if($MTU >= 200 && $MTU <= 1500) { set("mtu", $MTU); }
		else	{ $rlt="ERROR"; }
	}
	if( $PriDns != "" || $SecDns != "" )	{	set("dns/count", "2"); }
	else	{	set("dns/count", "0");}
	set("dns/entry", $PriDns); 
	set("dns/entry:2", $SecDns);
}
else if($Type == "DHCP")
{	
	set($path_inf_wan2."/active", "0");
	set($path_wan1_inet."/addrtype", "ipv4");
	set($path_wan1_inet."/ipv4/static", "0");
	anchor($path_wan1_inet."/ipv4");
	set($path_wan1_phyinf."/macaddr", $MacAddress);
	if($MTU == "0")
	{
		//$rlt = "ERROR_AUTO_MTU_NOT_SUPPORTED";
		set("mtu", 1500);
	}
	else
	{
		if($MTU >= 200 && $MTU <= 1500) { set("mtu", $MTU); }
		else	{ $rlt="ERROR"; }
	}
	if( $PriDns != "" || $SecDns != "" )	{	set("dns/count", "2"); }
	else	{	set("dns/count", "0");}
	set("dns/entry", $PriDns);
	set("dns/entry:2", $SecDns);
}
else if($Type == "StaticPPPoE" || $Type == "DHCPPPPoE")     //-----PPPoE
{
	set($path_inf_wan2."/active", "0");
	set($path_wan1_inet."/addrtype", "ppp4");
	set($path_wan1_inet."/ppp4/over", "eth");
	anchor($path_wan1_inet."/ppp4");
	if($Type == "StaticPPPoE")
	{
		set("static", 1);
		set("ipaddr", $IPAddress);
	}
	else
	{
		set("static", 0);
	}


	set("username", $Username);
	set("password", $Password);
	set("dialup/idletimeout", $MaxIdleTime);
	set("pppoe/servicename", $ServiceName);
	if($MaxIdleTime>0)
	{
		set("dialup/mode", "ondemand");
	}
	else
	{
		set("dialup/mode", "auto");
	}
	if($AutoReconnect=="true")
	{
		set("dialup/mode", "auto");
	}
	else if($AutoReconnect=="false")
	{
		set("dialup/mode", "ondemand");
	}
	set($path_wan1_phyinf."/macaddr", $MacAddress);
	if($MTU == "0")
	{
		//$rlt = "ERROR_AUTO_MTU_NOT_SUPPORTED";
		set("mtu", 1492);
	}
	else
	{
		if($MTU >= 200 && $MTU <= 1492) { set("mtu", $MTU); }
		else	{ $rlt="ERROR"; }
	}
	if( $PriDns != "" || $SecDns != "" )
	{
		set("autodns", 0);
		set("dns/count", "2");
	}
	else	{	set("dns/count", "0");}
	set($path_run_inf_wan1."/inet/ppp4/dns", $PriDns);
	set($path_run_inf_wan1."/inet/ppp4/dns:2", $SecDns); 
	set("dns/entry", $PriDns);
	set("dns/entry:2", $SecDns);
}
else if($Type == "StaticPPTP" || $Type == "DynamicPPTP")     //-----PPTP
{
	set($path_inf_wan2."/active", "1");
	set($path_inf_wan1."/lowerlayer", "WAN-2");
	set($path_inf_wan2."/upperlayer", "WAN-1");
	set($path_inf_wan2."/nat", "");
	set($path_wan1_inet."/addrtype", "ppp4");
	set($path_wan1_inet."/ppp4/over", "pptp");
	anchor($path_wan1_inet."/ppp4");
	if($Type == "StaticPPTP")
	{
		set($path_wan2_inet."/ipv4/static", 1);
		set($path_wan2_inet."/ipv4/ipaddr", $IPAddress);
		set($path_wan2_inet."/ipv4/mask", ipv4mask2int($SubnetMask));
	}
	else
	{
		set($path_wan2_inet."/ipv4/static", 0);
	}

	set($path_wan2_inet."/ipv4/gateway", $Gateway); 
	set("username", $Username);
	set("password", $Password);
	set("dialup/idletimeout", $MaxIdleTime);
	set("pptp/server", $ServiceName);
	if($MaxIdleTime>0)
	{
		set("dialup/mode", "ondemand");
	}
	else
	{
		set("dialup/mode", "auto");
	}
	if($AutoReconnect=="true")
	{
		set("dialup/mode", "auto");
	}
	else if($AutoReconnect=="false")
	{
		set("dialup/mode", "ondemand");
	}
	set($path_wan1_phyinf."/macaddr", $MacAddress);
	if($MTU == "0")
	{
		//$rlt = "ERROR_AUTO_MTU_NOT_SUPPORTED";
		set("mtu", 1400);
	}
	else
	{
		if($MTU >= 200 && $MTU <= 1400) { set("mtu", $MTU); }
		else	{ $rlt="ERROR"; }
	}
	if( $PriDns != "" || $SecDns != "" )	{	set($path_wan2_inet."/ipv4/dns/count", "2");}
	else	{	set($path_wan2_inet."/ipv4/dns/count", "0");}
	set($path_wan2_inet."/ipv4/dns/entry", $PriDns);
	set($path_wan2_inet."/ipv4/dns/entry:2", $SecDns);
}
else if($Type == "StaticL2TP" || $Type == "DynamicL2TP")     //-----L2TP
{
	set($path_inf_wan2."/active", "1");
	set($path_inf_wan1."/lowerlayer", "WAN-2");
	set($path_inf_wan2."/upperlayer", "WAN-1");	
	set($path_inf_wan2."/nat", "");
	set($path_wan1_inet."/addrtype", "ppp4");
	set($path_wan1_inet."/ppp4/over", "l2tp");
	anchor($path_wan1_inet."/ppp4");
	if($Type == "StaticL2TP")
	{
		set($path_wan2_inet."/ipv4/static", 1);
		set($path_wan2_inet."/ipv4/ipaddr", $IPAddress);
		set($path_wan2_inet."/ipv4/mask", ipv4mask2int($SubnetMask));
	}
	else
	{
		set($path_wan2_inet."/ipv4/static", 0);
	}

	set($path_wan2_inet."/ipv4/gateway", $Gateway);
	set("l2tp/server", $ServiceName);
	set("username", $Username);
	set("password", $Password);
	set("dialup/idletimeout", $MaxIdleTime);
	if($MaxIdleTime>0)
	{
		set("dialup/mode", "ondemand");
	}
	else
	{
		set("dialup/mode", "auto");
	}
	if($AutoReconnect=="true")
	{
		set("dialup/mode", "auto");
	}
	else if($AutoReconnect=="false")
	{
		set("dialup/mode", "ondemand");
	}
	set($path_wan1_phyinf."/macaddr", $MacAddress);
	if($MTU == "0")
	{
		//$rlt = "ERROR_AUTO_MTU_NOT_SUPPORTED";
		set("mtu", 1400);
	}
	else
	{
		if($MTU >= 200 && $MTU <= 1400) { set("mtu", $MTU); }
		else	{ $rlt="ERROR"; }
	}
	if( $PriDns != "" || $SecDns != "" )	{	set($path_wan2_inet."/ipv4/dns/count", "2");}
	else	{	set($path_wan2_inet."/ipv4/dns/count", "0");}
	set($path_wan2_inet."/ipv4/dns/entry", $PriDns);
	set($path_wan2_inet."/ipv4/dns/entry:2", $SecDns);	
}
else
{
	$rlt = "ERROR_BAD_WANTYPE";
}


fwrite("w",$ShellPath, "#!/bin/sh\n");
fwrite("a",$ShellPath, "echo \"[$0]-->Wan Change\" > /dev/console\n");
if($rlt=="OK")
{
	fwrite("a",$ShellPath, "/etc/scripts/dbsave.sh > /dev/console\n");
	fwrite("a",$ShellPath, "service WAN restart > /dev/console\n");
    fwrite("a",$ShellPath, "xmldbc -s /runtime/hnap/dev_status '' > /dev/console\n");	
	set("/runtime/hnap/dev_status", "ERROR");
}
else
{
	fwrite("a",$ShellPath, "echo \"We got a error in setting, so we do nothing...\" > /dev/console\n");
}
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
<soap:Body>
<SetWanSettingsResponse xmlns="http://purenetworks.com/HNAP1/">
<SetWanSettingsResult><?=$rlt?></SetWanSettingsResult>
</SetWanSettingsResponse>
</soap:Body>
</soap:Envelope>
