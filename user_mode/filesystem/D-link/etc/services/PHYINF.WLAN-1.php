<?
include "/etc/services/WIFI/wifi.php";
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/phyinf.php";
include "/htdocs/phplib/xnode.php";

fwrite("w",$START, "#!/bin/sh\n");
fwrite("w", $STOP, "#!/bin/sh\n");

$PHY_UID = "WLAN-1";

/* Get the phyinf */
$phy1	= XNODE_getpathbytarget("", "phyinf", "uid", $PHY_UID, 0);	if ($phy1 == "")	return;
$phyrp1	= XNODE_getpathbytarget("/runtime", "phyinf", "uid", $PHY_UID, 0);	if ($phyrp1 == "")	return;
$wifi1	= XNODE_getpathbytarget("/wifi", "entry", "uid", query($phy1."/wifi"), 0);

/* Is the phyinf active? */
$active1 = query($phy1."/active");

if ($active1!=1 ) {wifi_error("8"); return; }
else {wifi_service($PHY_UID); }

/* restart HOSTAPD */ 
fwrite("a",$START, "service HOSTAPD restart\n");
fwrite("a",$STOP,  "service HOSTAPD restart\n");

/* restart IGMP proxy, WIFI enhancement */
/* we need to wait for wireless interface up, and now wireless interface is brought up by HOSTAPD */
fwrite("a",$START, "sleep 3\n");
fwrite("a",$START, "service MULTICAST restart\n");
fwrite("a",$STOP,  "service MULTICAST restart\n");


fwrite("a",$START, "exit 0\n");
fwrite("a",$STOP,  "exit 0\n");

?>
