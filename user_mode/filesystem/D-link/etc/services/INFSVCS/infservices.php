<? /* vi: set sw=4 ts=4: */
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";

function startcmd($cmd)	{fwrite(a,$_GLOBALS["START"],$cmd."\n");}
function stopcmd($cmd)	{fwrite(a,$_GLOBALS["STOP"], $cmd."\n");}
function error($err)	{startcmd("exit ".$err); stopcmd("exit ".$err); return $err;}

/****************************************************************/
function infsvcs_setup($name, $cfg, $sts, $ipv6)
{
	anchor($cfg);

	$web	= query("web");
	//$hnap	= query("hnap");
	$upnp	= query("upnp/count");
	$dhcps4	= query("dhcps4");
	$dhcpc6	= query("dhcpc6");
	$dhcps6	= query("dhcps6");
	$ddns4	= query("ddns4");
	$ddns6	= query("ddns6");
	$dns4	= query("dns4");
	$dns6	= query("dns6");
	$dns    = query("dns");
	$defrt	= query("defaultroute");
	$bwc 	= query("bwc");
	$sshd   = query("sshd"); //sandy add
	$neap   = query("neap"); //sam_pan add
	$nameresolve   = query("nameresolve"); //sam_pan add
    $next   = query("infnext");
	$child  = query("child");

	//if (""!=$web)		startcmd("service HTTP."		.$name." start");
	if (""!=$web && $ipv6>0)
	{	
			startcmd("sleep 1");	
			startcmd("service HTTP."		.$name." start");
	}
	else	startcmd("service HTTP."		.$name." start");

	//if ($hnap==1)		startcmd("service HNAP."		.$name." start");
	if ($upnp>0)		startcmd("service UPNP."		.$name." start");
	if ($neap>0)		startcmd("service NEAP."		.$name." start"); //sam_pan add
	if ($nameresolve>0)	startcmd("service NAMERESOLV."	.$name." start");

	if ($ipv6>0)
	{
		if (""!=$dhcps6)	startcmd("service DHCPS6."	.$name." restart");//rbj
		if (""!=$ddns6)		startcmd("service DDNS6."	.$name." start");
		if (""!=$dns6)		startcmd("service DNS6."	.$name." start");
		if (""!=$sshd)		startcmd("service SSH6."	.$name." start"); //sandy add
	}
	else
	{
		if (""!=$dhcps4)	startcmd("service DHCPS4."	.$name." start");
		if (""!=$ddns4)		startcmd("service DDNS4."	.$name." start");
		if (""!=$dns4)		startcmd("service DNS4."	.$name." start");
		if (""!=$sshd)		startcmd("service SSH4."	.$name." start"); //sandy add
	}

	if (""!=$dns)
	{
		startcmd("service DNS.INF alias DNS");
		startcmd("service DNS start"); /* Unified DNS services. */
	}

	if (""!=$dhcpc6 && "0"!=$dhcpc6)	startcmd("service DHCPC6."		.$name." start");

	if (""!=$bwc) startcmd("service BWC.".$name." restart");

	startcmd("event ".$name.".CONNECTED");
	//if (""!=$next)	startcmd("service INET.".$next." start");
	if (""!=$next)
	{
			//startcmd("sleep 20");//wait for dslite info */	
			startcmd("service INET.".$next." start");
	}
	if (""!=$child)
	{
		//startcmd("service INET.CHILD.".$child." alias INF.CHILD.".$child);
		//startcmd("service INET.CHILD.".$child." restart");
		startcmd("service INET.".$child." restart");
	}

	stopcmd("event ".$name.".DISCONNECTED");

	/* Stop services .................................................. */
	/*if (""!=$child)		stopcmd("service INET.CHILD."	.$child." stop");*/
	/*if (""!=$child)		stopcmd("service INET."			.$child." stop");*/
	if (""!=$child)		stopcmd("phpsh /etc/scripts/stopchild.php CHILDUID=".$child);
	if (""!=$next)		stopcmd("service INET."			.$next." stop");
	if (""!=$bwc)		stopcmd("service BWC."			.$name." stop");
	if (""!=$dhcpc6)	stopcmd("service DHCPC6."		.$name." stop");
	if (""!=$dns)       stopcmd("service DNS stop");

	/* These services may be started after the interface was up.
	 * Stop them even if they were not started at the interface up. */
	if ($ipv6>0)
	{
		stopcmd("service DNS6."		.$name." stop");
		stopcmd("service DDNS6."	.$name." stop");
		stopcmd("service DHCPS6."	.$name." stop");
        stopcmd("service SSH6."		.$name." stop"); //sandy add
	}
	else
	{
		stopcmd("service DNS4."		.$name." stop");
		stopcmd("service DDNS4."	.$name." stop");
		stopcmd("service DHCPS4."	.$name." stop");
        stopcmd("service SSH4."		.$name." stop"); //sandy add
	}

	stopcmd("service UPNP."		.$name." stop");
	stopcmd("service HTTP."		.$name." stop");

	//if ($hnap==1)		stopcmd("service HNAP."		.$name." stop");
	if ($neap>0)		stopcmd("service NEAP."		.$name." stop");
	if ($nameresolve>0)	stopcmd("service NAMERESOLV.".$name." stop");
}

function infsvcs_wan($index)
{
	$uid = "WAN-".$index;
	$cfg = XNODE_getpathbytarget("", "inf", "uid", $uid, 0);
	$sts = XNODE_getpathbytarget("/runtime", "inf", "uid", $uid, 0);
	if ($cfg=="" || $sts=="")
	{
		SHELL_info($_GLOBALS["START"], "infsvcs_setup: (".$uid.") not exist.");
		SHELL_info($_GLOBALS["STOP"],  "infsvcs_setup: (".$uid.") not exist.");
		return error(9);
	}
	$upper		= query($cfg."/upperlayer");
	$lower		= query($cfg."/lowerlayer");
	$addrtype	= query($sts."/inet/addrtype");
	if ($addrtype=="ipv6" || $addrtype=="ppp6") $ipv6=1; else $ipv6=0;

	/* Firewall */
	$fw	= query("/acl/firewall/count")+query("/acl/firewall2/count")+query("/acl/firewall3/count");
	/* IPv6 Firewall */
	$fw6= query("/acl6/firewall/count")+0;

	/* Tell everybody, we are going down.
	 * Trigger this event before anything. */
	stopcmd("event INFSVCS.".$uid.".DOWN");


	/*************************************************************************/
	/* TODO: The following code is a wrong example and need to be corrected.
	 * Never ever assume an interface will have a certain type. WAN-3 is not
	 * always a 3G interface.	David Hsieh <david_hsieh@alphanetworks.com> */

	/* Some 3G adapters cannot disconnect ,they need to cmd some AT command. */
	if ($index == "3") stopcmd("event DIALINIT");
	/*************************************************************************/

	stopcmd("event UPNP.IGD.NOTIFY.WANIPCONN1");
	stopcmd("event UPDATERESOLV");

	/*************************************************************************/
	/* TODO: The following code is problematic, it will only be run at UP, not DOWN.
	 * David Hsieh <david_hsieh@alphanetworks.com> */

	/* To make sure "Automatic Uplink Speed" of QOS can re-detect when wan up/down. (sam_pan)*/
	set("/runtime/device/qos/bwup","0");
	set("/runtime/device/qos/monitor","0");
	/*************************************************************************/

	/* If we have no lowerlayer, we need to restart these services. */
	if ($lower=="")
	{
		if ($ipv6>0)
		{
			if ($fw6>0) stopcmd("service FIREWALL6 restart");
			stopcmd("service IP6TDEFCHAIN restart");
			stopcmd("service ROUTE6.DYNAMIC restart");
			stopcmd("service ROUTE6.STATIC restart");
		}
		else
		{
			stopcmd("service QOS restart");
			stopcmd("service MULTICAST restart");
			stopcmd("service ROUTE.STATIC restart");
			stopcmd("service ROUTE.DESTNET restart");
			stopcmd("service ROUTE.DOMAIN restart");
			stopcmd("service ROUTE.IPUNNUMBERED restart");
			stopcmd("service IPTDEFCHAIN restart");
			if ($fw>0) stopcmd("service FIREWALL restart");
		}
	}

	/* Walk through the WAN services */
	infsvcs_setup($uid, $cfg, $sts, $ipv6);

	/* If we have no upperlayer, we need to restart these services. */
	if ($upper=="")
	{
		if ($ipv6>0)
		{
			if ($fw6>0) startcmd("service FIREWALL6 restart");
			startcmd("service IP6TDEFCHAIN restart");
			startcmd("service ROUTE6.DYNAMIC restart");
			startcmd("service ROUTE6.STATIC restart");
		}
		else
		{
			if ($fw>0) startcmd("service FIREWALL restart");
			startcmd("service IPTDEFCHAIN restart");
			startcmd("service ROUTE.STATIC restart");
			startcmd("service ROUTE.DESTNET restart");
			startcmd("service ROUTE.DOMAIN restart");
			startcmd("service ROUTE.IPUNNUMBERED restart");
			startcmd("service MULTICAST restart");
			startcmd("service QOS restart");
			startcmd("service DEVICE.TIME start");
		}
	}

	startcmd("event UPDATERESOLV");

	if ($ipv6==0)
	{
		/*Check LAN DHCP setting. We will resatrt DHCP server if the DNS relay is disabled*/
		foreach ("/inf")
		{
			$disable= query("disable");
			$active = query("active");
			$dhcps4 = query("dhcps4");
			$dns4   = query("dns4");
			$dns    = query("dns");
			if ($disable != "1" && $active=="1" && $dhcps4!="")
			{
				if ($dns4 == "" && $dns == "") startcmd("event DHCPS4.RESTART");
			}
		}
	}

	startcmd("event INFSVCS.".$uid.".UP");
	startcmd("event UPNP.IGD.NOTIFY.WANIPCONN1");

	return error(0);
}

function infsvcs_lan($index)
{
	$uid = "LAN-".$index;
	$cfg = XNODE_getpathbytarget("", "inf", "uid", $uid, 0);
	$sts = XNODE_getpathbytarget("/runtime", "inf", "uid", $uid, 0);
	if ($cfg=="" || $sts=="")
	{
		SHELL_info($_GLOBALS["START"], "infsvcs_setup: (".$uid.") not exist.");
		SHELL_info($_GLOBALS["STOP"],  "infsvcs_setup: (".$uid.") not exist.");
		return error(9);
	}
	$addrtype = query($sts."/inet/addrtype");
	if ($addrtype=="ipv6" || $addrtype=="ppp6") $ipv6=1; else $ipv6=0;

	/* Tell everybody, we are going down. */
	stopcmd("event INFSVCS.".$uid.".DOWN");
	if ($ipv6>0)
	{
		stopcmd("service IP6TDEFCHAIN restart");
		stopcmd("service ROUTE6.STATIC restart");
		stopcmd("service ROUTE6.DYNAMIC restart");
	}
	else
	{
		stopcmd("service IPTDEFCHAIN restart");
		stopcmd("service MULTICAST restart");
		stopcmd("service ROUTE.STATIC restart");
		stopcmd("service ROUTE.IPUNNUMBERED restart");
	}

	/* Enable the LAN ports.
	 * Most of the router board has disabled LAN port by default. */
	startcmd("service ENLAN start");
	infsvcs_setup($uid, $cfg, $sts, $ipv6);

	/* Update the routing tables */
	if ($ipv6>0)
	{
		startcmd("service ROUTE6.STATIC restart");
		startcmd("service ROUTE6.DYNAMIC restart");
		startcmd("service IP6TDEFCHAIN restart");
	}
	else
	{
		startcmd("service ROUTE.STATIC restart");
		startcmd("service ROUTE.IPUNNUMBERED restart");
		startcmd("service MULTICAST restart");
		startcmd("service IPTDEFCHAIN restart");
	}
	startcmd("event INFSVCS.".$uid.".UP");

	return error(0);
}

?>
