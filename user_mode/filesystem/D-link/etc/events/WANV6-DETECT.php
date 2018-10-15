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
	$v4infp = XNODE_getpathbytarget("", "inf", "uid", $INFV4, 0);
	$phyinf = query($v4infp."/phyinf");
	$phyinfp = XNODE_getpathbytarget("/runtime", "phyinf", "uid", $phyinf, 0);
	$linkstatus = query($phyinfp."/linkstatus");
	$v4inet = query($v4infp."/inet");
	$v4inetp = XNODE_getpathbytarget("/inet", "entry", "uid", $v4inet, 0);
	$v4mode = query($v4inetp."/addrtype");
	if($v4mode=="ppp4") $over = query($v4inetp."/ppp4/over");
	if ($linkstatus == "")
	{
		/* We can't determine wan type if linkdown. */
		set("/runtime/services/wandetect/wantype", "None");
		set("/runtime/services/wandetect/status", "Link Down");
	}
	else
	{
		//$ifname	= PHYINF_getifname($phyinf);
		//$hostname = get("s", "/device/hostname");
		//$pidf = '/var/run/'.$INF.'_dhcp_det.pid';
		//$callback = '/etc/events/'.$INF.'_dhcp_cb.sh';
		//echo 'udhcpc -i '.$ifname.' -H '.$hostname.' -p '.$pidf.' -s '.$callback.' &\n';
		//echo 'xmldbc -t autodhcp_del:10:"kill \\`cat '.$pidf.'\\`"\n';
		//echo 'xmldbc -t autodhcp_chk:12:"/etc/events/WAN_dhcp_chk.sh '.$INF.'"\n';
		if($v4mode=="ppp4" && $over=="eth")
		{
			$llinfp = XNODE_getpathbytarget("", "inf", "uid", $INFLL, 0);
			/* detect PPPOE server */
			echo 'sh /etc/events/WANV6_ppp_dis.sh '.$INFV4.' START\n';
			//echo 'sleep 10\n';
			//echo 'result=`xmldbc -w /runtime/services/wandetect/wantype`\n';
			//echo 'echo wandetect result is $result > /dev/console\n';
			echo 'xmldbc -t autoppp_chk:12:"/etc/events/WANV6_ppp_chk.sh '.$INF.'"\n';
		}
		else
		{
			echo 'event WANV6.AUTOCONF.DETECT\n';
		}
	}
}
echo 'exit 0\n';
?>
