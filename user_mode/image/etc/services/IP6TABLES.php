<? /* vi: set sw=4 ts=4: */
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";
include "/etc/services/IP6TABLES/ip6tlib.php";

fwrite("w",$START,"#!/bin/sh\n");
fwrite("w", $STOP,"#!/bin/sh\n");

/* script starts here !! */
$layout = query("/runtime/device/layout");

IP6T_flushall($STOP);
fwrite("a",$STOP, "exit 0\n");

/* user-defined chains */
IP6T_newchain($START, "filter", "DOS");
IP6T_newchain($START, "filter", "SPI");
IP6T_newchain($START, "filter", "FIREWALL");

/* Create sub-chain for LAN */
$i = 1;
while ($i>0)
{
	$ifname = "LAN-".$i;
	$ifpath = XNODE_getpathbytarget("", "inf", "uid", $ifname, 0);
	if ($ifpath == "") { $i=0; break; }
	
	/* define chain */
	IP6T_newchain($START, "filter", "FWD.".$ifname);
	IP6T_newchain($START, "filter", "INP.".$ifname);

	$i++;
}
/* Create sub-chain for WAN */
$i = 1;
while ($i>0)
{
	$ifname = "WAN-".$i;
	$ifpath = XNODE_getpathbytarget("", "inf", "uid", $ifname, 0);
	if ($ifpath == "") { $i=0; break; }
	/* define chain */
	IP6T_newchain($START, "filter", "FWD.".$ifname);
	IP6T_newchain($START, "filter", "INP.".$ifname);

	$i++;
}
/* Create sub-chain for BRIDGE */
$i = 1;
while ($i>0)
{
	$ifname = "BRIDGE-".$i;
	$ifpath = XNODE_getpathbytarget("", "inf", "uid", $ifname, 0);
	if ($ifpath == "") { $i=0; break; }

	$i++;
}


/* Move the DOS/SPI from nat to filter table.
 * David Hsieh <david_hsieh@alphanetworks.com> */
/* DOS */
$limit	= "-m limit --limit 10/s --limit-burst 50";
$logcmd	= "-m limit --limit 10/m -j LOG --log-level info --log-prefix";
$iptcmd	= "ip6tables -A DOS";
fwrite("a",$START,
	$iptcmd." -p tcp --syn ".$limit." -j RETURN\n".
	$iptcmd." -p tcp --syn ".$logcmd." 'ATT:002[SYN-FLOODING]:'\n".
	$iptcmd." -p tcp --syn -j DROP\n".
	$iptcmd." -p icmpv6 --icmpv6-type echo-request ".$limit." -j RETURN\n".
	$iptcmd." -p icmpv6 --icmpv6-type echo-reply ".  $limit." -j RETURN\n".
	$iptcmd." -p icmpv6 --icmpv6-type router-solicitation ".  $limit." -j RETURN\n".
	$iptcmd." -p icmpv6 --icmpv6-type router-advertisement ".  $limit." -j RETURN\n".
	$iptcmd." -p icmpv6 --icmpv6-type neighbor-solicitation ".  $limit." -j RETURN\n".
	$iptcmd." -p icmpv6 --icmpv6-type neighbor-advertisement ".  $limit." -j RETURN\n".
	$iptcmd." -p icmpv6 ".$logcmd." 'ATT:002[PING-FLOODING]:'\n".
	$iptcmd." -p icmpv6 -j DROP\n"
	);




/* SPI */
$drpcmd = "-j DROP";
$logcmd	= "-m limit --limit 10/m -j LOG --log-level info --log-prefix";
$state	= "-m state --state NEW";
$iptcmd	= "ip6tables -A SPI -p tcp --tcp-flags";
fwrite("a",$START,
	$iptcmd." SYN,ACK SYN,ACK ".$state." ".	$logcmd." 'ATT:001[SYN-ACK]:'\n".
	$iptcmd." SYN,ACK SYN,ACK ".$state." ".	$drpcmd."\n".
	$iptcmd." ALL NONE ".					$logcmd." 'ATT:001[NULL]:'\n".
	$iptcmd." ALL NONE ".					$drpcmd."\n".
	$iptcmd." ALL FIN,URG,PSH ".			$logcmd." 'ATT:001[NMAP]:'\n".
	$iptcmd." ALL FIN,URG,PSH ".			$drpcmd."\n".
	$iptcmd." SYN,RST SYN,RST ".			$logcmd." 'ATT:001[SYN-RST]:'\n".
	$iptcmd." SYN,RST SYN,RST ".			$drpcmd."\n".
	$iptcmd." SYN,FIN SYN,FIN ".			$logcmd." 'ATT:001[SYN-FIN]:'\n".
	$iptcmd." SYN,FIN SYN,FIN ".			$drpcmd."\n".
	$iptcmd." ALL ALL ".					$logcmd." 'ATT:001[Xmas]:'\n".
	$iptcmd." ALL ALL ".					$drpcmd."\n".
	$iptcmd." ALL SYN,RST,ACK,FIN,URG ".	$logcmd." 'ATT:001[Xmas]:'\n".
	$iptcmd." ALL SYN,RST,ACK,FIN,URG ".	$drpcmd."\n"
	);


/* Setup routing routing tables */
fwrite(a, $START,
	"ip -6 rule add table DOMAIN prio 1000\n".
	"ip -6 rule add table DEST prio 1010\n".
	"ip -6 rule add table STATIC prio 1020\n".
    "ip -6 rule add table DYNAMIC  prio 1040\n".
	"ip -6 rule add table DHCP prio 5000\n".
	"ip -6 rule add table RESOLV prio 6000\n"
	
	);

/* exit */
fwrite(a,$START,'exit 0\n');
?>
