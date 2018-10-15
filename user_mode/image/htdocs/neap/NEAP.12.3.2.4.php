<?  /* extern parameters: $ACTION, $ENDCODE, $VALUE */		
	include "/htdocs/neap/NEAP_LIB.php";
	include "/htdocs/neap/NEAP_CONFIG.php";
						
	$set_value = $VALUE;
	$get_value = "";
	$path = "";				
	$uid = $WLAN1; 	
	$wifip = WIFI_getpathbyinf($uid);
			
	if($ENDCODE == "1")
	{
		$path = $wifip."/encrtype";				
		$encrtype = query($path);
		$get_value = wifi_wpax_encrtype_strtonum($encrtype);
		$set_value = wifi_wpax_encrtype_numtostr($set_value);
		if($ACTION == "SET" && $get_value != $set_value) set($wifip."/wps/configured", 1);				
	}else if($ENDCODE == "2"){		
		$path = $wifip."/nwkey/psk/passphrase";
		set($path, 1);
		$path = $wifip."/nwkey/psk/key";
		$get_value = query($path);
		$len = strlen($set_value);
		if($len < 8 || $len > 64) $path ="";
	}else if($ENDCODE == "3"){
		$path = $wifip."/nwkey/rekey";
		$get_value = query($path);
		if($set_value < 300 || $set_value > 1800) $path ="";
	}
		
	NEAP_action($ACTION, $path, $get_value, $set_value);
							
?>
