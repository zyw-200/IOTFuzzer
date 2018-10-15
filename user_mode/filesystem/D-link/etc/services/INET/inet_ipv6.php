<? /* vi: set sw=4 ts=4: */
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/phyinf.php";
include "/htdocs/phplib/inf.php";

function startcmd($cmd)	{fwrite(a,$_GLOBALS["START"], $cmd."\n");}
function stopcmd($cmd)	{fwrite(a,$_GLOBALS["STOP"], $cmd."\n");}
function error($err)	{startcmd("exit ".$err); stopcmd("exit ".$err); return $err;}
function enable_ipv6($d){fwrite(w, "/proc/sys/net/ipv6/conf/".$d."/disable_ipv6", 0);}

/***********************************************************************/

function get_dns($p)
{
	anchor($p);
	$cnt = query("dns/count")+0;
	foreach ("dns/entry")
	{
		if ($InDeX > $cnt) break;
		if ($dns=="") $dns = $VaLuE;
		else $dns = $dns." ".$VaLuE;
	}
	return $dns;
}

function ipaddr_6to4($v4addr, $hostid)
{
	$a = dec2strf("%02x", cut($v4addr,0,'.'));
	$b = dec2strf("%02x", cut($v4addr,1,'.'));
	$c = dec2strf("%02x", cut($v4addr,2,'.'));
	$d = dec2strf("%02x", cut($v4addr,3,'.'));
	return "2002:".$a.$b.":".$c.$d."::".$hostid;
}

function ipaddr_6rd($prefix, $pfxlen, $v4addr, $v4mask, $hostid)
{
	$sla = ipv4hostid($v4addr, $v4mask);
	/*TRACE_debug("INET: ipaddr_6rd sla: [".$sla."]");*/
	$slalen = 32-$v4mask;
	/*TRACE_debug("INET: ipaddr_6rd slalen: [".$slalen."]");*/
	return ipv6ip($prefix, $pfxlen, $hostid, $sla, $slalen);
}

/***********************************************************************/

function inet_ipv6_ll($inf, $phyinf)
{
	startcmd("# inet_ipv6_ll(".$inf.",".$phyinf.")");

	/* Get the Link Local IP. */
	$p = XNODE_getpathbytarget("/runtime", "phyinf", "uid", $phyinf, 0);
	if ($p=="") return error("9");

	/* Get device name */
	$devnam = query($p."/name");
	fwrite(w, "/proc/sys/net/ipv6/conf/".$devnam."/disable_ipv6", 0);

	/* Get the link local address. */
	$ipaddr = query($p."/ipv6/link/ipaddr");
	$prefix = query($p."/ipv6/link/prefix");
	if ($ipaddr=="" || $prefix=="") return error("9");

	/* Start script */
	startcmd("phpsh /etc/scripts/IPV6.INET.php ACTION=ATTACH".
		" MODE=LL".
		" INF=".$inf.
		" DEVNAM=".$devnam.
		" IPADDR=".$ipaddr.
		" PREFIX=".$prefix
		);

	/* Stop script */
	stopcmd("phpsh /etc/scripts/IPV6.INET.php ACTION=DETACH INF=".$inf);
}

/***********************************************************************/

function prepare_6in4_child($stsp, $child, $prefix, $plen)
{
	$mac = PHYINF_getphymac($child);
	$hostid = ipv6eui64($mac);

	/* If the prefix is less than 64, the child can use 64 bits prefix length. */
	if ($plen<64)	$slalen = 64-$plen;
	else			$slalen = 1;

	while($slalen > 32)
	{
		$slalen = $slalen-32;
		$ipaddr = ipv6ip($prefix, $plen, "0", "0", 32);
		$prefix = $ipaddr;
		$plen = $plen+32;
		/*TRACE_debug("INET: 6IN4 Child prepare use ".$prefix."/".$plen);*/
	}

	$ipaddr = ipv6ip($prefix, $plen, $hostid, "1", $slalen);
	$pfxlen	= $plen + $slalen;

	TRACE_debug("INET: 6IN4 Child [".$child."] use ".$ipaddr."/".$pfxlen);
	set($stsp."/child/uid", $child);
	set($stsp."/child/ipaddr", $ipaddr);
	set($stsp."/child/prefix", $pfxlen);
}

function inet_ipv6_6in4($mode, $inf, $infp, $stsp, $inetp)
{
	startcmd("# inet_ipv6_6in4(".$mode.",".$inf."@".$infp."/".$stsp.",".$inetp.")");

	/* Get the IPv4 address of the previous interface. */
	$child = query($infp."/child");
	$prev = query($infp."/infprevious");
	if ($prev!="") $local = INF_getcurripaddr($prev);

	/* Get mtu of the previous interface */
	$previnfp = XNODE_getpathbytarget("","inf","uid",$prev,0);
	$previnet = query($previnfp."/inet");
	$previnetp = XNODE_getpathbytarget("/inet","entry","uid",$previnet,0);
	$prevaddrt = query($previnetp."/addrtype");
	$prevmtu = query($previnetp."/".$prevaddrt."/mtu");

	/* Get INET setting */
	anchor($inetp."/ipv6");
	$mtu = query("mtu");

	if($mtu=="") $mtu=$prevmtu+1-1-20;/* minus ipv4 hdr */ 

	if ($mode=="6TO4")
	{
		/* convert the 6to4 address */
		$relay	= query("ipv6in4/relay");
		$ipaddr = ipaddr_6to4($local, "1");
		$prefix = 16;
		if ($relay=="")	$gateway = "::192.88.99.1";
		else			$gateway = "::".$relay;

		/* prepare child setting */
		if ($child!="") prepare_6in4_child($stsp, $child, $ipaddr, 48);
	}
	else if ($mode=="6RD")
	{
		/* convert the 6rd address */
		$relay	= query("ipv6in4/relay");
		$pfx	= query("ipv6in4/rd/ipaddr");
		$prefix	= query("ipv6in4/rd/prefix");
		$v4mask	= query("ipv6in4/rd/v4mask");

		if($pfx=="")
		{
			/* 6rd dhcpv4 option */
			$prevstsp = XNODE_getpathbytarget("/runtime","inf","uid",$prev,0);
			$pfx = query($prevstsp."/udhcpc/sixrd_pfx");
			$prefix = query($prevstsp."/udhcpc/sixrd_pfxlen");
			$v4mask = query($prevstsp."/udhcpc/sixrd_msklen");
			$relay = query($prevstsp."/udhcpc/sixrd_brip");
		}
		$ipaddr = ipaddr_6rd($pfx, $prefix, $local, $v4mask, "1");
		/*TRACE_debug("INET: 6RD ipaddr [".$ipaddr."]");*/
		if ($relay=="")	$gateway = "::192.88.99.1";
		else			$gateway = "::".$relay;

		/* save related info */
		set($stsp."/inet/ipv6/ipv6in4/rd/ipaddr",$pfx);
		set($stsp."/inet/ipv6/ipv6in4/rd/prefix",$prefix);
		set($stsp."/inet/ipv6/ipv6in4/rd/v4mask",$v4mask);

		/* prepare child setting */
		if ($child!="")
		{
			prepare_6in4_child($stsp, $child, $ipaddr, $prefix+32-$v4mask);

			/* add blackhole routing rule */
			$ipaddrb = ipaddr_6rd($pfx, $prefix, $local, $v4mask, "0");
			$slalen = $prefix+32-$v4mask;
			if($v4mask>0 && $slalen<64)
			{
				startcmd('ip -6 route add blackhole '.$ipaddrb.'/'.$slalen.' dev lo');
				set($stsp."/blackhole/prefix",$ipaddrb);
				set($stsp."/blackhole/plen",$slalen);
			}
		}
	}
	else
	{
		$ipaddr = query("ipaddr");
		$prefix = query("prefix");
		$gateway= query("gateway");
		$remote = query("ipv6in4/remote");

		/* prepare child setting */
		if ($child!="") prepare_6in4_child($stsp, $child, $ipaddr, $prefix);
	}

	/* Start script ... */
	anchor($inetp."/ipv6");
	startcmd("phpsh /etc/scripts/V6IN4-TUNNEL.php ACTION=CREATE".
		" INF=".$inf." MODE=".$mode.
		" DEVNAM=".	"sit.".$inf.
		" MTU=".$mtu.
		" IPADDR=".	$ipaddr.
		" PREFIX=".	$prefix.
		" GATEWAY=".$gateway.
		" REMOTE=".	$remote.
		" LOCAL=".	$local.
		' "DNS='.get_dns($inetp."/ipv6").'"'
		);

	/* Stop script */
	stopcmd('phpsh /etc/scripts/V6IN4-TUNNEL.php ACTION=DESTROY INF='.$inf);
}

function inet_ipv6_tspc($inf, $infp, $stsp, $inetp)
{
	startcmd('# inet_ipv6_tspc('.$inf.'@'.$infp.'/'.$stsp.','.$inetp.')');

	/* Get INET setting */
	anchor($inetp.'/ipv6/ipv6in4');
	$mtu	= query('mtu');
	$remote	= query('remote');
	$userid	= query('tsp/username');
	$passwd = query('tsp/password');
	$prelen = query('tsp/prefix');

	/* TSPC config */
	$tspc_dir	= '/var/etc';
	$tspc_sh	= 'tspc_helper-'.$inf;
	$callback	= $tspc_dir.'/template/'.$tspc_sh.'.sh';
	$config		= $tspc_dir."/tspc-".$inf.".conf";

	/* the host type option. */
	$hosttype = "host";
	if (query("/runtime/device/layout")=="router")
	{
		$child = query($infp."/child");
		if ($child!="")
		{
			$hosttype = "router";
			set($stsp."/child/uid", $child);
		}
	}

	/* Generate the config file for tspc. */
	fwrite(w, $config,
		"# tspc.conf - Automatically generated for INET.".$inf."\n".
		"tsp_dir=".$tspc_dir."\n".
		"userid=".$userid."\n".
		"passwd=".$passwd."\n".
		"template=".$tspc_sh."\n".
		"server=".$remote."\n".
		"host_type=".$hosttype."\n".
		"prefixlen=".$prelen."\n".
		"if_tunnel_v6v4=sit.".$inf."\n".
		"if_tunnel_v6udpv4=tun.".$inf."\n".
		"auth_method=any\nclient_v4=auto\nretry_delay=30\ntunnel_mode=v6anyv4\nproxy_client=no\n".
		"keepalive=yes\nkeepalive_interval=30\n".
		
		);

	/* Generate the call back script. */
	fwrite(w, $callback,
		'#!/bin/sh\n'.
		'phpsh /etc/scripts/V6IN4-TUNNEL.php ACTION=CREATE'.
			' INF='.$inf.' MODE=TSP TYPE=$TSP_TUNNEL_MODE'.
			' DEVNAM=$TSP_TUNNEL_INTERFACE'.
			' MTU='.$mtu.
			' IPADDR=$TSP_CLIENT_ADDRESS_IPV6'.
			' PREFIX=$TSP_TUNNEL_PREFIXLEN'.
			' GATEWAY=$TSP_SERVER_ADDRESS_IPV6'.
			' REMOTE=$TSP_SERVER_ADDRESS_IPV4'.
			' "DNS='.get_dns($inetp."/ipv6").'"'.

			' "TSP_HOST_TYPE=$TSP_HOST_TYPE"'.
			' "TSP_SERVER_ADDRESS_IPV4=$TSP_SERVER_ADDRESS_IPV4"'.
			' "TSP_SERVER_ADDRESS_IPV6=$TSP_SERVER_ADDRESS_IPV6"'.
			' "TSP_CLIENT_ADDRESS_IPV4=$TSP_CLIENT_ADDRESS_IPV4"'.
			' "TSP_CLIENT_ADDRESS_IPV6=$TSP_CLIENT_ADDRESS_IPV6"'.
			' "TSP_PREFIX=$TSP_PREFIX"'.
			' "TSP_PREFIXLEN=$TSP_PREFIXLEN"\n'.
		'exit 0\n'
		);


	/* Start script */
	startcmd('chmod +x '.$callback);
	startcmd('tspc -vvv -f '.$config);

	/* Stop script */
	stopcmd('killall tspc');
	stopcmd('rm -f '.$config.' '.$callback);
	stopcmd('phpsh /etc/scripts/V6IN4-TUNNEL.php ACTION=DESTROY INF='.$inf);
}

/***********************************************************************/

function inet_ipv6_static($inf, $devnam, $inetp)
{
	startcmd("# inet_start_ipv6_static(".$inf.",".$devnam.",".$inetp.")");

	fwrite(w, "/proc/sys/net/ipv6/conf/".$devnam."/disable_ipv6", 0);

	/* if having previous inf */
	$infp   = XNODE_getpathbytarget("", "inf", "uid", $inf, 0);
	$previnf = query($infp."/infprevious");
	if($previnf!="") 
	{
		//$previnfp = XNODE_getpathbytarget("", "inf", "uid", $previnf, 0);
		$prevstsp = XNODE_getpathbytarget("/runtime", "inf", "uid", $previnf, 0);
		$devnam = query($prevstsp."/devnam");	
	}

	anchor($inetp."/ipv6");

	/* Start scripts */
	startcmd("phpsh /etc/scripts/IPV6.INET.php ACTION=ATTACH".
		" MODE=STATIC INF=".$inf.
		" DEVNAM=".		$devnam.
		" IPADDR=".		query("ipaddr").
		" PREFIX=".		query("prefix").
		" GATEWAY=".	query("gateway").
		" ROUTERLFT=".	query("routerlft").
		" PREFERLFT=".	query("preferlft").
		" VALIDLFT=".	query("validlft").
		' "DNS='.get_dns($inetp."/ipv6").'"'
		);

	/* Stop script */
	stopcmd("phpsh /etc/scripts/IPV6.INET.php ACTION=DETACH INF=".$inf);
}

/************************************************************/

function inet_ipv6_auto($inf, $infp, $ifname, $phyinf, $stsp, $inetp) 
{
	startcmd('# inet_start_ipv6_auto('.$inf.','.$infp.','.$ifname.','.$phyinf.','.$stsp.','.$inetp.')');

	/* Preparing ... */
	/* del it because we do it by rdisc6 */
	//$conf = "/proc/sys/net/ipv6/conf/".$ifname;
	//if (isfile($conf."/ra_mflag")!=1) return error(9);

	//$conf = "/var/run/".$ifname;

	/* Turn off forwarding to enable autoconfig. */
	//fwrite(w, $conf."/forwarding",			"0");
	//fwrite(w, $conf."/autoconf",			"0");/* Don't autoconfigure address, do it by outselves */
	//fwrite(w, $conf."/accept_ra",	"0");/* Don't let kernel add static route according to RA*/
	/* Turn off default route, we will handle the routing table. */
	//fwrite(w, $conf."/accept_ra_defrtr",	"0");
	/* Restart IPv6 function to send RS. */
	//fwrite(w, $conf."/disable_ipv6",		"1");
	//fwrite(w, $conf."/disable_ipv6",		"0");

	/* Record the device name */
	set($stsp."/devnam", $ifname);

	/* Record the infprevious */
	$infprev = query($infp."/infprevious");
	set($stsp."/infprevious", $infprev);

	/* Record the infnext */
	$infnext = query($infp."/infnext");
	set($stsp."/infnext", $infnext);

	/* Record the child uid. */
	if (query("/runtime/device/layout")=="router")
		set($stsp."/child/uid", query($infp."/child"));

	/* Generate wait script. */
	$rawait = "/var/servd/INET.".$inf."-rawait.sh";
	fwrite(w, $rawait,
		"#!/bin/sh\n".
		"phpsh /etc/scripts/RA-WAIT.php".
			" INF=".$inf.
			" PHYINF=".$phyinf.
			" DEVNAM=".$ifname.
			" DHCPOPT=".query($inetp."/ipv6/dhcpopt").
			' "DNS='.get_dns($inetp."/ipv6").'"'.
			" ME=".$rawait.
			"\n");

	/* Start script ... */
	startcmd("chmod +x ".$rawait);
	startcmd('xmldbc -t "ra.iptest.'.$inf.':0:'.$rawait.'"');

	/* Stop script ... */
	//stopcmd('echo 1 > /proc/sys/net/ipv6/conf/'.$ifname.'/forwarding');
	//stopcmd('echo 1 > /proc/sys/net/ipv6/conf/'.$ifname.'/autoconf');
	//stopcmd('echo 1 > /proc/sys/net/ipv6/conf/'.$ifname.'/accept_ra');
	//stopcmd('echo 1 > /proc/sys/net/ipv6/conf/'.$ifname.'/accept_ra_defrtr');
	//stopcmd('echo -1 > /proc/sys/net/ipv6/conf/'.$ifname.'/ra_mflag');
	//stopcmd('echo -1 > /proc/sys/net/ipv6/conf/'.$ifname.'/ra_oflag');
	stopcmd('rm -f '.$rawait);
	stopcmd('xmldbc -k ra.iptest.'.$inf);
	stopcmd("/etc/scripts/killpid.sh /var/servd/".$inf."-dhcp6c.pid");
	//if (isfile($conf.".ra_mflag")!=1) stopcmd("rm -f ".$conf.".ra_mflag");
	//if (isfile($conf.".ra_oflag")!=1) stopcmd("rm -f ".$conf.".ra_oflag");
	//if (isfile($conf.".ra_prefix")!=1) stopcmd("rm -f ".$conf.".ra_prefix");
	//if (isfile($conf.".ra_prefix_len")!=1) stopcmd("rm -f ".$conf.".ra_prefix_len");
	//if (isfile($conf.".ra_saddr")!=1) stopcmd("rm -f ".$conf.".ra_saddr");
	$conf = "/var/run/".$ifname;
	if($infprev!="")
	{
		$prevnam = PHYINF_getruntimeifname($infprev);
		$conf = "/var/run/".$prevnam;
	}
	stopcmd("rm -f ".$conf.".ra_mflag");
	stopcmd("rm -f ".$conf.".ra_oflag");
	stopcmd("rm -f ".$conf.".ra_prefix");
	stopcmd("rm -f ".$conf.".ra_prefix_len");
	stopcmd("rm -f ".$conf.".ra_saddr");
	stopcmd("rm -f ".$conf.".ra_rdnss");
	stopcmd("killall rdisc6");
	stopcmd('phpsh /etc/scripts/IPV6.INET.php ACTION=DETACH INF='.$inf);
}

/************************************************************/

function inet_ipv6_pppdhcp($inf, $infp, $ifname, $phyinf, $stsp, $inetp, $infprev) 
{
	startcmd('# inet_start_ipv6_pppdhcp('.$inf.','.$infp.','.$ifname.','.$phyinf.','.$stsp.','.$inetp.','.$infprev.')');

	$hlp = "/var/servd/".$inf."-dhcp6c.sh";
	$pid = "/var/servd/".$inf."-dhcp6c.pid";
	$cfg = "/var/servd/".$inf."-dhcp6c.cfg";

	/* DHCP over PPP session */
	if ($infprev!="")
	{
		$pppdev = PHYINF_getruntimeifname($infprev);
		if ($pppdev=="") 
		{
			TRACE_debug("INET: PPPDHCP - no PPP device");
			return error("9");
		}
	}

	/* Record the device name */
	set($stsp."/devnam", $ifname);
	/* Record the child uid. */
	if (query("/runtime/device/layout")=="router")
		set($stsp."/child/uid", query($infp."/child"));

	/* Generate configuration file. */
	$opt = query($inetp."/ipv6/dhcpopt");
	TRACE_debug("INET: PPPDHCP - dhcpopt: ".$opt);
	if (strstr($opt,"IA-NA")!="") {$send=$send."\tsend ia-na 0;\n"; $idas=$idas."id-assoc na {\n};\n";}
	if (strstr($opt,"IA-PD")!="") {$send=$send."\tsend ia-pd 0;\n"; $idas=$idas."id-assoc pd {\n};\n";}
	fwrite(w, $cfg,
		"interface ".$pppdev." {\n".
		$send.
		"\trequest domain-name-servers;\n".
		"\trequest domain-name;\n".
		"\tscript \"".$hlp."\";\n".
		"};\n".
		$idas);

	/* generate callback script */
	fwrite(w, $hlp,
		"#!/bin/sh\n".
		"echo [$0]: [$new_addr] [$new_pd_prefix] [$new_pd_plen] > /dev/console\n".
		"phpsh /etc/services/INET/inet6_dhcpc_helper.php".
			" INF=".$inf.
			" MODE=PPPDHCP".
			" DEVNAM=".$pppdev.
			" GATEWAY=".$router.
			" DHCPOPT=".$opt.
			' "NAMESERVERS=$new_domain_name_servers"'.
			' "NEW_ADDR=$new_addr"'.
			' "NEW_PD_PREFIX=$new_pd_prefix"'.
			' "NEW_PD_PLEN=$new_pd_plen"'.
			' "DNS='.$dns.'"'.
			' "NEW_AFTR_NAME=$new_aftr_name"'.
			"\n");

	/* Start DHCP client */
	startcmd("chmod +x ".$hlp);
	startcmd("dhcp6c -c ".$cfg." -p ".$pid." -t LL -o ".$ifname." ".$pppdev);

	/* Stop script ... */
	stopcmd("/etc/scripts/killpid.sh /var/servd/".$inf."-dhcp6c.pid");
	stopcmd('phpsh debug /etc/scripts/IPV6.INET.php ACTION=DETACH INF='.$inf);
}

/************************************************************/

function inet_ipv6_autodetect($inf, $infp, $ifname, $phyinf, $stsp, $inetp ,$infnext) 
{
	startcmd('# inet_start_ipv6_autodetect('.$inf.','.$infp.','.$ifname.','.$phyinf.','.$stsp.','.$inetp.','.$infnext.')');

	/* check if WANv4 is ppp or not */
	$v4infp   = XNODE_getpathbytarget("", "inf", "uid", $infnext, 0);
	$v4inet   = query($v4infp."/inet");
	$v4inetp  = XNODE_getpathbytarget("/inet", "entry", "uid", $v4inet, 0);
	$v4mode   = query($v4inetp."/addrtype");
	if($v4mode=="ppp4") $over = query($v4inetp."/ppp4/over");
	if($v4mode=="ppp4" && $over=="eth") 
	{
		$username = query($v4inetp."/ppp4/username");
		$password = query($v4inetp."/ppp4/password");
		$dialupmode= query($v4inetp."/ppp4/dialup/mode");
		$i = 1;
		$found=0;
		while ($i>0)
		{
			/* find ipv6 LL inf as first priority */
			$tinf = "WAN-".$i;
			$tinfp = XNODE_getpathbytarget("", "inf", "uid", $tinf, 0);
			if($tinfp=="") {$i=0; break;}
			$active = query($tinfp."/active");
			if($active=="1")
			{
				/* find addrtype is LL */
				$tinet = query($tinfp."/inet");	
				$tinetp = XNODE_getpathbytarget("/inet", "entry", "uid", $tinet, 0);
				$taddrtype = query($tinetp."/addrtype");
				if($taddrtype=="ipv6")
				{
					$tmode = query($tinetp."/".$taddrtype."/mode"); 
					if($tmode=="LL") { $found=1; break;}
				}
			}
			$i++;
		}
		/* if not found, choose empty inf */
		if($found=="0")
		{
			while ($i>0)
			{
				$tinf = "WAN-".$i;
				$tinfp = XNODE_getpathbytarget("", "inf", "uid", $tinf, 0);
				if($tinfp=="") {$i=0; break;}
				$active = query($tinfp."/active");
				if($active=="0"){ $found=1; break;}
				$i++;
			}
		}
		if($found=="0")
				startcmd('# Cannot find temporary inf for ppp10');

		$llinf="WAN-".$i;
		$llinfp = XNODE_getpathbytarget("", "inf", "uid", $llinf, 0);
		/* detect PPPOE server */
		startcmd('sh /etc/events/WAN_ppp_dis.sh '.$infnext.' START');
		startcmd('sleep 10');
		startcmd('result=`xmldbc -w /runtime/services/wandetect/wantype`');
		startcmd('echo wandetect result is $result > /dev/console');

		startcmd('if [ $result == "PPPoE" ]; then');
		startcmd('echo AUTODETECT change to ppp10 mode > /dev/console');
		startcmd('xmldbc -s '.$v4infp.'/child '.$llinf);
		startcmd('xmldbc -s '.$v4inetp.'/addrtype "ppp10"');
		startcmd('xmldbc -s '.$v4inetp.'/ppp6/username '.$username);
		startcmd('xmldbc -s '.$v4inetp.'/ppp6/password '.$password);
		startcmd('xmldbc -s '.$v4inetp.'/ppp6/dialup/mode '.$dialupmode);
		startcmd('xmldbc -s '.$llinfp.'/infnext '.$inf);
		startcmd('xmldbc -s '.$llinfp.'/inet ""');
		startcmd('xmldbc -s '.$infp.'/infprevious '.$llinf);
		startcmd('xmldbc -s '.$infp.'/infnext ""');
		startcmd('xmldbc -s '.$infp.'/defaultroute "0"');
		startcmd('xmldbc -s '.$infp.'/active "1"');
		//startcmd('xmldbc -s '.$infp.'/child "LAN-4"');
		startcmd('xmldbc -s '.$inetp.'/ipv6/mode "AUTO"');
		startcmd('xmldbc -s '.$inetp.'/ipv6/dhcpopt "IA-PD"');
		startcmd('event WAN.RESTART');
		startcmd('else');
		startcmd('xmldbc -s '.$inetp.'/ipv6/mode "AUTO"');
		startcmd('xmldbc -s '.$infp.'/infnext ""');
		startcmd('echo AUTODETECT change to AUTO mode > /dev/console');
		startcmd('event WAN.RESTART');
		startcmd('fi');
	}
	else
	{
		/* configure to AUTO mode */
		set($inetp."/ipv6/mode", "AUTO");
		set($infp."/infnext", "");
		set($infp."/defaultroute", "1");
		startcmd('# Change to AUTO mode');
		startcmd('echo AUTODETECT change to AUTO mode > /dev/console');
		startcmd('event WAN.RESTART');
	}
}

/* IPv6 *********************************************************/
fwrite(a,$START, "# INFNAME = [".$INET_INFNAME."]\n");
fwrite(a,$STOP,  "# INFNAME = [".$INET_INFNAME."]\n");

/* These parameter should be valid. */
$inf    = $INET_INFNAME;
$infp   = XNODE_getpathbytarget("", "inf", "uid", $inf, 0);
$phyinf = query($infp."/phyinf");
$default= query($infp."/defaultroute");
$inet   = query($infp."/inet");
$inetp  = XNODE_getpathbytarget("/inet", "entry", "uid", $inet, 0);
$ifname = PHYINF_getifname($phyinf);
$infprev = query($infp."/infprevious"); 
$infnext = query($infp."/infnext"); 

/* Create the runtime inf. Set phyinf. */
$stsp = XNODE_getpathbytarget("/runtime", "inf", "uid", $inf, 1);
set($stsp."/phyinf", $phyinf);
set($stsp."/defaultroute", $default);
$mode = query($inetp."/ipv6/mode");
if ($mode=="STATIC")	inet_ipv6_static($inf, $ifname, $inetp);
else if	($mode=="LL")	inet_ipv6_ll($inf, $phyinf);
else if	($mode=="AUTO")	inet_ipv6_auto($inf, $infp, $ifname, $phyinf, $stsp, $inetp);
else if	($mode=="PPPDHCP")	inet_ipv6_pppdhcp($inf, $infp, $ifname, $phyinf, $stsp, $inetp, $infprev);
else if	($mode=="TSP")	inet_ipv6_tspc($inf, $infp, $stsp, $inetp);
else if	($mode=="6IN4"
	||	 $mode=="6TO4"
	||	 $mode=="6RD")	inet_ipv6_6in4($mode, $inf, $infp, $stsp, $inetp);
else if	($mode=="AUTODETECT")	inet_ipv6_autodetect($inf, $infp, $ifname, $phyinf, $stsp, $inetp, $infnext);
?>
