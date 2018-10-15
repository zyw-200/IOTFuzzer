<?  /* extern parameters: $ACTION, $ENDCODE, $VALUE */		
	include "/htdocs/neap/NEAP_LIB.php";
	include "/htdocs/neap/NEAP_CONFIG.php";
					
	$set_value = $VALUE;
	$get_value = "";
	$path = "";						
								
	if($ENDCODE == "1" && $ACTION == "SET")
	{						
		event("REBOOT");
	}else if($ENDCODE == "2"){		
		$get_value = $REBOOTTIME;
	}else if($ENDCODE == "3" && $ACTION == "SET"){		
		event("DBSAVE");		
	}else if($ENDCODE == "4"){				
	}else if($ENDCODE == "5"){
		$path = "/device/account/entry:1/password";
		$get_value ="";
	}		
	
	NEAP_action($ACTION, $path, $get_value, $set_value);	
							
?>
