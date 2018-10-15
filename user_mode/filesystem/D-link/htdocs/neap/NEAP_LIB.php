<?  	
	include "/htdocs/phplib/xnode.php";	
	include "/htdocs/phplib/inet.php";		
						
	function NEAP_action($action, $path, $get_value, $set_value)
	{		
		if($action == "GET")
		{			
			if($get_value !="") echo $get_value; else echo " ";			
		}
		if($action == "SET" && $path != "") set($path, $set_value);
	}		
		
	function WAN_cable_status($uid)
	{
		$infp = XNODE_getpathbytarget("", "inf", "uid", $uid, "0");
		$phyinf_uid = query($infp."/phyinf");
		$rphyinfp = XNODE_getpathbytarget("/runtime", "phyinf", "uid", $phyinf_uid, "0");		
		$linkstatus = query($rphyinfp."/linkstatus"); 
		return $linkstatus;
	}	
	
	function WAN_network_status($uid)
	{			
		$infp = XNODE_getpathbytarget("runtime", "inf", "uid", $uid, "0");
		$inetp = $infp."/inet";
		$addrtype = query($inetp."/addrtype");	
		$addrtype_path = $inetp."/".$addrtype;
		$valid = query($addrtype_path."/valid");
		$linkstatus = WAN_cable_status($uid);
		if($valid == 1 && $linkstatus !="")  return 1; else return 0;		
	}
	
	function NR_PHYINF_getpathbyinf($uid)
	{		
		$infp = XNODE_getpathbytarget("", "inf", "uid", $uid, "0");
		if ($infp == "") return "";
		$phyinf_uid = query($infp."/phyinf");
		if ($phyinf_uid == "") return "";
		$phyinfp = XNODE_getpathbytarget("", "phyinf", "uid", $phyinf_uid, "0");		
		if ($phyinfp == "") return "";
		return $phyinfp;
	}
	
	function WIFI_getpathbyinf($uid)
	{
		$phyinfp = XNODE_getpathbytarget("", "phyinf", "uid", $uid);	
		if ($phyinfp == "") return "";
		$phyinf_wifi = query($phyinfp."/wifi");
		if ($phyinf_wifi == "") return "";
		$wifip = XNODE_getpathbytarget("wifi", "entry", "uid", $phyinf_wifi, 0);
		if ($wifip == "") return "";
		return $wifip;
	}
	
	
	function wifi_share_encrtype_strtonum($encrtype)
	{		
		if($encrtype == "NONE")  return 0;
		if($encrtype == "WEP")   return 1;				
		return "";
	}	
	
	function wifi_share_encrtype_numtostr($encrtype)
	{		
		if($encrtype == 0) return "NONE";
		if($encrtype == 1) return "WEP";		
		return "";
	}
	
	function wifi_wpax_encrtype_strtonum($encrtype)
	{				
		if($encrtype == "TKIP+AES")  return 0;
		if($encrtype == "TKIP")      return 1;
		if($encrtype == "AES")       return 2;		
		return "";
	}	
	
	function wifi_wpax_encrtype_numtostr($encrtype)
	{		
		if($encrtype == 0) return "TKIP+AES";				
		if($encrtype == 1) return "TKIP";
		if($encrtype == 2) return "AES";		
		return "";
	}		
	
	function wifi_authtype_strtonum($authtype)
	{
		if($authtype == "OPEN")     return 0;
		if($authtype == "SHARED")   return 1;
		//if($authtype == "WPA")      return "";
		if($authtype == "WPAPSK")   return 4;
		if($authtype == "WPA2")     return 5;
		if($authtype == "WPA2PSK")  return 6;
		if($authtype == "WPA+2")    return 7;				
		if($authtype == "WPA+2PSK") return 8;
		//if($authtype == "802.1X")   return 8;
		return "";
	}
	
	function wifi_authtype_numtostr($authtype)
	{			
		if($authtype == 0) return "OPEN";
		if($authtype == 1) return "SHARED";
		//if($authtype == 3) return "WPA";		
		if($authtype == 4) return "WPAPSK";
		if($authtype == 5) return "WPA2";
		if($authtype == 6) return "WPA2PSK";
		if($authtype == 7) return "WPA+2";
		if($authtype == 8) return "WPA+2PSK";
		//if($authtype == 8) return "802.1X";
		return "";
	}
	
	function ppp_over_strtonum($over)
	{
		if($over == "eth")   return 0;
		if($over == "pptp")  return 1;
		if($over == "l2tp")  return 2;			
		return "";
	}			
	
	function ppp_over_numtostr($over)
	{
		if($over == 0)  return "eth";
		if($over == 1)  return "pptp";
		if($over == 2)  return "l2tp";	
		return "";		
	}		
	
	function ppp_dialupmode_strtonum($mode)
	{
		if($mode == "auto")     return 0;				
		if($mode == "ondemand") return 1;						
		if($mode == "manual") 	return 2;
		return "";
	}		
	
	function ppp_dialupmode_numtostr($mode)
	{
		if($mode == 0)  return "auto";
		if($mode == 1)  return "ondemand";
		if($mode == 2)  return "manual";	
		return "";
	}
	
	function pptp_static_get($static)
	{
		if($static == 1) return 1;				
		if($static == 0) return 2;				
		return "";
	}
	
	function pptp_static_set($static)
	{
		if($static == 1) return 1;				
		if($static == 2) return 0;				
		return "";
	}
	
	function l2tp_static_get($static)
	{
		pptp_static_get($static);
	}	
	
	function l2tp_static_set($static)
	{
		pptp_static_set($static);
	}
						
	function ipv4_static_get($static)
	{
		if($static == 1) return 0;				
		if($static == 0) return 1;	
		return "";			
	}		
	
	function ipv4_static_set($static)
	{
		if($static == 0) return 1;				
		if($static == 1) return 0;				
		return "";
	}
	
	function wep_ascii_get($ascii)
	{		
		if($ascii == 0) return 1;				
		if($ascii == 1) return 0;	
		return "";			
	}		
	
	function wep_ascii_set($ascii)
	{
		
		if($ascii == 0) return 1;				
		if($ascii == 1) return 0;				
		return "";
	}		
	
	function wifi_txpower_get($txpower)
	{				
		if($txpower == 100) 	return "full";				
		if($txpower == 50) 		return "half";	
		if($txpower == 20) 		return "quarter";	
		if($txpower == "12.5")  return "eighth";
		return "";			
	}		
	
	function wifi_txpower_set($txpower)
	{
		if($txpower == "full")                        return 100;
		if($txpower == "half") 	                      return 50;	
		if($txpower == "quarter")                     return 20;	
		if($txpower == "eighth" || $txpower == "min") return "12.5";	
		return "";
	}
	
	function wifi_preamble_get($preamble)
	{		
		if($preamble == "short") return 0;
		if($preamble == "long")  return 1;
		return "";			
	}		
													
	function ipv4_ifstatic_get($inetp)
	{						
		$addrtype_path = $inetp."/ipv4";
		$node = "/static";		
		$path = $addrtype_path.$node;		
		$static = query($path);		
		return $static;			
	}
	
	function ipv4_dnscount_set($inetp, $dns)
	{							
		$dns_count =0;
		$p1 = $inetp."/ipv4/dns/entry:1";
		$p2 = $inetp."/ipv4/dns/entry:2";
		$dns1 = query($p1);
		$dns2 = query($p2);		
		if($dns1 != "") $dns_count++;		
		if($dns2 != "") $dns_count++;		 		
		$dnscount_path = $inetp."/ipv4/dns/count";
		set($dnscount_path, $dns_count);		
		return $dns_count;
	}	
		
	function pptp_l2tp_func($waninf, $wan2inf, $over, $ACTION, $ENDCODE, $VALUE)
	{														
		$path = "";
		$get_value = "";						
		$set_value = $VALUE;
		$uid = $wan2inf;		
		$inetp = INET_getpathbyinf($uid);
		$addrtype_path = $inetp."/ipv4";
		
		$infp = XNODE_getpathbytarget("", "inf", "uid", $waninf, "0");
		set($infp."/lowerlayer", "WAN-2");					
		
		$infp = XNODE_getpathbytarget("", "inf", "uid", $wan2inf, "0");
		set($infp."/active", 1);		
		set($infp."/upperlayer", "WAN-1");
		set($infp."/defaultroute", "");
		if($over == "pptp") set($infp."/nat", "");	
		
		
		if($ENDCODE == "1")
		{							
			$node = "/static";
			$path = $addrtype_path.$node;
			$static = query($path);						
			$get_value = pptp_static_get($static);
			$set_value = pptp_static_set($set_values);									 						
		}else if($ENDCODE == "2"){							
			$node = "/ipaddr";
			$path = $addrtype_path.$node;										
			$get_value = query($path);								
		}else if($ENDCODE == "3"){			
			$node = "/mask";
			$path = $addrtype_path.$node;										
			$mask = query($path);
			if($mask != "") $get_value = ipv4int2mask($mask);
			$set_value = ipv4mask2int($set_value);
		}else if($ENDCODE == "4"){			
			$node = "/gateway";
			$path = $addrtype_path.$node;										
			$get_value = query($path);
		}else if($ENDCODE == "5"){					
			$uid = $waninf;
			$inetp = INET_getpathbyinf($uid);									
			$path = $inetp."/ppp4/".$over."/server";													
			$get_value = query($path);										
		}
						
												
		NEAP_action($ACTION, $path, $get_value, $set_value);
				
	}		
?>
