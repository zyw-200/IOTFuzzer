<? include "/htdocs/phplib/xnode.php"; ?>
HTTP/1.1 200 OK  Content-Type: text/xml; charset=utf-8
<? 
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
$path_phyinf_wlan1 = XNODE_getpathbytarget("", "phyinf", "uid", "WLAN-1", 0);
$path_wlan1_wifi = XNODE_getpathbytarget("/wifi", "entry", "uid", $path_phyinf_wlan1."/wifi", 0);
$channel=query($path_phyinf_wlan1."/media/channel");
if(query($path_phyinf_wlan1."/active")=="1" && query($path_phyinf_wlan1."/media/channel")=="0")
{
		//update channel value when autochannel setup for HNAP Spec.
		//$channel=query("/runtime/stats/wireless/channel");
		$channel="0";
}
?>

<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetWLanSettings24Response xmlns="http://purenetworks.com/HNAP1/">
      <GetWLanSettings24Result>OK</GetWLanSettings24Result>
      <Enabled><?echo map($path_phyinf_wlan1."/active", "1", "true", "*", "false");?></Enabled>
      <MacAddress><?echo query("/runtime/devdata/lanmac");?></MacAddress>
      <SSID><?echo query($path_wlan1_wifi."/ssid");?></SSID>
      <SSIDBroadcast><?echo map($path_wlan1_wifi."/ssidHidden", "1", "false", "*", "true");?></SSIDBroadcast>
      <Channel><?=$channel?></Channel>
    </GetWLanSettings24Response>
  </soap:Body>
</soap:Envelope>
