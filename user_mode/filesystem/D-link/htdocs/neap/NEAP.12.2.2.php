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
		$path = $wifip."/authtype";
		$authtype = query($path);	
		$get_value = wifi_authtype_strtonum($authtype);		
		$set_value = wifi_authtype_numtostr($set_value);		
	}
		
	NEAP_action($ACTION, $path, $get_value, $set_value);							
?>
