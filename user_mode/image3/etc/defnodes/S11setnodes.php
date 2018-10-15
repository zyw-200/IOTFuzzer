<? /* vi: set sw=4 ts=4: */
/* Get values */
$imgsig	= fread("/etc/config/image_sign");
$bver	= fread("/etc/config/buildver");
$bverrev = fread("/etc/config/buildrev");
$bnum	= fread("/etc/config/buildno");
$bdate	= fread("/etc/config/builddate");
$lanmac	= query("/runtime/nvram/lanmac");
$wanmac	= query("/runtime/nvram/wanmac");
$hwrev	= query("/runtime/nvram/hwrev");
$pin	= query("/runtime/nvram/pin");
$ccode	= query("/runtime/nvram/countrycode");
$wlanmac_a=query("/runtime/nvram/wlanmac_a");

/* Validate */
if ($lanmac == "") { $lanmac="00:15:e9:2c:75:00"; }
if ($wanmac == "") { $wanmac="00:15:e9:2c:75:00"; }
if ($wlanmac_a == "") { $wlanmac_a="00:15:e9:2c:75:08"; }
if ($hwrev  == "")	{ $hwrev="N/A"; }
if ($ccode  == "") 	{$ccode = "840";}
/* Set */
/* layout */
set("/runtime/layout/countrycode",	$ccode);
set("/runtime/wireless/setting/status",	"0");
set("/runtime/aparray/version", "2.01");

set("/runtime/layout/image_sign", "");
anchor("/runtime/layout");
	set("image_sign",		$imgsig);
	set("wanmac",		$wanmac);
	set("lanmac",		$lanmac);
	set("wlanmac",		$lanmac);
	set("wlanmac_a",	$wlanmac_a);
	set("wanif",		"eth0");
	set("lanif",		"br0");
	set("wlanif",		"ath0");
	set("wlanif_g",		"ath0");// joe, add for dual-band concurrent AP, 20090112
	set("wlanif_a",		"ath16");// joe, add for dual-band concurrent AP, 20090112
/* sys info */
set("/runtime/sys/info/dummy", "");
anchor("/runtime/sys/info");
	set("hardwareversion",	"rev ".$hwrev);
	set("firmwareversion",	$bver);
	set("firmwarebuildno",	$bnum);
	set("firmwarebuildate",	$bdate);
	set("firmwareverreve",	$bverrev);
/* WPS pin */
set("/runtime/wps/pin",		$pin);
/*  others */
set("/sys/sessiontimeout",	"180");
set("/proc/web/sessionum",		"16");
set("/proc/web/authnum",		"14");

?>
