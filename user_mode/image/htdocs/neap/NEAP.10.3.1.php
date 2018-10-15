<?  /* extern parameters: $ACTION, $ENDCODE, $VALUE */		
	include "/htdocs/neap/NEAP_LIB.php";
					
	$set_value = $VALUE;
	$path = "";
	$get_value = "";
								
	if($ENDCODE == "1")
	{				
		$path = "/device/session/captcha";
		$get_value = query($path);
	}
	
	NEAP_action($ACTION, $path, $get_value, $set_value);	
							
?>
