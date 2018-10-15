<?
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/phyinf.php";

function startcmd($cmd)	{fwrite("a", $_GLOBALS["START"], $cmd."\n");}
function stopcmd($cmd)	{fwrite("a", $_GLOBALS["STOP"], $cmd."\n");}
/***********************************************/
function error($err, $msg)
{
	startcmd('# '.$msg);
	startcmd('exit '.$err);
	stopcmd( '# '.$msg);
	stopcmd( 'exit '.$err);
	return $err;
}
/***********************************************/

function commands($inf, $stsp, $phyinf, $dhcpsp)
{
	startcmd('# dhcps6: inf='.$inf.', stsp='.$stsp.', phyinf='.$phyinf.', dhcps='.$dhcpsp);

	/* get the network info. */
	$ifname = PHYINF_getifname($phyinf);

	$dhcps6_mode    = query($dhcpsp.'/mode');
	$dhcps6_network = query($dhcpsp.'/network');
	$dhcps6_prefix  = query($dhcpsp.'/prefix');
	$dhcps6_start   = query($dhcpsp.'/start');
	$dhcps6_count   = query($dhcpsp.'/count');
	$dhcps6_domain  = query($dhcpsp.'/domain');

	/* get pd info */
	$pd_enable    = query($dhcpsp.'/pd/enable');
	$pd_mode      = query($dhcpsp.'/pd/mode');
	$pd_slalen    = query($dhcpsp.'/pd/slalen');
	$pd_network   = query($dhcpsp.'/pd/network');
	$pd_prefix    = query($dhcpsp.'/pd/prefix');
	$pd_start     = query($dhcpsp.'/pd/start');
	$pd_count     = query($dhcpsp.'/pd/count');
	$pd_plft      = query($dhcpsp.'/pd/preferlft');
	$pd_vlft      = query($dhcpsp.'/pd/validlft');

	$inet_network   = query($stsp.'/inet/ipv6/ipaddr');
	$inet_prefix    = query($stsp.'/inet/ipv6/prefix');

	if ($dhcps6_network == '')
	{
		$network = ipv6networkid($inet_network, $inet_prefix);
		$prefix  = $inet_prefix;
		$dhcps6_domain  = XNODE_get_var($inf."_DOMAIN");
		XNODE_del_var($inf."_DOMAIN");
	}
	else
	{
		$network = $dhcps6_network;
		$prefix  = $dhcps6_prefix;
	}

	$domain = $dhcps6_domain;
	$mode   = $dhcps6_mode;
	$start  = ipv6ip($network, $prefix, $dhcps6_start, 0, 0);
	$stop	= ipv6addradd($start, $dhcps6_count);

	startcmd('# phyifname='.$ifname.', network='.$network.'/'.$prefix.', domain='.$domain);

	/* Update status. */
	set($stsp.'/dhcps6/mode',		$mode);
	set($stsp.'/dhcps6/network',	$network);
	set($stsp.'/dhcps6/prefix',		$prefix);
	set($stsp.'/dhcps6/start',		$start);
	set($stsp.'/dhcps6/count',		$dhcps6_count);
	set($stsp.'/dhcps6/domain',		$domain);
	setattr($stsp.'/dfltrtmtu', "get", "ip -6 route show default | scut -p mtu");

	/* Update pd status. */
	$rpdnetwork = query($stsp.'/dhcps6/pd/network');
	$rpdplen = query($stsp.'/dhcps6/pd/prefix');
	if($pd_mode=="0") /* generic */
	{
		/* all information can acquire from db */
	}
	else if($pd_mode=="1") /* dlink */
	{
		if($rpdplen<="56")				{$pd_slalen="4";$pd_start="1";$pd_count="15";}
		else if($rpdplen>="57" && $rpdplen<="59")	{$pd_slalen="3";$pd_start="1";$pd_count="7";}
		else if($rpdplen>="60" && $rpdplen<="61")	{$pd_slalen="2";$pd_start="1";$pd_count="3";}
		else if($rpdplen=="62")				{$pd_slalen="1";$pd_start="1";$pd_count="1";}
		else 						{$pd_enable="0";}
	}
	
	set($stsp.'/dhcps6/pd/enable',		$pd_enable);
	set($stsp.'/dhcps6/pd/mode',		$pd_mode);	
	$pdmsg = " ";
	if($pd_enable=="1")
	{
		set($stsp.'/dhcps6/pd/slalen',		$pd_slalen);
		if($rpdnetwork!="")
		{
			$pd_network = $rpdnetwork;
			$pd_prefix  = $rpdplen; 
		}
		set($stsp.'/dhcps6/pd/network',	$pd_network);
		set($stsp.'/dhcps6/pd/prefix',	$pd_prefix);
		set($stsp.'/dhcps6/pd/start',		$pd_start);
		set($stsp.'/dhcps6/pd/count',		$pd_count);
		if($pd_plft=="") $pd_plft="3600"; 
		if($pd_vlft=="") $pd_vlft="7200"; 
		set($stsp.'/dhcps6/pd/preferlft',	$pd_plft);
		set($stsp.'/dhcps6/pd/validlft',	$pd_vlft);

		/* generate pd config */
		$pdmsg= '\npd_conf default\n'.
			'{\n'.
			'	prefix '.$pd_network.'/'.$pd_prefix.' '.$pd_plft.' '.$pd_vlft.';\n'.
			'	segment_bits '.$pd_slalen.';\n'.
			'	pd_range '.$pd_start.' to '.$pd_count.';\n'.
			'};\n';
	}

	$cnt = query($stsp."/dhcps6/dns/entry#");
	while ($cnt > 0) {del($stsp."/dhcps6/dns/entry"); $cnt--;}

	/* Get DNS from user config */
	$dns = '';
	$cnt = query($dhcpsp.'/dns/count');
	foreach ($dhcpsp."/dns/entry")
	{
		if ($InDeX > $cnt) break;
		add($stsp."/dhcps6/dns/entry", $VaLuE);
		if ($dns=="")	$dns = $VaLuE;
		else		$dns = $dns.' '.$VaLuE;
	}

	$infp = XNODE_getpathbytarget("","inf","uid",$inf,0);
	/*
	$dns6 = query($infp."/dns6");
	if ($dns == '' && $dns6 != "")
	{
		$dns = $inet_network;
		add($stsp.'/dhcps6/dns/entry',	$dns);
	}
	*/
	$needrelay = query($infp."/dnsrelay");
	if($needrelay == "") $needrelay = 1;
	if ($dns == '' && $needrelay == "1")
	{
		$dns = $inet_network;
		add($stsp.'/dhcps6/dns/entry',	$dns);
	}

	if($needrelay == "0") 
	{
		//$cntmax = 3;
		foreach("/runtime/inf")
		{
			$addrtype = query("inet/addrtype");
			if($addrtype=="ipv6" || $addrtype=="ppp6")
			{
				if(query("inet/".$addrtype."/valid")=="1")
				{
					foreach("inet/".$addrtype."/dns")
					{
						//if($InDeX>$cntmax) break;
						add($stsp."/dhcps6/dns/entry", $VaLuE);
						if ($dns=="")	$dns = $VaLuE;
						else		$dns = $dns.' '.$VaLuE;
					}
				}
			}
			//if($addrtype=="ppp10")
			if($addrtype=="ppp4")
			{
				if(query("inet/ppp4/valid")=="1" || query("inet/ppp6/valid")=="1" )
				{
					foreach("inet/ppp6/dns")
					{
						//if($InDeX>$cntmax) break;
						add($stsp."/dhcps6/dns/entry", $VaLuE);
						if ($dns=="")	$dns = $VaLuE;
						else		$dns = $dns.' '.$VaLuE;
					}
				}
			}
		}	
		if ($dns == '')//needed?
		{
			$dns = $inet_network;
			add($stsp.'/dhcps6/dns/entry',	$dns);
		}
	}

	$mtu = query($stsp."/dfltrtmtu");
	if($mtu!="")
	{
		$mtucmd="	AdvLinkMTU ".$mtu.";\n";
	}
	else $mtucmd=" ";

	/* generate callback script */
	$hlp = "/var/servd/dhcps6.".$inf.".sh";
	fwrite(w, $hlp,
		"#!/bin/sh\n".
		"echo [$0]: [$DHCP6S_INF] [$DHCP6S_ACTION] [$DHCP6S_HOST] [$DHCP6S_MAC] [$DHCP6S_DST] [$DHCP6S_GATEWAY] [$DHCP6S_DEV]> /dev/console\n".
		"phpsh /etc/scripts/dhcp6s_helper.php".
			' "INF=$DHCP6S_INF"'.
			' "ACTION=$DHCP6S_ACTION"'.
			' "HOST=$DHCP6S_HOST"'.
			' "MAC=$DHCP6S_MAC"'.
			' "DST=$DHCP6S_DST"'.
			' "GATEWAY=$DHCP6S_GATEWAY"'.
			' "DEVNAM=$DHCP6S_DEV"'.
		"\n");
	startcmd('chmod +x '.$hlp);

	/* parse router Information option */
	$wanpdvlft = XNODE_get_var($inf."_PDVLFT");
	startcmd('# dhcps6: PDVLFT ='.$wanpdvlft);
	if($wanpdvlft!="")
	{
		XNODE_del_var($inf."_PDVLFT");
		$routemsg='\troute '.$rpdnetwork.'/'.$rpdplen.'\n'.
			'\t{\n'.
			'	\tAdvRoutePreference high;\n'.
			'	\tAdvRouteLifetime '.$wanpdvlft.';\n'.
			'\t};\n';
	}
	else
	{
		$routemsg='\n';
	}
 
	if ($mode == 'STATELESS')
	{
		startcmd('# stateless mode!!!');
		$racfg = '/var/run/radvd.'.$inf.'.conf';
		$rapid = '/var/run/radvd.'.$inf.'.pid';
		$routerlft = query($stsp.'/inet/ipv6/routerlft');
		$rdnss = query("/device/rdnss");
		$ralft = $routerlft/3;

		/* add rdnss info */
		if($rdnss=="1")
		{
			$oflagmsg ='	AdvOtherConfigFlag off;\n';
			if($routerlft !="") $rdnsslt=2*$ralft;
			else $rdnsslt=20;

			/* radvd only accept 3 dns server */
			$i = 0;
			$dnsr = '';
			while ($i < 3)
			{
				$val = scut($dns, $i, "");
				if ($dnsr=="")	$dnsr = $val;
				else		$dnsr = $dnsr.' '.$val;
				$i++;
			}
	  
			$rdnssmsg='\tRDNSS '.$dnsr.'\n'.
				'\t{\n'.
				'	\tAdvRDNSSPreference 8;\n'.
				'#	\tAdvRDNSSOpen off;\n'.
				'	\tAdvRDNSSLifetime '.$rdnsslt.';\n'.
				'\t};\n';
		}
		else
		{
			$oflagmsg ='	AdvOtherConfigFlag on;\n';
			$rdnssmsg='\n';
		}

		if($routerlft != "")
		{
			fwrite(w, $racfg,
				'# radvd config for '.$inf.'\n'.
				'interface '.$ifname.'\n'.
				'{\n'.
				'	AdvSendAdvert on;\n'.
				'	AdvManagedFlag off;\n'.
				//'	AdvOtherConfigFlag on;\n'.
				$oflagmsg.$mtucmd.
				'	MaxRtrAdvInterval '.$ralft.';\n'.
				'	prefix '.$network.'/'.$prefix.'\n'.
				'	{\n'.
				'		AdvOnLink on;\n'.
				'		AdvAutonomous on;\n'.
				//'	};\n'.$rdnssmsg.$routemsg.
				'	};\n'.$routemsg.$rdnssmsg.
				'};\n'.
			);
		}
		else
		{
			fwrite(w, $racfg,
				'# radvd config for '.$inf.'\n'.
				'interface '.$ifname.'\n'.
				'{\n'.
				'	AdvSendAdvert on;\n'.
				'	AdvManagedFlag off;\n'.
				//'	AdvOtherConfigFlag on;\n'.
				$oflagmsg.$mtucmd.
				'	MinRtrAdvInterval 3;\n'.
				'	MaxRtrAdvInterval 10;\n'.
				'	prefix '.$network.'/'.$prefix.'\n'.
				'	{\n'.
				'		AdvOnLink on;\n'.
				'		AdvAutonomous on;\n'.
				//'	};\n'.$rdnssmsg.$routemsg.
				'	};\n'.$routemsg.$rdnssmsg.
				'};\n'.
			);
		}
		startcmd('radvd -C '.$racfg.' -p '.$rapid);

		/*dns information via dhcpv6*/
		if($pd_enable=="1")
		{
			$mainmsg='interface '.$ifname.'\n'.
				'{\n'.
				'	allow rapid-commit;\n'.
				'	address-pool dummy 3600 7200;\n'.
				'};\n'.
				'pool dummy\n'.
				'{\n'.
				'	range fe80::1 to fe80::1;\n'.
				'};\n';
		}
		else
		{
			$mainmsg='interface '.$ifname.'\n'.
				'{\n'.
				'	allow rapid-commit;\n'.
				'};\n';
		}

		if($rdnss=="" || $rdnss=="0")
		{       
			/* SLAAC + Stateless DHCP */
			$dhcpcfg = '/var/run/dhcps6.'.$inf.'.conf';
			$dhcppid = '/var/run/dhcps6.'.$inf.'.pid';
			fwrite(w,$dhcpcfg,
				'option domain-name-servers '.$dns.';\n'.
				'option domain-name "'.$domain.'";\n'.
				$mainmsg.
				$pdmsg.
				'\n'
			);
			//startcmd('dhcp6s -c '.$dhcpcfg.' -P '.$dhcppid.' '.$ifname);
		}
		else
		{
			/* SLAAC + RDNSS */
			$dhcpcfg = '/var/run/dhcps6.'.$inf.'.conf';
			$dhcppid = '/var/run/dhcps6.'.$inf.'.pid';
			fwrite(w,$dhcpcfg,
				$mainmsg.
				$pdmsg.
				'\n'
			);
			//startcmd('dhcp6s -c '.$dhcpcfg.' -P '.$dhcppid.' '.$ifname);
		}
		startcmd('dhcp6s -c '.$dhcpcfg.' -P '.$dhcppid.' -s '.$hlp.' -u '.$inf.' '.$ifname);
	}
	else /* STATEFUL */
	{
		startcmd('# stateful mode!!!');
		$racfg = '/var/run/radvd.'.$inf.'.conf';
		$rapid = '/var/run/radvd.'.$inf.'.pid';
		$preferlft = query($stsp.'/inet/ipv6/preferlft');
		$validlft = query($stsp.'/inet/ipv6/validlft');
		fwrite(w,$racfg,
			'# radvd config for '.$inf.'\n'.
			'interface '.$ifname.'\n'.
			'{\n'.
			'	AdvSendAdvert on;\n'.
			'	AdvManagedFlag on;\n'.
			'	AdvOtherConfigFlag on;\n'.$mtucmd.
			'	MinRtrAdvInterval 3;\n'.
			'	MaxRtrAdvInterval 10;\n'.
			'	prefix '.$network.'/'.$prefix.'\n'.
			'	{\n'.
			'		AdvOnLink on;\n'.
			'		AdvAutonomous off;\n'.
			'	};\n'.
			'};\n'
			);
		startcmd('radvd -C '.$racfg.' -p .'.$rapid);

		$dhcpcfg = '/var/run/dhcps6.'.$inf.'.conf';
		$dhcppid = '/var/run/dhcps6.'.$inf.'.pid';
		
		if($preferlft == "") $preferlft="3600";
		if($validlft == "")  $validlft="7200";

		fwrite(w,$dhcpcfg,
			'option domain-name-servers '.$dns.';\n'.
			'option domain-name "'.$domain.'";\n'.
			'interface '.$ifname.' {\n'.
			'	allow rapid-commit;\n'.
			'	preference 128;\n'.
			'	address-pool dhcpv6pool '.$preferlft.' '.$validlft.';\n'.
			'};\n'.
			'\n'.
			'pool dhcpv6pool {\n'.
			'	range '.$start.' to '.$stop.';\n'.
			'};\n'.
			$pdmsg
		);
	
		startcmd('dhcp6s -c '.$dhcpcfg.' -P '.$dhcppid.' -s '.$hlp.' -u '.$inf.' '.$ifname);
	}

	startcmd('exit 0');

	/* stop dhcps & radvd */
	stopcmd("/etc/scripts/killpid.sh ".$dhcppid);
	stopcmd("/etc/scripts/killpid.sh ".$rapid);
	stopcmd("/etc/scripts/delpathbytarget.sh /runtime inf uid ".$inf." dhcps6");
	stopcmd('exit 0;');
}

/* Service will exit with:
 *	0 - Success
 *	8 - Interface is not available/active.
 *	9 - Something wrong with the configuration.
 */
function dhcps6setup($name)
{
	/* Get the interface */
	$infp = XNODE_getpathbytarget("", "inf", "uid", $name, 0);
	$stsp = XNODE_getpathbytarget("/runtime", "inf", "uid", $name, 0);
	startcmd('# '.$name.': infp='.$infp.', stsp='.$stsp);
	if ($stsp=="") return error(8, "dhcps6setup: ".$name." is not active.");

	/* Check runtime status */
	if (query($stsp."/inet/addrtype")!="ipv6" || query($stsp."/inet/ipv6/valid")!="1")
		return error(9, "dhcps6setup: ".$name." not IPv6.");

	/* Get the physical interface */
	$phyinf = query($stsp."/phyinf");
	if ($phyinf=="") return error(9, "dhcps6setup: ".$name." no phyinf.");

	/* Get the profile */
	$dhcps6 = query($infp."/dhcps6");
	$dhcpsp = XNODE_getpathbytarget("/dhcps6", "entry", "uid", $dhcps6, 0);
	if ($dhcpsp=="") return error(9, "dhcps6setup: ".$name." no profile.");

	/* */
	return commands($name, $stsp, $phyinf, $dhcpsp);
}
?>

