<?  /* extern parameters: $ACTION, $ENDCODE, $VALUE */		
	include "/htdocs/neap/NEAP_LIB.php";
	include "/htdocs/neap/NEAP_CONFIG.php";
					
	$set_value = $VALUE;
	$get_value = "";				
	$path = "";
	$uid = $WLAN1;
	$phyinfp = XNODE_getpathbytarget("", "phyinf", "uid", $uid);
	$phyinf_media = $phyinfp."/media";							
	$phyinf_wifi = query($phyinfp."/wifi");
	$wifip = XNODE_getpathbytarget("wifi", "entry", "uid", $phyinf_wifi, 0);	
				
	if($ENDCODE == "1")
	{
		$path = "/runtime/devdata/countrycode";
		$get_value = query($path);			
	}else if($ENDCODE == "2"){					
	}else if($ENDCODE == "3"){						
	}else if($ENDCODE == "4"){		
	}else if($ENDCODE == "5"){
		$path = $wifip."/ssid";
		$get_value = query($path);
		if($ACTION == "SET" && $get_value != $set_value) set($wifip."/wps/configured", 1);		
	}else if($ENDCODE == "6"){
		$path = $wifip."/ssidhidden";
		$get_value = query($path);
	}else if($ENDCODE == "7"){
		$path = $phyinf_media."/channel";
		$get_value = query($path);
	}else if($ENDCODE == "8"){		
	}else if($ENDCODE == "9"){				
		$path = $phyinf_media."/channel";		
		$channel = query($path);
		if($channel == "0") $get_value = 1; else $get_value = 0;
		$path = "";					
	}else if($ENDCODE == "10"){		
		$path = $phyinf_media."/txrate";
		$get_value = query($path);
	}else if($ENDCODE == "11"){
		$path = $phyinf_media."/beacon";
		$get_value = query($path);
	}else if($ENDCODE == "12"){
		$path = $phyinf_media."/dtim";
		$get_value = query($path);		
		if($set_value < 0 || $set_value > 255) $path = "";
	}else if($ENDCODE == "13"){
		$path = $phyinf_media."/fragthresh";
		$get_value = query($path);
		if($set_value < 256 || $set_value > 2346) $path = "";
	}else if($ENDCODE == "14"){
		$path = $phyinf_media."/rtsthresh";
		$get_value = query($path);
		if($set_value < 0 || $set_value > 2346) $path = "";	
	}else if($ENDCODE == "15"){		
		$path = $phyinf_media."/txpower";
		$txpower = query($path);
		$get_value = wifi_txpower_get($txpower);
		$set_value = wifi_txpower_set($set_value);
	}else if($ENDCODE == "16"){
		$preamble = query($phyinf_media."/preamble");			
		$get_value = wifi_preamble_get($preamble);
	}
		
	NEAP_action($ACTION, $path, $get_value, $set_value);
							
?>
