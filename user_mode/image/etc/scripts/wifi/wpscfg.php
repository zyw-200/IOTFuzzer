
<?
include "/htdocs/phplib/xnode.php";

$PHY_UID = "WLAN-1";
$PHY_UID2 = "WLAN-2";

$phy	= XNODE_getpathbytarget("", "phyinf", "uid", $PHY_UID, 0);
$phy2	= XNODE_getpathbytarget("", "phyinf", "uid", $PHY_UID2, 0);
$phyrp	= XNODE_getpathbytarget("/runtime", "phyinf", "uid", $PHY_UID, 0);
$phyrp2	= XNODE_getpathbytarget("/runtime", "phyinf", "uid", $PHY_UID2, 0);
$wifi	= XNODE_getpathbytarget("/wifi", "entry", "uid", query($phy."/wifi"), 0);
$wifi2	= XNODE_getpathbytarget("/wifi", "entry", "uid", query($phy2."/wifi"), 0);
$br		= XNODE_getpathbytarget("", "inf", "uid", query($phyrp."/brinf"),0);
$br2		= XNODE_getpathbytarget("", "inf", "uid", query($phyrp2."/brinf"),0);
$brrp	= XNODE_getpathbytarget("/runtime", "phyinf", "uid", query($br."/phyinf"), 0);
$brrp2	= XNODE_getpathbytarget("/runtime", "phyinf", "uid", query($br2."/phyinf"), 0);

$winf1	= query($phyrp."/name");
$winf2	= query($phyrp2."/name");

$PINcode = query($phyrp."/media/wps/enrollee/pin");

//echo "wpatalk ".$winf1." configthem &\n"; 
//marco, only start wps if wps enable && one of the wifi interfaces is up
$phy_active=query($phy."/active");
$phy2_active=query($phy2."/active");

$wifi_wps_anable=query($wifi."/wps/enable");
$wifi2_wps_anable=query($wifi2."/wps/enable");

$intf_active="0";
$wps_enable="0";
if($wifi_wps_anable=="1" && $wifi2_wps_anable=="1")
{
	$wps_enable="1";
}
if($phy_active=="1" || $phy2_active=="1")
{
	$intf_active="1";
}


if( $wps_enable=="1"&&$intf_active=="1" )
{
	echo "event WPS_IN_PROGRESS\n";
	if($ACTION == "PBC")
	{	
		echo "wpatalk ".$winf1." configthem &\n"; 
		echo "wpatalk ".$winf2." configthem &\n"; 
	}
	else if($ACTION == "PIN")
	{
		echo "wpatalk ".$winf1." \"configthem pin=".$PINcode."\" &\n"; 
		echo "wpatalk ".$winf2." \"configthem pin=".$PINcode."\" &\n"; 
	}
}

?>
