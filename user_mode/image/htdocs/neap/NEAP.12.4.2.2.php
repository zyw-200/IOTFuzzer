<?  /* extern parameters: $ACTION, $ENDCODE, $VALUE */			
	include "/htdocs/neap/NEAP_LIB.php";
	include "/htdocs/neap/NEAP_CONFIG.php";
					
	$set_value = $VALUE;
	$get_value = "";
	$path ="";				
	$uid = $WLAN2; 		
	$wifip = WIFI_getpathbyinf($uid);		
	
	if($ENDCODE == "1")
	{				
		$path = $wifip."/encrtype";
		$encrtype = query($path);
		$get_value = wifi_share_encrtype_strtonum($encrtype);		
		//hendry
		//$set_value = wifi_share_encrtype_numtostr($set_value);	
		if($ACTION == "SET" && $get_value != $set_value) set($wifip."/wps/configured", 1);
		$set_value = wifi_share_encrtype_numtostr($set_value);	
	}else if($ENDCODE == "2"){
		$path = $wifip."/nwkey/wep/defkey";		
		$get_value = query($path);							
	}else if($ENDCODE == "3"){
		$path = $wifip."/nwkey/wep/ascii";
		$ascii = query($path);
		$get_value = wep_ascii_get($ascii);		
		$set_value = wep_ascii_set($set_value);
	}else if($ENDCODE == "4" || $ENDCODE == "6" || $ENDCODE == "8" || $ENDCODE == "10"){		
		$path = $wifip."/nwkey/wep/size";
		$get_value = query($path);		
		$len = strlen($set_value);
		if($len != 64 && $len != 128) $path =""; 
	}else if($ENDCODE == "5" || $ENDCODE == "7" || $ENDCODE == "9" || $ENDCODE == "11"){		
		$key = $ENDCODE-3;
		if($key == 2) $key =1;
		else if($key == 4) $key =2;
		else if($key == 6) $key =3;
 		else if($key == 8) $key =4;
		$path = $wifip."/nwkey/wep/key:".$key;
		$get_value = query($path);
	}
		
	NEAP_action($ACTION, $path, $get_value, $set_value);
							
?>
