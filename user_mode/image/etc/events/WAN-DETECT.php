<?
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/phyinf.php";

echo '#!/bin/sh\n';

$layout = query("/runtime/device/layout");
del("/runtime/services/wandetect");

if ($layout=="bridge")
{
	/* There is no wan when Device works on bridge mode. */
	set("/runtime/services/wandetect/wantype", "None");
	set("/runtime/services/wandetect/desc", "Bridge Mode");
}
else
{
	$infp = XNODE_getpathbytarget("", "inf", "uid", $INF, 0);
	$phyinf = query($infp."/phyinf");
	$phyinfp = XNODE_getpathbytarget("/runtime", "phyinf", "uid", $phyinf, 0);
	$linkstatus = query($phyinfp."/linkstatus");
	if ($linkstatus == "")
	{
		/* We can't determine wan type if linkdown. */
		set("/runtime/services/wandetect/wantype", "None");
		set("/runtime/services/wandetect/status", "Link Down");
	}
	else
	{
		$ifname	= PHYINF_getifname($phyinf);
		$hostname = get("s", "/device/hostname");
		$pidf = '/var/run/'.$INF.'_dhcp_det.pid';
		$callback = '/etc/events/'.$INF.'_dhcp_cb.sh';
		echo 'udhcpc -i '.$ifname.' -H '.$hostname.' -p '.$pidf.' -s '.$callback.' &\n';
		echo 'xmldbc -t autodhcp_del:10:"kill \\`cat '.$pidf.'\\`"\n';
		echo 'xmldbc -t autodhcp_chk:12:"/etc/events/WAN_dhcp_chk.sh '.$INF.'"\n';
	}
}
echo 'exit 0\n';
?>
