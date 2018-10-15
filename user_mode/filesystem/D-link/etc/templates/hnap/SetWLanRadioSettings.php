HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
include "/htdocs/phplib/xnode.php";
$nodebase="/runtime/hnap/SetWLanRadioSettings/";
if( query($nodebase."RadioID") == "2.4GHZ")
{	$path_phyinf_wlan = XNODE_getpathbytarget("", "phyinf", "uid", "WLAN-1", 0);	}
if( query($nodebase."RadioID") == "5GHZ")
{	$path_phyinf_wlan = XNODE_getpathbytarget("", "phyinf", "uid", "WLAN-2", 0);	}
$path_wlan_wifi = XNODE_getpathbytarget("/wifi", "entry", "uid", query($path_phyinf_wlan."/wifi"), 0);

$result = "REBOOT";
if( query($nodebase."RadioID") != "2.4GHZ" && query($nodebase."RadioID") != "5GHZ" )
{
	$result = "ERROR_BAD_RADIOID";
}
else
{
	$mode = query($nodebase."Mode");
	$ssid = query($nodebase."SSID");
	if( query($nodebase."Enabled") == "true" )
	{ $wlanEn = "1"; }
	else
	{ $wlanEn = "0"; }
	if( $mode == "802.11b" )
	{ $wlanMode = "b"; }
	else if( $mode == "802.11g" )
	{ $wlanMode = "g"; }
	else if( $mode == "802.11n" )
	{ $wlanMode = "n"; }
	else if( $mode == "802.11bg" )
	{ $wlanMode = "bg"; }
	else if( $mode == "802.11bn" )
	{ $wlanMode = "bn"; }
	else if( $mode == "802.11gn" )
	{ $wlanMode = "gn"; }
	else if( $mode == "802.11bgn" )
	{ $wlanMode = "bgn"; }
	else if( $mode == "802.11a" )
	{ $wlanMode = "a"; }
	else if( $mode == "802.11an" )
	{ $wlanMode = "an"; }
	else
	{ 
		if( $wlanEn == "1" ) { $result = "ERROR_BAD_MODE"; }
	}
	if( $wlanEn == "1" && $ssid == "" )
	{ $result = "ERROR"; }
	if( query($nodebase."SSIDBroadcast") == "false" )
	{ $ssidHidden = "1"; }
	else
	{ $ssidHidden = "0"; }
	$width = query($nodebase."ChannelWidth");
	if( $width == "20" )
	{ $bandWidth = "20"; }
	else if( $width == "40" )
	{ $bandWidth = "40"; }
	else if( $width == "0")
	{ $bandWidth = "20+40"; }
	$channel = query($nodebase."Channel");
	$countryCode = query("/runtime/devdata/countrycode");
	$secondaryChnl = query($nodebase."SecondaryChannel");
	$model = query("/runtime/device/modelname");
	if( $width == "" ) 
	{ 
		if( $secondaryChnl!="0" )
		{ $result = "ERROR_BAD_SECONDARY_CHANNEL"; }
	}
	if(query($nodebase."QoS") == "false" )
	{ $qos = "0"; }
	else
	{ $qos = "1"; }
	if( $result == "REBOOT" )
	{
	  set($path_phyinf_wlan."/active",$wlanEn);
	  if( $wlanEn == "1" )
	  {
		$old_ssid = query($path_wlan_wifi."/ssid");
		if($old_ssid != $ssid) { set($path_wlan_wifi."/wps/configured", "1"); }
		set($path_wlan_wifi."/ssid",$ssid);
		set($path_phyinf_wlan."/media/wlmode",$wlanMode);
		set($path_wlan_wifi."/ssidhidden",$ssidHidden);
		if( $bandWidth == "20" || $bandWidth == "40" || $bandWidth == "20+40") { set($path_phyinf_wlan."/media/dot11n/bandwidth",$bandWidth); }
		if( $channel == "0" )
		{ set($path_phyinf_wlan."/media/channel","0"); }
		else
		{
			
			set($path_phyinf_wlan."/media/channel",$channel);
		}
		set("/wireless/SecondaryChannel",$secondaryChnl);
		set($path_phyinf_wlan."/media/wmm/enable", $qos);
	  }
	}
}

fwrite("w",$ShellPath, "#!/bin/sh\n");
fwrite("a",$ShellPath, "echo \"[$0]-->WLan Change\" > /dev/console\n");
if( $result == "REBOOT" )
{
	fwrite("a",$ShellPath, "/etc/scripts/dbsave.sh > /dev/console\n");
	fwrite("a",$ShellPath, "service WIFI.WLAN-1 restart > /dev/console\n");
	fwrite("a",$ShellPath, "xmldbc -s /runtime/hnap/dev_status '' > /dev/console\n");
	set("/runtime/hnap/dev_status", "ERROR");
}
else
{
	fwrite("a",$ShellPath, "echo \"We got a error, so we do nothing...\" > /dev/console");
}
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <SetWLanRadioSettingsResponse xmlns="http://purenetworks.com/HNAP1/">
      <SetWLanRadioSettingsResult><?=$result?></SetWLanRadioSettingsResult>
    </SetWLanRadioSettingsResponse>
  </soap:Body>
</soap:Envelope>
