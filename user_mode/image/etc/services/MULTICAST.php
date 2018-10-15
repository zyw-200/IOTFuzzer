<? /* vi: set sw=4 ts=4: */
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/phyinf.php";
include "/htdocs/phplib/xnode.php";

function addwifienhance($ifname, $phyinf)
{
	fwrite("a",$_GLOBALS["START"],
			'echo enable > /proc/net/br_igmpp_'.$phyinf.'\n'.
			'echo enable > /proc/net/br_mac_'.$phyinf.'\n'
		  );
	fwrite("a",$_GLOBALS["STOP"],
			'echo disable > /proc/net/br_igmpp_'.$phyinf.'\n'.
			'echo disable > /proc/net/br_mac_'.$phyinf.'\n'
		  );

	foreach("/runtime/phyinf")
	{
		if (query("brinf")==$ifname)
		{
			$wlaninf = query("name");
			fwrite("a",$_GLOBALS["START"],	'echo "setwl '.$wlaninf.'" > /proc/net/br_igmpp_'.$phyinf.'\n');
		}
	}

}

$cfile	= "/var/run/igmpproxy.conf";
$sfile	= "/etc/scripts/igmpproxy_helper.sh";
$iproxy = query("/device/multicast/igmpproxy");
$we		= query("/device/multicast/wifienhance");
$layout = query("/runtime/device/layout");

fwrite("w",$START, "#!/bin/sh\n");
fwrite("w",$STOP,  "#!/bin/sh\n");
fwrite("w",$cfile, "");

if ($we == "1")
{
	if ($layout == "router")
	{
		$i = 1;
		while ($i>0)
		{
			$ifname = "LAN-".$i;
			$phyinf = PHYINF_getruntimeifname($ifname);
			if ($phyinf=="") break;
			addwifienhance($ifname, $phyinf);
			$i++;
		}
	}
	else
	{
		$ifname = "BRIDGE-1";
		$phyinf = PHYINF_getruntimeifname($ifname);
		addwifienhance($ifname, $phyinf);
		fwrite("a", $cfile, $phyinf." downstream 1 0\n");
	}
}

if ($layout=="router" && $iproxy=="1")
{
	fwrite("a", $START, "igmpproxy -c ".$cfile." -s ".$sfile." &\n");
	fwrite("a",$STOP,
			'killall igmpproxy\n'.
			'rm -f '.$cfile.'\n'
		  );
	$i = 1;
	while ($i>0)
	{
		$ifname = "WAN-".$i;
		$phyinf = PHYINF_getruntimeifname($ifname);
		if ($phyinf=="") break;
		fwrite("a",$cfile, $phyinf." upstream 1 0\n");
		$i++;
	}
	$i = 1;
	while ($i>0)
	{
		$ifname = "LAN-".$i;
		$phyinf = PHYINF_getruntimeifname($ifname);
		if ($phyinf=="") break;
		fwrite("a", $cfile, $phyinf." downstream 1 0\n");
		$i++;
	}
	$i = 1;
	while ($i>0)
	{
		$waninf = "WAN-".$i;
		$path = XNODE_getpathbytarget("/runtime", "inf", "uid", $waninf, 0);
		if ($path == "") break;
		fwrite("a",$START,"service IPT.".$waninf." restart\n");
		fwrite('a', $STOP,"service IPT.".$waninf." restart\n");
		$i++;
	}
	fwrite("a",$STOP, "sleep 2\n");
}

fwrite("a",$START, "exit 0\n");
fwrite("a",$STOP,  "exit 0\n");
?>
