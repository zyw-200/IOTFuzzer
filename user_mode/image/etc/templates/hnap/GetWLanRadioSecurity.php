HTTP/1.1 200 OK 
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
include "/htdocs/phplib/xnode.php"; 
$radioID = get("h","/runtime/hnap/GetWLanRadioSecurity/RadioID");
if( $radioID == "2.4GHZ")
{	$path_phyinf_wlan = XNODE_getpathbytarget("", "phyinf", "uid", "WLAN-1", 0);	}
if( $radioID == "5GHZ")
{	$path_phyinf_wlan = XNODE_getpathbytarget("", "phyinf", "uid", "WLAN-2", 0);	}
$path_wlan_wifi = XNODE_getpathbytarget("/wifi", "entry", "uid", query($path_phyinf_wlan."/wifi"), 0);

$result = "OK";

if( $radioID != "2.4GHZ" && $radioID != "5GHZ")   
{
	$result = "ERROR_BAD_RADIOID";
	$enabled = "";
	$keyRenewal = "";
	$type = "";
	$encrypt = "";
	$key = "";
	$radiusIP1 = "";
	$radiusPort1 = "";
	$radiusSecret1 = "";
	$radiusIP2 = "";
	$radiusPort2 = "";
	$radiusSecret2 = "";
}
else
{
	$enable = get("x",$path_wlan_wifi."/encrtype");
	$authType	= get("x",$path_wlan_wifi."/authtype");
	if( $enable != "NONE" )
	{
		$enabled = "true";
		if( $enable == "WEP" )
		{
			if( $authType != "OPEN" && $authType != "SHARED" )
			{
				$result = "ERROR";
			}
			else
			{
				$defKey = get("x",$path_wlan_wifi."/nwkey/wep/defkey");
				$key = get("x",$path_wlan_wifi."/nwkey/wep/key:".$defKey);
				if( $authType == "OPEN" )
				{
					$type = "WEP-OPEN";
					$keyLen = get("x",$path_wlan_wifi."/nwkey/wep/size");
					$encrypt = "WEP-".$keyLen;
				}
				else if( $authType == "SHARED" )
				{
					$type = "WEP-SHARED";
					$keyLen = get("x",$path_wlan_wifi."/nwkey/wep/size");
					$encrypt = "WEP-".$keyLen;
				}
				else
				{
					$result = "ERROR";
				}
			}
		}
		else if( $authType == "WPA" || $authType == "WPA2" || $authType == "WPA+2")
		{
			if( $authType == "WPA" ) { $type = "WPA-RADIUS"; }
			if( $authType == "WPA2" ) { $type = "WPA2-RADIUS"; }
			if( $authType == "WPA+2" ) { $type = "WPAORWPA2-RADIUS"; } /* ALPHA add, not follow HNAP Spec 1.1 */
			if( $enable == "TKIP" ) {	$encrypt = "TKIP"; } 
			else if( $enable == "AES" ) { $encrypt = "AES"; }
			else if( $enable == "TKIP+AES" ) { $encrypt = "TKIPORAES"; }
			else { $result = "ERROR"; }
			$keyRenewal = get("x",$path_wlan_wifi."/nwkey/rekey/gtk");
			$radiusIP1 = get("x",$path_wlan_wifi."/nwkey/eap/radius");
			$radiusPort1 = get("x",$path_wlan_wifi."/nwkey/eap/port");
			$radiusSecret1 = get("x",$path_wlan_wifi."/nwkey/eap/secret");
			$radiusIP2 = get("x",$path_wlan_wifi."/nwkey/eap/radius2");
			$radiusPort2 = get("x",$path_wlan_wifi."/nwkey/eap/port2");
			$radiusSecret2 = get("x",$path_wlan_wifi."/nwkey/eap/secret2");
		}
		else if( $authType == "WPAPSK" || $authType == "WPA2PSK" || $authType == "WPA+2PSK")
		{
			if( $authType == "WPAPSK" ) { $type = "WPA-PSK"; }
			if( $authType == "WPA2PSK" ) { $type = "WPA2-PSK"; }
			if( $authType == "WPA+2PSK" ) { $type = "WPAORWPA2-PSK"; } /* ALPHA add, not follow HNAP Spec 1.1 */
			if( $enable == "TKIP" ) {	$encrypt = "TKIP"; } 
			else if( $enable == "AES" ) { $encrypt = "AES"; }
			else if( $enable == "TKIP+AES" ) { $encrypt = "TKIPORAES"; }
			else { $result = "ERROR"; }
			$keyRenewal = get("x",$path_wlan_wifi."/nwkey/rekey/gtk");
			$key = get("x",$path_wlan_wifi."/nwkey/psk/key");
		}
	}
	else
	{
		$enabled = "false";
		$keyRenewal = "0";
		$radiusPort1 = "0";
		$radiusPort2 = "0";
	}
	//fix for TestDevice
	if($keyRenewal == "") { $keyRenewal = "0"; }
	if($radiusPort1 == "") { $radiusPort1 = "0"; }
	if($radiusPort2 == "") { $radiusPort2 = "0"; }
}

?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <GetWLanRadioSecurityResponse xmlns="http://purenetworks.com/HNAP1/">
      <GetWLanRadioSecurityResult><?=$result?></GetWLanRadioSecurityResult>
      <Enabled><?=$enabled?></Enabled>
      <Type><?=$type?></Type>
      <Encryption><?=$encrypt?></Encryption>
      <KeyRenewal><?=$keyRenewal?></KeyRenewal>
      <Key><?=$key?></Key> 
      <RadiusIP1><?=$radiusIP1?></RadiusIP1>
      <RadiusPort1><?=$radiusPort1?></RadiusPort1>
	  <RadiusSecret1><?=$radiusSecret1?></RadiusSecret1>
      <RadiusIP2><?=$radiusIP2?></RadiusIP2>
      <RadiusPort2><?=$radiusPort2?></RadiusPort2>
	  <RadiusSecret2><?=$radiusSecret2?></RadiusSecret2>
    </GetWLanRadioSecurityResponse>
  </soap:Body>
</soap:Envelope>
