<?  /* extern parameters: $ACTION, $ENDCODE, $VALUE */		
	include "/htdocs/neap/NEAP_LIB.php";
	include "/htdocs/neap/NEAP_CONFIG.php";
						
	$set_value = $VALUE;
	$get_value = "";
	$path ="";			
	$uid = $WAN1;	
					
	if($ENDCODE == "1")
	{	
		$inetp = INET_getpathbyinf($uid);		
		$addrtype = query($inetp."/addrtype");	
		if($addrtype == "ipv4" || $addrtype == "ipv6")
		{									
			$static = query($inetp."/ipv4/static");			
			if($static == 1) $get_value ="0"; else $get_value ="2";			
		}else {
			$over = query($inetp."/ppp4/over");			
			if($over == "eth") $get_value ="1"; else $get_value ="0";			
		}							
	}else if($ENDCODE == "2"){
		$cable_status = WAN_cable_status($uid);
		if($cable_status == "")          $get_value = 0;
		else if($cable_status == "100F") $get_value = 1;		
		else if($cable_status == "100H") $get_value = 2;		
		else if($cable_status == "10F")  $get_value = 3;		
		else if($cable_status == "10H")  $get_value = 4;				
	}else if($ENDCODE == "3"){			
		$mode = query("/runtime/device/layout");
		if($mode == "router") $get_value = 1; else $get_value = 0;
	}else if($ENDCODE == "4"){
		/* open dns ??*/		
		$get_value = 0;		
	}	
		
	NEAP_action($ACTION, $path, $get_value, $set_value);
							
?>
