#!/bin/sh
echo [$0] ... > /dev/console
<? /* vi: set sw=4 ts=4: */

$WLAN_g = "/wlan/inf:1";  // b, g, n band
$WLAN_a = "/wlan/inf:2";	//a,n band

require("/etc/templates/troot.php");

if ($generate_start==1)
{

	$ip_mode = query("/wan/rg/inf:1/mode"); /* 1:static, 2:dynamic */
	$own_dynamic_ip_addr    = query("/runtime/wan/inf:1/ip");
	$own_dynamic_ip_netmask    = query("/runtime/wan/inf:1/netmask");
	$own_static_ip_addr     = query("/wan/rg/inf:1/static/ip");
	$own_static_ip_netmask     = query("/wan/rg/inf:1/static/netmask");
	if($ip_mode==2 && $own_dynamic_ip_addr!="")
	{
		$own_ip_addr    = $own_dynamic_ip_addr;
		$own_ip_netmask    = $own_dynamic_ip_netmask;
	} else {
		$own_ip_addr    = $own_static_ip_addr;
		$own_ip_netmask    = $own_static_ip_netmask;
	}

	$asu_ip = query("/sys/wapi/ext_cert/asu_ip");
	$asu_port = query("/sys/wapi/ext_cert/asu_port");
	$cert_name = query("/sys/wapi/certname");
	$asu_cert_name = query("/sys/wapi/asucertname");
	$as_cert_name = query("/sys/wapi/ascertname");
	$cert_index = query("/sys/wapi/cert_index");
	$cert_status = query("/sys/wapi/cert_status");
	$cert_mode = query("/sys/wapi/cert_mode");

	$WAPI_CONF = "/var/run/wapi.conf";
	$WAPI_pid = "/var/run/wapid.pid";
	$wapi_run = 0;
	$wpa_supplicant_index = 0;

	fwrite($WAPI_CONF, "debug=0\n");
	fwrite2($WAPI_CONF, "CERT_NAME=/var/etc/wapi/".$cert_name."\n");
	fwrite2($WAPI_CONF, "ASU_CERT_NAME=/var/etc/wapi/".$asu_cert_name."\n");
	fwrite2($WAPI_CONF, "CA_CERT_NAME=/var/etc/wapi/".$as_cert_name."\n");
	fwrite2($WAPI_CONF, "CERT_INDEX=".$cert_index."\n");
	fwrite2($WAPI_CONF, "CERT_MODE=".$cert_mode."\n");
	fwrite2($WAPI_CONF, "CERT_STATUS=".$cert_status."\n");
	fwrite2($WAPI_CONF, "ASU_IP=".$asu_ip."\n");
	fwrite2($WAPI_CONF, "ASU_PORT=".$asu_port."\n");
	fwrite2($WAPI_CONF, "[WLAN_BEGIN]\n");

	$RADIO_CONF	= "/var/run/radio.conf";
	$wpa2_run = 0;
	$wpa2_apc_run = 0;


	//start, xmlnode for wapi
	$WAPI_SLOT="/wapi/slot:";
	//end, xmlnode for wapi

	/*-----------------------wlan_g start add by log_luo-------------------------*/
	if (query($WLAN_g."/enable")==1)
	{
		$WLAN=$WLAN_g;
		$auth_mode = query($WLAN."/authentication");

		$wlanif = query("/runtime/layout/wlanif_g");
		$multi_ssid_path = $WLAN."/multi";
		$multi_total_state = query($multi_ssid_path."/state");  
		$ap_mode	= query($WLAN."/ap_mode");  // "1" #1--->a   0---> b,g,n
		$wlan_ap_operate_mode = query($WLAN."/ap_mode");

		$wapi_psk_type  = query($WLAN."/wapi/psk_type");
		$wapi_password  = query($WLAN."/wapi/password");
		$usk_rekey_period       = query($WLAN."/wapi/usk_rekey_period");
		$msk_rekey_period       = query($WLAN."/wapi/msk_rekey_period");
		if ($wlan_ap_operate_mode != 1)
		{		
			$TOPOLOGY_CONF  = "/var/run/conf_filename";
			if ($auth_mode==3 || /*wpa-psk*/
					$auth_mode==5 || /*wpa2-psk*/
					$auth_mode==7 || /*wpa-auto-psk*/
					$auth_mode==2 || /*wpa-eap*/
					$auth_mode==4 || /*wpa2-eap*/
					$auth_mode==6 || /*wpa-auto-eap*/
					$auth_mode==9) /*802.1x*/
			{
				$HAPD_conf	= "/var/run/hostapd.".$wlanif.".ap_bss";	
				fwrite2($TOPOLOGY_CONF, " ".$HAPD_conf." ");
				$wpa2_run = 1;
			}
			else if ($auth_mode==10 || $auth_mode==11)
			{
				echo "xmldbc -is ".$WAPI_SLOT."1/config/AuthenticationSuiteEnabled 1\n";
				echo "xmldbc -is ".$WAPI_SLOT."1/config/UnicastRekeyTime ".$usk_rekey_period."\n";
				echo "xmldbc -is ".$WAPI_SLOT."1/config/MulticastRekeyTime ".$msk_rekey_period."\n";
				if ($wapi_password == "")
				{
					$wapi_password = "00000000";
				}
				if ($auth_mode==10)
				{
					fwrite2($WAPI_CONF, $wlanif." 7 ".$wapi_psk_type." ".$wapi_password." ".$usk_rekey_period." ".$msk_rekey_period."\n");
					$wapi_run = 1;
					//start, xmlnode for wapi
					echo "xmldbc -is ".$WAPI_SLOT."1/config/AuthenticationSuite 00-14-72-02\n";
					//end, xmlnode for wapi
				}
				else if ($auth_mode==11)
				{
					fwrite2($WAPI_CONF, $wlanif." 11 ".$wapi_psk_type." ".$wapi_password." ".$usk_rekey_period." ".$msk_rekey_period."\n");
					$wapi_run = 1;
					//start, xmlnode for wapi
					echo "xmldbc -is ".$WAPI_SLOT."1/config/AuthenticationSuite 00-14-72-01\n";
					//end, xmlnode for wapi
				}
			}
			/**----------------------Multi start add by log_luo----------------------*/	
			if ($multi_total_state == 1)
			{
				$index=0; 
				//$index1=0;
				for ($multi_ssid_path."/index")
				{      
					$index++; 
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

					$multi_ind_state = query($multi_ssid_path."/index:".$index."/state");  
					if ($multi_ind_state==1 && $multi_ind_schedule_state == 1)  
					{ 
						$multi_auth = query($multi_ssid_path."/index:".$index."/auth");
						if ($multi_auth==3 || /*wpa-psk*/
								$multi_auth==5 || /*wpa2-psk*/
								$multi_auth==7 || /*wpa-auto-psk*/
								$multi_auth==2 || /*wpa-eap*/
								$multi_auth==4 || /*wpa2-eap*/
								$multi_auth==6 || /*wpa-auto-eap*/
								$multi_auth==9) /*802.1x*/
						{
							$hostapd_conf	= "/var/run/hostapd0".$index.".ap_bss";
							fwrite2($TOPOLOGY_CONF, " ".$hostapd_conf." ");
							$wpa2_run = 1;
						}
						else if ($multi_auth==10 || $multi_auth==11)
						{
							$wapi_psk_type  = query($multi_ssid_path."/index:".$index."/wapi/psk_type");
							$wapi_password  = query($multi_ssid_path."/index:".$index."/wapi/password");
							$usk_rekey_period       = query($multi_ssid_path."/index:".$index."/wapi/usk_rekey_period");
							$msk_rekey_period       = query($multi_ssid_path."/index:".$index."/wapi/msk_rekey_period");
							if ($wapi_password == "")
							{
								$wapi_password = "00000000";
							}

							echo "xmldbc -is ".$WAPI_SLOT.$index."/config/AuthenticationSuiteEnabled 1\n";
							echo "xmldbc -is ".$WAPI_SLOT.$index."/config/UnicastRekeyTime ".$usk_rekey_period."\n";
							echo "xmldbc -is ".$WAPI_SLOT.$index."/config/MulticastRekeyTime ".$msk_rekey_period."\n";
							/*security +++*/
							if($multi_auth==10)
							{
								fwrite2($WAPI_CONF, "ath".$index." 7 ".$wapi_psk_type." ".$wapi_password." ".$usk_rekey_period." ".$msk_rekey_period."\n");
								$wapi_run = 1;
								//start, xmlnode for wapi
								echo "xmldbc -is ".$WAPI_SLOT.$index."/config/AuthenticationSuite 00-14-72-02\n";
								//end, xmlnode for wapi
							}
							else if($multi_auth==11)
							{
								fwrite2($WAPI_CONF, "ath".$index." 11 ".$wapi_psk_type." ".$wapi_password." ".$usk_rekey_period." ".$msk_rekey_period."\n");
								$wapi_run = 1;
								//start, xmlnode for wapi
								echo "xmldbc -is ".$WAPI_SLOT.$index."/config/AuthenticationSuite 00-14-72-01\n";
								//end, xmlnode for wapi
							}
						}//End multi_auth==10 or $multi_auth==11
					}//End multi_ind_state==1 and $multi_ind_schedule_state
				}//End multi_ssid_path
			}
			/**----------------------Multi end add by log_luo----------------------*/
		}
		else
		{
			$TOPOLOGY_CONF = "/var/run/sta_conf_filename";
			if ($auth_mode==3 || /*wpa-psk*/
					$auth_mode==5 || /*wpa2-psk*/
					$auth_mode==7 || /*wpa-auto-psk*/
					$auth_mode==2 || /*wpa-eap*/
					$auth_mode==4 || /*wpa2-eap*/
					$auth_mode==6 || /*wpa-auto-eap*/
					$auth_mode==9) /*802.1x*/
			{
				$wpa_supplicant_conf 	= "/var/run/wpa_supplicant.".$wlanif.".conf";
				fwrite2($TOPOLOGY_CONF, " -c".$wpa_supplicant_conf." -i".$wlanif." -bbr0 ");
				//fwrite2($TOPOLOGY_CONF, "-c/var/run/wpa_supplicant.ath1.conf -iath1 -bbr0");

				$wpa2_apc_run = 1;
			}
		}
		if ($wlan_ap_operate_mode == 2)
		{
			$TOPOLOGY_CONF = "/var/run/sta_conf_filename";
			if ($auth_mode==3 || /*wpa-psk*/
					$auth_mode==5 || /*wpa2-psk*/
					$auth_mode==7 || /*wpa-auto-psk*/
					$auth_mode==2 || /*wpa-eap*/
					$auth_mode==4 || /*wpa2-eap*/
					$auth_mode==6 || /*wpa-auto-eap*/
					$auth_mode==9) /*802.1x*/
			{
				$wpa_supplicant_conf 	= "/var/run/wpa_supplicant.ath1.conf";
				//fwrite2($TOPOLOGY_CONF, " -c".$wpa_supplicant_conf." -i"ath1" -bbr0 ");
				fwrite2($TOPOLOGY_CONF, "-c/var/run/wpa_supplicant.ath1.conf -iath1 -bbr0");

				$wpa2_apc_run = 1;
			}
		}
		//if($wlan_ap_operate_mode == 2)
		//{
		//	if ($auth_mode==3 || /*wpa-psk*/
		//			$auth_mode==5 || /*wpa2-psk*/
		//			$auth_mode==7 || /*wpa-auto-psk*/
		//			$auth_mode==2 || /*wpa-eap*/
		//			$auth_mode==4 || /*wpa2-eap*/
		//			$auth_mode==6 || /*wpa-auto-eap*/
		//			$auth_mode==9) /*802.1x*/
		//	{
		//		$wpa_supplicant_conf 	= "/var/run/wpa_supplicant.ath1.sta";
		//		fwrite2($TOPOLOGY_CONF, "interface ath1\n");

		//		fwrite2($RADIO_CONF, "sta ath1\n{\n");
		//		fwrite2($RADIO_CONF, "bridge br0\n");
		//		fwrite2($RADIO_CONF, "driver atheros\n");
		//		fwrite2($RADIO_CONF, "config ".$wpa_supplicant_conf."\n}\n");
		//		$wpa2_apc_run = 1;
		//	}
		//}
		if ($wlan_ap_operate_mode==3 || $wlan_ap_operate_mode==4)
		{		
			$index=7;
			$index_mac=0;
			for ($WLAN."/wds/list/index")
			{   
				$index++;     
				$index_mac++;   
				$wds_mac = query($WLAN."/wds/list/index:".$index_mac."/mac");  
				if ($wds_mac!="")  
				{   
					if ($auth_mode==3 || /*wpa-psk*/
							$auth_mode==5 || /*wpa2-psk*/
							$auth_mode==7 || /*wpa-auto-psk*/
							$auth_mode==2 || /*wpa-eap*/
							$auth_mode==4 || /*wpa2-eap*/
							$auth_mode==6) /*wpa-auto-eap*/
					{
						$wpa_supplicant_conf = "/var/run/wpa_supplicant".$index.".conf_wds";
						$hostapd_conf	= "/var/run/hostapd".$index.".conf_wds";

						if (query($WLAN."/wds/list/index:".$index_mac."/cmp")==1)
						{
							$TOPOLOGY_CONF  = "/var/run/conf_filename";					
							fwrite2($TOPOLOGY_CONF, " ".$hostapd_conf." ");
							$wpa2_run = 1;
						}else
						{
							$TOPOLOGY_CONF = "/var/run/sta_conf_filename";
							if($wpa_supplicant_index==0){fwrite2($TOPOLOGY_CONF, " -c".$wpa_supplicant_conf." -iath".$index." -bbr0 ");$wpa_supplicant_index++;}
							else {fwrite2($TOPOLOGY_CONF, " -N -c".$wpa_supplicant_conf." -iath".$index." -bbr0 "); }
							$wpa2_apc_run = 1;
						}
					}

				}//if ($wds_mac!="")  	
			}//  for ($WLAN."/wds/list/index")		
		}   //($wlan_ap_operate_mode==3 || $wlan_ap_operate_mode==4)
	}
	/*-----------------------wlan_g end add by log_luo-------------------------*/

	/*-----------------------wlan_a start add by log_luo-------------------------*/
	if (query($WLAN_a."/enable")==1)
	{
		$WLAN=$WLAN_a;
		$auth_mode = query($WLAN."/authentication");


		$wlanif = query("/runtime/layout/wlanif_a");
		$multi_ssid_path = $WLAN."/multi";
		$multi_total_state = query($multi_ssid_path."/state");  
		$ap_mode	= query($WLAN."/ap_mode");  // "1" #1--->a   0---> b,g,n
		$wlan_ap_operate_mode = query($WLAN."/ap_mode");

		$wapi_psk_type  = query($WLAN."/wapi/psk_type");
		$wapi_password  = query($WLAN."/wapi/password");
		$usk_rekey_period       = query($WLAN."/wapi/usk_rekey_period");
		$msk_rekey_period       = query($WLAN."/wapi/msk_rekey_period");

		if ($wlan_ap_operate_mode != 1)
		{		
			$TOPOLOGY_CONF  = "/var/run/conf_filename";
			if ($auth_mode==3 || /*wpa-psk*/
					$auth_mode==5 || /*wpa2-psk*/
					$auth_mode==7 || /*wpa-auto-psk*/
					$auth_mode==2 || /*wpa-eap*/
					$auth_mode==4 || /*wpa2-eap*/
					$auth_mode==6 || /*wpa-auto-eap*/
					$auth_mode==9) /*802.1x*/
			{
				$HAPD_conf	= "/var/run/hostapd.".$wlanif.".ap_bss";	
				fwrite2($TOPOLOGY_CONF, " ".$HAPD_conf." ");
				$wpa2_run = 1;
			}
			else if ($auth_mode==10 || $auth_mode==11)
			{
				if ($wapi_password == "")
				{
					$wapi_password = "00000000";
				}
				echo "xmldbc -is ".$WAPI_SLOT."17/config/AuthenticationSuiteEnabled 1\n";
				echo "xmldbc -is ".$WAPI_SLOT."17/config/UnicastRekeyTime ".$usk_rekey_period."\n";
				echo "xmldbc -is ".$WAPI_SLOT."17/config/MulticastRekeyTime ".$msk_rekey_period."\n";
				if ($auth_mode==10)
				{
					fwrite2($WAPI_CONF, $wlanif." 7 ".$wapi_psk_type." ".$wapi_password." ".$usk_rekey_period." ".$msk_rekey_period."\n");
					$wapi_run = 1;
					//start, xmlnode for wapi
					echo "xmldbc -is ".$WAPI_SLOT."17/config/AuthenticationSuite 00-14-72-02\n";
					//end, xmlnode for wapi
				}
				else if ($auth_mode==11)
				{
					fwrite2($WAPI_CONF, $wlanif." 11 ".$wapi_psk_type." ".$wapi_password." ".$usk_rekey_period." ".$msk_rekey_period."\n");
					$wapi_run = 1;
					//start, xmlnode for wapi
					echo "xmldbc -is ".$WAPI_SLOT."17/config/AuthenticationSuite 00-14-72-01\n";
					//end, xmlnode for wapi
				}
			}
			/**----------------------Multi start add by log_luo----------------------*/	
			if ($multi_total_state == 1)
			{
				$index=0; 
				$index1=16;
				$index2=17; //xmlnode for wapi
				for ($multi_ssid_path."/index")
				{      
					$index++;
					$index1++; 
					$index2++;  //xmlnode for wapi
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

					$multi_ind_state = query($multi_ssid_path."/index:".$index."/state");  
					if ($multi_ind_state==1 && $multi_ind_schedule_state == 1)  
					{ 
						$multi_auth = query($multi_ssid_path."/index:".$index."/auth");
						if ($multi_auth==3 || /*wpa-psk*/
								$multi_auth==5 || /*wpa2-psk*/
								$multi_auth==7 || /*wpa-auto-psk*/
								$multi_auth==2 || /*wpa-eap*/
								$multi_auth==4 || /*wpa2-eap*/
								$multi_auth==6 || /*wpa-auto-eap*/
								$multi_auth==9) /*802.1x*/
						{
							$hostapd_conf	= "/var/run/hostapd0".$index1.".ap_bss";
							fwrite2($TOPOLOGY_CONF," ".$hostapd_conf." ");
							$wpa2_run = 1;
						}
						else if($multi_auth==10 || $multi_auth==11)
						{
							$wapi_psk_type  = query($multi_ssid_path."/index:".$index."/wapi/psk_type");
							$wapi_password  = query($multi_ssid_path."/index:".$index."/wapi/password");
							$usk_rekey_period       = query($multi_ssid_path."/index:".$index."/wapi/usk_rekey_period");
							$msk_rekey_period       = query($multi_ssid_path."/index:".$index."/wapi/msk_rekey_period");
							if ($wapi_password == "")
							{
								$wapi_password = "00000000";
							}
							echo "xmldbc -is ".$WAPI_SLOT.$index2."/config/AuthenticationSuiteEnabled 1\n";
							echo "xmldbc -is ".$WAPI_SLOT.$index2."/config/UnicastRekeyTime ".$usk_rekey_period."\n";
							echo "xmldbc -is ".$WAPI_SLOT.$index2."/config/MulticastRekeyTime ".$msk_rekey_period."\n";

							/*security +++*/
							if($multi_auth==10)
							{
								fwrite2($WAPI_CONF, "ath".$index1." 7 ".$wapi_psk_type." ".$wapi_password." ".$usk_rekey_period." ".$msk_rekey_period."\n");
								$wapi_run = 1;
								//start, xmlnode for wapi
								echo "xmldbc -is ".$WAPI_SLOT.$index2."/config/AuthenticationSuite 00-14-72-02\n";
								//end, xmlnode for wapi
							}
							else if($multi_auth==11)
							{
								fwrite2($WAPI_CONF, "ath".$index1." 11 ".$wapi_psk_type." ".$wapi_password." ".$usk_rekey_period." ".$msk_rekey_period."\n");
								$wapi_run = 1;
								//start, xmlnode for wapi
								echo "xmldbc -is ".$WAPI_SLOT.$index2."/config/AuthenticationSuite 00-14-72-01\n";
								//end, xmlnode for wapi
							}
						}//End multi_auth==10 or $multi_auth==11
					}//End multi_ind_state==1 and $multi_ind_schedule_state
				}//End multi_ssid_path
			}
			/**----------------------Multi end add by log_luo----------------------*/
		}
		else
		{
			$TOPOLOGY_CONF = "/var/run/sta_conf_filename";
			if ($auth_mode==3 || /*wpa-psk*/
					$auth_mode==5 || /*wpa2-psk*/
					$auth_mode==7 || /*wpa-auto-psk*/
					$auth_mode==2 || /*wpa-eap*/
					$auth_mode==4 || /*wpa2-eap*/
					$auth_mode==6 || /*wpa-auto-eap*/
					$auth_mode==9) /*802.1x*/
			{
				$wpa_supplicant_conf 	= "/var/run/wpa_supplicant.".$wlanif.".conf";
				fwrite2($TOPOLOGY_CONF, " -c".$wpa_supplicant_conf." -i".$wlanif." -bbr0 ");
				$wpa2_apc_run = 1;
			}
		}

		if ($wlan_ap_operate_mode==3 || $wlan_ap_operate_mode==4)
		{		
			$index=23;
			$index_mac=0;	
			for ($WLAN."/wds/list/index")
			{   
				$index++;     
				$index_mac++;   
				$wds_mac = query($WLAN."/wds/list/index:".$index_mac."/mac");  
				if ($wds_mac!="")  
				{   
					if ($auth_mode==3 || /*wpa-psk*/
							$auth_mode==5 || /*wpa2-psk*/
							$auth_mode==7 || /*wpa-auto-psk*/
							$auth_mode==2 || /*wpa-eap*/
							$auth_mode==4 || /*wpa2-eap*/
							$auth_mode==6) /*wpa-auto-eap*/
					{
						$wpa_supplicant_conf = "/var/run/wpa_supplicant".$index.".conf_wds";
						$hostapd_conf	= "/var/run/hostapd".$index.".conf_wds";
						if (query($WLAN."/wds/list/index:".$index_mac."/cmp")==1)
						{
							$TOPOLOGY_CONF  = "/var/run/conf_filename";
							fwrite2($TOPOLOGY_CONF, " ".$hostapd_conf." ");
							$wpa2_run = 1;
						}else
						{
							$TOPOLOGY_CONF = "/var/run/sta_conf_filename";
							if($wpa_supplicant_index==0) {fwrite2($TOPOLOGY_CONF, " -c".$wpa_supplicant_conf." -iath".$index." -bbr0 ");$wpa_supplicant_index++;}
							else {fwrite2($TOPOLOGY_CONF, " -N -c".$wpa_supplicant_conf." -iath".$index." -bbr0 ");}
							$wpa2_apc_run = 1;
						}
					}

				}//if ($wds_mac!="")  	
			}//  for ($WLAN."/wds/list/index")		
		}   //($wlan_ap_operate_mode==3 || $wlan_ap_operate_mode==4)
	}
	/*-----------------------wlan_a end add by log_luo-------------------------*/
	fwrite2($WAPI_CONF, "[WLAN_END]\n");
	if ($wapi_run==1)
	{
		echo "echo Start wapid ... > /dev/console\n";
		echo "wapid -c ".$WAPI_CONF." &\n";
		echo "echo $! > ".$WAPI_pid."\n";
		echo "sleep 1\n";
		//      echo "sh /etc/templates/wapi.sh start > /dev/console &\n";
	}
	if ($wpa2_run==1)
	{
		echo "echo Start hostapd ... > /dev/console\n";
		echo "hostapd -B `cat /var/run/conf_filename` -e /var/run/123 &\n";
		echo "sleep 1\n";
	}
	if ($wpa2_apc_run==1)
	{
		echo "echo Start wpa_supplicant ... > /dev/console\n";
		echo "wpa_supplicant `cat /var/run/sta_conf_filename`\n";
		echo "sleep 1\n";
	}
}
else // end generate_start==1
{
//start, xmlnode for wapi add by log_luo 20100702
/**---------------------------2.4G-------------------------*/
$WLAN=$WLAN_g;
$multi_ssid_path = $WLAN."/multi";
$multi_total_state = query($multi_ssid_path."/state"); 
$wlan_ap_operate_mode = query($WLAN."/ap_mode");
$WAPI_SLOT="/wapi/slot:";

if ($wlan_ap_operate_mode != 1)
{
	echo "xmldbc -is ".$WAPI_SLOT."1/config/AuthenticationSuiteEnabled 0\n";
	echo "xmldbc -is ".$WAPI_SLOT."1/config/AuthenticationSuite 00-00-00-00\n";
	if ($multi_total_state == 1)
	{
		$index=1;
		for ($multi_ssid_path."/index")
		{
			$index++;
			echo "xmldbc -is ".$WAPI_SLOT.$index."/config/AuthenticationSuiteEnabled 0\n";
			echo "xmldbc -is ".$WAPI_SLOT.$index."/config/AuthenticationSuite 00-00-00-00\n";
		}
	}
}
/**---------------------------2.4G-------------------------*/
/**---------------------------5G-------------------------*/
$WLAN=$WLAN_a;
$multi_ssid_path = $WLAN."/multi";
$multi_total_state = query($multi_ssid_path."/state"); 
$wlan_ap_operate_mode = query($WLAN."/ap_mode");
$WAPI_SLOT="/wapi/slot:";

if ($wlan_ap_operate_mode != 1)
{
	echo "xmldbc -is ".$WAPI_SLOT."17/config/AuthenticationSuiteEnabled 0\n";
	echo "xmldbc -is ".$WAPI_SLOT."17/config/AuthenticationSuite 00-00-00-00\n";
//	set($WAPI_SLOT."17/config/AuthenticationSuiteEnabled", "0");
//	set($WAPI_SLOT."17/config/AuthenticationSuite", "00-00-00-00");
	if ($multi_total_state == 1)
	{
		$index=17;
		for ($multi_ssid_path."/index")
		{
			$index++;
			echo "xmldbc -is ".$WAPI_SLOT.$index."/config/AuthenticationSuiteEnabled 0\n";
			echo "xmldbc -is ".$WAPI_SLOT.$index."/config/AuthenticationSuite 00-00-00-00\n";
		}
	}
}
/**---------------------------5G-------------------------*/
//end, xmlnode for wapi add by log_luo 20100702
	echo "kill -9 `ps | grep hostapd | grep -v grep|cut -b 1-5` > /dev/null 2>&1\n";
	echo "kill -9 `ps | grep wpa_supplicant | grep -v grep | cut -b 1-5` > /dev/null 2>&1\n";
	echo "[ -f /var/run/conf_filename ] && rm -f `cat /var/run/conf_filename` \n";
	echo "[ -f /var/run/conf_filename ] && rm -rf var/run/hostapd \n";
	echo "cd /var/run; rm -f `ls | grep wpa_supplicant`; cd / \n";
	echo "rm -f /var/run/conf_filename \n";
	echo "rm -f /var/run/sta_conf_filename \n";
	echo "kill `ps | grep wapid | grep -v grep|cut -b 1-5` > /dev/null 2>&1\n";
	echo "rm -f /var/run/wapi.conf";
}

?>	
