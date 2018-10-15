<? /* vi: set sw=4 ts=4: */
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/phyinf.php";
include "/htdocs/phplib/inf.php";

function startcmd($cmd)	{fwrite(a,$_GLOBALS["START"], $cmd."\n");}
function stopcmd($cmd)	{fwrite(a,$_GLOBALS["STOP"], $cmd."\n");}

function inet_ipv4_static($inf, $ifname, $inet, $inetp, $default)
{
	startcmd("# inet_start_ipv4_static(".$inf.",".$ifname.",".$inet.",".$inetp.")");

	/* Get INET setting */
	anchor($inetp."/ipv4");
	$ipaddr = query("ipaddr");
	$mask	= query("mask");
	$gw		= query("gateway");
	$mtu	= query("mtu");

	/* Get DNS setting */
	$cnt = query("dns/count")+0;
	foreach("dns/entry")
	{
		if ($InDeX > $cnt) break;
		if ($dns=="") $dns = $VaLuE;
		else $dns = $dns." ".$VaLuE;
	}

	/* Start script */
	startcmd("phpsh /etc/scripts/IPV4.INET.php ACTION=ATTACH".
		" STATIC=1".
		" INF=".$inf.
		" DEVNAM=".$ifname.
		" IPADDR=".$ipaddr.
		" MASK=".$mask.
		" GATEWAY=".$gw.
		" MTU=".$mtu.
		' "DNS='.$dns.'"'
		);
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
				startcmd("event DHCPS4.RESTART");
            }
	    }
    	}
	/* Stop script */
	stopcmd("phpsh /etc/scripts/IPV4.INET.php ACTION=DETACH INF=".$inf);
}

function inet_ipv4_dynamic($inf, $ifname, $inet, $inetp)
{
	startcmd("# inet_start_ipv4_dynamic(".$inf.",".$ifname.",".$inetp.")");

	/* Setup DHCP */

	/* When interface has upperlayer, WANPORT.LINKUP do nothing and
	 * upperlayer will take care this.
	 *
	 * Builder 2009/10/12 */
	$infp = XNODE_getpathbytarget("", "inf", "uid", $inf, 0);
	if (query($infp."/upperlayer")!="")
	{
		$WANPORTLINKUP = 'true';
		$WANPORTLINKDOWN = 'true';
	}
	else
	{
		$WANPORTLINKUP = '"/etc/events/DHCP4-RENEW.sh '.$inf.'"';
		$WANPORTLINKDOWN = '"/etc/events/DHCP4-RELEASE.sh '.$inf.'"';
	}

	/* Get Setting */
	$hostname	= get("s", "/device/hostname");
	$mtu		= query($inetp."/ipv4/mtu");
	/* Get DNS setting */
	$cnt = query($inetp."/ipv4/dns/count")+0;
	foreach($inetp."/ipv4/dns/entry")
	{
		if ($InDeX > $cnt) break;
		$dns = $dns.$VaLuE." ";
	}

	/* The files */
	$udhcpc_helper	= "/var/servd/".$inf."-udhcpc.sh";
	$udhcpc_pid		= "/var/servd/".$inf."-udhcpc.pid";
	$hlper			= "/etc/services/INET/inet4_dhcpc_helper.php";

	/* Generate the callback script for udhcpc. */
    fwrite(w,$udhcpc_helper,
		'#!/bin/sh\n'.
		'echo [$0]: $1 $interface $ip $subnet $router $lease $domain $scope $winstype $wins $sixrd_prefix $sixrd_prefixlen $sixrd_msklen $sixrd_bripaddr ... > /dev/console\n'.
		'phpsh '.$hlper.' ACTION=$1'.
			' INF='.$inf.
			' INET='.$inet.
			' MTU='.$mtu.
			' INTERFACE=$interface'.
			' IP=$ip'.
			' SUBNET=$subnet'.
			' BROADCAST=$broadcast'.
			' LEASE=$lease'.
			' "DOMAIN=$domain"'.
			' "ROUTER=$router"'.
			' "DNS='.$dns.'$dns"'.
			' "CLSSTROUT=$clsstrout"'.
			' "SSTROUT=$sstrout"'.
			' "SCOPE=$scope"'.
			' "WINSTYPE=$winstype"'.
			' "WINS=$wins"'.
			' "SIXRDPFX=$sixrd_prefix"'.
			' "SIXRDPLEN=$sixrd_prefixlen"'.
			' "SIXRDMSKLEN=$sixrd_msklen"'.
			' "SIXRDBRIP=$sixrd_bripaddr"\n'.
		'exit 0\n'
		);

	/* set MTU */
	if ($mtu!="") startcmd('ip link set '.$ifname.' mtu '.$mtu);
	
	/* For Henan DHCP+, China */
	$dhcpplus_pid	 = "/var/run/dhcpplus.pid";
	$dhcpplus_enable = query($inetp."/ipv4/dhcpplus/enable");
	$dhcpplus_user	 = get("s", $inetp."/ipv4/dhcpplus/username");
	$dhcpplus_pass	 = get("s", $inetp."/ipv4/dhcpplus/password");
	if ($dhcpplus_enable == 1)
	{
		startcmd('dhcpplus -U '.$dhcpplus_user.' -P '.$dhcpplus_pass.' &\n');
		$dhcpplus_cmd = '-m';
		stopcmd('/etc/scripts/killpid.sh '.$dhcpplus_pid.'\n');
	}

	/* Set event for DHCP client. Start DHCP client. */
	startcmd(
		'event '.$inf.'.DHCP.RENEW     add "kill -SIGUSR1 \\`cat '.$udhcpc_pid.'\\`"\n'.
		'event '.$inf.'.DHCP.RELEASE   add "kill -SIGUSR2 \\`cat '.$udhcpc_pid.'\\`"\n'.
		'event WANPORT.LINKUP          add '.$WANPORTLINKUP.'\n'.
		'event WANPORT.LINKDOWN        add '.$WANPORTLINKDOWN.'\n'.
		'phpsh "/etc/scripts/control_smart404.php" ACTION=START_DHCP\n'.
		'chmod +x '.$udhcpc_helper.'\n'.
		'udhcpc -i '.$ifname.' -H '.$hostname.' -p '.$udhcpc_pid.' -s '.$udhcpc_helper.' '.$dhcpplus_cmd.' &\n'
		);

	/* Stop script */
	stopcmd(
		'/etc/scripts/killpid.sh '.$udhcpc_pid.'\n'.
		'while [ -f '.$udhcpc_pid.' ]; do sleep 1; done\n'.
		'event WANPORT.LINKUP flush\n'.
		'event WANPORT.LINKDOWN flush\n'.
		'phpsh "/etc/scripts/control_smart404.php" ACTION=STOP_DHCP\n'.
		'event '.$inf.'.DHCP.RELEASE flush\n'.
		'event '.$inf.'.DHCP.RENEW flush\n'.
		'sleep 3\n'
		);
}

function inet_ipv4_dslite($inf, $ifname, $inet, $inetp, $infprev)
{
	startcmd("# inet_start_ipv4_dslite(".$inf.",".$ifname.",".$inet.",".$inetp.",".$infprev.")");

	/* Get INET setting */
	$b4addr = query($inetp."/ipv4/ipaddr");
	anchor($inetp."/ipv4/ipv4in6");
	$remote = query("remote");

	$stsp = XNODE_getpathbytarget("/runtime", "inf", "uid", $inf, 0);
	if($b4addr!="") set($stsp."/inet/ipv4/ipaddr", $b4addr);
	else set($stsp."/inet/ipv4/ipaddr","");
	$devnam = "ip4ip6.".$inf;
	//if($local!="")  $lcmd = " local ".$local;

	if($remote!="") /* static */
	{
		if($infprev!="") 
		{
			$local = INF_getcurripaddr($infprev);
			if($local!="")  $lcmd = " local ".$local;
		}
		$rcmd = " remote ".$remote;
		startcmd("ip -6 tunnel add ".$devnam." mode ip4ip6".$rcmd.$lcmd);
		set($stsp."/inet/ipv4/ipv4in6/remote",	$remote);
		startcmd("ip link set dev ".$devnam." up");
		if($b4addr!="")
			startcmd("ip -4 addr add ".$b4addr." dev ".$devnam);
		startcmd("ip route add default dev ".$devnam);
		$uptime = query("/runtime/device/uptime");
		set($stsp."/inet/uptime",	query("/runtime/device/uptime"));
		set($stsp."/inet/ipv4/valid", "1");
		startcmd("event ".$inf.".UP");
		startcmd("echo 1 > /var/run/".$inf.".UP");
		
		stopcmd("ip -6 tunnel del ".$devnam);		
		stopcmd("ip route del default dev ".$devnam);
		stopcmd("xmldbc -s ".$stsp."/inet/ipv4/valid 0");
		stopcmd("event ".$inf.".DOWN");
		stopcmd("rm -f /var/run/".$inf.".UP");
	}
	else /* dynamic */
	{
		/* Get the IPv6 address of the previous interface */
		if($infprev!="") 
		{
			$local = INF_getcurripaddr($infprev);
			if($local!="")  $lcmd = " local ".$local;
			$prevstsp = XNODE_getpathbytarget("/runtime", "inf", "uid", $infprev, 0);
			$remote = query($prevstsp."/inet/ipv4/ipv4in6/remote");
			startcmd("sip=`gethostip -6 ".$remote."`");

			/* check if remote is acquired */
			startcmd("if [ \"$remote\" == \"\" ]; then");
			startcmd("	sleep 1");
			startcmd("	remote=`xmldbc -w ".$prevstsp."/inet/ipv4/ipv4in6/remote`");
			startcmd("	while [ \"$remote\" == \"\" ]; do" );
			startcmd("		sleep 1");
			startcmd("		remote=`xmldbc -w ".$prevstsp."/inet/ipv4/ipv4in6/remote`");
			startcmd("	done");
			startcmd("	sip=`gethostip -6 $remote`");
			startcmd("fi");

			//startcmd("sip=`gethostip -6 ".$remote."`");
		}
		startcmd("b4addr=`xmldbc -w ".$inetp."/ipv4/ipaddr`");
	
		startcmd("if [ \"$sip\" != \"\" ]; then");
		startcmd("	ip -6 tunnel add ".$devnam." mode ip4ip6 remote $sip".$lcmd);
		startcmd("	xmldbc -s ".$stsp."/inet/ipv4/ipv4in6/remote $sip");
		startcmd("	ip link set dev ".$devnam." up");
		startcmd("	if [ \"$b4addr\" != \"\" ]; then");
		startcmd("		ip -4 addr add $b4addr dev ".$devnam);
		startcmd("	fi");
		startcmd("	ip route add default dev ".$devnam);
		$uptime = query("/runtime/device/uptime");
		startcmd("	xmldbc -s ".$stsp."/inet/uptime ".$uptime);
		startcmd("	xmldbc -s ".$stsp."/inet/ipv4/valid 1");
		startcmd("	event ".$inf.".UP");
		startcmd("	echo 1 > /var/run/".$inf.".UP");
		startcmd("else");
		startcmd("	echo Cannot resolve aftr server name > /dev/console");
		startcmd("	xmldbc -s ".$stsp."/inet/ipv4/valid 0");
		startcmd("fi");

		/* Start script */
		/*
		startcmd("phpsh /etc/scripts/IPV4.INET.php ACTION=ATTACH".
			" STATIC=1".
			" INF=".$inf.
			" DEVNAM=".$ifname.
			" IPADDR=".$ipaddr.
			" MASK=".$mask.
			" GATEWAY=".$gw.
			" MTU=".$mtu.
			' "DNS='.$dns.'"'
		);
		*/
		//startcmd("event ".$inf.".UP");
		//startcmd("echo 1 > /var/run/".$inf.".UP");
		//startcmd("fi");

		/* Stop script */
		stopcmd("if [ -e /var/run/".$inf.".UP ]; then");
		stopcmd("	ip -6 tunnel del ".$devnam);		
		stopcmd("	ip route del default dev ".$devnam);
		stopcmd("	xmldbc -s ".$stsp."/inet/ipv4/valid 0");
		stopcmd("	event ".$inf.".DOWN");
		stopcmd("	rm -f /var/run/".$inf.".UP");
		stopcmd("fi");
		//stopcmd("phpsh /etc/scripts/IPV4.INET.php ACTION=DETACH INF=".$inf);
	}
}

/* IPv4 *********************************************************/
fwrite(a,$START, "# INFNAME = [".$INET_INFNAME."]\n");
fwrite(a,$STOP,  "# INFNAME = [".$INET_INFNAME."]\n");

/* These parameter should be valid. */
$inf	= $INET_INFNAME;
$infp	= XNODE_getpathbytarget("", "inf", "uid", $inf, 0);
$phyinf	= query($infp."/phyinf");
$default= query($infp."/defaultroute");
$inet	= query($infp."/inet");
$inetp	= XNODE_getpathbytarget("/inet", "entry", "uid", $inet, 0);
$ifname	= PHYINF_getifname($phyinf);
$infprev = query($infp."/infprevious");

/* Create the runtime inf. Set phyinf. */
$stsp = XNODE_getpathbytarget("/runtime", "inf", "uid", $inf, 1);
set($stsp."/phyinf", $phyinf);
set($stsp."/defaultroute", $default);
set($stsp."/upperlayer", query($infp."/upperlayer"));
set($stsp."/lowerlayer", query($infp."/lowerlayer"));
set($stsp."/inet/uid", $inet);
set($stsp."/inet/addrtype", query($inetp."/addrtype"));
set($stsp."/infprevious", $infprev);

//$s = query($inetp."/ipv4/static");
//set($stsp."/inet/ipv4/static", $s);
//if ($s==1)	inet_ipv4_static($inf, $ifname, $inet, $inetp, $default);
//else		inet_ipv4_dynamic($inf, $ifname, $inet, $inetp);

$mode = query($inetp."/ipv4/ipv4in6/mode");
if($mode != "")
{
	$remote = query($inetp."/ipv4/ipv4in6/remote");
	set($stsp."/inet/ipv4/ipv4in6/mode", $mode);
	set($stsp."/inet/ipv4/ipv4in6/remote", $remote);
	fwrite(a,$START, "# IPv4in6 mode is [".$mode."]\n");
	if($mode=="dslite") inet_ipv4_dslite($inf, $ifname, $inet, $inetp, $infprev);
}
else
{
	$s = query($inetp."/ipv4/static");
	set($stsp."/inet/ipv4/static", $s);
	if ($s==1)	inet_ipv4_static($inf, $ifname, $inet, $inetp, $default);
	else		inet_ipv4_dynamic($inf, $ifname, $inet, $inetp);
}
?>
