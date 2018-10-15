HTTP/1.1 200 OK
Content-Type: text/xml; charset=utf-8

<?
echo "\<\?xml version='1.0' encoding='utf-8'\?\>";
include "/htdocs/phplib/xnode.php";
$nodebase="/runtime/hnap/SetWLanRadioSecurity/";

if( query($nodebase."RadioID") == "2.4GHZ")
{	$path_phyinf_wlan = XNODE_getpathbytarget("", "phyinf", "uid", "WLAN-1", 0);	}
if( query($nodebase."RadioID") == "5GHZ")
{	$path_phyinf_wlan = XNODE_getpathbytarget("", "phyinf", "uid", "WLAN-2", 0);	}
$path_wlan_wifi = XNODE_getpathbytarget("/wifi", "entry", "uid", query($path_phyinf_wlan."/wifi"), 0);

anchor($path_wlan_wifi);
$result = "REBOOT";
if( query($nodebase."RadioID") != "2.4GHZ" && query($nodebase."RadioID") != "5GHZ" )
{
	$result = "ERROR_BAD_RADIOID";
}
else
{
	if(query($nodebase."Enabled") == "false" )
	{
		set("encrtype","NONE");
		set("authtype","OPEN");
	}
	else
	{
		$type = query($nodebase."Type");
		$encrypt = query($nodebase."Encryption");
		$key = query($nodebase."Key");
		$keyRenewal = query($nodebase."KeyRenewal");
		$radiusIP1 = query($nodebase."RadiusIP1");
		$radiusPort1 = query($nodebase."RadiusPort1");
		$radiusSecret1 = query($nodebase."RadiusSecret1");
		$radiusIP2 = query($nodebase."RadiusIP2");
		$radiusPort2 = query($nodebase."RadiusPort2");
		$radiusSecret2 = query($nodebase."RadiusSecret2");
		if( $type == "WEP-OPEN" || $type == "WEP-SHARED" )
		{
			if( $encrypt == "WEP-64" )
			{
				$wepLen = 64;
			}
			else if( $encrypt == "WEP-128" )
			{
				$wepLen = 128;
			}
			else
			{
				$result = "ERROR_ENCRYPTION_NOT_SUPPORTED";
			}
			if( $type == "WEP-OPEN" )
			{
				$auth = "OPEN";
			}
			else
			{
				$auth = "SHARED";
			}
			if( $key == "" )
			{ $result = "ERROR_ILLEGAL_KEY_VALUE"; }
			if( $result == "REBOOT" )
			{
				set("wps/configured", "1");
				set("authtype", $auth);
				set("encrtype","WEP");
				set("nwkey/wep/size", $wepLen);
				set("nwkey/wep/ascii", "0");
				set("nwkey/wep/defkey", "1"); 
				$defKey = query("nwkey/wep/defkey");
				set("nwkey/wep/key:".$defKey, $key);
			}
		}
		else if( $type == "WPA-PSK" || $type == "WPA2-PSK" || $type == "WPAORWPA2-PSK" )
		{
			if( $keyRenewal == "" )
			{
				$result = "ERROR_KEY_RENEWAL_BAD_VALUE";
			}
			//more strict
			if( $keyRenewal < 60 || $keyRenewal > 7200 )
			{
				$result = "ERROR_KEY_RENEWAL_BAD_VALUE";
			}
			if( $key == "" )
			{
				$result = "ERROR_ILLEGAL_KEY_VALUE";
			}
			if( $encrypt != "TKIP" && $encrypt != "AES" && $encrypt != "TKIPORAES" )
			{
				$result = "ERROR_ENCRYPTION_NOT_SUPPORTED";
			}
			if( $type == "WPA-PSK" )
			{ $auth = "WPAPSK"; }
			else if( $type == "WPA2-PSK" )
			{ $auth = "WPA2PSK"; }
			else
			{ $auth = "WPA+2PSK"; }
			if( $encrypt == "TKIP" )
			{ $encrypttype = "TKIP"; }
			else if( $encrypt == "AES" )
			{ $encrypttype = "AES"; }
			else
			{ $encrypttype = "TKIP+AES"; }
			if( $result == "REBOOT" )
			{
				set("wps/configured", "1");
				set("authtype",$auth);
				set("encrtype",$encrypttype);
				set("nwkey/wep/ascii","1");
				set("nwkey/psk/key",$key);
				set("nwkey/psk/passphrase", "1");
				set("nwkey/rekey/gtk",$keyRenewal);
			}
		}
		else if( $type == "WPA-RADIUS" || $type == "WPA2-RADIUS" || $type == "WPAORWPA2-RADIUS" )
		{
			if( $keyRenewal == "" )
			{
				$result = "ERROR_KEY_RENEWAL_BAD_VALUE";
			}
			//more strict
			if( $keyRenewal < 60 || $keyRenewal > 7200 )
			{
				$result = "ERROR_KEY_RENEWAL_BAD_VALUE";
			}
			if( $encrypt != "TKIP" && $encrypt != "AES" && $encrypt != "TKIPORAES" )
			{
				$result = "ERROR_ENCRYPTION_NOT_SUPPORTED";
			}
			if( $radiusIP1 == "" || $radiusPort1 == "" || $radiusSecret1 == "" )
			{
				$result = "ERROR_BAD_RADIUS_VALUES";
			}
			if( $type == "WPA-RADIUS" )
			{ $auth = "WPA"; }
			else if( $type == "WPA2-RADIUS" )
			{ $auth = "WPA2"; }
			else
			{ $auth = "WPA+2"; }
			if( $encrypt == "TKIP" )
			{ $encrypttype = "TKIP"; }
			else if( $encrypt == "AES" )
			{ $encrypttype = "AES"; }
			else
			{ $encrypttype = "TKIP+AES"; }
			if( $result == "REBOOT" )
			{
				set("wps/configured", "1");
				set("authtype",$auth);
				set("encrtype",$encrypttype);
				set("nwkey/wep/ascii","1");
				set("nwkey/eap/radius",$radiusIP1);
				set("nwkey/eap/port",$radiusPort1);
				set("nwkey/eap/secret",$radiusSecret1);
				set("nwkey/eap/radius2",$radiusIP2);
				set("nwkey/eap/port2",$radiusPort2);
				set("nwkey/eap/secret2",$radiusSecret2);
				set("nwkey/rekey/gtk",$keyRenewal);
			}
		}
		else
		{
			$result = "ERROR_TYPE_NOT_SUPPORT";
		}
	}
}

fwrite("w",$ShellPath, "#!/bin/sh\n");
fwrite("a",$ShellPath, "echo \"[$0]-->WLan Change\" > /dev/console\n");
if($result=="REBOOT")
{
	fwrite("a",$ShellPath, "/etc/scripts/dbsave.sh > /dev/console\n");
	fwrite("a",$ShellPath, "service WIFI.WLAN-1 restart > /dev/console\n");
	fwrite("a",$ShellPath, "xmldbc -s /runtime/hnap/dev_status '' > /dev/console\n");
	set("/runtime/hnap/dev_status", "ERROR");
}
else
{
	fwrite("a",$ShellPath, "echo \"We got a error in setting, so we do nothing...\" > /dev/console");
}
?>
<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
  <soap:Body>
    <SetWLanRadioSecurityResponse xmlns="http://purenetworks.com/HNAP1/">
      <SetWLanRadioSecurityResult><?=$result?></SetWLanRadioSecurityResult>
    </SetWLanRadioSecurityResponse>
  </soap:Body>
</soap:Envelope>
