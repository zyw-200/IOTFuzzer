#!/bin/sh
<? /* $IFNAME, $DEVICE, $SPEED, $IP, $REMOTE, $PARAM */
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/phyinf.php";

$infp = XNODE_getpathbytarget("", "inf", "uid", $PARAM, 0);
if ($infp == "") exit;
$inet = query($infp."/inet");
if ($inet == "") exit;

$defaultroute = query($infp."/defaultroute");
/* create phyinf */
PHYINF_setup("PPP.".$PARAM, "ppp", $IFNAME);

/* get mtu value*/
$inetp = XNODE_getpathbytarget("/inet", "entry", "uid", $inet, 0);
$mtu = query($inetp."/ppp4/mtu");

/* create inf */
$stsp = XNODE_getpathbytarget("/runtime", "inf", "uid", $PARAM, 1);
del($stsp."/inet");
set($stsp."/inet/uid",			$inet);
set($stsp."/inet/addrtype",		"ppp4");
set($stsp."/inet/uptime",		query("/runtime/device/uptime"));
set($stsp."/inet/ppp4/valid",	"1");
set($stsp."/inet/ppp4/mtu",           	$mtu);
set($stsp."/inet/ppp4/local",	$IP);
set($stsp."/inet/ppp4/peer",	$REMOTE);
set($stsp."/phyinf",			"PPP.".$PARAM);
set($stsp."/defaultroute",		$defaultroute);

/* Add this network in 'LOCAL' */
echo 'ip route add '.$REMOTE.' dev '.$IFNAME.' src '.$IP.' table LOCAL\n';

if ($defaultroute!="")
{
	if($defaultroute == 0)
	{
		echo 'ip route add '.$REMOTE.' dev '.$IFNAME.' src '.$IP.' table '.$PARAM.'\n';
	}
	else if($defaultroute == 1)
	{
		echo '#pppd will insert defaultroute so we do not add defaultroute.\n';
	}
	else if($defaultroute > 1)
	{
		echo 'ip route add default via '.$REMOTE.' metric '.$defaultroute.' table default\n';
	}
}

/* user dns */
$cnt = 0;
if ($inetp != "")
{
	$cnt = query($inetp."/ppp4/dns/count");
	if ($cnt=="") $cnt = 0;
	$i = 0;
	while ($i < $cnt)
	{
		$i++;
		$value = query($inetp."/ppp4/dns/entry:".$i);
		if ($value != "") add($stsp."/inet/ppp4/dns", $value);
	}
}
/* auto dns */
if ($cnt == 0 && isfile("/etc/ppp/resolv.conf.".$PARAM)==1)
{
	$dnstext = fread("r", "/etc/ppp/resolv.conf.".$PARAM);
	$cnt = scut_count($dnstext, "");
	$i = 0;
	while ($i < $cnt)
	{
		$token = scut($dnstext, $i, "");
		if ($token == "nameserver")
		{
			$i++;
			$token = scut($dnstext, $i, "");
			add($stsp."/inet/ppp4/dns", $token);
		}
		$i++;
	}
}

/* We use PING peer IP to trigger the dailup at 'ondemand' mode.
 * So we need to update the command to PING the new gateway. */
$dial = XNODE_get_var($PARAM.".DIALUP");
if ($dial=="") $dial = query($inetp."/ppp4/dialup/mode");
if ($dial=="ondemand")
	echo 'event '.$PARAM.'.PPP.DIALUP add "ping '.$REMOTE.'"\n';

/* 3G connection mode */
if (query($inetp."/ppp4/over")=="tty") echo "event TTY.UP\n";

/*Check LAN DHCP setting. We will resatrt DHCP server if the DNS relay is disabled*/
foreach ("/inf")
{
	$disable= query("disable");
	$active = query("active");
	$dhcps4 = query("dhcps4");
	$dns4   = query("dns4");
	if ($disable != "1" && $active=="1" && $dhcps4!="")
	{
		if ($dns4 =="") echo "event DHCPS4.RESTART\n";
	}
}

/*
echo "event ".$PARAM.".UP\n";
echo "echo 1 > /var/run/".$PARAM.".UP\n";
*/

/* check if the other up script is finished */
$child = query($infp."/child");
if($child!="")
{
	$childip = query($stsp."/child/ipaddr");
	if($childip!="")
	{
		echo "echo IPV6CP is ready!! > /dev/console\n";

		/* user dns */
		$cnt = 0;
		if ($inetp != "")
		{
			$cnt = query($inetp."/ppp6/dns/count");
			if ($cnt=="") $cnt = 0;
			$i = 0;
			while ($i < $cnt)
			{
				$i++;
				$value = query($inetp."/ppp6/dns/entry:".$i);
				if ($value != "") add($stsp."/inet/ppp6/dns", $value);
			}
		}
		//set($stsp."/inet/addrtype",		"ppp10");
		set($stsp."/inet/addrtype",		"ppp4");

		$childpfx = query($stsp."/child/prefix");
		XNODE_set_var($child."_ADDRTYPE", "ipv6");
		XNODE_set_var($child."_IPADDR", $childip);
		XNODE_set_var($child."_PREFIX", $childpfx);
		XNODE_set_var($child."_PHYINF", "PPP.".$PARAM);
		echo "event ".$PARAM.".UP\n";
		echo "echo 1 > /var/run/".$PARAM.".UP\n";
		//echo "phpsh debug /etc/scripts/IPV6.INET.php ACTION=ATTACH INF=".$PARAM;
	}
	else echo "echo IPV6CP is not ready!! WAIT --- > /dev/console\n";
}
else
{
	echo "event ".$PARAM.".UP\n";
	echo "echo 1 > /var/run/".$PARAM.".UP\n";
}
?>
exit 0
