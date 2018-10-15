<?
include "/htdocs/phplib/phyinf.php";
include "/htdocs/phplib/xnode.php";

function get_igd_uuid()
{
	$igd = XNODE_getpathbytarget("/runtime/upnp", "dev", "deviceType",
			"urn:schemas-upnp-org:device:InternetGatewayDevice:1", 0);

	if ($igd != "") return query($igd."/guid");
	return "";
}

$conf = "/var/lld2d.conf";
$laninf = PHYINF_getruntimeifname("LAN-1");
$wlaninf = PHYINF_getifname("WLAN-1");
if ($laninf=="") PHYINF_getruntimeifname("BRIDGE-1");
if ($laninf=="") fwrite(a,$START,"exit 9\n");
else
{

	$icon = "/etc/config/lld2d.ico";

	fwrite(w, $conf,
		"helper = /etc/scripts/libs/lld2d-helper.php\n".
		"icon = ".$icon."\n".
		"jumbo-icon = ".$icon."\n".
		"uuid = ".get_igd_uuid()."\n".
		"net_flags = 0x70000000\n".
		"qos_flags = 0\n".
		"wl_physical_medium = 2\n".
		"max_op_rate = 108\n".
		"link_speed = 540000\n".
		"bridge_behavior = 1\n".
		"switch_speed = 1000000\n"
		);

	foreach ("/runtime/phyinf")
		if (query("valid")=="1" && query("type")=="wifi" && query("media/parent")=="")
			fwrite(a,$conf, "wifi_radio = 108,0x2,0x1,".query("macaddr")."\n");

	fwrite(w,$START,"#!/bin/sh\nlld2d -c ".$conf." ".$laninf);
	if ($wlaninf!="") fwrite(a,$START," ".$wlaninf);
	fwrite(a,$START," & > /dev/console\nexit 0\n");
}

fwrite(w,$STOP,"#!/bin/sh\nkillall lld2d\nexit 0\n");
?>
