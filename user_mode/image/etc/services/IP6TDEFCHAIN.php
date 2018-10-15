<? /* vi: set sw=4 ts=4: */
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/phyinf.php";

function startcmd($cmd) { fwrite(a, $_GLOBALS["START"], $cmd."\n"); }

function lan_default($stsp, $name, $dev)
{
	/* Check status */
	anchor($stsp."/inet");
	$addrtype = query("addrtype");
	if		($addrtype=="ipv6" && query("ipv6/valid")=="1") 
	{ $ipaddr=query("ipv6/ipaddr"); $mask = query("ipv6/mask"); }
	else if	($addrtype=="ppp6" && query("ppp6/valid")=="1") $ipaddr=query("ppp6/local");
	else return;

	/* FORWARD */
	startcmd("ip6tables -t filter -A FORWARD -i ".$dev." -j FWD.".$name);
	/* INPUT */
	startcmd("ip6tables -t filter -A INPUT -i ".$dev." -j INP.".$name);
}

function wan_default($infp, $stsp, $name, $dev)
{
	/* Check status */
	anchor($stsp."/inet");
	$addrtype = query("addrtype");
	if		($addrtype=="ipv6" && query("ipv6/valid")=="1") $ipaddr=query("ipv6/ipaddr");
	else if	($addrtype=="ppp6" && query("ppp6/valid")=="1") $ipaddr=query("ppp6/local");
	else return;

	/* FORWARD */
	if (query("/acl6/dos/enable")=="1") startcmd("ip6tables -A FORWARD -i ".$dev." -j DOS");
	if (query("/acl6/spi/enable")=="1") startcmd("ip6tables -A FORWARD -i ".$dev." -j SPI");
	startcmd("ip6tables -A FORWARD -i ".$dev." -j FWD.".$name);

	/* Mangle table */
	/* Move to radvd with AdvLinkMTU */
	/*
	$defaultroute = query($stsp."/defaultroute");
	if($defaultroute > 0)
		startcmd("ip6tables -t mangle -A FORWARD -p tcp --tcp-flags SYN,RST,FIN SYN -j TCPMSS --clamp-mss-to-pmtu");
	*/
}

/**************************************************************************/
fwrite("w",$START,"#!/bin/sh\n");
fwrite("w",$STOP,"#!/bin/sh\n");

/* flush default chain */
startcmd(
	"ip6tables -F FORWARD; ".
	"ip6tables -F INPUT; ".
	"ip6tables -t mangle -F FORWARD; "
	);

/* Firewall */
if (query("/acl6/dos/enable")=="1") startcmd("ip6tables -A INPUT -j DOS");
if (query("/acl6/spi/enable")=="1") startcmd("ip6tables -A INPUT -j SPI");

$layout = query("/runtime/device/layout");
if ($layout == "router")
{
	/* Walk through all the actived LAN interfaces. */
	startcmd("# LAN interfaces");
	
	foreach ("/runtime/inf")	$count++;
	
	$i=1;
	while ($i<=$count)
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
			if ($laninf!="") lan_default($stsp, $name, $laninf);
		}

		/* Advance to next */
		$i++;
	}

	/* Walk through all the actived WAN interfaces. */
	startcmd("# WAN interfaces");
	$i = 1;
	while ($i<=$count)
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
}


fwrite("a",$START, "exit 0\n");
fwrite("a",$STOP, "exit 0\n");
?>
