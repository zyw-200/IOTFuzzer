<?  /* extern parameters: $ACTION, $ENDCODE, $VALUE */		
	include "/htdocs/neap/NEAP_LIB.php";
	include "/htdocs/neap/NEAP_CONFIG.php";
		
	$set_value = $VALUE;
	$get_value = "";
	$path = "";		
	$uid = $LAN1;
	$rinfp = XNODE_getpathbytarget("/runtime", "inf", "uid", $uid, "0");
	$pool = $rinfp."/dhcps4/pool";
	
	if($ENDCODE == "1")
	{
		$path = $pool."/start";
		$get_value = query($path);			
	}else if($ENDCODE == "2"){
		$path = $pool."/end";
		$get_value = query($path);			
	}else if($ENDCODE == "3"){				
		$path = $pool."/mask";
		$mask = query($path);
		$get_value = ipv4int2mask($mask);
		$set_value = ipv4mask2int($set_value);					
	}else if($ENDCODE == "4" || $ENDCODE == "5"){
		$path = $pool."/domain";
		$get_value = query($path);	
	}else if($ENDCODE == "6"){
		$path = $pool."/leasetime";
		$get_value = query($path);
	}else if($ENDCODE == "7"){
		$path = $pool."/leasetime";
		$get_value = query($path);
	}else if($ENDCODE == "8"){
		$path = $pool."/router";
		$get_value = query($path);
	}else if($ENDCODE == "9"){
		$get_value ="";	
	}
	
	NEAP_action($ACTION, $path, $get_value, $set_value);
							
?>
