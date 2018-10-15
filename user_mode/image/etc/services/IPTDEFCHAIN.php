<? /* vi: set sw=4 ts=4: */
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/phyinf.php";

function startcmd($cmd) { fwrite(a, $_GLOBALS["START"], $cmd."\n"); }

function lan_default($infp, $stsp, $name, $dev)
{
	/* Check status */
	anchor($stsp."/inet");
	$addrtype = query("addrtype");
	if		($addrtype=="ipv4" && query("ipv4/valid")=="1") 
	{ $ipaddr=query("ipv4/ipaddr"); $mask = query("ipv4/mask"); }
	else if	($addrtype=="ppp4" && query("ppp4/valid")=="1") $ipaddr=query("ppp4/local");
	else return;

	/* PREROUTING */
	/* Walk through the active WAN interfaces */
	$i = 1;
	while ($i > 0)
	{
		/* get WAN path */
		$wan = "WAN-".$i;
		$winfp = XNODE_getpathbytarget("", "inf", "uid", $wan, 0);
		$wstsp = XNODE_getpathbytarget("/runtime", "inf", "uid", $wan, 0);
		if ($wstsp=="" || $winfp=="") { $i=0; break; }
		/* Get nat */
		$nat = query($winfp."/nat");
		if ($nat == "") { $i++; continue; }
		/* Check status */
		anchor($wstsp."/inet");
		$at = query("addrtype");
		if		($at=="ipv4" && query("ipv4/valid")=="1") $wip=query("ipv4/ipaddr");
		else if	($at=="ppp4" && query("ppp4/valid")=="1") $wip=query("ppp4/local");
		else { $i++; continue; }

		startcmd("iptables -t nat -A PREROUTING -i ".$dev." -d ".$wip." -j PFWD.".$nat);
		$i++;
	}
	$bwc = query($infp."/bwc");
	if($bwc!="") 
	{
		startcmd("iptables -t mangle -A PREROUTING -i ".$dev." -j PRE.BWC.".$name);
		startcmd("iptables -t mangle -A POSTROUTING -o ".$dev." -j PST.BWC.".$name);
	}

	startcmd("iptables -t nat -A PREROUTING -i ".$dev." -j PRE.".$name);
	/* FORWARD */
	startcmd("iptables -t filter -A FORWARD -i ".$dev." -j FWD.".$name);
	/* INPUT */
	startcmd("iptables -t filter -A INPUT -i ".$dev." -j INP.".$name);
	/* POSTROUTING */
	if ($addrtype=="ipv4")
	{
		startcmd("iptables -t nat -A POSTROUTING -o ".$dev.
			" -s ".$ipaddr."/".$mask." -j SNAT --to-source ".$ipaddr);
	}
}

function wan_default($infp, $stsp, $name, $dev)
{
	/* Check status */
	anchor($stsp."/inet");
	$addrtype = query("addrtype");
	if		($addrtype=="ipv4" && query("ipv4/valid")=="1") $ipaddr=query("ipv4/ipaddr");
	else if	($addrtype=="ppp4" && query("ppp4/valid")=="1") $ipaddr=query("ppp4/local");
	else return;

	/* PREROUTING */
	startcmd("iptables -t nat -A PREROUTING -i ".$dev." -d ".$ipaddr." -j PRE.".$name);
	startcmd("iptables -t nat -A PREROUTING -i ".$dev." -d 224.0.0.0/4 -j PRE.".$name);
	startcmd("iptables -t nat -A PREROUTING -i ".$dev." -j DROP");

	/* FORWARD */
	if (query("/acl/dos/enable")=="1") startcmd("iptables -A FORWARD -i ".$dev." -j DOS");
	if (query("/acl/spi/enable")=="1") startcmd("iptables -A FORWARD -i ".$dev." -j SPI");
	startcmd("iptables -A FORWARD -i ".$dev." -j FWD.".$name);

	/* Get NAT */
	$nat = query($infp."/nat");
	if ($nat=="") return;

	/* POSTROUTING */
	if (fread(e,"/etc/config/nat")=="Daniel's NAT") $target = "PST.MASQ.".$nat;
	else $target = "MASQ.".$nat;
	startcmd("iptables -t nat -A POSTROUTING -o ".$dev." -j ".$target);

	/* Mangle table */
	$mtu = query($stsp."/inet/".$addrtype."/mtu");
	if ($mtu >= 40)
	{
		$mss = $mtu-40;
		$mss1 = $mss+1;
		$iptopt = "-p tcp --tcp-flags SYN,RST,FIN SYN -m tcpmss --mss ".$mss1.":1500 -j TCPMSS --set-mss ".$mss;
		startcmd("iptables -t mangle -A PREROUTING -i ".$dev." ".$iptopt);
		startcmd("iptables -t mangle -A POSTROUTING -o ".$dev." ".$iptopt);
	}
	else
	{
		SHELL_error($START, "IPTDEFCHAIN.php: ".$stsp."/inet/".$addrtype."/mtu=[".$mtu."]!");
	}

	$bwc = query($infp."/bwc");
	if($bwc!="")
	{
		startcmd("iptables -t mangle -A PREROUTING -i ".$dev." -j PRE.BWC.".$name);
		startcmd("iptables -t mangle -A POSTROUTING -o ".$dev." -j PST.BWC.".$name);
	}

	/* Deny QQ */
	$proxy = 0;
	$deny_qq = query("/acl/applications/qq/action");
	if ($deny_qq == "DENY")
	{
		$proxy = 1;
		startcmd("iptables -t mangle -A POSTROUTING -m layer7 --l7proto qq -j DROP\n");
	}
	/* Deny MSN */
	$deny_msn = query("/acl/applications/msn/action");
	if ($deny_msn == "DENY")
	{
		$proxy = 1;
		startcmd("iptables -t mangle -A POSTROUTING -m layer7 --l7proto msnmessenger -j DROP\n");
	}
	/* Deny Proxy for QQ,MSN */
	if ($proxy > 0)
	{
		startcmd("echo 5 > /proc/fastnat/min_gone\n");
		startcmd("iptables -t mangle -A POSTROUTING -m layer7 --l7proto qq-agent -j DROP\n");
		startcmd("iptables -t mangle -A POSTROUTING -m layer7 --l7proto socks -j DROP\n");
	}
	else
	{
		/* restore to default */
		startcmd("echo 2 > /proc/fastnat/min_gone\n");
	}

	/* NetSniper */
	$natp = XNODE_getpathbytarget("/nat", "entry", "uid", $nat, 0);
	if (query($natp."/netsniper/enable")==1)
		startcmd("iptables -t mangle -A POSTROUTING -o ".$dev." -j PERS --tweak src --conf /etc/netsniper/pers.conf");

	startcmd("echo 1 > /proc/nf_conntrack_flush ");		
			
}

/**************************************************************************/
fwrite("w",$START,"#!/bin/sh\n");
fwrite("w",$STOP,"#!/bin/sh\n");

/* flush default chain */
startcmd(
	"iptables -t nat -F PREROUTING; ".
	"iptables -t nat -F POSTROUTING; ".
	"iptables -F FORWARD; ".
	"iptables -F INPUT; ".
	"iptables -t mangle -F PREROUTING; ".
	"iptables -t mangle -F POSTROUTING"
	);

/* Firewall */
if (query("/acl/dos/enable")=="1") startcmd("iptables -A INPUT -j DOS");
if (query("/acl/spi/enable")=="1") startcmd("iptables -A INPUT -j SPI");

$layout = query("/runtime/device/layout");
if ($layout == "router")
{
	/* Increase the TTL, so the packet with TTL=1 will not be dropped. */
	startcmd("iptables -t mangle -A PREROUTING -j TTL --ttl-inc 1");

	/* smart404 support (tom, 20101007) */
	startcmd('phpsh /etc/events/update_smart404.php');

	/* Walk through all the actived LAN interfaces. */
	startcmd("# LAN interfaces");
	$i = 1;
	while ($i>0)
	{
		/* If LAN exist ? */
		$name = "LAN-".$i;
		$infp = XNODE_getpathbytarget("", "inf", "uid", $name, 0);
		if ($infp=="") break;

		/* If LAN activated ? */
		$stsp = XNODE_getpathbytarget("/runtime", "inf", "uid", $name, 0);
		if ($stsp!="")
		{
			/* Get phyinf */
			$laninf = PHYINF_getruntimeifname($name);
			if ($laninf!="") lan_default($infp, $stsp, $name, $laninf);
		}

		/* Advance to next */
		$i++;
	}

	/* Walk through all the actived WAN interfaces. */
	startcmd("# WAN interfaces");
	$i = 1;
	while ($i>0)
	{
		/* If WAN exist ? */
		$name = "WAN-".$i;
		$infp = XNODE_getpathbytarget("", "inf", "uid", $name, 0);
		if($infp=="") break;

		/* If WAN activated ? */
		$stsp = XNODE_getpathbytarget("/runtime", "inf", "uid", $name, 0);
		if ($stsp!="")
		{
			/* Get phyinf */
			$waninf = PHYINF_getruntimeifname($name);
			if ($waninf!="") wan_default($infp, $stsp, $name, $waninf);
		}

		/* Advance to next */
		$i++;
	}

	startcmd("iptables -I INPUT -p TCP --tcp-flags ALL NONE -j DROP\n");
}
else if ($layout == "bridge")
{
	/* Walk through all the BRIDGE interfaces. */
	fwrite("a",$START,"# BRIDGE interfaces\n");
	$i = 1;
	while ($i>0)
	{
		/* get BRIDGE path */
		$name = "BRIDGE-".$i;
		$infp = XNODE_getpathbytarget("", "inf", "uid", $name, 0);
		$stsp = XNODE_getpathbytarget("/runtime", "inf", "uid", $name, 0);
		if ($stsp=="" || $infp=="") { $i=0; break; }
		/* Check status */
		anchor($stsp."/inet");
		$addrtype = query("addrtype");
		if      ($addrtype=="ipv4" && query("ipv4/valid")=="1") $ipaddr=query("ipv4/ipaddr");
		else if ($addrtype=="ppp4" && query("ppp4/valid")=="1") $ipaddr=query("ppp4/local");
		else { $i++; continue; }
		/* Get phyinf */
		$laninf = PHYINF_getruntimeifname($name);
		if ($laninf=="") { $i++; continue; }

		/* PREROUTING */
		fwrite("a",$START, "iptables -t nat -A PREROUTING -i ".$laninf." -j PRE.".$name."\n");

		/* Advance to next */
		$i++;
	}
}

fwrite("a",$START, "exit 0\n");
fwrite("a",$STOP, "exit 0\n");
?>
