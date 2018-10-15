<? /* vi: set sw=4 ts=4: */
echo "echo UP 2.4G interface ! > /dev/console\n";
$WLAN_g = "/wlan/inf:1";	//b,g,n band
$WLAN = $WLAN_g;      
$multi_ssid_path = $WLAN."/multi";
$wds_path = $WLAN."/wds";
$sys = "/sys";
$eth = "/lan/ethernet";

$multi_total_state = query($multi_ssid_path."/state");  
$ap_mode	= query($WLAN."/ap_mode");  // "1" #1--->a   0---> b,g,n
$wlanif_g = query("/runtime/layout/wlanif_g");
$wlanmac_g= query("/runtime/layout/wlanmac");
$igmpsnoop = query("/wlan/inf:1/igmpsnoop");
$txpower    = query("/wlan/inf:1/txpower");
$wlxmlpatch_ap_pid = "/var/run/wlxmlpatch_g.pid";
$wlan_ap_operate_mode = query($WLAN."/ap_mode");

$EmRadiusState  =   query("/wlan/inf:1/wpa/embradius/state");
$EmRadiusCertState  =   query("/wlan/inf:1/wpa/embradius/certstate");
$EmRadiusEAPUser_conf   =   "/var/hostapd_g.eap_user";
$EmRadiusDefaultCA  = "/etc/templates/certs/cacert.pem";
$EmRadiusDefaultCAKey   =   "/etc/templates/certs/cakey.key";
$EmRadiusDefaultCAPass  =   "DEFAULT";
$EmRadiusEapUser    =   "/wlan/inf:1/wpa/embradius/eap_user/index";
$EmRadiusE_SrvCert  =   "/var/etc/certs/hostapd/eca_srvcert.pem";
$EmRadiusE_SrvKey   =   "/var/etc/certs/hostapd/eca_srvkey.key";
$EmRadiusE_Srv_KeyPass  =   query("/wlan/inf:1/wpa/embradius/eca_keypasswd");
$Brctl_igmp_g = -1;
if ($ap_mode == 4)  // WDS without AP // WDSwithoutAP_bug_0321
{    $withoutap = 1; }
else  // ($WLAN == 3)  WDS with AP
{    $withoutap = 0; }


if (query("/wlan/inf:1/enable")!=1)
{
	echo "echo WLAN is disabled ! > /dev/console\n";
	exit;
}
if ($wlan_ap_operate_mode==1)
{
	echo "echo APC MODE  > /dev/console\n";	
//	exit;
}		

//$lan_mac = query("/runtime/layout/lanmac");
anchor($WLAN);
/* common cmd */
//    $IWPRIV="iwpriv ".$wlanif_g;
$IWCONF="iwconfig ".$wlanif_g;
require("/etc/templates/troot.php");
$auth_mode = query("/wlan/inf:1/authentication");
$ap_igmp_pid = "/var/run/ap_igmp_g.pid";
// $wlan_ap_operate_mode = query($WLAN."/ap_mode");
$W_PATH=$WLAN."/";

//****************************Device UP*********************************************
//~~~~~~~~~~APMDOE: ath0 up~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~  	

echo "ifconfig ".$wlanif_g." up\n";
if (query("/wlan/inf:1/autochannel")==1) {echo $IWCONF." freq 0\n";}   //erial_acs_201009
echo "brctl addif br0 ".$wlanif_g."\n";
if ($wlan_ap_operate_mode==2)
{
	echo "sleep 3\n";
 	echo "ifconfig ath1 up\n";
	echo "ifconfig br0 hw ether `ifconfig ath1 | grep ath1 | scut -f5`\n";	
	echo "sh ".$template_root."/apr_loop.sh &\n";
}else{ 
       echo "ifconfig br0 hw ether ".$wlanmac_g." \n";   //switch apr to apc/wds will cause ping fail,william_201207.
}

//~~~~~~~~~MSSID : ath1-8 up~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
if ($multi_total_state == 1)
{

	$index=0; 
	//       $index1=0;
	for ($multi_ssid_path."/index")
	{      
		$index++;     
		/*add schedule for multi-ssid by yuda start*/
                $schedule_enable=query("/schedule/enable");
                if ($schedule_enable==1)
                {
                        if(query($multi_ssid_path."/index:".$index."/schedule_rule_state")==1)
                        {
                                $multi_ind_schedule_state = query($multi_ssid_path."/index:".$index."/schedule_state");
                        }
                        else
                        {
                                $multi_ind_schedule_state = 1;
                        }
                }
                else
                {
                        $multi_ind_schedule_state = 1;
                }

                /*add schedule for multi-ssid by yuda end*/

		$multi_ind_state = query($multi_ssid_path."/index:".$index."/state");  
		if ($multi_ind_state==1)  
		{ 	            
			echo "ifconfig ath".$index." up\n";
			echo "brctl addif br0 ath".$index."\n";	
		}  // end of if ($multi_ind_state==1)  
	}  // end of for ($multi_ssid_path."/index")
} // end of ($multi_total_state == 1)

	
//~~~~~~~~WDS: ath8-15 up~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
if ($wlan_ap_operate_mode==3 || $wlan_ap_operate_mode==4)
{
	$index=7;
	$index_mac=0;
	$up_ath_cnt=0;	

	for ($WLAN."/wds/list/index")
	{   
		$index++;     
		$index_mac++;   
		$wds_mac = query($WLAN."/wds/list/index:".$index_mac."/mac");  
		if ($wds_mac!="")  
		{   
			$up_ath_cnt++;    
			echo "sleep 3\n";	
			echo "\necho UP WDS ath".$index."... > /dev/console\n";
			
			echo "ifconfig ath".$index." up\n";
			echo "brctl addif br0 ath".$index."\n";
		
		}//if ($wds_mac!="")  
	
	}//  for ($WLAN."/wds/list/index")

}   //($wlan_ap_operate_mode==3 || $wlan_ap_operate_mode==4)



//**********************************demon up*******************************************

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~MMSID:demon up~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~


if ($multi_total_state == 1)
{
	$index=0; 
	//$index1=0;
	for ($multi_ssid_path."/index")
	{      
		$index++;     
		//	        $IWPRIV="iwpriv ath".$index;
	
		 /*add schedule for multi-ssid by yuda start*/
                $schedule_enable=query("/schedule/enable");
                if ($schedule_enable==1)
                {
                        if(query($multi_ssid_path."/index:".$index."/schedule_rule_state")==1)
                        {
                                $multi_ind_schedule_state = query($multi_ssid_path."/index:".$index."/schedule_state");
                        }
                        else
                        {
                                $multi_ind_schedule_state = 1;
                        }
                }
                else
                {
                        $multi_ind_schedule_state = 1;
                }

                /*add schedule for multi-ssid by yuda end*/

		$multi_ind_state = query($multi_ssid_path."/index:".$index."/state");  
		
		if ($multi_ind_state==1)  
		{ 	                   
			echo "\necho UP MSSID-demon ath".$index."... > /dev/console\n";
			$multi_auth = query($multi_ssid_path."/index:".$index."/auth");
			$multi_cipher = query($multi_ssid_path."/index:".$index."/cipher");
			$multi_ssid = query($multi_ssid_path."/index:".$index."/ssid");//query($multi_ssid_path."/index:".$index."/ssid");
			
			$multi_embradius_state = query($multi_ssid_path."/index:".$index."/embradius_state");
			/* Jack add 13/02/08 +++ wlxmlpatch_v2*/
			$wlxmlpatch_pid	= "/var/run/wlxmlpatch".$index.".pid";
			echo "wlxmlpatch -S ath".$index." /runtime/stats/wlan/inf:1/mssid:".$index." RADIO_SSID1_ON RADIO_SSID1_BLINK MADWIFI > /dev/console &\n";
			echo "echo $! > ".$wlxmlpatch_pid."\n";
			/* Jack add 13/02/08 --- wlxmlpatch_v2*/
			
			/*security +++*/
			
			
			if ($multi_auth==3 || /*wpa-psk*/ 
				$multi_auth==5 || /*wpa2-psk*/  
				$multi_auth==7 || /*wpa-auto-psk*/     
				$multi_auth==2 || /*wpa-eap*/ 
				$multi_auth==4 || /*wpa2-eap*/  
				$multi_auth==6 || /*wpa-auto-eap*/           
				$multi_auth==9) /*802.1x only*/           
			{            
				$multi_interval = query($multi_ssid_path."/index:".$index."/interval"); 
				$lanif  = query("/runtime/layout/lanif");          
				$hostapd_conf	= "/var/run/hostapd0".$index.".ap_bss";
				if ($multi_auth==3 || $multi_auth==2)          {   $wpa_hostapd    = 1; } /*wpa*/
				else if ($multi_auth==5 || $multi_auth==4)     {   $wpa_hostapd    = 2; } /*wpa2*/
				else if ($multi_auth==7 || $multi_auth==6)     {   $wpa_hostapd    = 3; } /*wpa-auto*/
				else if ($multi_auth==9)					   {   $wpa_hostapd    = 0; } /*802.1x, disable wpa*/
				
				// open hostapd0x.conf                                     
				fwrite($hostapd_conf,
							"interface=ath".$index."\n".
							"bridge=br0\n".
	                    		"logger_syslog=-1\n".
	                    		"logger_syslog_level=2\n".
	                    		"logger_stdout=-1\n".
								"logger_stdout_level=2\n".
								"ctrl_interface=/var/run/hostapd\n".
								"ctrl_interface_group=0\n".
								"ssid=".$multi_ssid."\n".
					"max_num_sta=255\n".
					"macaddr_acl=0\n".
					"auth_algs=1\n".
					"ignore_broadcast_ssid=0\n".
					"wme_enabled=0\n".
					"eapol_version=2\n".
					"eapol_key_index_workaround=0\n".
					//"wps_disable=1\n".
					//"wps_upnp_disable=1\n".
	                    		"wpa=".$wpa_hostapd."\n");	        
				
				if($multi_auth!=9) {
					// Group key rekey interval 
					if ($multi_interval!="")	{ fwrite2($hostapd_conf, "wpa_group_rekey=".$multi_interval."\n"); }
				
					// Cipher mode 
					if		($multi_cipher==2)	{ fwrite2($hostapd_conf, "wpa_pairwise=TKIP\n"); }
					else if	($multi_cipher==3)	{ fwrite2($hostapd_conf, "wpa_pairwise=CCMP\n"); }
					else if	($multi_cipher==4)	{ fwrite2($hostapd_conf, "wpa_pairwise=TKIP CCMP\n"); }
				}

				/* 1. psk*/
				if ($multi_auth==3 || /*wpa-psk*/ 
					$multi_auth==5 || /*wpa2-psk*/  
					$multi_auth==7)   /*wpa-auto-psk*/  
				{
				// Passphrase //
				if(query("/wlan/inf:1/multi/index:".$index."/autorekey/enable")==1 && query("/runtime".$multi_ssid_path."/index:".$index."/passphrase")!="")//add for autorekey by log luo
                                {
                                        $multi_passphrase = query("/runtime".$multi_ssid_path."/index:".$index."/passphrase");
                                }
                                else if(query($multi_ssid_path."/index:".$index."/passphrase")!="")
                                {
				$multi_passphrase = query($multi_ssid_path."/index:".$index."/passphrase");
                                }
                                else
                                {
                                        $multi_passphrase = 00000000;
                                }
				fwrite2($hostapd_conf,
									"ieee8021x=0\n".
					"wpa_key_mgmt=WPA-PSK\n");

                                if(query("/wlan/inf:1/multi/index:".$index."/autorekey/enable")==1)    {$wpapskformat = 1;}
                                else	{$wpapskformat   = query($multi_ssid_path."/index:".$index."/passphraseformat");}
                                if ($wpapskformat == 1)  {fwrite2($hostapd_conf, "wpa_passphrase=".$multi_passphrase."\n");}
                                else                     {fwrite2($hostapd_conf, "wpa_psk=".$multi_passphrase."\n");}

				}
				/* 2. eap*/
				else if ($multi_auth==2 || /*wpa-eap*/ 
					$multi_auth==4 || /*wpa2-eap*/  
					$multi_auth==6 || /*wpa-auto-eap*/ 
					$multi_auth==9)   /*802.1x only*/ 
				{                          
                                        $ip_mode = query("/wan/rg/inf:1/mode");     /* 1:static, 2:dynamic */
                                        $own_dynamic_ip_addr        = query("/runtime/wan/inf:1/ip");
                                        $own_static_ip_addr = query("/wan/rg/inf:1/static/ip");
                                        if($ip_mode==2 && $own_dynamic_ip_addr!="")
                                        {
                                            $own_ip_addr    = $own_dynamic_ip_addr;
                                        } else {
                                            $own_ip_addr    = $own_static_ip_addr;
                                        }
					if($multi_embradius_state == 1)/*   **** radius is on board******************** */
					{
						if ($multi_auth!=9) {
	                        			fwrite2($hostapd_conf, "wpa_key_mgmt=WPA-EAP\n");
	                    			}
						fwrite2($hostapd_conf, "ieee8021x=1\n");	
						if($EmRadiusCertState == 1){	/* 0:Use Default Root CA Cert, 1:Use External Server Cert */
							fwrite2($hostapd_conf,	"server_cert=".$EmRadiusE_SrvCert."\n");
							fwrite2($hostapd_conf,	"private_key=".$EmRadiusE_SrvKey."\n");
							fwrite2($hostapd_conf,	"private_key_passwd=".$EmRadiusE_Srv_KeyPass."\n");
						}
						else	/* 0: Use Default Root CA */
						{
							fwrite2($hostapd_conf,	"server_cert=".$EmRadiusDefaultCA."\n");
							fwrite2($hostapd_conf,	"private_key=".$EmRadiusDefaultCAKey."\n");
							fwrite2($hostapd_conf,	"private_key_passwd=".$EmRadiusDefaultCAPass."\n");
						}
						fwrite2($hostapd_conf, "eap_server=1\n");
						fwrite2($hostapd_conf, "eap_user_file=".$EmRadiusEAPUser_conf."\n");
						$VLAN_state = query("/sys/vlan_state");
						$NAP_enable	= query("/sys/vlan_mode");
						if ($VLAN_state == 1 && $NAP_enable!="")	{ fwrite2($hostapd_conf, "nap_enable=".$NAP_enable."\n");}//??

					   /* accounting server   Jack add  multi_ssid 12/03/07 */	                         
						/*accounting is for external radius,william_200912*/
//			        	if (query($multi_ssid_path."/index:".$index."/acct_state")==1) /* accounting enable */
//			        	{
//			            	fwrite2($hostapd_conf,
//			           		//"own_ip_addr=".query("/wan/rg/inf:1/static/ip")."\n".
//						"own_ip_addr=".$own_ip_addr."\n".
//			           		"nas_identifier=".query("/runtime/layout/lanmac")."\n".
//			           		"acct_server_addr=".query($multi_ssid_path."/index:".$index."/acct_server")."\n".
//			           		"acct_server_port=".query($multi_ssid_path."/index:".$index."/acct_port")."\n".
//			           		"acct_server_shared_secret=".query($multi_ssid_path."/index:".$index."/acct_secret")."\n");
			                   		
//						$multi_b_acct_server = query($multi_ssid_path."/index:".$index."/b_acct_server");
//		            	    $multi_b_acct_port = query($multi_ssid_path."/index:".$index."/b_acct_port");
//		                	$multi_b_acct_secret = query($multi_ssid_path."/index:".$index."/b_acct_secret"); 
//			            	if($multi_b_acct_server!="" && $multi_b_acct_port!="" && $multi_b_acct_secret!="")
//			            	{
//								fwrite2($hostapd_conf,
//								"acct_server_addr=".$multi_b_acct_server."\n".
//								"acct_server_port=".$multi_b_acct_port."\n".
//								"acct_server_shared_secret=".$multi_b_acct_secret."\n");                   		
//			             	}
//			            }
			            /* Disable EAP reauthentication */
						fwrite2($hostapd_conf, "eap_reauth_period=0\n");

						/* Radius Retry Primary Interval */
						fwrite2($hostapd_conf, "radius_retry_primary_interval=600\n");

						/* 802.1x support for dynamic WEP keying */
						if ($multi_auth == 9)
						{
							$DyWepKeyLen	= query($multi_ssid_path."/index:".$index."/d_wepkeylen");
							$DyWepRKeyInt	= query($multi_ssid_path."/index:".$index."/d_wep_rekey_interval");
						/* Key lengths , 5 = 64-bit , 13 = 128-bit (default: 128-bit) */
							if ($DyWepKeyLen == 64)
							{
								fwrite2($hostapd_conf, "wep_key_len_unicast=5\n");
								fwrite2($hostapd_conf, "wep_key_len_broadcast=5\n");
							}
							else/* $DyWepKeyLen == 128 */
							{
								fwrite2($hostapd_conf, "wep_key_len_unicast=13\n");
								fwrite2($hostapd_conf, "wep_key_len_broadcast=13\n");
							}

							/* Rekeying period in seconds (default:300 secs) */
							if ($DyWepRKeyInt != "")	{	fwrite2($hostapd_conf, "wep_rekey_period=".$DyWepRKeyInt."\n");	}
						}
					}//end of radius on board.....

					else	/*   **** radius server is outside******************** */
					{
	                    if ($multi_auth!=9) {
	                        fwrite2($hostapd_conf, "wpa_key_mgmt=WPA-EAP\n");
	                    }
						$multi_radius_server = query($multi_ssid_path."/index:".$index."/radius_server");
						$multi_radius_port = query($multi_ssid_path."/index:".$index."/radius_port");
						$multi_radius_secret = query($multi_ssid_path."/index:".$index."/radius_secret");
						/* RADIUS settings */
						fwrite2($hostapd_conf,
						"ieee8021x=1\n".
						"auth_server_addr=".$multi_radius_server."\n".
						"auth_server_port=".$multi_radius_port."\n".
						"auth_server_shared_secret=".$multi_radius_secret."\n"); 
					
						$multi_b_radius_server = query($multi_ssid_path."/index:".$index."/b_radius_server");
						$multi_b_radius_port = query($multi_ssid_path."/index:".$index."/b_radius_port");
						$multi_b_radius_secret = query($multi_ssid_path."/index:".$index."/b_radius_secret"); 
					
						if($multi_b_radius_server!="" && $multi_b_radius_port!="" && $multi_b_radius_secret!="")
						{
							fwrite2($hostapd_conf,
								"auth_server_addr=".$multi_b_radius_server."\n".
								"auth_server_port=".$multi_b_radius_port."\n".
								"auth_server_shared_secret=".$multi_b_radius_secret."\n");
						}
					
						/* NAP Support */
						$VLAN_state = query("/sys/vlan_state");
						$NAP_enable	= query("/sys/vlan_mode");
						if ($VLAN_state == 1 && $NAP_enable!="")	{ fwrite2($hostapd_conf, "nap_enable=".$NAP_enable."\n");}
					
						/* accounting server   Jack add  multi_ssid 12/03/07 */	                         
						if (query($multi_ssid_path."/index:".$index."/acct_state")==1) /* accounting enable */
						{
							fwrite2($hostapd_conf,
								//"own_ip_addr=".query("/wan/rg/inf:1/static/ip")."\n".
								"own_ip_addr=".$own_ip_addr."\n".
								"nas_identifier=".query("/runtime/layout/lanmac")."\n".
								"acct_server_addr=".query($multi_ssid_path."/index:".$index."/acct_server")."\n".
								"acct_server_port=".query($multi_ssid_path."/index:".$index."/acct_port")."\n".
								"acct_server_shared_secret=".query($multi_ssid_path."/index:".$index."/acct_secret")."\n");
							$multi_b_acct_server = query($multi_ssid_path."/index:".$index."/b_acct_server");
							$multi_b_acct_port = query($multi_ssid_path."/index:".$index."/b_acct_port");
							$multi_b_acct_secret = query($multi_ssid_path."/index:".$index."/b_acct_secret"); 
						
							if($multi_b_acct_server!="" && $multi_b_acct_port!="" && $multi_b_acct_secret!="")
							{
								fwrite2($hostapd_conf,
									"acct_server_addr=".$multi_b_acct_server."\n".
									"acct_server_port=".$multi_b_acct_port."\n".
									"acct_server_shared_secret=".$multi_b_acct_secret."\n");			                    		
							}
						}
						/* Disable EAP reauthentication */
						fwrite2($hostapd_conf, "eap_reauth_period=0\n");
					
						/* Radius Retry Primary Interval */
						fwrite2($hostapd_conf, "radius_retry_primary_interval=600\n");

						/* 802.1x support for dynamic WEP keying */
						if ($multi_auth == 9)
						{
							$DyWepKeyLen	= query($multi_ssid_path."/index:".$index."/d_wepkeylen");
							$DyWepRKeyInt	= query($multi_ssid_path."/index:".$index."/d_wep_rekey_interval");

							/* Key lengths , 5 = 64-bit , 13 = 128-bit (default: 128-bit) */
							if ($DyWepKeyLen == 64)
							{
								fwrite2($hostapd_conf, "wep_key_len_unicast=5\n");
								fwrite2($hostapd_conf, "wep_key_len_broadcast=5\n");
							}
							else/* $DyWepKeyLen == 128 */
							{
								fwrite2($hostapd_conf, "wep_key_len_unicast=13\n");
								fwrite2($hostapd_conf, "wep_key_len_broadcast=13\n");
							}

							/* Rekeying period in seconds (default:300 secs) */
							if ($DyWepRKeyInt != "")	{	fwrite2($hostapd_conf, "wep_rekey_period=".$DyWepRKeyInt."\n");	}
						}
					}    		
				}
			} // end of wpa-psk and eap                                
			else if ($multi_auth==1 || $multi_auth==0 )  /*shared key or open*/  
			{                    
				echo "iwpriv ath".$index." authmode 1\n"; // set auth as open system first.                
				/*   Jack modify 24/04/07 multi_ssid_temp_soluation_no_shared_key*/
				if ($multi_auth==1) 
				{ 
					echo "iwpriv ath".$index." authmode 2\n";
				} 
				if ($multi_cipher==1)
				{            
					$multi_key_index = query($multi_ssid_path."/index:".$index."/wep_key_index"); 
					$wep_key_path = $WLAN."/wepkey:".$multi_key_index;
					$multi_keylength	= query($wep_key_path."/keylength");
					$multi_keyformat	= query($wep_key_path."/keyformat");
					//$multi_key =          query($wep_key_path); 
					/*
					*	For ASCII string:
					*		iwconfig ath0 key s:"ascii" [1]
					*	For Hex string:
					*		iwconfig ath0 key "1234567890" [1]
					*/
					if ($multi_keyformat==1)	
					{ 
						$iw_keystring="s:\"".get("s",$wep_key_path)."\" [".$multi_key_index."]"; 
					//$iw_keystring="s:\'".$multi_key."\' [".$multi_key_index."]";
					}
					else
					{ 
						$multi_key = query($wep_key_path); 
						$iw_keystring=$multi_key." [".$multi_key_index."]";
					}
					echo "iwconfig ath".$index." key ".$iw_keystring."\n"; 
				}	
			}
			/*security ---*/
			
			
			/* IGMP Snooping dennis 2008-01-29 start*/
			if ($igmpsnoop == 1){
				echo "echo enable > /proc/net/br_igmp_ap_br0\n";
				echo "echo setwl ath".$index." > /proc/net/br_igmp_ap_br0\n";
				echo "ap_igmp &> /dev/console\n";				
				echo "echo $! > /var/run/ap_igmp_".$index.".pid\n";
			}
			if ($igmpsnoop == 1){ //echo "brctl igmp_snooping br0 ".$igmpsnoop."\n"; 
				$Brctl_igmp_g = 1;   }
			else {  //echo "brctl igmp_snooping br0 0\n"; 
				$Brctl_igmp_g = 0;    }
			/* IGMP Snooping dennis 2008-01-29 end */
		
		
		}  // end of if ($multi_ind_state==1)  
	}  // end of for ($multi_ssid_path."/index")
} // end of ($multi_total_state == 1)


//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~WDS:demon up~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
if ($wlan_ap_operate_mode==3 || $wlan_ap_operate_mode==4)
{

	$index=7;
	$index_mac=0;
	$up_ath_cnt=0;	
   
	for ($WLAN."/wds/list/index")
	{   
		$index++;     
		$index_mac++;   
		$wds_mac = query($WLAN."/wds/list/index:".$index_mac."/mac");  
		if ($wds_mac!="")  
		{   
			$up_ath_cnt++;    
			
			echo "\necho UP WDS-demon ath".$index."... > /dev/console\n";
			
			/* Jack add 13/02/08 +++ wlxmlpatch_v2*/
			//         $index_tmp = $index-7;
			$wlxmlpatch_pid	= "/var/run/wlxmlpatch".$index.".pid";
			echo "wlxmlpatch -S ath".$index." /runtime/stats/wlan/inf:1/wds:".$index_mac." RADIO_SSID1_ON RADIO_SSID1_BLINK MADWIFI > /dev/console &\n";
			echo "echo $! > ".$wlxmlpatch_pid."\n";
			/* Jack add 13/02/08 --- wlxmlpatch_v2*/
			
			/* IGMP Snooping dennis 2008-01-29 start*/
			if($withoutap == 0 ){//wds with ap mode
				if ($igmpsnoop == "1"){
					echo "echo enable > /proc/net/br_igmp_ap_br0\n";
					echo "echo setwl ath".$index." > /proc/net/br_igmp_ap_br0\n";
					echo "ap_igmp &> /dev/console\n";					
					echo "echo $! > /var/run/ap_igmp_".$index.".pid\n";
				}
				if ($igmpsnoop=="1"){ //echo "brctl igmp_snooping br0 ".$igmpsnoop."\n";
					$Brctl_igmp_g = 1;    }
				else {  //echo "brctl igmp_snooping br0 0\n";  
					$Brctl_igmp_g = 0;   }
			}	
			/* IGMP Snooping dennis 2008-01-29 end */
			if($withoutap == 1 ){
				//echo "brctl igmp_snooping br0 0\n";  
				$Brctl_igmp_g = 0;   
			}
			/* IGMP Snooping dennis 2008-01-29 end */            
			
			if ($auth_mode==1 || $auth_mode==0 )  /*shared key or open*/  
			{     
			             			 
			}else if ($auth_mode==3 || /*wpa-psk*/ 
				$auth_mode==5 || /*wpa2-psk*/  
				$auth_mode==7 || /*wpa-auto-psk*/     
				$auth_mode==2 || /*wpa-eap*/ 
				$auth_mode==4 || /*wpa2-eap*/  
				$auth_mode==6) /*wpa-auto-eap*/           
			{           
			
                        	$wpa_supplicant_conf = "/var/run/wpa_supplicant".$index.".conf_wds";
                        	$hostapd_conf	= "/var/run/hostapd".$index.".conf_wds";
				echo "HorS=\`rstrcmp -s ".$wlanmac_g." -S ".$wds_mac."`\n";
				echo "if [ $HorS -ge 0 ] ; then\n";
				echo "xmldbc -s ".$WLAN."/wds/list/index:".$index_mac."/cmp 1\n";
				echo "rm -f ".$wpa_supplicant_conf."\n";
				echo "else\n";
				echo "xmldbc -s ".$WLAN."/wds/list/index:".$index_mac."/cmp 0\n";
				echo "rm -f ".$hostapd_conf."\n";
				echo "fi\n";
				echo "sleep 1\n";
			
			} // end of wpa-psk and eap 
		
		}// if ($wds_mac!="")
	
	}//  for ($WLAN."/wds/list/index")


} //      if ($wlan_ap_operate_mode==3 || $wlan_ap_operate_mode==4)  




//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~APMODE:demon  up~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~  
echo "wlxmlpatch -S ".$wlanif_g." /runtime/stats/wlan/inf:1 RADIO_SSID1_ON RADIO_SSID1_BLINK MADWIFI > /dev/console &\n";
echo "echo $! > ".$wlxmlpatch_ap_pid."\n";


if ($wlan_ap_operate_mode != 1) // if not APC mode
{
	$auth_mode = query("/wlan/inf:1/authentication");
	if ($auth_mode>1){  
		echo $IWCONF." essid \"".get("s",$WLAN."/ssid")."\"\n";
		require($template_root."/__auth_hostapd_g.php"); 
	}
	else {
		/*keep ori space */
	}
}
echo "sleep 5\n";
/*	echo "iwlist ath0 compare\n";	*/

	if($txpower == 2){
		echo "iwpriv wifi0 tpscale 1\n";
	}
	else if($txpower == 3){
		echo "iwpriv wifi0 tpscale 2\n";
	}
	else if($txpower == 4){
		echo "iwpriv wifi0 tpscale 3\n";
	}
	else {
		echo "iwpriv wifi0 tpscale 0\n";
	}

/* IGMP Snooping dennis 2008-01-29 start*/
if ($igmpsnoop == 1 && $wlan_ap_operate_mode != 1){
	//echo "brctl igmp_snooping br0 1\n";
	$Brctl_igmp_g = 1;
	echo "echo enable > /proc/net/br_igmp_ap_br0\n";
	echo "echo setwl ath0 > /proc/net/br_igmp_ap_br0\n";
	echo "ap_igmp &> /dev/console\n";	
	echo "echo $! > ".$ap_igmp_pid."\n";
}
else{
	//echo "brctl igmp_snooping br0 0\n";
	$Brctl_igmp_g = 0;
}
    /* IGMP Snooping dennis 2008-01-29 end */
	
	 echo "iwpriv ath0 apband 0\n"; //show ap band in wireless driver */
?>
