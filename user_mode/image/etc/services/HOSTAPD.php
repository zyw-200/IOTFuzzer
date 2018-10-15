<?
include "/etc/services/wifi.php";
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/phyinf.php";
include "/htdocs/phplib/xnode.php";

function hostapd_error($errno)
{
	fwrite("a", $_GLOBALS["START"], "exit ".$errno."\n");
	fwrite("a", $_GLOBALS["STOP"],  "exit ".$errno."\n");
}

fwrite("w",$START, "#!/bin/sh\n");
fwrite("w", $STOP, "#!/bin/sh\n");

/* Get the phyinf */
$phy1	= XNODE_getpathbytarget("", "phyinf", "uid", "WLAN-1", 0);	if ($phy1 == "")	return;
$phy2	= XNODE_getpathbytarget("", "phyinf", "uid", "WLAN-2", 0);	if ($phy2 == "")	return;
$phyrp1	= XNODE_getpathbytarget("/runtime", "phyinf", "uid", "WLAN-1", 0);	if ($phyrp1 == "")	return;
$phyrp2	= XNODE_getpathbytarget("/runtime", "phyinf", "uid", "WLAN-2", 0);	if ($phyrp2 == "")	return;

/* prepare needed config files */
$winf1	= query($phyrp1."/name");
$winf2	= query($phyrp2."/name");
$hostapdcfg1		= "/var/run/hostapd-".$winf1.".conf.new";
$hostapdcfg2		= "/var/run/hostapd-".$winf2.".conf.new";
$hapd_eapuserfile1	= "/var/run/hostapd-".$winf1.".wps.eap_user.new";
$hapd_topology 		= "/var/topology.conf";

/* Is the phyinf active? */
$active1 = query($phy1."/active");
$active2 = query($phy2."/active");

if ($active1!=1 && $active2!=1) {hostapd_error("8"); return; }
if ($active1==1)				{ $h_winf1 = $winf1; }
if ($active2==1)				{ $h_winf2 = $winf2; }

/* config file of hostapd. */
fwrite("a", $START,
	'xmldbc -P /etc/services/WIFI/hostapdcfg.php -V TOPOLOGYFILE='.$hapd_topology.' -V WLINF1='.$h_winf1.' -V WLINF2='.$h_winf2.' -V HOSTAPDCFG1='.$hostapdcfg1.' -V HOSTAPDCFG2='.$hostapdcfg2. ' -V PHY_UID=WLAN-1 > /dev/null\n'.
	'xmldbc -P /etc/services/WIFI/hostapdcfg.php -V PHY_UID=WLAN-1 -V EAPUSERFILE='.$hapd_eapuserfile1.' > '.$hostapdcfg1.'\n'.
	'xmldbc -P /etc/services/WIFI/hostapdcfg.php -V PHY_UID=WLAN-2 -V EAPUSERFILE='.$hapd_eapuserfile1.' > '.$hostapdcfg2.'\n'
);

fwrite("a", $START, 'sleep 3\n');
fwrite("a", $START, 'hostapd '.$hapd_topology.' &\n');
fwrite("a", $START, 'sleep 4\n');
fwrite("a", $START, 'brctl addif br0 '.$winf1.'\n');
fwrite("a", $START, 'brctl addif br0 '.$winf2.'\n');
fwrite("a", $START,'brctl setbwctrl br0 '.$winf1.' 900\n');
fwrite("a", $START,'brctl setbwctrl br0 '.$winf2.' 900\n');
fwrite("a", $START,'ifconfig '.$winf1.' txqueuelen 250\n');
fwrite("a", $START,'ifconfig '.$winf2.' txqueuelen 250\n');
fwrite("a",$STOP,  'brctl delif br0 '.$winf1.'\n');
fwrite("a",$STOP, 'brctl delif br0 '.$winf2.'\n');
fwrite("a",$STOP,  "killall hostapd\n");
fwrite("a",$START, "exit 0\n");
fwrite("a",$STOP,  "exit 0\n");

?>
