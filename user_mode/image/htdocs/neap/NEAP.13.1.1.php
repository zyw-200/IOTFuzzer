<?  /* extern parameters: $ACTION, $ENDCODE, $VALUE */		
	include "/htdocs/neap/NEAP_LIB.php";
	include "/htdocs/neap/NEAP_CONFIG.php";
	
	$set_value = $VALUE;
	$get_value = "";	
	$uid = $LAN1;
	$inet = INET_getpathbyinf($uid);
	$ipv4= $inet."/ipv4";	
		
	if($ENDCODE == "1")
	{
		$path = $ipv4."/ipaddr";										
		$get_value = query($path);
	}else if($ENDCODE == "2"){
		$path = $ipv4."/mask";
		$mask = query($path);
		$get_value = ipv4int2mask($mask);
		$set_value = ipv4mask2int($set_value);				
	}
			
	NEAP_action($ACTION, $path, $get_value, $set_value);
?>
