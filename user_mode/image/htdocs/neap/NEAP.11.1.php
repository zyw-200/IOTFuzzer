<?  /* extern parameters: $ACTION, $ENDCODE, $VALUE */			
	include "/htdocs/neap/NEAP_LIB.php";
	include "/htdocs/neap/NEAP_CONFIG.php";
					
	$set_value = $VALUE;
	$get_value = "";				
	$path = "";	
	$uid = $WAN1;										
	$inetp = INET_getpathbyinf($uid);		
	$addrtype_path = $inetp."/ipv4";
			
	if($ENDCODE == "1")
	{							
		$addrtype = query($inetp."/addrtype");	
		if($addrtype == "ipv4" || $addrtype == "ipv6") $get_value = "1"; else $get_value = "0";
	}else if($ENDCODE == "2"){			
		$status = WAN_network_status($uid);			
		if($status == 1) $get_value = 2; else $get_value = 5;
	}else if($ENDCODE == "3"){
		NEAP_action($ACTION, $inetp."/addrtype", "", "ipv4");													
		$node = "/static";		
		$path = $addrtype_path.$node;		
		$static = query($path);		
		$get_value = ipv4_static_get($static);			
		$set_value = ipv4_static_set($set_value);				
	}else if($ENDCODE == "4"){					
		$node = "/ipaddr";									
		$path = $addrtype_path.$node;
		$get_value = query($path);					
	}else if($ENDCODE == "5"){						
		$node = "/mask";
		$path = $addrtype_path.$node;
		$mask =query($path);														
		$get_value = ipv4int2mask($mask);
		$set_value = ipv4mask2int($set_value);		
	}else if($ENDCODE == "6"){		
		$node = "/gateway";		
		$path = $addrtype_path.$node;
		$get_value = query($path);		
	}else if($ENDCODE == "7" || $ENDCODE == "8"){				
		if($ENDCODE == "7") $num = 1; else $num = 2;		
		$path = $addrtype_path."/dns/entry:".$num;
		$get_value = query($path);
				
		if($ACTION == "SET")
		{			
			$static = ipv4_ifstatic_get($inetp);																			
			if($static == 0 && $set_value == "") $path ="";																					
		}	
	}else if($ENDCODE == "9"){
		$node = "/mtu";
		$path = $addrtype_path.$node;
		$get_value = query($path);	
	}else if($ENDCODE == "10" || $ENDCODE == "11"){			
		$phyinfp = NR_PHYINF_getpathbyinf($uid);			
		$path = $phyinfp."/macaddr";
		$macaddr = query($path);									
		if($ENDCODE == "11") 
		{ 
			$path ="";	
			if($macaddr !="") $get_value = 1; else $get_value =0; 				
		}else $get_value = $macaddr;
	}		
			
	NEAP_action($ACTION, $path, $get_value, $set_value);	
	if($ENDCODE == "8" && $ACTION == "SET") ipv4_dnscount_set($inetp, $set_value);
	
?>
