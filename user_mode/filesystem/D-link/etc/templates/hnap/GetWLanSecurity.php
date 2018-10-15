HTTP/1.1 200 OK  
Content-Type: text/xml; charset=utf-8

<?  
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
include "/htdocs/phplib/xnode.php";
$path_phyinf_wlan1 = XNODE_getpathbytarget("", "phyinf", "uid", "WLAN-1", 0);
$path_wifi_wifi1 = XNODE_getpathbytarget("wifi", "entry", "uid", "WIFI-1", 0);
$auth=query($path_wifi_wifi1."/authtype");
$encrypt=query($path_wifi_wifi1."/encrtype");
$key="";
if($encrypt!="NONE")
{
	$enabled="true";
	if($auth != "OPEN" && $auth != "SHARED")
	{
		$auth="WPA";
		$key=get("x",$path_wifi_wifi1."/nwkey/eap/secret");    
	}
	else
	{
		$auth="WEP";
		$id=query($path_wifi_wifi1."/nwkey/wep/defkey");     
		$key=get("x",$path_wifi_wifi1."/nwkey/wep/key:".$id);
	}
}
else
{
	$enabled="false";
	$auth="WEP";
}
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetWLanSecurityResponse xmlns="http://purenetworks.com/HNAP1/">
      <GetWLanSecurityResult>OK</GetWLanSecurityResult>
      <Enabled><?=$enabled?></Enabled>
      <Type><?=$auth?></Type>
      <WEPKeyBits><?echo map($path_wifi_wifi1."/nwkey/wep/size", "", "64");?></WEPKeyBits>
      <SupportedWEPKeyBits>
        <int>64</int>
        <int>128</int>
      </SupportedWEPKeyBits>
      <Key><?=$key?></Key>
      <RadiusIP1><?echo query($path_wifi_wifi1."/nwkey/eap/radius");?></RadiusIP1>
      <RadiusPort1><?echo map($path_wifi_wifi1."/nwkey/eap/port", "", "0");?></RadiusPort1>
      <RadiusIP2><?echo query($path_wifi_wifi1."/nwkey/eap/radius");?></RadiusIP2>
      <RadiusPort2><?echo map($path_wifi_wifi1."/nwkey/eap/port", "", "0");?></RadiusPort2>
    </GetWLanSecurityResponse>
  </soap:Body>
</soap:Envelope>
