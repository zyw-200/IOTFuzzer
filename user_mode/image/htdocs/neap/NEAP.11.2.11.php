<?  /* extern parameters: $ACTION, $ENDCODE, $VALUE */		
	include "/htdocs/neap/NEAP_LIB.php";
	include "/htdocs/neap/NEAP_CONFIG.php";
					
	$set_value = $VALUE;
	$get_value = "";				
	$path ="";
	$uid = $WAN1;							
	$inetp = INET_getpathbyinf($uid);	
	$addrtype = query($inetp."/addrtype");		
	$addrtype_path = $inetp."/".$addrtype;
	
	if ($addrtype == "ppp4" || $addrtype == "ppp6")			
	{	
		if($ENDCODE == "1")
		{										
			$node = "/pppoe/acname";									
			$path = $addrtype_path.$node;							
			$get_value = query($path);														
		}else if($ENDCODE == "2"){				
			$node = "/pppoe/servicename";
			$path = $addrtype_path.$node;										
			$get_value = query($path);							
		}
	}	
		
	NEAP_action($ACTION, $path, $get_value, $set_value);
							
?>
