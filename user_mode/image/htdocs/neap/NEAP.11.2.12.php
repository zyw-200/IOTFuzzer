<?  /* extern parameters: $ACTION, $ENDCODE, $VALUE */		
	include "/htdocs/neap/NEAP_LIB.php";
	include "/htdocs/neap/NEAP_CONFIG.php";
										
	$over = "pptp";	
	pptp_l2tp_func($WAN1, $WAN2, $over, $ACTION, $ENDCODE, $VALUE);
														
?>
