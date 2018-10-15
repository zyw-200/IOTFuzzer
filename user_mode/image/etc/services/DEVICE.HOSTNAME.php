<?
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";

function startcmd($cmd)	{fwrite(a,$_GLOBALS["START"], $cmd."\n");}
function stopcmd($cmd)	{fwrite(a,$_GLOBALS["STOP"], $cmd."\n");} 

fwrite(w,$_GLOBALS["START"], "#!/bin/sh\n");
fwrite(w,$_GLOBALS["STOP"], "#!/bin/sh\n"); 

function setup_nameresolv($prefix)
{
	$i = 1;
	while ($i>0)
	{
		$ifname = $prefix."-".$i;
		$ifpath = XNODE_getpathbytarget("", "inf", "uid", $ifname, 0);
		if ($ifpath == "") { $i=0; break; }
		TRACE_debug("SERVICES/DEVICE.HOSTNAME: ifname = ".$ifname);
		startcmd("service NAMERESOLV.".$ifname." restart");
		$i++;
	}
}

setup_nameresolv("LAN");
$inet = XNODE_getpathbytarget("/runtime", "inf", "uid", "WAN-1", 0);
if (query($inet."/inet/addrtype")=="ipv4" && query($inet."/inet/ipv4/static")=="0")
{
	startcmd("service INET.WAN-1 restart");
}
?>
