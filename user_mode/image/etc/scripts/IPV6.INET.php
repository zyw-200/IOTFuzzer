#!/bin/sh
<?
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";

function cmd($cmd) {echo $cmd."\n";}
function msg($msg) {cmd("echo ".$msg." > /dev/console");}

/*****************************************/
function add_each($list, $path, $node)
{
	//echo "# add_each(".$list.",".$path.",".$node.")\n";
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

/*****************************************/
function add_route($ipaddr,$prefix,$gw,$metric,$inf)
{
	$base = "/runtime/dynamic/route6";
	$cnt = query($base."/entry#");
	if($cnt=="") $cnt=0;
	$cnt++;
	
	$ip = query($base."/entry:".$cnt."/ipaddr");
	if($ip == "")
	{
		set($base."/entry:".$cnt."/ipaddr", $ipaddr);
		set($base."/entry:".$cnt."/prefix", $prefix);
		set($base."/entry:".$cnt."/gateway", $gw);
		set($base."/entry:".$cnt."/metric", $metric);
		set($base."/entry:".$cnt."/inf", $inf);
	}
}

/*****************************************/
function remove_route($ipaddr,$prefix,$gw)
{
	$base = "/runtime/dynamic/route6";
	$cnt = query($base."/entry#");
	//msg('remove route: ip:'.$ipaddr.',prefix:'.$prefix.',gw:'.$gw);	
	$i=1;
	while($i<=$cnt)
	{
		$dest = query($base."/entry:".$i."/ipaddr");
		$pfx = query($base."/entry:".$i."/prefix");
		$via = query($base."/entry:".$i."/gateway");
		//msg('route entry:'.$i.', dest:'.$dest.',pfx:'.$pfx.',via:'.$via);	
		if($ipaddr==$dest && $prefix==$pfx && $gw==$via)
		{
			del($base."/entry:".$i);
		}
		$i++;
	}
}

/*****************************************/

function dev_detach($hasevent)
{
	$sts = XNODE_getpathbytarget("/runtime", "inf", "uid", $_GLOBALS["INF"], 0);
	if ($sts=="") return $_GLOBALS["INF"]." has no runtime nodes.";
	if (query($sts."/inet/addrtype")!="ipv6") return $_GLOBALS["INF"]." is not ipv6.";
	if (query($sts."/inet/ipv6/valid")!=1) return $_GLOBALS["INF"]." is not active.";
	$devnam = query($sts."/devnam");
	if ($devnam=="") return $_GLOBALS["INF"]." has no device name.";

	anchor($sts."/inet/ipv6");
	$mode	= query("mode");
	$ipaddr	= query("ipaddr");
	$prefix	= query("prefix");
	$gw	= query("gateway");
	$dhcpopt= query("dhcpopt");
	$defrt	= query($sts."/defaultroute");
	$blackholepfx = query($sts."/blackhole/prefix");

	/* default route */
	if ($defrt!="" && $defrt>0)
	{
		if ($gw!="")
		{
			cmd("ip -6 route del ::/0 via ".$gw." dev ".$devnam);
			remove_route("::","0",$gw);
		}
		else if ($mode!="TSP")
		{
			cmd("ip -6 route del ::/0 dev ".$devnam);
			remove_route("::","0","");
		}
	}
	else cmd("ip -6 route flush table ".$_GLOBALS["INF"]);

	/* TSPC will destroy the tunnel, so we don't need to detach. */
	if ($mode != "TSP")
	{
		/* peer-to-peer */
		if ($prefix==128 && $gw!="") cmd("ip -6 route del ".$gw."/128 dev ".$devnam);

		/* detach */
		if ($mode=="LL") msg($_GLOBALS["INF"].' a is link local interface.');
		else if	($mode=="STATEFUL" && $dhcpopt!="IA-PD") 
		{
			msg($_GLOBALS["INF"].' is a stateful-IANA interface.');
			$netid = ipv6networkid($ipaddr, $prefix);
			remove_route($netid, $prefix, "::");
		}
		else if	($mode=="STATELESS")
		{
			msg($_GLOBALS["INF"].' is a stateless interface.');
			$netid = ipv6networkid($ipaddr, $prefix);
			remove_route($netid, $prefix, "::");
		}
		else
		{
			/* check if the $ipaddr is our link-local address */
			$phyinf = query($sts."/phyinf");
			$path = XNODE_getpathbytarget("/runtime", "phyinf", "uid", $phyinf, 0);
			$llipaddr = query($path."/ipv6/link/ipaddr");
			$netid = ipv6networkid($ipaddr, $prefix);
			//cmd("ip -6 addr del ".$ipaddr."/".$prefix." dev ".$devnam);
			if($ipaddr!=$llipaddr)
			{
				cmd("ip -6 route del ".$netid."/".$prefix." dev ".$devnam);
				remove_route($netid, $prefix, "::");
				cmd("ip -6 addr del ".$ipaddr."/".$prefix." dev ".$devnam);
			}
			else	msg($ipaddr.' is our link-local address.');
		}
	}

	/* remove blackhole rule if needed */
	if ($blackholepfx != "")
	{
		$blackholeplen = query($sts."/blackhole/plen");
		cmd("ip -6 route del blackhole ".$blackholepfx."/".$blackholeplen." dev lo");
		del($sts."/blackhole");
	}

	if ($hasevent>0)
	{
		cmd("rm -f /var/run/".$_GLOBALS["INF"].".UP");
		cmd("event ".$_GLOBALS["INF"].".DOWN");
	}

	del($sts."/inet");
	del($sts."/devnam");
	
	if($hasevent>0) 
	{
		$childip = query($sts."/child/ipaddr");
		if($childip!="") del($sts."/child");
	}
}

function dev_attach($hasevent)
{
	$cfg = XNODE_getpathbytarget("", "inf", "uid", $_GLOBALS["INF"], 0);
	if ($cfg=="") return $_GLOBALS["INF"]." does not exist!";

	/* The runtime node of INF should already be created when starting INET service.
	 * Set the create flag to make sure is will always be created. */
	$sts = XNODE_getpathbytarget("/runtime", "inf", "uid", $_GLOBALS["INF"], 1);

	/* Just in case the device is still alive. */
	if (query($sts."/inet/ipv6/valid")==1) dev_detach(0);

	/* Set Child variables */
	$child = query($sts."/child/uid");
	if ($child!="")
	{
		$ipaddr = query($sts."/child/ipaddr");
		$prefix = query($sts."/child/prefix");
		$pdnetwork = query($sts."/child/pdnetwork");
		$pdprefix = query($sts."/child/pdprefix");
		if ($ipaddr!="" && $prefix!="") $addrtype="ipv6";
		XNODE_set_var($child."_ADDRTYPE", $addrtype);
		XNODE_set_var($child."_IPADDR", $ipaddr);
		XNODE_set_var($child."_PREFIX", $prefix);
		XNODE_set_var($child."_PDNETWORK", $pdnetwork);
		XNODE_set_var($child."_PDPREFIX", $pdprefix);
	}

	/* Get the default metric from config. */
	$defrt = query($cfg."/defaultroute");
	
	/***********************************************/
	/* Update Status */
	$M = $_GLOBALS["MODE"];
	anchor($sts);
	set("defaultroute", 	$defrt);
	set("devnam",			$_GLOBALS["DEVNAM"]);
	set("inet/uid",			query($cfg."/inet"));
	set("inet/addrtype",	"ipv6");
	set("inet/uptime",		query("/runtime/device/uptime"));
	set("inet/ipv6/valid",	"1");
	/* INET */
	anchor($sts."/inet/ipv6");
	set("mode",		$M);
	set("ipaddr",	$_GLOBALS["IPADDR"]);
	set("prefix",	$_GLOBALS["PREFIX"]);
	set("gateway",	$_GLOBALS["GATEWAY"]);
	set("routerlft",$_GLOBALS["ROUTERLFT"]);
	set("preferlft",$_GLOBALS["PREFERLFT"]);
	set("validlft",	$_GLOBALS["VALIDLFT"]);
	/* DNS & ROUTING */
	add_each($_GLOBALS["DNS"], $sts."/inet/ipv6", "dns");
	/***********************************************/
	/* attach */
	$dhcpopt = query("dhcpopt");
	if		($M=="LL") msg($_GLOBALS["INF"].' a is link local interface.');
	else if	($M=="STATELESS") msg($_GLOBALS["INF"].' is a self-configured interface.');
	else if	($M=="STATEFUL" && $dhcpopt!="IA-PD") msg($_GLOBALS["INF"].' is a stateful-IANA interface.');
	else cmd("ip -6 addr add ".$_GLOBALS["IPADDR"]."/".$_GLOBALS["PREFIX"]." dev ".$_GLOBALS["DEVNAM"]);

	if($M!="LL")
	{
		$rtnetid = ipv6networkid($_GLOBALS["IPADDR"], $_GLOBALS["PREFIX"]);
		if($rtnetid!="fe80::")
			add_route($rtnetid, $_GLOBALS["PREFIX"], "::", "256", $_GLOBALS["INF"]);
	}

	/* Handle the peer-to-peer connection. */
	if ($_GLOBALS["PREFIX"]==128 && $_GLOBALS["GATEWAY"]!="")
		cmd("ip -6 route add ".$_GLOBALS["GATEWAY"]."/128 dev ".$_GLOBALS["DEVNAM"]);
	/* gateway */
	if ($defrt!="" && $defrt>0)
	{
		if ($_GLOBALS["GATEWAY"]!="")
		{
			cmd("ip -6 route add ::/0 via ".$_GLOBALS["GATEWAY"]." dev ".$_GLOBALS["DEVNAM"]." metric ".$defrt);
			add_route("::", "0", $_GLOBALS["GATEWAY"], $defrt, $_GLOBALS["INF"]);
		}
		else
		{
			cmd("ip -6 route add ::/0 dev ".$_GLOBALS["DEVNAM"]." metric ".$defrt);
			add_route("::", "0", "::", "256", $_GLOBALS["INF"]);
		}
	}
	else
	{
		$netid = ipv6networkid($_GLOBALS["IPADDR"], $_GLOBALS["PREFIX"]);
		cmd("ip -6 route add ".$netid."/".$_GLOBALS["PREFIX"]." dev ".$_GLOBALS["DEVNAM"].
				" src ".$_GLOBALS["IPADDR"]." table ".$_GLOBALS["INF"]);
	}
	/* Routing */
	// Currently, the INF specific routing table is not used. by David.
	//$hasroute=0;
	//if ($hasroute>0) echo "ip -6 rule add table ".$_GLOBALS["INF"]." prio 30000\n";
	if ($hasevent>0)
	{
		cmd("event ".$_GLOBALS["INF"].".UP");
		cmd("echo 1 > /var/run/".$_GLOBALS["INF"].".UP");
	}
}

function main_entry()
{
	if ($_GLOBALS["INF"]=="") return "No INF !!";
	if		($_GLOBALS["ACTION"]=="ATTACH") return dev_attach(1);
	else if	($_GLOBALS["ACTION"]=="DETACH") return dev_detach(1);
	return "Unknown action - ".$_GLOBALS["ACTION"];
}

/*****************************************/
/* Required variables:
 *
 *	ACTION:		ATTACH/DETACH
 *	MODE:		IPv6 mode
 *	INF:		Interface UID
 *	DEVNAM:		device name
 *	IPADDR:		IP address
 *	PREFIX:		Prefix length
 *	GATEWAY:	Gateway
 *	ROUTERLFT:	Router lift time
 *	PREFERLFT:	Prefer lift time
 *	VALIDLFT:	Valid lift time
 *	DNS:		DNS servers
 */
$ret = main_entry();
if ($ret!="")	cmd("# ".$ret."\nexit 9\n");
else			cmd("exit 0\n");
?>
