<? /* vi: set sw=4 ts=4: */
include "/htdocs/phplib/xnode.php";

function IPT_flushall($S)
{
	/* flush default chains */
	fwrite("a",$S, "iptables -F; iptables -F -t nat; iptables -F -t mangle; ");
	/* delete user-defined chains */
	fwrite("a",$S, "iptables -X; iptables -X -t nat; iptables -X -t mangle; ");
	/* set default policy */
	fwrite("a",$S, "iptables -P INPUT ACCEPT; iptables -P OUTPUT ACCEPT; iptables -P FORWARD ACCEPT; ");
	fwrite("a",$S, "iptables -t nat -P PREROUTING ACCEPT; iptables -t nat -P POSTROUTING ACCEPT\n");
}

function IPT_newchain($S,$tbl,$name)
{
	if ($tbl=="")	fwrite("a",$S, "iptables -N ".$name."\n");
	else			fwrite("a",$S, "iptables -t ".$tbl." -N ".$name."\n");
}

function IPT_saverun($S,$script)		{ fwrite("a",$S, "[ -f ".$script." ] && ".$script."\n"); }
function IPT_setfile($S,$file,$value)	{ fwrite("a",$S, "echo \"".$value."\" > ".$file."\n"); }
function IPT_killall($S,$app)			{ fwrite("a",$S, "killall ".$app."\n"); }

function IPT_build_time_command($uid)
{
	$sch = XNODE_getpathbytarget("/schedule", "entry", "uid", $uid, 0);
	if ($sch == "") return "";

	$days   = XNODE_getscheduledays($sch);
	$start  = query($sch."/start");
	$end    = query($sch."/end");
	if ($start=="" || $end=="" || $days=="") return "";
	return "-m time --timestart ".$start." --timestop ".$end." --days ".$days;
}

function IPT_scan_lan()
{
	$count = 0;
	foreach ("/runtime/inf")	$count++;
	
	$i=1;
	while ($i<=$count)
	{
		$name = "LAN-".$i;
		XNODE_del_var($name.".IPADDR");
		XNODE_del_var($name.".MASK");

		$path = XNODE_getpathbytarget("/runtime", "inf", "uid", $name, 0);
		if ($path != "")
		{
			$addrtype = query($path."/inet/addrtype");
			if ($addrtype == "ipv4" && query($path."/inet/ipv4/valid")==1)
			{
				$ipaddr = query($path."/inet/ipv4/ipaddr");
				$mask	= query($path."/inet/ipv4/mask");
				XNODE_set_var($name.".IPADDR", $ipaddr);
				XNODE_set_var($name.".MASK", $mask);
			}
		}
		$i++;
	}
}

?>
