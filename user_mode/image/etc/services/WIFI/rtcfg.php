<? /* vi: set sw=4 ts=4: */
/********************************************************************************
 *	NOTE: 
 *		The commands in this configuration generator is based on 
 *		Ralink RT2860 Linux SoftAP Drv1.9.0.0 Release Note and User's Guide.	 
 *		Package Name : rt2860v2_SDK3100_v1900.tar.bz2
 *******************************************************************************/
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/phyinf.php";

/***************************** functions ************************************/
function isblocked($channel, $freq)
{
	//if not A band, return 0
	if($freq != "5") { return 0; }
	
	//1. read from the file 
	$blockch_list = fread("", "/var/dfs_blocked.txt");
	//format is : "100,960;122,156;" --> channel 100, remaining time is 960 seconds
	//								 --> channel 122, remaining time is 156 seconds
	$ttl_block_chn = cut_count($blockch_list, ";")-1;
	$i = 0;
	while($i < $ttl_block_chn)
	{
		//assume that blocked channel can be more than one channel.
		$ch_field = cut($blockch_list, $i, ';');	//i mean each "100,960;" represent 1 field 
		$ch = cut ($ch_field, 0, ',');
		
		if($channel == $ch) 
			return 1; 
		$i++;
	}
	return 0;
}

function wmm_paramters($band)
{
	/* AP */
	echo "APAifsn=3;7;1;1"	."\n";
	echo "APCwmin=4;4;3;2"	."\n"; //if ($auth_logo=="1")  echo "APCwmin=3;3;3;2"."\n";
	echo "APCwmax=6;10;4;3"	."\n";
	if		($band == "b")	echo "APTxop=0;0;188;102"."\n";
	else if ($band == "g" || $band == "a")	echo "APTxop=0;0;94;47"."\n";
	echo "APACM=0;0;0;0"	."\n";

	/* STA */
	echo "BSSAifsn=3;7;2;2"		."\n";
	echo "BSSCwmin=4;4;3;2"		."\n";
	echo "BSSCwmax=10;10;4;3"	."\n";
	if		($band == "b")	echo "BSSTxop=0;0;188;102"	."\n";
	else if ($band == "g" || $band == "a")	echo "BSSTxop=0;0;94;47"	."\n";
	echo "BSSACM=0;0;0;0"		."\n";

	echo "AckPolicy=0;0;0;0"	."\n";
}

function mbssid_paramters($phy_uid)
{
	$hidessid = "";
	$authtype = "";
	$encrtype = "";
	$defkeyid = "";
	$wepkeytp = "";
	$wepkeys = "";
	$wep = 0;
	$idx = 0; /* $idx: the index of mbssid */
	foreach ("/phyinf")
	{
		if (query("uid")==$phy_uid || query("media/parent")==$phy_uid)
		{
			$idx++;
			if ($idx == 1)	$delimiter = "";
			else			$delimiter = ";";
			$wifi = XNODE_getpathbytarget("/wifi",	"entry",  "uid", query("wifi"));

			/* -------- RT2860AP.dat -------*/
			/* SSID may contains ';' */
			echo "SSID".$idx."=".query($wifi."/ssid")."\n";
			/* -----------------------------*/

			/* ssidhidden */
			if(query($wifi."/ssidhidden")==1)	$hidessid = $hidessid.$delimiter."1";
			else								$hidessid = $hidessid.$delimiter."0";
			/* authtype */
			$auth = query($wifi."/authtype");
			if		($auth == "OPEN")			$authtype = $authtype.$delimiter."OPEN";
			else if ($auth == "SHARED")			$authtype = $authtype.$delimiter."SHARED";
			else if ($auth == "WPA")			$authtype = $authtype.$delimiter."WPA";
			else if ($auth == "WPAPSK")			$authtype = $authtype.$delimiter."WPAPSK";
			else if ($auth == "WPA2")			$authtype = $authtype.$delimiter."WPA2";
			else if ($auth == "WPA2PSK")		$authtype = $authtype.$delimiter."WPA2PSK";
			else if ($auth == "WPA+2")			$authtype = $authtype.$delimiter."WPA1WPA2";
			else if ($auth == "WPA+2PSK")		$authtype = $authtype.$delimiter."WPAPSKWPA2PSK";
			/* encrtype */
			$encrypt = query($wifi."/encrtype");
			if		($encrypt == "NONE")		$encrtype = $encrtype.$delimiter."NONE";
			else if ($encrypt == "WEP")			$encrtype = $encrtype.$delimiter."WEP";
			else if ($encrypt == "TKIP")		$encrtype = $encrtype.$delimiter."TKIP";
			else if ($encrypt == "AES")			$encrtype = $encrtype.$delimiter."AES";
			else if ($encrypt == "TKIP+AES")	$encrtype = $encrtype.$delimiter."TKIPAES";

			if ($encrypt == "WEP")
			{
				$wep++;
				$def = query($wifi."/nwkey/wep/defkey");
				$defkeyid = $defkeyid.$delimiter.$def;
				$wepkeytp = $wepkeytp.$delimiter.query($wifi."/nwkey/wep/ascii");
				/* -------- RT2860AP.dat -------*/
				/* WEP Key may contains ';' */
				echo "Key".$def."Str".$idx."=".query($wifi."/nwkey/wep/key:".$def)."\n";
				/* -----------------------------*/
			}
			else
			{
				$defkeyid = $defkeyid.$delimiter."2";
				$wepkeytp = $wepkeytp.$delimiter."0";
			}

			/* Access Control List, rt2860v2 max acl entry is 64. */
			$acl_count	= query($wifi."/acl/count");
			$acl_max	= query($wifi."/acl/max");
			$policy		= query($wifi."/acl/policy");
			$acl_idx = $idx - 1;
			if		($policy == "ACCEPT")	echo "AccessPolicy".$acl_idx."=1\n";
			else if ($policy == "DROP")		echo "AccessPolicy".$acl_idx."=2\n";
			else							echo "AccessPolicy".$acl_idx."=0\n";
			foreach ($wifi."/acl/entry")
			{
				if ($InDeX > $acl_count || $InDeX > $acl_max) break;
				if ($acl_list!="")	$acl_list = $acl_list.";".query("mac");
				else				$acl_list = query("mac");
			}
			if ($acl_list!="")	echo "AccessControlList".$acl_idx."=".$acl_list."\n";
		}
	}
	echo "HideSSID="	.$hidessid	."\n";
	echo "AuthMode="	.$authtype	."\n";
	echo "EncrypType="	.$encrtype	."\n";
	if ($wep > 0)
	{
		echo "DefaultKeyID=".$defkeyid	."\n";
		echo "Key1Type="	.$wepkeytp	."\n";
		echo "Key2Type="	.$wepkeytp	."\n";
		echo "Key3Type="	.$wepkeytp	."\n";
		echo "Key4Type="	.$wepkeytp	."\n";
	}
}
/**********************************************************************************/
/* prepare the needed path */
if ($PHY_UID == "") $PHY_UID="WLAN-1";
$phy	= XNODE_getpathbytarget("",			"phyinf", "uid", $PHY_UID);
$phyrp	= XNODE_getpathbytarget("/runtime",	"phyinf", "uid", $PHY_UID);
$wifi	= XNODE_getpathbytarget("/wifi",	"entry",  "uid", query($phy."/wifi"));

/* ----------------------------- get configuration -----------------------------------*/
/* country code */
$ccode = query("/runtime/devdata/countrycode");
if (isdigit($ccode)==1)
{
	TRACE_debug("WIFI.WLAN-1 service [rtcfg.php (ralink conf)]:".
				"Your country code (".$ccode.") is in number format. ".
				"Please change the country code as ISO name. ".
				"Use 'US' as country code.");
	$ccode = "US";
}
if ($ccode == "")
{
	TRACE_error("WIFI.WLAN-1 service: no country code! ".
				"Please check the initial value of this board! ".
				"Use 'US' as country code.");
	$ccode = "US";
}

/* we know that GB = EU, but driver doesn't recognize EU. */
if ($ccode == "EU")
{
	TRACE_error("Country code is set to EU. Change it to GB so that driver can recognize it\n");
	$ccode = "GB";
}

$RDRegion = "FCC";

if		($ccode == "JP") {$a_region = 9;	$c_region = 1; $RDRegion = "JAP";}
else if	($ccode == "GB") {$a_region = 1;	$c_region = 1; $RDRegion = "CE";}
else if ($ccode == "KR") {$a_region = 5;	$c_region = 1; }
/* use 'US' as default value of $ccode. */
else					 {$a_region = 0;	$c_region = 0;}

/* wireless mode */
/* ralink setting: 0: b+g, 1: b, 2:a, 4:g. 6:n, 7:g+n, 8:a+n, 9:b+g+n, 10: a+g+n, 11:n in 5G band only.*/
$wlmode = query($phy."/media/wlmode");
$freq = query($phy."/media/freq");
$wlanmac = "";
if ($wlmode == "a" || $wlmode == "an" || $freq == "5" )	/* A band */
{
	$c_region = "";
	$bsc_rate = "";
	$wmm_param = "a";
	$dfs = "1";
	/*5G using wlanmac*/
	$wlanmac = query("/runtime/devdata/wlanmac");
}
else
{
	$a_region="";
	/*2.4G mac using the wan mac for samsung tv issue*/
	$wlanmac = query("/runtime/devdata/wlanmac2");
}

if		($wlmode == "a")	{$wlmode = "2";	$en11n = 0;}
else if	($wlmode == "an")	{$wlmode = "8";	$en11n = 1;}
else if($wlmode == "bgn")	{$wlmode = "9"; $bsc_rate = "15";	$wmm_param = "g"; $en11n = 1;}
else if	($wlmode == "gn")	{$wlmode = "7"; $bsc_rate = "351";	$wmm_param = "g"; $en11n = 1;}
else if ($wlmode == "bg")	{$wlmode = "0"; $bsc_rate = "15";	$wmm_param = "g"; $en11n = 0;}
else if ($wlmode == "n")	
{
	if($freq == "5") {	$wlmode = "11"; } else { $wlmode = "6"; }	
	$bsc_rate = "15";	$wmm_param = "g"; $en11n = 1;
}
else if ($wlmode == "g")	{$wlmode = "4"; $bsc_rate = "351";	$wmm_param = "g"; $en11n = 0;}
else if ($wlmode == "b")	{$wlmode = "1"; $bsc_rate = "3";	$wmm_param = "b"; $en11n = 0;}
else
{
	/* use 'bgn' as default.*/
	TRACE_info("rtcfg (ralink conf): Not supported wireless mode: [".$wlmode."].Use 'bng' as default wireless mode.");
	$wlmode = "9";	$bsc_rate = "15"; $wmm_param = "g";
}

$wmm = query($phy."/media/wmm/enable");
if ($wmm == 1)	{$txburst = 0;}
else			{$txburst = 1; $wmm = 0;}

/* -------- RT2860AP.dat -------*/
echo "Default"."\n";	/* The word of "Default" must not be removed. */

if(PHYINF_validmacaddr($wlanmac)=="1") echo "MacAddress=".$wlanmac."\n";

if ($c_region != "")	echo "CountryRegion="		.$c_region	."\n";
if ($a_region != "")	echo "CountryRegionABand="	.$a_region	."\n";
if ($bsc_rate != "")	echo "BasicRate="			.$bsc_rate	."\n";	
echo "CountryCode="			.$ccode		."\n";
echo "WirelessMode="		.$wlmode	."\n";
echo "WmmCapable="			.$wmm		."\n";
/* If wmm is enabled, wmm power saving should be enabled too. */
echo "APSDCapable="			.$wmm		."\n";
echo "TxBurst="				.$txburst	."\n";
/* enable DFS */
if ($dfs == "1")
{
	echo "IEEE80211H=1\n";
	echo "RDRegion=".$RDRegion."\n";
	echo "DfsIndoor=1\n";
	echo "DfsRssiHighFromCfg=-30\n";
}
/* Remember modify the TxStream and RxStream according the board supported. */
echo "HT_TxStream=2"					."\n";
echo "HT_RxStream=2"					."\n";
/*************************************************************************************
 * Ralink's recommendation:
 *		1. no matter wmm is diabled or enabled, the WMM parameters should be set.
 *		2. Both of parameter for STA and AP have to be set.
 *************************************************************************************/
wmm_paramters($wmm_param);

/* ----------------------------- get configuration -----------------------------------*/
$bssid = 1;
foreach ("/phyinf") {if (query("media/parent") == $PHY_UID) $bssid++;}
$channel = query($phy."/media/channel");
if ( $channel==0 || isblocked($channel, $freq)==1 )	
{
	$channel = 0;
	$auto_ch = 1;
}
else 
	$auto_ch = 0;

$beacon		= query($phy."/media/beacon");
$dtim		= query($phy."/media/dtim");
$rtsthresh	= query($phy."/media/rtsthresh");
$fragthresh	= query($phy."/media/fragthresh");
$txpower	= query($phy."/media/txpower");
$preamble	= query($phy."/media/preamble");
if		($txpower == "50")	$txpower = 45;
else if	($txpower == "25")	$txpower = 25;
else if	($txpower == "12.5")$txpower = 10;
else if	($txpower == "6.25")$txpower = 5;
else						$txpower = 95;
/* -------- RT2860AP.dat -------*/
echo "BssidNum="			.$bssid		."\n";
mbssid_paramters($PHY_UID);
echo "AutoChannelSelect="	.$auto_ch	."\n";
if ($channel != 0)			echo "Channel=".$channel."\n";
echo "BeaconPeriod="		.$beacon	."\n";
echo "DtimPeriod="			.$dtim		."\n";
echo "RTSThreshold="		.$rtsthresh	."\n";
echo "FragThreshold="		.$fragthresh."\n";
echo "TxPower="				.$txpower	."\n";
/**********************************************************************************************
 * for passing the WiFi test in secter 4.2.3.2.2. 
 * The default value of preamble in Ralink driver is auto (long preamble).
 * When the preamble is long preamble, the result of 4.2.3.2 MA8 is 
 * 3.543/3.430 (Broadcom-G, Intel-B(RTS-256), but the throughput must be more than 4.140/2.253.
 * Ralink said to set the preamble as short to pass this item.
 * (set the TxPreabmle=1.)
 **********************************************************************************************/
//echo "TxPreamble=1\n";
//hendry, Remember to set it back to 1 if we want to do wifi test. 
if($preamble == "long")	{ echo "TxPreamble=0\n"; } else { echo "TxPreamble=1\n"; }

echo "WiFiTest=1\n";
echo "ShortSlot=1\n";
echo "CSPeriod=6\n";
echo "PktAggregate=1\n";

/* ----------------------------- get configuration -----------------------------------*/
if ($en11n == 0)
{
	$txrate = query($phy."/media/txrate");
	if		($txrate == "1"		||  $txrate == "6")		$mcs_idx = 0;
	else if ($txrate == "2"		||  $txrate == "9")		$mcs_idx = 1;
	else if ($txrate == "5.5"	||  $txrate == "12")	$mcs_idx = 2;
	else if ($txrate == "11"	||  $txrate == "18")	$mcs_idx = 3;
	else if (						$txrate == "24")	$mcs_idx = 4;
	else if (						$txrate == "36")	$mcs_idx = 5;
	else if (						$txrate == "48")	$mcs_idx = 6;
	else if (						$txrate == "54")	$mcs_idx = 7;
	else												$mcs_idx = 33; /* set default as 'auto' */
}
else
{
	if (query($phy."/media/dot11n/mcs/auto")==1)	$mcs_idx = 33;
	else											$mcs_idx = query($phy."/media/dot11n/mcs/index");
}
if (query($phy."/media/dot11n/guardinterval") == "400")		$sgi = 1;
else														$sgi = 0;
if (query($phy."/media/dot11n/bandwidth") == "20")			$bw = 0;
else														$bw = 1;
/* -------- RT2860AP.dat -------*/
/* HT (11n) */
echo "HT_MCS="			.$mcs_idx	."\n";
echo "HT_HTC=1"			."\n";
echo "HT_RDG=1"			."\n";
echo "HT_LinkAdapt=0"	."\n";
echo "HT_OpMode=0"		."\n";
echo "HT_MpduDensity=5"	."\n";
echo "HT_AutoBA=1"		."\n";
echo "HT_AMSDU=0"		."\n";
echo "HT_BAWinSize=64"	."\n";
echo "HT_STBC=1"		."\n";
echo "HT_BADecline=0"	."\n";
echo "HT_PROTECT=1"		."\n";
echo "HT_GI="			.$sgi	."\n";
echo "HT_BW="			.$bw	."\n";

//we set as indoor
echo "ChannelGeography=1\n";

if ($bw == "1")
{
	/* 0: below the control channel, 1: above the control channel. */
	$extcha = 0;
	if ($channel <= 4) $extcha =1;
	echo "HT_EXTCHA="	.$extcha	."\n";
}
/* bridge */
if (query($wifi."/opmode")=="APNF") echo "NoForwarding=1\n";
else								echo "NoForwarding=0\n";
echo "NoForwardingBTNBSSID=1\n";

/* -------- RT2860AP.dat end -------*/
?>
