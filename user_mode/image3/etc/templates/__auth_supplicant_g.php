<?
if ($wlan_ap_operate_mode_g==1){
	$wpa_supplicant_conf 	= "/var/run/wpa_supplicant.".$wlanif_g.".conf";
}else if($wlan_ap_operate_mode_g==2){
	$wpa_supplicant_conf 	= "/var/run/wpa_supplicant.ath1.conf";
}
//$wpa_supplicant_pid    = "/var/run/wpa_supplicant.".$wlanif_g.".pid";
$encrtype		= query("/wlan/inf:1/wpa/wepmode");
$wepdefkey 		= query("/wlan/inf:1/defkey");
$wepkeyformat 		= query("/wlan/inf:1/wepkey:".$wepdefkey."/keyformat");
$wepkey1		= query("/wlan/inf:1/wepkey:1");
$wepkey2		= query("/wlan/inf:1/wepkey:2");
$wepkey3		= query("/wlan/inf:1/wepkey:3");
$wepkey4		= query("/wlan/inf:1/wepkey:4");
$ssid			= query("/wlan/inf:1/ssid");
$wpapsk			= query("/wlan/inf:1/wpa/wpapsk");
$wpapskformat		= query("/wlan/inf:1/wpa/passphraseformat");

/*echo $IWPRIV." authmode 5\n"; *//* WPA/RSN 802.1x/PSK */

fwrite($wpa_supplicant_conf, "ap_scan=1\n");
fwrite2($wpa_supplicant_conf, "network={\n");
fwrite2($wpa_supplicant_conf, "ssid=\"".$ssid."\"\n");
fwrite2($wpa_supplicant_conf, "scan_ssid=1\n");

if      ($auth_mode==0) { $wpa_type=0; $use_8021x=0; }  /* OPEN */
else if ($auth_mode==1) { $wpa_type=0; $use_8021x=0; }  /* SHARED */
else if ($auth_mode==2) { $wpa_type=1; $use_8021x=1; }  /* WPA */
else if ($auth_mode==3) { $wpa_type=1; $use_8021x=0; }  /* WPA-PSK */
else if ($auth_mode==4) { $wpa_type=2; $use_8021x=1; }  /* WPA2 */
else if ($auth_mode==5) { $wpa_type=2; $use_8021x=0; }  /* WPA2-PSK */
else if ($auth_mode==6) { $wpa_type=3; $use_8021x=1; }  /* WPA + WPA2 */
else if ($auth_mode==7) { $wpa_type=3; $use_8021x=0; }  /* WPA-PSK + WPA2-PSK */

if      ($auth_mode==1)	{ fwrite2($wpa_supplicant_conf, "auth_alg=SHARED\n"); }
		
if ($use_8021x==0) {
	if($wpa_type == 0){
		if($encrtype==1) {
			if($wepkeyformat==2){
		    	if     ($wepdefkey==1){fwrite2($wpa_supplicant_conf,"wep_key0=".$wepkey1."\n");}
				else if($wepdefkey==2){fwrite2($wpa_supplicant_conf,"wep_key1=".$wepkey2."\n");}
				else if($wepdefkey==3){fwrite2($wpa_supplicant_conf,"wep_key2=".$wepkey3."\n");}
				else if($wepdefkey==4){fwrite2($wpa_supplicant_conf,"wep_key3=".$wepkey4."\n");}
			}else{
				if     ($wepdefkey==1){fwrite2($wpa_supplicant_conf,"wep_key0=\"".$wepkey1."\"\n");}
				else if($wepdefkey==2){fwrite2($wpa_supplicant_conf,"wep_key1=\"".$wepkey2."\"\n");}
				else if($wepdefkey==3){fwrite2($wpa_supplicant_conf,"wep_key2=\"".$wepkey3."\"\n");}
				else if($wepdefkey==4){fwrite2($wpa_supplicant_conf,"wep_key3=\"".$wepkey4."\"\n");}
			}
			if     ($wepdefkey==1){fwrite2($wpa_supplicant_conf,"wep_tx_keyidx=0\n");}
			else if($wepdefkey==2){fwrite2($wpa_supplicant_conf,"wep_tx_keyidx=1\n");}
			else if($wepdefkey==3){fwrite2($wpa_supplicant_conf,"wep_tx_keyidx=2\n");}
			else if($wepdefkey==4){fwrite2($wpa_supplicant_conf,"wep_tx_keyidx=3\n");}
		}
		fwrite2($wpa_supplicant_conf,"key_mgmt=NONE\n");
	}else{
		if ($wpapskformat==1){
			fwrite2($wpa_supplicant_conf,"key_mgmt=WPA-PSK\npsk=\"".$wpapsk."\"\n");
		} else {
			fwrite2($wpa_supplicant_conf,"key_mgmt=WPA-PSK\npsk=".$wpapsk."\n");
		}
	}
}

if      ($wpa_type==1) { fwrite2($wpa_supplicant_conf,"proto=WPA\n"); }
else if ($wpa_type==2) { fwrite2($wpa_supplicant_conf,"proto=WPA2\n"); }
else if ($wpa_type==3) { fwrite2($wpa_supplicant_conf,"proto=WPA2 WPA\n"); }
	
// Cipher mode 
if      ($encrtype==2)   { fwrite2($wpa_supplicant_conf, "pairwise=TKIP\ngroup=TKIP\n"); }
else if ($encrtype==3)   { fwrite2($wpa_supplicant_conf, "pairwise=CCMP\ngroup=CCMP TKIP\n"); }
else if ($encrtype==4)   { fwrite2($wpa_supplicant_conf, "pairwise=CCMP TKIP\ngroup=CCMP TKIP\n"); }

fwrite2($wpa_supplicant_conf,"}\n");
/*echo "echo $! > ".$wpa_supplicant_pid."\n";*/

?>
