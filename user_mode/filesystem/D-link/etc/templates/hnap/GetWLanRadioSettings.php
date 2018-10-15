HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<? 
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
include "/htdocs/phplib/xnode.php";
if( get("s","/runtime/hnap/GetWLanRadioSettings/RadioID") == "2.4GHZ")
{	$path_phyinf_wlan = XNODE_getpathbytarget("", "phyinf", "uid", "WLAN-1", 0);	}
if( get("s","/runtime/hnap/GetWLanRadioSettings/RadioID") == "5GHZ")
{	$path_phyinf_wlan = XNODE_getpathbytarget("", "phyinf", "uid", "WLAN-2", 0);	} 
$path_wlan_wifi = XNODE_getpathbytarget("/wifi", "entry", "uid", query($path_phyinf_wlan."/wifi"), 0);

if( get("s","/runtime/hnap/GetWLanRadioSettings/RadioID") != "2.4GHZ" && get("s","/runtime/hnap/GetWLanRadioSettings/RadioID") != "5GHZ" )  
{ $result = "ERROR_BAD_RADIO"; } 
else
{
	$result = "OK";
	$channel=query($path_phyinf_wlan."/media/channel");
	if(query($path_phyinf_wlan."/active")=="1" && query($path_phyinf_wlan."/media/channel")=="0")
	{
		//update channel value when autochannel setup for HNAP Spec.
		//$channel=query("/runtime/stats/wireless/channel");
		$channel="0";
	}
	$wlanMode = query($path_phyinf_wlan."/media/wlmode");
	if( $wlanMode == "b" )		{ $wlanStr = "802.11b" ;}
	else if( $wlanMode == "g" )	{ $wlanStr = "802.11g"; }
	else if( $wlanMode == "bg" )	{ $wlanStr = "802.11bg"; }
	else if( $wlanMode == "n" )	{ $wlanStr = "802.11n"; }
	else if( $wlanMode == "bn" )	{ $wlanStr = "802.11bn"; }
	else if( $wlanMode == "gn" )	{ $wlanStr = "802.11gn"; }
	else if( $wlanMode == "bgn" )	{ $wlanStr = "802.11bgn"; }
	else if( $wlanMode == "a" )	{ $wlanStr = "802.11a"; }
	else if( $wlanMode == "an" )	{ $wlanStr = "802.11an"; }
	else						{ $result = "ERROR"; }
	$width = query($path_phyinf_wlan."/media/dot11n/bandwidth");
	if( $width == "20" )
	{ $bandWidth = "20"; }
	else if( $width == "40" )
	{ $bandWidth = "40"; }
	else
	{ $bandWidth = "0"; }
	$secondaryChnl = query("/wireless/SecondaryChannel"); 
	if($secondaryChnl == "")
	{
		$support11n = "1";
		if($support11n == "1")
		{
			$ccode = query("/runtime/devdata/countrycode");
			if( $ccode == "" )
			{
				$ccode = query("/runtime/devdata/countrycode");
			}		
			if( $ccode == "840" ) 
				{ $chnl_num = 11; }
			else if( $ccode == "826" || $ccode == "152" || $ccode == "392" )
				{ $chnl_num = 13; }
			else
				{ $chnl_num = 13; }   
			if( $bandWidth == 40 && $channel <= 4 )
			{	 
				$secondaryChnl = $channel + 4; 
			}
			else if( $channel > 4 && $channel < 8 && $bandWidth == 40 )
			{ 
				$secondaryChnl = $channel - 4; 
			}      
			else if( $channel >= 8 && $bandWidth == 40)
			{ 
				if( $chnl_num - $channel < 4 )
				{ $secondaryChnl = $channel - 4; }
				else
				{ $secondaryChnl = $channel - 4;}
			}
			else
			{
				$secondaryChnl = $channel;
			}	
		} 
		else if($width == "")
		{
			$secondaryChnl = 0;
		}
	
	}
}
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetWLanRadioSettingsResponse xmlns="http://purenetworks.com/HNAP1/">
      <GetWLanRadioSettingsResult><?=$result?></GetWLanRadioSettingsResult>
      <Mode><?=$wlanStr?></Mode>
	  <Enabled><? echo map($path_phyinf_wlan."/active", "1", "true", "*", "false");?></Enabled>
      <MacAddress><? echo query("/runtime/devdata/lanmac");?></MacAddress>
      <SSID><? echo query($path_wlan_wifi."/ssid");?></SSID>
      <SSIDBroadcast><? echo map($path_wlan_wifi."/ssidhidden", "1", "false", "*", "true");?></SSIDBroadcast>
      <ChannelWidth><?=$bandWidth?></ChannelWidth>
	  <Channel><?=$channel?></Channel>
	  <SecondaryChannel><?=$secondaryChnl?></SecondaryChannel>
	  <QoS><?echo map($path_phyinf_wlan."/media/wmm/enable","0","false","*","true");?></QoS>
    </GetWLanRadioSettingsResponse>
  </soap:Body>
</soap:Envelope>
