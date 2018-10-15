<?  /* extern parameters: $ACTION, $ENDCODE, $VALUE */			
	include "/htdocs/neap/NEAP_LIB.php";
	include "/htdocs/neap/NEAP_CONFIG.php";
						
	$set_value = $VALUE;
	$get_value = "";				
	$path = "";
	$uid = $WAN1;
	$inetp = INET_getpathbyinf($uid);	
	$addrtype_path = $inetp."/ppp4";	
			
	if($ENDCODE == "1")
	{															
		set($addrtype_path."/mtu", 1400);
		set($addrtype_path."/static", 0);
		set($addrtype_path."/ipaddr", "");		
		set($addrtype_path."/mru", "");
		set($addrtype_path."/mppe/enable", 0);
		set($addrtype_path."/dialup/idletimeout", 0);
		set($addrtype_path."/dialup/mode", "auto");
		
		$addrtype = query($inetp."/addrtype");
		if($addrtype == "ppp4" || $addrtype == "ppp6") $get_value = "1"; else $get_value = "0";									
	}else if($ENDCODE == "2"){				
		$status = WAN_network_status($uid);
		if($status == 1) $get_value = 2; else $get_value = 5;
	}else if($ENDCODE == "3"){															
		$node = "/dialup/idletimeout";									
		$path = $addrtype_path.$node;										
		$get_value = query($path);														
	}else if($ENDCODE == "4"){												
		$node = "/username";									
		$path = $addrtype_path.$node;							
		$get_value = query($path);								
	}else if($ENDCODE == "5"){													
		$node = "/password";									
		$path = $addrtype_path.$node;							
		$get_value = query($path);								
	}else if($ENDCODE == "6"){			
	}else if($ENDCODE == "7"){				
	}else if($ENDCODE == "8"){		
	}else if($ENDCODE == "9"){		
	}else if($ENDCODE == "10"){			
		$node = "/mtu";									
		$path = $addrtype_path.$node;							
		$get_value = query($path);
	}else if($ENDCODE == "11"){					
	}else if($ENDCODE == "12"){		
	}else if($ENDCODE == "13"){		
	}else if($ENDCODE == "14"){
		NEAP_action($ACTION, $inetp."/addrtype", "", "ppp4");				
		$node = "/over";									
		$path = $addrtype_path.$node;													
		$over = query($path);			
		$get_value = ppp_over_strtonum($over);
		$set_value = ppp_over_numtostr($set_value);		
	}else if($ENDCODE == "15"){							
		$node = "/dialup/mode";	
		$path = $addrtype_path.$node;		
		$mode = query($path);			
		$get_value = ppp_dialupmode_strtonum($mode);
		$set_value = ppp_dialupmode_numtostr($set_value);
	}else if($ENDCODE == "16" || $ENDCODE == "17"){					
		$phyinfp = NR_PHYINF_getpathbyinf($uid);		
		$path = $phyinfp."/macaddr";
		$macaddr = query($path);
		if($ENDCODE == "17") 
		{ 
			$path ="";
			if($macaddr !="") $get_value = 1; else $get_value =0; 
		} else $get_value = $macaddr;
	}
			
	NEAP_action($ACTION, $path, $get_value, $set_value);
							
?>
