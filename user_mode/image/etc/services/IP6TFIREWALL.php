<?
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/phyinf.php";
include "/etc/services/IP6TABLES/ip6tlib.php";

$CHAIN = 'FIREWALL';
XNODE_set_var($CHAIN."6.USED", "0");

fwrite("w",$START, "#!/bin/sh\n");
fwrite("a",$START, "ip6tables -F ".$CHAIN."\n");
fwrite("w",$STOP,  "#!/bin/sh\n");
fwrite("a",$STOP,  "ip6tables -F ".$CHAIN."\n");

$def_policy = query("/acl6/firewall/policy");
$rules = 0;
if ($def_policy != "DISABLE")
{
	$cnt = query("/acl6/firewall/count");
	if ($cnt=="") $cnt = 0;
	foreach ("/acl6/firewall/entry")
	{
		if ($InDeX > $cnt) break;
		/* active ? */
		if (query("enable")!="1") continue;

		/* Reset the iptable command */
		$IPT = "";
		/* time */
		$sch = query("schedule");
		if ($sch!="")
		{
			$timecmd = IP6T_build_time_command($sch);
			if ($timecmd!="") $IPT=$IPT." ".$timecmd;
		}

		/* src interface */
		$srcinf = query("src/inf");
		if ($srcinf!="")
		{
			$phyinf = PHYINF_getruntimeifname($srcinf);
			if ($phyinf == "") continue;
			$IPT=$IPT." -i ".$phyinf;
		}
		/* dst interface */
		$dstinf = query("dst/inf");
		if ($dstinf!="")
		{
			$phyinf = PHYINF_getruntimeifname($dstinf);
			if ($phyinf == "") continue;
			$IPT=$IPT." -o ".$phyinf;
		}
		/* check the IP range. */
		$sipstart	= query("src/host/start");
		$sipend		= query("src/host/end");
		$dipstart	= query("dst/host/start");
		$dipend		= query("dst/host/end");
		if ($sipstart != "" && $dipstart != "")
		{
			/* We have both source and destination IP address restriction */
			if ($sipend!="" &&
				$dipend!="")		$IPT=$IPT." -m iprange --src-range ".$sipstart."-".$sipend.
												" --dst-range ".$dipstart."-".$dipend;
			else if ($sipend!="")	$IPT=$IPT." -d ".$dipstart." -m iprange --src-range ".$sipstart."-".$sipend;
			else if ($dipend!="")	$IPT=$IPT." -s ".$sipstart." -m iprange --dst-range ".$dipstart."-".$dipend;
			else					$IPT=$IPT." -s ".$sipstart." -d ".$dipstart;
		}
		else if ($sipstart != "")
		{
			/* We have only source IP address restriction */
			if ($sipend != "")	$IPT=$IPT." -m iprange --src-range ".$sipstart."-".$sipend;
			else				$IPT=$IPT." -s ".$sipstart;
		}
		else if ($dipstart != "")
		{
			/* We have only destination IP address restriction */
			if ($dipend != "")	$IPT=$IPT." -m iprange --dst-range ".$dipstart."-".$dipend;
			else				$IPT=$IPT." -d ".$dipstart;
		}

		/* policy ? ACCEPT/DROP */
		//$policy = query("policy");
		if($def_policy == "ACCEPT"){$policy	= "DROP";}
		else {$policy = "ACCEPT";}

		/* protocol ALL/TCP/UDP/ICMP */
		$prot = query("protocol");
		if ($prot=="TCP" || $prot=="UDP")
		{
			$dportstart	= query("dst/port/start");
			$dportend	= query("dst/port/end");

			/* port */
			if ($dportstart!="" && $dportend!="" &&
				$dportstart!=$dportend)	$IPT=$IPT." -m mport --dports ".$dportstart.":".$dportend;
			else if ($dportstart!="")	$IPT=$IPT." -m mport --dports ".$dportstart;
		}

		if ($policy == "DROP") fwrite('a',$_GLOBALS["START"],
			'ip6tables -A '.$CHAIN.' -p '.$prot.' '.$IPT.' -j LOG --log-level notice --log-prefix DRP:006:\n');
		fwrite("a",$_GLOBALS["START"],
			'ip6tables -A '.$CHAIN.' -p '.$prot.' '.$IPT.' -j '.$policy.'\n');

		$rules++;
	}

	if ($def_policy == "DROP")
	{
		fwrite("a",$_GLOBALS["START"],
			'ip6tables -A '.$CHAIN.' -m state --state ESTABLISHED,RELATED -j ACCEPT\n'.
			'ip6tables -A '.$CHAIN.' -j LOG --log-level notice --log-prefix DRP:006:\n'.
			'ip6tables -A '.$CHAIN.' -j DROP\n'
			);
	}
}

if($def_policy != "DISABLE" )
{
	XNODE_set_var($CHAIN."6.USED", $rules);
}

fwrite("a",$START, "exit 0\n");
fwrite("a",$STOP,  "exit 0\n");
?>
