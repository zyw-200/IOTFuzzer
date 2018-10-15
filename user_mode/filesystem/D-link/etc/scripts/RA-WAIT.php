#!/bin/sh
<?
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/phyinf.php";

function cmd($cmd)	{echo $cmd."\n";}
function msg($msg)	{cmd("echo [DHCP6C]: ".$msg." > /dev/console");}
function error($m)	{cmd("echo [RA-WAIT]: ERROR: ".$m); return 9;}

/***********************************************************************/
function dhcp_client($mode, $inf, $devnam, $opt, $router, $dns)
{
	$hlp = "/var/servd/".$inf."-dhcp6c.sh";
	$pid = "/var/servd/".$inf."-dhcp6c.pid";
	$cfg = "/var/servd/".$inf."-dhcp6c.cfg";

	/* DHCP over PPP session ? */
	$previnf = XNODE_get_var($inf."_PREVINF");
	XNODE_del_var($inf."_PREVINF");

	/* dslite ? */
	$nextinf = XNODE_get_var($inf."_NEXTINF");
	XNODE_del_var($inf."_NEXTINF");

	//if ($mode=="PPPDHCP" && $_GLOBALS["PREVINF"]!="")
	//msg("mode is ".$mode.", previnf is ".$previnf);
	msg("mode is ".$mode.", previnf is ".$previnf.", nextinf is ".$nextinf);
	if ($mode=="PPPDHCP" && $previnf!="")
	{
		//$pppdev = PHYINF_getruntimeifname($_GLOBALS["PREVINF"]);
		$pppdev = PHYINF_getruntimeifname($previnf);
		if ($pppdev=="") return error("no PPP device.");
		msg("PPP device = ".$pppdev);
	}
	msg("dhcpopt: ".$opt);

	/* Gererate DHCP-IAID from 32-bit of mac address*/
	$mac = PHYINF_getphymac($inf);
	$mac1 = cut($mac, 3, ":"); $mac2 = cut($mac, 0, ":"); $mac3 = cut($mac, 1, ":"); $mac4 = cut($mac, 2, ":");
	$iaidstr = $mac1.$mac2.$mac3.$mac4;
	$iaid = strtoul($iaidstr, 16);	
		
	/* Generate configuration file. */
	if ($mode=="INFOONLY")
	{
		$send="\tinformation-only;\n";
		$idas="";
	}
	else
	{
		//if (strstr($opt,"IA-NA")!="") {$send=$send."\tsend ia-na 0;\n"; $idas=$idas."id-assoc na {\n};\n";}
		if (strstr($opt,"IA-NA")!="") {$send=$send."\tsend ia-na ".$iaid.";\n"; $idas=$idas."id-assoc na ".$iaid."{\n};\n";}
		if (strstr($opt,"IA-PD")!="") {$send=$send."\tsend ia-pd 0;\n"; $idas=$idas."id-assoc pd {\n};\n";}
	}

	if($mode=="PPPDHCP") $dname = $pppdev;
	else $dname = $devnam;

	$nextinfp = XNODE_getpathbytarget("", "inf", "uid", $nextinf, 0);
	$nextinet = query($nextinfp."/inet");
	$nextinetp = XNODE_getpathbytarget("inet", "entry", "uid", $nextinet, 0);
	$nextmode = query($nextinetp."/ipv4/ipv4in6/mode");
		
	if($nextinf!="" && $nextmode=="dslite")
	{ 
		$rqstmsg = "\trequest aftr-server-domain-name;\n";
	}
	else	$rqstmsg = "";

	fwrite(w, $cfg,
		//"interface ".$devnam." {\n".
		"interface ".$dname." {\n".
		$send.
		"\trequest domain-name-servers;\n".
		"\trequest domain-name;\n".
		//"\trequest aftr-server-domain-name;\n".
		$rqstmsg.
		"\tscript \"".$hlp."\";\n".
		"};\n".
		$idas);

	/* generate callback script */
	fwrite(w, $hlp,
		"#!/bin/sh\n".
		"echo [$0]: [$new_addr] [$new_pd_prefix] [$new_pd_plen] [$new_pd_pltime] [$new_pd_vltime] > /dev/console\n".
		"phpsh /etc/services/INET/inet6_dhcpc_helper.php".
			" INF=".$inf.
			" MODE=".$mode.
			//" DEVNAM=".$devnam.
			" DEVNAM=".$dname.
			" GATEWAY=".$router.
			" DHCPOPT=".$opt.
			' "NAMESERVERS=$new_domain_name_servers"'.
			' "DOMAIN=$new_domain_name"'.
			' "NEW_ADDR=$new_addr"'.
			' "NEW_PD_PREFIX=$new_pd_prefix"'.
			' "NEW_PD_PLEN=$new_pd_plen"'.
			' "NEW_PD_PLTIME=$new_pd_pltime"'.
			' "NEW_PD_VLTIME=$new_pd_vltime"'.
			' "DNS='.$dns.'"'.
			' "NEW_AFTR_NAME=$new_aftr_name"'.
			"\n");

	/* Start DHCP client */
	cmd("chmod +x ".$hlp);
	if ($pppdev=="")
		 cmd("dhcp6c -c ".$cfg." -p ".$pid." -t LL ".$devnam);
	else cmd("dhcp6c -c ".$cfg." -p ".$pid." -t LL -o ".$devnam." ".$pppdev);
	return 0;
}

/***********************************************************************/

function main_entry($inf, $phyinf, $devnam, $dhcpopt, $dns, $me)
{
	/* generate callback script */
	$hlp = "/var/servd/".$inf."-rdisc6.sh";
	fwrite(w, $hlp,
		"#!/bin/sh\n".
		"echo [$0]: [$IFNAME] [$MFLAG] [$OFLAG] > /dev/console\n".
		"phpsh /etc/services/INET/inet6_rdisc6_helper.php".
			' "IFNAME=$IFNAME"'.
			' "MFLAG=$MFLAG"'.
			' "OFLAG=$OFLAG"'.
			' "PREFIX=$PREFIX"'.
			' "PFXLEN=$PFXLEN"'.
			' "LLADDR=$LLADDR"'.
			' "RDNSS=$RDNSS"'.
			"\n");

	cmd("chmod +x ".$hlp);

	/* run rdisc */
	//cmd("killall rdisc6");
	//cmd("sleep 1");
	
	/* INF status path. */
	$stsp = XNODE_getpathbytarget("/runtime", "inf", "uid", $inf, 0);
	if ($stsp=="") return error($inf." has not runtime nodes !");

	/* need infprev */
	$infprev = query($stsp."/infprevious");
	if($infprev!="")
	{
		$prevstsp = XNODE_getpathbytarget("/runtime", "inf", "uid", $infprev, 0);
		$prevdevnam = query($prevstsp."/devnam");
		$prevphyinf = query($prevstsp."/phyinf");
	}

	/* check if interface if ppp */
	if(strstr($prevdevnam,"ppp")=="")
	{
		if(strstr($prevdevnam,"sit")=="")
			cmd("rdisc6 -c ".$hlp." -q ".$devnam." &");	
		else
		{
			msg("SIT-Autoconf mode");
			/* auto in sit mode */
			$p = XNODE_getpathbytarget("/runtime", "phyinf", "uid", $prevphyinf, 0);
			if($p!="") $ipaddr = query($p."/ipv6/link/ipaddr");
			else return error("SIT tunnel need exist!! ");
			msg("ipaddr: ".$ipaddr." and devnam: ".$prevdevnam);
			cmd("rdisc6 -c ".$hlp." -q -e ".$ipaddr." ".$prevdevnam." &");	
		}
	}
	else
	{
		msg("PPP-Autoconf mode");
		/* need infprev */
		$infprev = query($stsp."/infprevious");
		$prevstsp = XNODE_getpathbytarget("/runtime", "inf", "uid", $infprev, 0);
		/* need infprev */
		$atype = query($prevstsp."/inet/addrtype");
		if($atype == "ipv6")
			$ipaddr = query($prevstsp."/inet/ipv6/ipaddr");/* share session with ipv4*/
		else if($atype == "ppp6")  	
			$ipaddr = query($prevstsp."/inet/ppp6/local");/* only ipv6 session */
		else return error($inf." has wrong addrtype of infprevious");
		cmd("rdisc6 -c ".$hlp." -q -e ".$ipaddr." ".$prevdevnam." &");	
	}

	cmd("sleep 5");

	/* Clear any old records. */
	del($stsp."/stateless");
	/* Preparing & Get M flag */
	$child	= query($stsp."/child/uid");
	if(strstr($prevdevnam,"ppp")=="") $conf	= "/var/run/".$devnam;
	else $conf = "/var/run/".$prevdevnam;
	$mflag	= fread("e", $conf.".ra_mflag");
	$oflag	= fread("e", $conf.".ra_oflag"); 
	$rdnss  = fread("e", $conf.".ra_rdnss");

	/* need infnext */
	$infnext = query($stsp."/infnext");
	if($infnext!="")
	{
		XNODE_set_var($inf."_NEXTINF",$infnext);
	}

	if(strstr($prevdevnam,"ppp")=="") msg($inf."/".$devnam.", M=[".$mflag."], O=[".$oflag."]");
	else msg($inf."/".$prevdevnam.", M=[".$mflag."], O=[".$oflag."]");

	if ($mflag=="1")
	{
		/* Stateful ... */
		if ($dhcpopt=="")
		{
			if ($child=="") $dhcpopt = "IA-NA";
			else $dhcpopt = "IA-NA+IA-PD";
		}
		//dhcp_client("STATEFUL", $inf, $devnam, $dhcpopt, $router, $dns);
		if(strstr($prevdevnam,"ppp")=="")
			dhcp_client("STATEFUL", $inf, $devnam, $dhcpopt, $router, $dns);
		else
		{
			XNODE_set_var($inf."_PREVINF",$infprev);
			dhcp_client("PPPDHCP", $inf, $devnam, $dhcpopt, $router, $dns);
		}
		return 0;
	}
	else if ($mflag=="0")
	{
		/* Stateless */
		$p = XNODE_getpathbytarget("/runtime", "phyinf", "uid", $phyinf, 0);
		if ($p=="") return error($phyinf." has not runtime nodes!");
		/* Get self-configured IP address. */
		/*$ipaddr = query($p."/ipv6/global/ipaddr");*/
		/*$prefix = query($p."/ipv6/global/prefix");*/
		
		$mac = PHYINF_getphymac($inf);
		$hostid = ipv6eui64($mac);
		$ra_prefix = fread("e", $conf.".ra_prefix");
		$prefix = fread("e", $conf.".ra_prefix_len");
		$ipaddr = ipv6ip($ra_prefix, $prefix, $hostid, 0, 0);
		$router	= fread("e", $conf.".ra_saddr");

		if(strstr($prevdevnam,"ppp")!="")
		{
			$p = XNODE_getpathbytarget("/runtime", "phyinf", "uid", $prevphyinf, 0);
			$llipaddr = query($p."/ipv6/link/ipaddr");
			$hostid = ipv6hostid($llipaddr,64);
			$ipaddr = ipv6ip($ra_prefix, $prefix, $hostid, 0, 0);
		}

		if ($ipaddr!="")
		{
			if ($oflag=="0" && $dns=="" && $rdnss!="") {$dns=$rdnss;}

			msg("Stateless Self-Config IP: ".$ipaddr."/".$prefix);
			//$router	= fread("e", $conf."/ra_saddr");
			if ($child!="")
			{
				set($stsp."/stateless/ipaddr",	$ipaddr);
				set($stsp."/stateless/prefix",	$prefix);
				set($stsp."/stateless/gateway",	$router);
				set($stsp."/stateless/dns",		$dns);
				/* run dhcpc6 for the child interface, and attach at the callback of dhcpc. */
				//dhcp_client("STATELESS", $inf, $devnam, "IA-PD", $router, $dns);
				if(strstr($prevdevnam,"ppp")=="")
					dhcp_client("STATELESS", $inf, $devnam, "IA-PD", $router, $dns);
				else
				{
					XNODE_set_var($inf."_PREVINF",$infprev);
					dhcp_client("PPPDHCP", $inf, $devnam, "IA-PD", $router, $dns);
				}
			}
			else
			{
				cmd("phpsh /etc/scripts/IPV6.INET.php ACTION=ATTACH".
						" MODE=STATELESS".
						" INF=".$inf.
						" DEVNAM=".$devnam.
						" IPADDR=".$ipaddr.
						" PREFIX=".$prefix.
						" GATEWAY=".$router.
						' "DNS='.$dns.'"');
				if ($oflag=="1" && $dns=="")
				{
					msg("STATELESS DHCP: information only.");
					dhcp_client("INFOONLY", $inf, $devnam, "", "", "");
				}
			}
			return 0;
		}
	}
	
	cmd("killall rdisc6");
	cmd("sleep 1");

	/* Not configured, try later. */
	cmd('xmldbc -t "ra.iptest.'.$inf.':5:'.$me.'"');

	/* force to send RS */
	//$conf = "/proc/sys/net/ipv6/conf/".$_GLOBALS["DEVNAM"];
	//fwrite(w, $conf."/disable_ipv6",  "1");
	//fwrite(w, $conf."/disable_ipv6",  "0");

	return 0;
}

/* Main entry */
main_entry(
	$_GLOBALS["INF"],
	$_GLOBALS["PHYINF"],
	$_GLOBALS["DEVNAM"],
	$_GLOBALS["DHCPOPT"],
	$_GLOBALS["DNS"],
	$_GLOBALS["ME"]
	);
?>
