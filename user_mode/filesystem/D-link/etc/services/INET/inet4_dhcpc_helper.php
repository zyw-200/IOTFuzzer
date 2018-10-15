#!/bin/sh
<? /* vi: set sw=4 ts=4: */
include "/htdocs/phplib/xnode.php";

function add_each($list, $path, $node)
{
	$i = 0;
	$cnt = scut_count($list, "");
	while ($i < $cnt)
	{
		$val = scut($list, $i, "");
		if ($val!="") add($path."/".$node, $val);
		$i++;
	}
	return $cnt;
}

if ($ACTION=="bound")
{
	/* Actuall, we don't need to set the 'udhcpc' nodes under /runtime/inf.
	 * Those were temporary nodes of the old implementation. (That's why I did not put into the documentation.)
	 * But there are still some modules referencing these nodes, so I keep these code for compatible reason.
	 *			David Hsieh <david_hsieh@alphanetworks.com> */

	/* Anchor to the target interface's runtime status path.  */
	$sts = XNODE_getpathbytarget("/runtime",  "inf", "uid", $INF, 1);

	/* Check if there are existing setting ? */
	if (query($sts."/udhcpc/inet")==$INET)
	{
		anchor($sts."/udhcpc");
		if (query("interface")==$INTERFACE &&
			query("ip")==$IP &&
			query("subnet")==$SUBNET &&
			query("broadcast")==$BROADCAST &&
			query("lease")==$LEASE &&
			query("domain")==$DOMAIN &&
			query("raw_router")==$ROUTER &&
			query("raw_dns")==$DNS &&
			query("raw_clsstrout")==$CLSSTROUT &&
			query("raw_sstrout")==$SSTROUT &&
			query("sixrd_pfx")==$SIXRDPFX &&
			query("sixrd_pfxlen")==$SIXRDPFXLEN &&
			query("sixrd_msklen")==$SIXRDMSKLEN &&
			query("sixrd_bripaddr")==$SIXRDBRIP)
		{
			$nochange = 1;
			echo 'echo "[$0]: no changed in '.$INF.' ..." > /dev/console';
		}
		else
		{
			$nochange = 0;
			echo "phpsh /etc/scripts/IPV4.INET.php ACTION=DETACH INF=".$INF."\n";
		}
	}

	if ($nochange!=1)
	{
		del($sts."/udhcpc");
		set($sts."/udhcpc/inet",$INET);
		anchor($sts."/udhcpc");

		/* Record the arguments */
		set("interface",$INTERFACE);
		set("ip",		$IP);
		set("subnet",	$SUBNET);
		set("broadcast",$BROADCAST);
		set("lease",	$LEASE);
		set("domain",	$DOMAIN);
		set("raw_router",	$ROUTER);
		set("raw_dns",		$DNS);
		set("raw_clsstrout",$CLSSTROUT);
		set("raw_sstrout",	$SSTROUT);

		/* 6rd info */
		set("sixrd_pfx",	$SIXRDPFX);		
		set("sixrd_pfxlen",	$SIXRDPLEN);		
		set("sixrd_msklen",	$SIXRDMSKLEN);		
		set("sixrd_brip",	$SIXRDBRIP);		

		add_each($ROUTER,	$statusp."/udhcpc", "router");
		add_each($DNS,		$statusp."/udhcpc", "dns");
		add_each($CLSSTROUT,$statusp."/udhcpc", "cltrout");
		add_each($SSTROUT,	$statusp."/udhcpc", "sstrout");

		echo "phpsh /etc/scripts/IPV4.INET.php ACTION=ATTACH".
				" STATIC=0".
				" INF=".$INF.
				" DEVNAM=".$INTERFACE.
				" MTU=".$MTU.
				" IPADDR=".$IP.
				" SUBNET=".$SUBNET.
				" BROADCAST=".$BROADCAST.
				" GATEWAY=".$ROUTER.
				' "DOMAIN='.$DOMAIN.'"'.
				' "DNS='.$DNS.'"'.
				' "CLSSTROUT='.$CLSSTROUT.'"'.
				' "SSTROUT='.$SSTROUT.'"'.
				'\n';
		$restartdhcpswer = 0;
		if ($DOMAIN != query("/runtime/device/domain"))
		{
			echo "xmldbc -s /runtime/device/domain \"".$domain."\"\n";
			$restartdhcpswer = 1;
		}
		/*Check LAN DHCP setting. We will resatrt DHCP server if the DNS relay is disabled*/
		foreach ("/inf")
		{
		    $disable= query("disable");
		    $active = query("active");
		    $dhcps4 = query("dhcps4");
		    $dns4 = query("dns4");
		    if ($disable != "1" && $active=="1" && $dhcps4!=""){
	            if ($dns4 =="")
	            {
	                $restartdhcpswer = 1;
	            }
		    }
		}
		if ($restartdhcpswer == 1)
		{
		    echo "event DHCPS4.RESTART\n";
		}	
	}
}
else if ($ACTION=="deconfig")
{
	$sts = XNODE_getpathbytarget("/runtime",  "inf", "uid", $INF, 0);
	if ($sts=="") echo 'echo "[$0]: no interface '.$INF.' ..." > /dev/console';
	else
	{
		del($sts."/udhcpc");
		echo "phpsh /etc/scripts/IPV4.INET.php ACTION=DETACH INF=".$INF;
	}
}
else if ($ACTION=="renew")
{
	echo 'echo "[$0]: got renew for '.$INF.' ..." > /dev/console';
}
else if ($ACTION=="dhcpplus")
{
	echo 'echo "[DHCP+]: config '.$INTERFACE.' '.$IP.'/'.$SUBNET.' default gw '.$ROUTER.'" > /dev/console\n';
	/* Get the netmask */
	if ($SUBNET!="") $mask = ipv4mask2int($SUBNET);
	/* Get the broadcast address */
	if ($BROADCAST!="") $brd = $BROADCAST;
	else
	{
		$max = ipv4maxhost($mask);
		$brd = ipv4ip($IP, $mask, $max);
	}
	echo "ip addr add ".$IP."/".$mask." broadcast ".$brd." dev ".$INTERFACE."\n";
	
	/* gateway */
	$cfg = XNODE_getpathbytarget("", "inf", "uid", $INF, 0);

	/* Get the defaultroute metric from config. */
	$defrt = query($cfg."/defaultroute");
	if ($ROUTER!="")
	{
		$netid = ipv4networkid($IP, $mask);
		if ($defrt!="" && $defrt>0)
			echo "ip route add default via ".$ROUTER." metric ".$defrt." table default\n";
		else
			echo "ip route add ".$netid."/".$mask." dev ".$INTERFACE." src ".$IP." table ".$INF."\n";
	}
}
else
{
	echo '# unknown action - ['.$ACTION.']';
}
?>
exit 0
