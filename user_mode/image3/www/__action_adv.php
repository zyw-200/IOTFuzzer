<?
$AUTH_GROUP=fread("/var/proc/web/session:".$sid."/user/group");
if ($AUTH_GROUP!="0") {require("/www/permission_deny.php");exit;}
$switch = query("/runtime/web/display/switchable");
if($ACTION_POST == "adv_trafficmanage")
{
	if(valid_ip($cli_ip) != 0){exit;}
	if(valid_mac($cli_mac) != 0){exit;}
}
else if($ACTION_POST == "adv_perf")
{
	if(valid_num($ack_timeout) != 0){exit;}
}
else if($ACTION_POST == "adv_arpspoofing")
{
	if(valid_ip($ip_addr) != 0){exit;}
}
else if($ACTION_POST == "adv_schedule")
{
	if(valid_time($f_start_time) != 0){exit;}
	if(valid_time($f_end_time) != 0){exit;}
}
else if($ACTION_POST == "adv_acl_perssid" || $ACTION_POST == "adv_acl")
{
	if(valid_mac($hacl_mac) != 0){exit;}
}
else if($ACTION_POST == "adv_8021q" || $ACTION_POST == "adv_8021q_s")
{
	if(valid_num($f_vid) != 0){exit;}
}

if($ACTION_POST == "adv_rogue")
{
	echo "<!--\n";
	echo "f_detect = ". $f_detect ."\n";	
	echo "f_type = ". $f_type ."\n";
	echo "-->\n";

	$SUBMIT_STR = "";
	$dirty = 0;
	$cfg_ap_band = $band;
	set("/wlan/ch_mode",	$band);
	$cfg_ap_index = $cfg_ap_band+1;
	if($switch == 1){$cfg_ap_index = 1;}
	if($f_detect == "1")
	{
		if($switch == 1)
		{
			set("/wlan/inf:1/instruction",1);
			set("/runtime/wlan/inf:1/st_ap",0);
			if($band == 0)
			{$SUBMIT_STR="submit ROGUEAP_SCAN_G;submit COMP_G";}
			else{$SUBMIT_STR="submit ROGUEAP_SCAN_A;submit COMP_A";}
		}
		else
		{
			if($cfg_ap_band == 0)
			{
				set("/wlan/inf:1/instruction",1);//add for dennis to difference at apc scan or instruction scan
				set("/runtime/wlan/inf:1/st_ap",0);
				$SUBMIT_STR="submit ROGUEAP_SCAN_G;submit COMP_G";
			}
			else if($cfg_ap_band == 1)
			{
				set("/wlan/inf:2/instruction",1);
				set("/runtime/wlan/inf:2/st_ap",0);
				$SUBMIT_STR="submit ROGUEAP_SCAN_A;submit COMP_A";
			}
		}
	}
	else
	{
		anchor("/runtime/wlan/inf:".$cfg_ap_index."/rogue_ap");
		$i=0;
		$runtime_client = 0;
		for("/runtime/wlan/inf:".$cfg_ap_index."/rogue_ap/client")
		{
			$runtime_client++;
		}		
		while($i< $runtime_client)
		{
			$index=$i+1;
			$client_idx	= "client_idx".$index;
			$band		= query("/runtime/wlan/inf:".$cfg_ap_index."/rogue_ap/client:".$index."/band");
			$chan		= query("/runtime/wlan/inf:".$cfg_ap_index."/rogue_ap/client:".$index."/channel");
			$ssid		= query("/runtime/wlan/inf:".$cfg_ap_index."/rogue_ap/client:".$index."/ssid");
			$mac		= query("/runtime/wlan/inf:".$cfg_ap_index."/rogue_ap/client:".$index."/mac");
			$time		= query("/runtime/wlan/inf:".$cfg_ap_index."/rogue_ap/client:".$index."/time");
			$time_str	= query("/runtime/wlan/inf:".$cfg_ap_index."/rogue_ap/client:".$index."/time_str");
			$status		= query("/runtime/wlan/inf:".$cfg_ap_index."/rogue_ap/client:".$index."/status");

			if($$client_idx == 1)
			{
				set("client:".$index."/type", $f_type);

				$j = 1;
				for("/wlan/inf:".$cfg_ap_index."/rogue_ap/client")
				{
					if($mac == query("mac"))
					{
						set("/runtime/wlan/inf:".$cfg_ap_index."/rogue_ap/client:".$index."/index", $@);
					}	
									
					$j++;
				} 
			
				$fresh_rogue_list_index = query("/runtime/wlan/inf:".$cfg_ap_index."/rogue_ap/client:".$index."/index");

				if($f_type != 0 )
				{
					
					if($fresh_rogue_list_index == 0)
					{
						$fresh_rogue_list_index = $j;
					}
					
					set("/wlan/inf:".$cfg_ap_index."/rogue_ap/client:".$fresh_rogue_list_index."/type", $f_type);
					set("/wlan/inf:".$cfg_ap_index."/rogue_ap/client:".$fresh_rogue_list_index."/band", $band);
					set("/wlan/inf:".$cfg_ap_index."/rogue_ap/client:".$fresh_rogue_list_index."/channel", $chan);
					set("/wlan/inf:".$cfg_ap_index."/rogue_ap/client:".$fresh_rogue_list_index."/ssid", $ssid);
					set("/wlan/inf:".$cfg_ap_index."/rogue_ap/client:".$fresh_rogue_list_index."/mac",  $mac);
					set("/wlan/inf:".$cfg_ap_index."/rogue_ap/client:".$fresh_rogue_list_index."/time", $time);		
					set("/wlan/inf:".$cfg_ap_index."/rogue_ap/client:".$fresh_rogue_list_index."/time_str", $time_str);				
					set("/wlan/inf:".$cfg_ap_index."/rogue_ap/client:".$fresh_rogue_list_index."/status", $status);			

				}
				else if($f_type == 0 && $fresh_rogue_list_index != 0)
				{
					del("/wlan/inf:".$cfg_ap_index."/rogue_ap/client:".$fresh_rogue_list_index);	
				}			
				$dirty ++;
			}
			$i++;
		}
		
	}
	
	if($dirty > 0)
	{
		set("/runtime/web/submit/commit",1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}	
		
		
	set("/runtime/web/sub_str",$SUBMIT_STR);
	set("/runtime/web/next_page",$MY_NAME);
	if($SUBMIT_STR!="" && $f_detect=="1")	{require($G_SCAN_URL);}
	else {require($G_SAVING_URL);}	
}
else if($ACTION_POST == "adv_perf")
{
	echo "<!--\n";
	echo "wl_enable 	= ". $wl_enable ."\n";	
	echo "f_wlmode 		= ". $f_wlmode ."\n";
	echo "f_rate 			= ". $f_rate ."\n";
	echo "bi 			= ". $bi ."\n";
	echo "dtim 			= ". $dtim ."\n";
	echo "frag 			= ". $frag ."\n";
	echo "rts 			= ". $rts ."\n";
	echo "power 		= ". $power ."\n";
	echo "wmm 			= ". $wmm ."\n";
	echo "ack_timeout 	= ". $ack_timeout ."\n";
	echo "shortgi 		= ". $shortgi ."\n";
	echo "igmp 			= ". $igmp ."\n";
	echo "link_integrality  =".$link_integrality."\n";
	echo "-->\n";	
	
	$SUBMIT_STR = "";
	$dirty = 0;		
	$dirty_m2u = 0;
	$multi_num=0;
	$cfg_ap_band = $band;
	set("/wlan/ch_mode",$band);
	if($switch == 1)
	{
		anchor("/wlan/inf:1");	
	}
	else
	{
		if($cfg_ap_band == 0 ) //11g
		{
				anchor("/wlan/inf:1");	
				$band_index=1;
		}
		else
		{
				anchor("/wlan/inf:2");
				$band_index=2;
		}
	}	
	
	$cfg_ap_mode = query("ap_mode");
	
	if(query("enable")	!=$wl_enable)	{set("enable", $wl_enable);	$dirty++;}
	if($wl_enable != 0)
	{	
		if(query("wlmode")	!=$f_wlmode)	{set("wlmode", $f_wlmode);	$dirty++;}
		if($f_wlmode ==2 || $f_wlmode ==7)
		{
			set("cwmmode", 0);	$dirty++;
		}
		else if($f_wlmode == 1 || $f_wlmode == 3) // 11n mode, Joe
		{
			if(query("cwmmode")==2) // cwmmode=2 means 80MHz, only valid for 11ac mode
			{
				set("cwmmode", 0);	$dirty++;
			}
		}
		
		if($f_wlmode ==2 || $f_wlmode ==7)
		{
			if(query("fixedrate")	!=$f_rate)	{set("fixedrate", $f_rate);	$dirty++;}
		}
		else
		{
			if(query("fixedrate")	!=31)	{set("fixedrate", 31);	$dirty++;}
		}
		if(query("fraglength")	!=$frag)	{set("fraglength", $frag);	$dirty++;}
		if(query("rtslength")	!=$rts)		{set("rtslength", $rts);	$dirty++;}
		if(query("txpower")	!=$power)		{set("txpower", $power);	$dirty++;}
		if(query("wmm/enable")	!=$wmm)		{set("wmm/enable", $wmm);	$dirty++;}
		if($cfg_ap_band == 0 ) //11g
		{
			if(query("acktimeout_g") != $ack_timeout)    {set("acktimeout_g", $ack_timeout);	$dirty++;}
		}
		else
		{
			if(query("acktimeout_a") != $ack_timeout)    {set("acktimeout_a", $ack_timeout);	$dirty++;}
		}	
		if(query("shortgi")	!=$shortgi)		{set("shortgi", $shortgi);	$dirty++;}

		if($cfg_ap_mode != 1) //not apc		
		{
			if(query("beaconinterval")	!=$bi)	{set("beaconinterval", $bi);	$dirty++;}
			if(query("dtim")	!=$dtim)		{set("dtim", $dtim);	$dirty++;}
			if(query("igmpsnoop")	!=$igmp)		{set("igmpsnoop", $igmp);	$dirty++;}	
		}		
		if(query("mcastrate") !=$f_mcast)	{set("mcastrate", $f_mcast);  $dirty++;}
		if(query("coexistence/enable") !=$f_coexistence)   {set("coexistence/enable", $f_coexistence);    $dirty++;}		
		if(query("/wlan/inf:1/multicast_bwctrl") != $bandrate_state)    {set("/wlan/inf:1/multicast_bwctrl", $bandrate_state);  $dirty++;}
		if($bandrate_state == 1)
		{
			if(query("/wlan/inf:1/multicast_bw_rate") != $band_rate)    {set("/wlan/inf:1/multicast_bw_rate", $band_rate);  $dirty++;}
		}
	}
	if(query("/sys/dhcp_mc2uc") != $m2u_status){set("/sys/dhcp_mc2uc", $m2u_status); $dirty_m2u++;}
	
	if($dirty > 0)
	{
		set("/runtime/web/submit/wlan", 1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}	
	if($dirty_m2u > 0)
	{
		set("/runtime/web/submit/m2u", 1);
	}
	
	set("/runtime/web/next_page",$MY_NAME);
	require($G_SAVING_URL);
}

else if($ACTION_POST == "adv_resource")
{
    echo "<!--\n";
    echo "limit_state   = ". $limit_state ."\n";
    echo "limit_num     = ". $limit_num ."\n";
    echo "utilization   = ". $utilization  ."\n";
    echo "-->\n";

    $SUBMIT_STR = "";
    $dirty = 0;
    $multi_num=0;
    $cfg_ap_band = $band;
    set("/wlan/ch_mode",$band);
	if($switch == 1)
	{
		anchor("/wlan/inf:1");
	}
	else
	{
		if($cfg_ap_band == 0 ) //11g
		{
				anchor("/wlan/inf:1");
				$band_index=1;
		}
		else
		{
				anchor("/wlan/inf:2");
				$band_index=2;
		}
	}

    $cfg_ap_mode = query("ap_mode");

	if($cfg_ap_mode == 0) //access point
	{
		if(query("assoc_limit/enable")  !=$limit_state) {set("assoc_limit/enable", $limit_state);   $dirty++;}
		if($limit_state == 1)
		{
			if(query("assoc_limit/number")  !=$limit_num)   {set("assoc_limit/number", $limit_num); $dirty++;}
			if(query("wlan_bytes_lim")  !=$utilization)   {set("wlan_bytes_lim", $utilization); $dirty++;}
		}
	}
	if(query("agbyrssiordrstatus")!=$aging_out)
	{
		set("agbyrssiordrstatus",$aging_out);
		$multi_num =0;
		for("/wlan/inf:".$band_index."/multi/index")
		{
			$multi_num++;
			set("agbyrssiordrstatus",$aging_out);
		}
		$dirty++;
	}
	if(query("agbyrssiordrstatus") == 1)
	{
		if(query("agingbyrssithreshhold")!=$rssi_threshod)
		{
			set("agingbyrssithreshhold",$rssi_threshod);
		}
		$multi_num =0;
		for("/wlan/inf:".$band_index."/multi/index")
		{
			$multi_num++;
			set("agingbyrssithreshhold",$rssi_threshod);
		}
		$dirty++;
	}
	if(query("agbyrssiordrstatus")==2)
	{
		if(query("agingbydrthreshhold")!=$Date_threshod)
		{
			set("agingbydrthreshhold",$Date_threshod);
		}
		$multi_num =0;
		for("/wlan/inf:".$band_index."/multi/index")
		{
			$multi_num++;
			set("agingbydrthreshhold",$Date_threshod);
		}
		$dirty++;
	}
	if(query("aclallbywlmode")!=$preferred_11n)
	{
		set("aclallbywlmode",$preferred_11n);
		$multi_num =0;
		for("/wlan/inf:".$band_index."/multi/index")
		{
			$multi_num++;
			set("aclallbywlmode",$preferred_11n);
		}
		$dirty++;
	}
	if(query("aclbyrssi")!=$acl_rssi)
	{
		set("aclbyrssi",$acl_rssi);
		for("/wlan/inf:".$band_index."/multi/index")
		{
			$multi_num++;
			set("aclbyrssi",$acl_rssi);
		}
		$dirty++;
	}
	if(query("aclbyrssithreshhold")!=$acl_rssi_thre)
	{
		set("aclbyrssithreshhold",$acl_rssi_thre);
		$multi_num =0;
		for("/wlan/inf:".$band_index."/multi/index")
		{
			$multi_num++;
			set("aclbyrssithreshhold",$acl_rssi_thre);
		}
		$dirty++;
	}
	if(query("/sys/wlan/preferred5g")!=$preferred_5g)
	{
		set("/sys/wlan/preferred5g",$preferred_5g);
		$dirty++;
	}
	if($preferred_5g == 1)
	{
		if(query("/sys/wlan/pre5g_age")!=$preferred_age_5g)
		{
			set("/sys/wlan/pre5g_age",$preferred_age_5g);
			$dirty++;
		}
		if(query("/sys/wlan/pre5g_diff")!=$preferred_diff_5g)
		{
			set("/sys/wlan/pre5g_diff",$preferred_diff_5g);
			$dirty++;
		}
		if(query("/sys/wlan/pre5g_refuse")!=$preferred_refuse_5g)
		{
			set("/sys/wlan/pre5g_refuse",$preferred_refuse_5g);
			$dirty++;
		}
	}

    if($dirty > 0)
    {
        set("/runtime/web/submit/wlan", 1);
        set("/runtime/web/sub_str",$SUBMIT_STR);
    }

    set("/runtime/web/next_page",$MY_NAME);
	require($G_SAVING_URL);
}
else if($ACTION_POST == "adv_mssid")
{
	echo "<!--\n";
	echo "f_mssid_del 	= ". $f_mssid_del  ."\n";
	echo "mssid_enable	= ". $mssid_enable  ."\n";
	echo "priority_enable	= ". $priority_enable  ."\n";
	echo "priority	= ". $priority  ."\n";
	echo "f_wpa_passphraseformat = ". $f_wpa_passphraseformat  ."\n";
	echo "-->\n";

	$SUBMIT_STR = "";
	$dirty = 1;			
	$captive_dirty = 0;

	$cfg_ap_band = $band;
	set("/wlan/ch_mode",	$band);
	if($switch == 1)
	{
		anchor("/wlan/inf:1");
	}
	else
	{
		if($cfg_ap_band == 0 ) //11g
		{
			anchor("/wlan/inf:1");	
		}
		else
		{
			anchor("/wlan/inf:2");
		}	
	}
	if($f_mssid_del != "")
	{
		$dirty++;

		set("multi/index:".$f_mssid_del."/state",0);	
		
	}
	else if($f_add != "") //add
	{
		if($index != 0)
		{
			if(query("multi/state")	!= $mssid_enable)	{set("multi/state",	$mssid_enable);			$dirty++;}
		}
		if($mssid_enable == 1)
		{
			if(query("multi/state")	!= $mssid_enable)	{set("multi/state",	$mssid_enable);			$dirty++;}
			if($index == 0 && query("/runtime/web/display/priority") == 1)
			{
				if(query("multi/pri_by_ssid")	!= $priority_enable)	{set("multi/pri_by_ssid",	$priority_enable);			$dirty++;}
				if($priority_enable == 1)
				{
					if(query("pri_bit")	!= $priority)	{set("pri_bit",	$priority);			$dirty++;}	
				}
			}
			if(query("/runtime/web/display/mssid_support_sharedkey") == 0)//mssid not support Shared Key	
			{		
				if(query("authentication") == 1) //Shared Key
				{
					set("authentication", 0); $dirty++;//open
				}
			}
			
			if(query("ap_mode") == 1 || query("ap_mode") == 2 || query("ap_mode") == 4) // not access point, wds with ap
			{
				set("ap_mode", 0);$dirty++;
			}
		} 
				
		if($index != 0 && $mssid_enable == 1)
		{
			if(query("multi/index:".$index."/state")	!= 1)	{set("multi/index:".$index."/state",	1);			$dirty++;}	
			if(query("multi/index:".$index."/ssid")	!= $ssid)	
			{
				set("multi/index:".$index."/ssid",	$ssid);	$dirty++;
				if($cfg_ap_band == 0) {$ps_band = $index + 1;}
				else {$ps_band = $index + 9;}
				if(query("/captival/ssid:".$ps_band."/state") == 3) //check whether this ssid is passcode, if yes, delect the nodes
				{
					$tmp = 0;
					for("/captival/ssid:".$ps_band."/passcode/index")
					{$tmp++;}
					$i = 0;
					while($i < $tmp)
					{
						del("/captival/ssid:".$ps_band."/passcode/index:1");$i++;
					}
					$captive_dirty++;
				}
				$that_ssid = "";
				$tmp_ps = 1;
				while($tmp_ps < 17) //check whether there's another ssid is passcode
				{
					if(query("/captival/ssid:".$tmp_ps."/state") == 3 && $tmp_ps != $ps_band) //if yes, compare ssid
					{
						if($tmp_ps == 1){$that_ssid = query("/wlan/inf:1/ssid");}
						else if($tmp_ps < 9){$tmp_psss = $tmp_ps - 1; $that_ssid = query("/wlan/inf:1/multi/index:".$tmp_psss."/ssid");}
						else if($tmp_ps == 9){$that_ssid = query("/wlan/inf:2/ssid");}
						else if($tmp_ps > 9){$tmp_psss = $tmp_ps - 9; $that_ssid = query("/wlan/inf:2/multi/index:".$tmp_psss."/ssid");}
						if($ssid == $that_ssid) //if yes, copy the another's passcode to this ssid
						{
							$i=0;$j=0;
							for("/captival/ssid:".$tmp_ps."/passcode/index"){$i++;}
							while($j<$i)
							{
								$j++;
								$ps_number = query("/captival/ssid:".$tmp_ps."/passcode/index:".$j."/number");
								$ps_duration = query("/captival/ssid:".$tmp_ps."/passcode/index:".$j."/duration");
								$ps_end = query("/captival/ssid:".$tmp_ps."/passcode/index:".$j."/end");
								$ps_device = query("/captival/ssid:".$tmp_ps."/passcode/index:".$j."/device");
								set("/captival/ssid:".$ps_band."/passcode/index:".$j."/number", $ps_number);
								set("/captival/ssid:".$ps_band."/passcode/index:".$j."/duration", $ps_duration);
								set("/captival/ssid:".$ps_band."/passcode/index:".$j."/end", $ps_end);
								set("/captival/ssid:".$ps_band."/passcode/index:".$j."/device", $ps_device);
							}
						}
					}
					$tmp_ps++;
				}
			}
			if(query("multi/index:".$index."/ssid_hidden")	!=$ap_hidden)	{set("multi/index:".$index."/ssid_hidden", $ap_hidden);	$dirty++;}
			if(query("multi/index:".$index."/auth")	!=$f_auth)	{set("multi/index:".$index."/auth", $f_auth);	$dirty++;}
			if(query("multi/pri_by_ssid")	!= $priority_enable)	{set("multi/pri_by_ssid",	$priority_enable);			$dirty++;}

			if(query("/runtime/web/display/priority") == 1 && $priority_enable == 1)
			{
				if(query("multi/index:".$index."/pri_bit")	!= $priority)	{set("multi/index:".$index."/pri_bit",	$priority);	$dirty++;}
			}
			if(query("multi/index:".$index."/wmm/enable")	!= $wmm)	{set("multi/index:".$index."/wmm/enable",	$wmm);	$dirty++;}
			if(query("multi/index:".$index."/captival/state")  !=$captival_profile)     {set("multi/index:".$index."/captival/state", $captival_profile);   $dirty++;}
			if(query("multi/index:".$index."/cipher")	!=$f_cipher)	{set("multi/index:".$index."/cipher", $f_cipher);	$dirty++;}	
	
			if($f_auth == 0 || $f_auth == 1 || $f_auth == 8) //open, shared, both
			{
				if($f_cipher == 1)
				{
					if(query("multi/index:".$index."/wep_key_index")	!=$defkey)	{set("multi/index:".$index."/wep_key_index", $defkey);	$dirty++;}	
					
					if($defkey == 1)
					{
						if(query("wepkey:1/keylength")	!=$keysize)	{set("wepkey:1/keylength", $keysize);	$dirty++;}	
						if(query("wepkey:1/keyformat")	!=$keytype)	{set("wepkey:1/keyformat", $keytype);	$dirty++;}	
						if(queryEnc("wepkey:1")	!=$key)	{setEnc("wepkey:1", $key);	$dirty++;}	
					}
					else if($defkey == 2)
					{
	
						if(query("wepkey:2/keylength")	!=$keysize)	{set("wepkey:2/keylength", $keysize);	$dirty++;}	
						if(query("wepkey:2/keyformat")	!=$keytype)	{set("wepkey:2/keyformat", $keytype);	$dirty++;}	
						if(queryEnc("wepkey:2")	!=$key)	{setEnc("wepkey:2", $key);	$dirty++;}	
					}	
					else if($defkey == 3)
					{
						if(query("wepkey:3/keylength")	!=$keysize)	{set("wepkey:3/keylength", $keysize);	$dirty++;}	
						if(query("wepkey:3/keyformat")	!=$keytype)	{set("wepkey:3/keyformat", $keytype);	$dirty++;}	
						if(queryEnc("wepkey:3")	!=$key)	{setEnc("wepkey:3", $key);	$dirty++;}	
					}
					else if($defkey == 4)
					{
						if(query("wepkey:4/keylength")	!=$keysize)	{set("wepkey:4/keylength", $keysize);	$dirty++;}	
						if(query("wepkey:4/keyformat")	!=$keytype)	{set("wepkey:4/keyformat", $keytype);	$dirty++;}	
						if(queryEnc("wepkey:4")	!=$key)	{setEnc("wepkey:4", $key);	$dirty++;}	
					}	
															
				}			
			}
			else if($f_auth == 2 || $f_auth == 4 || $f_auth == 6 || $f_auth == 9) //eap or 802.1x
			{	
				if($f_auth != 9)	//802.1x don't have the follow settings
				{	
					if(query("multi/index:".$index."/interval")	!=$gkui)	{set("multi/index:".$index."/interval", $gkui);	$dirty++;}
				}
				else	//802.1x key update interval
				{
					if(query("multi/index:".$index."/d_wep_rekey_interval")	!=$kui)	{set("multi/index:".$index."/d_wep_rekey_interval", $kui);	$dirty++;}					
				}

				if(query("/runtime/web/display/local_radius_server") !="0") //Remote or Local RADIUS Server
				{							
					if(query("multi/index:".$index."/embradius_state")	!= $radius_type)	{set("multi/index:".$index."/embradius_state", $radius_type);}
				}
				if($radius_type == 0 || $radius_type == ""){
				if(query("multi/index:".$index."/radius_server")	!=$radius_srv_1)	{set("multi/index:".$index."/radius_server", $radius_srv_1);	$dirty++;}	
				if(query("multi/index:".$index."/radius_port")	!=$radius_port_1)	{set("multi/index:".$index."/radius_port", $radius_port_1);	$dirty++;}	
				if(queryEnc("multi/index:".$index."/radius_secret")	!=$radius_sec_1)	{setEnc("multi/index:".$index."/radius_secret", $radius_sec_1);	$dirty++;}	
				if(query("/runtime/web/display/backup_radius_server") !="0")
				{
					if(query("multi/index:".$index."/b_radius_server")	!=$radius_srv_2)	{set("multi/index:".$index."/b_radius_server", $radius_srv_2);	$dirty++;}	
					if(query("multi/index:".$index."/b_radius_port")	!=$radius_port_2)	{set("multi/index:".$index."/b_radius_port", $radius_port_2);	$dirty++;}	
					if(queryEnc("multi/index:".$index."/b_radius_secret")	!=$radius_sec_2)	{setEnc("multi/index:".$index."/b_radius_secret", $radius_sec_2);	$dirty++;}	
				}	
				
				if(query("/runtime/web/display/accounting_server") != "0")
				{
					if(query("multi/index:".$index."/acct_state")	!=$acc_mode)	{set("multi/index:".$index."/acct_state", $acc_mode);	$dirty++;}	
					if($acc_mode == 1)
					{
						if(query("multi/index:".$index."/acct_server")	!=$acc_srv_1)	{set("multi/index:".$index."/acct_server", $acc_srv_1);	$dirty++;}	
						if(query("multi/index:".$index."/acct_port")	!=$acc_port_1)	{set("multi/index:".$index."/acct_port", $acc_port_1);	$dirty++;}	
						if(queryEnc("multi/index:".$index."/acct_secret")	!=$acc_sec_1)	{setEnc("multi/index:".$index."/acct_secret", $acc_sec_1);	$dirty++;}	
						if(query("multi/index:".$index."/b_acct_server")	!=$acc_srv_2)	{set("multi/index:".$index."/b_acct_server", $acc_srv_2);	$dirty++;}	
						if(query("multi/index:".$index."/b_acct_port")	!=$acc_port_2)	{set("multi/index:".$index."/b_acct_port", $acc_port_2);	$dirty++;}	
						if(queryEnc("multi/index:".$index."/b_acct_secret")	!=$acc_sec_2)	{setEnc("multi/index:".$index."/b_acct_secret", $acc_sec_2);	$dirty++;}					
					}	
				}
				}				
			}	
			else //psk
			{
				if(query("multi/index:".$index."/interval")	!=$gkui)	{set("multi/index:".$index."/interval", $gkui);	$dirty++;}
				if(query("multi/index:".$index."/autorekey/enable")	!=$f_enable_rekey)	{set("multi/index:".$index."/autorekey/enable", $f_enable_rekey);	$dirty++;}
				if($f_enable_rekey == 1)
				{
					if(query("/wlan/inf:1/autorekey/time")	!=$f_time_interuol)	{set("/wlan/inf:1/autorekey/time", $f_time_interuol);	$dirty++;}
					if(query("/wlan/inf:1/autorekey/starttime")	!=$f_start_time)	{set("/wlan/inf:1/autorekey/starttime", $f_start_time);	$dirty++;}		
					if(query("/wlan/inf:1/autorekey/startweek")	!=$f_start_week)	{set("/wlan/inf:1/autorekey/startweek", $f_start_week);	$dirty++;}		
				}
				else
				{
					if(queryEnc("multi/index:".$index."/passphrase") != $passphrase)
					{
						if(query("multi/index:".$index."/passphraseformat")!=$f_wpa_passphraseformat)	{set("multi/index:".$index."/passphraseformat", $f_wpa_passphraseformat);	$dirty++;}
						setEnc("multi/index:".$index."/passphrase", $passphrase);	$dirty++;
					}
				}
			}	
		}	
	}		
	else
	{
		if(query("multi/state")	!= $mssid_enable)	{set("multi/state",	$mssid_enable);			$dirty++;}

		if($mssid_enable == 1)
		{	
			$dirty++;
			if(query("/runtime/web/display/priority") == 1)
			{
				if(query("multi/pri_by_ssid")	!= $priority_enable)	{set("multi/pri_by_ssid",	$priority_enable);			$dirty++;}
			}
			if(query("/runtime/web/display/mssid_support_sharedkey") == 0)//mssid not support Shared Key	
			{				
				if(query("authentication") == 1) //Shared Key
				{
					set("authentication", 0); $dirty++;//open
				}
			}
			
			if(query("ap_mode") == 1 || query("ap_mode") == 2 || query("ap_mode") == 4) // not access point, wds with ap
			{
				set("ap_mode", 0);$dirty++;
			}
		} 
	}
		
	if($dirty > 0)
	{
		if($f_add != "" || $f_mssid_del != "")
		{
			for("multi/index")
			{
				if(query("autorekey/enable")=="1")
				{
					set("/runtime/web/msg_autorekey", "display");
				}
			}
			set("/runtime/web/submit/commit",1);
		}
			set("/runtime/web/submit/wlan", 1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}	
	if($captive_dirty > 0)
	{
		set("/runtime/web/submit/captival", 1);
		set("/runtime/web/submit/apneaps_v2", 1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}
	
	set("/runtime/web/next_page",$MY_NAME);
	require($G_SAVING_URL);
}

else if($ACTION_POST == "adv_partition")
{
        echo "<!--\n";
        echo "isc       = ". $isc  ."\n";
        echo "isc1      = ". $f_isc1  ."\n";
        echo "isc2      = ". $f_isc2  ."\n";
        echo "isc3      = ". $f_isc3 ."\n";
        echo "isc4      = ". $f_isc4."\n";
        echo "isc5      = ". $f_isc5  ."\n";
        echo "isc6      = ". $f_isc6  ."\n";
        echo "isc7      = ". $f_isc7 ."\n";
        echo "ewa       = ". $ewa  ."\n";
        echo "-->\n";

        $SUBMIT_STR = "";
        $dirty = 0;

		set("/wlan/ch_mode",    $band);
	if($switch == 1)
	{
		anchor("/wlan/inf:1");
	}
	else
	{
        if($band == 1 ) //5G
        {
                anchor("/wlan/inf:2");
        }
        else    //2.4G
        {
                anchor("/wlan/inf:1");
        }
	}
        if(query("ethlink") != $link_integrality)   
         {
         	set("ethlink", $link_integrality);        $dirty++;
         	}
        if(query("w_partition") !=$f_isc)       {set("w_partition", $f_isc);                    $dirty++;}
        if(query("multi/index:1/w_partition")   !=$f_isc1)      {set("multi/index:1/w_partition", $f_isc1);                     $dirty++;}
        if(query("multi/index:2/w_partition")   !=$f_isc2)      {set("multi/index:2/w_partition", $f_isc2);                     $dirty++;}
        if(query("multi/index:3/w_partition")   !=$f_isc3)      {set("multi/index:3/w_partition", $f_isc3);                     $dirty++;}
        if(query("multi/index:4/w_partition")   !=$f_isc4)      {set("multi/index:4/w_partition", $f_isc4);                     $dirty++;}
        if(query("multi/index:5/w_partition")   !=$f_isc5)      {set("multi/index:5/w_partition", $f_isc5);                     $dirty++;}
        if(query("multi/index:6/w_partition")   !=$f_isc6)      {set("multi/index:6/w_partition", $f_isc6);                     $dirty++;}
        if(query("multi/index:7/w_partition")   !=$f_isc7)      {set("multi/index:7/w_partition", $f_isc7);                     $dirty++;}
        if(query("e_partition") !=$ewa) {set("e_partition", $ewa);                      $dirty++;}

        if($dirty > 0)
        {
                set("/runtime/web/submit/wlan", 1);
                set("/runtime/web/sub_str",$SUBMIT_STR);
        }

        set("/runtime/web/next_page",$MY_NAME);
		require($G_SAVING_URL);
}
else if($ACTION_POST == "adv_acl")
{
	echo "<!--\n";
	echo "acl_type	= ". $acl_type  ."\n";
	echo "f_acl_del	= ". $f_acl_del  ."\n";
	echo "-->\n";	
	
	$SUBMIT_STR = "";
	$dirty = 0;		
	$active_dirty=0;
	
	set("/wlan/ch_mode",    $band);
	$cfg_ap_band = $band;
	if($switch == 1)
	{
		anchor("/wlan/inf:1");
	}
	else
	{
		if($cfg_ap_band == 0 ) //11g
		{
			anchor("/wlan/inf:1");	
		}
		else
		{
			anchor("/wlan/inf:2");
		}
	}
	if($f_acl_del!="")//del
	{
		$dirty++;
		$acl_entry = 0;
		for("acl/mac"){$acl_entry++;}
		if($acl_entry== 1)
		{
			set("acl/mode", 0);		
		}	
		del("acl/mac:".$f_acl_del);	
	}	
	else if($f_client_info_mac_add != "")
	{
		$dirty++;
		set("acl/mode", 2);		
		$acl_num=1;
		for("acl/mac"){$acl_num++;}
		if(query("acl/mac:".$acl_num)	!=$hacl_mac)	{set("acl/mac:".$acl_num,	$hacl_mac);		$dirty++;}				
	}	
	else if($f_add != "") //add
	{
		if(query("acl/mode")	!=$acl_type)	
		{
			set("acl/mode", $acl_type);	
			$dirty++;
		}				
		
		if($acl_type != 0 && $hacl_mac != 0)
		{
			$acl_num=1;
			for("acl/mac"){$acl_num++;}
			if(query("acl/mac:".$acl_num)	!=$hacl_mac)	{set("acl/mac:".$acl_num,	$hacl_mac);		$dirty++;}				
		}
	}
	else //apply
	{
		if(query("acl/mode")	!=$acl_type)	
		{
			set("acl/mode", $acl_type);	
		}				
		$active_dirty++;
	}
	if(query("acl/mode") !=0)
	{
		set("zonedefence",0);$dirty++;
	}
	if($active_dirty > 0)
	{
		$SUBMIT_ACL = "submit WLAN_ACL";
		set("/runtime/web/sub_acl",$SUBMIT_ACL);
	}
	else if($dirty > 0)
	{
		set("/runtime/wlan/ch_mode",$band);
		set("/runtime/web/submit/wlan_acl", 1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}
	set("/runtime/web/next_page",$MY_NAME);
	require($G_SAVING_URL);
}
else if($ACTION_POST == "adv_acl_perssid")
{
	echo "<!--\n";
	echo "acl_type	= ". $acl_type  ."\n";
	echo "f_acl_del	= ". $f_acl_del  ."\n";
	echo "-->\n";	
	
	$SUBMIT_STR = "";
	$dirty = 0;		
	$active_dirty=0;
	
	set("/wlan/ch_mode",    $band);
	$cfg_ap_band = $band;
	if($switch == 1)
	{
		if($ssid_index == 0){anchor("/wlan/inf:1");}
		else{anchor("/wlan/inf:1/multi/index:".$ssid_index);}
	}
	else
	{
		if($cfg_ap_band == 0 ) //11g
		{
			if($ssid_index == 0){anchor("/wlan/inf:1");}
			else{anchor("/wlan/inf:1/multi/index:".$ssid_index);}
		}
		else
		{
			if($ssid_index == 0){anchor("/wlan/inf:2");}
			else{anchor("/wlan/inf:2/multi/index:".$ssid_index);}
		}
	}
	set("/runtime/acl/edit_whichindex", $ssid_index);
	if($f_acl_del!="")//del
	{
		$dirty++;
		$acl_entry = 0;
		for("acl/mac"){$acl_entry++;}
		if($acl_entry== 1)
		{
			set("acl/mode", 0);		
		}	
		del("acl/mac:".$f_acl_del);	
	}	
	else if($f_client_info_mac_add != "")
	{
		$dirty++;
		set("acl/mode", 2);		
		$acl_num=1;
		for("acl/mac"){$acl_num++;}
		if(query("acl/mac:".$acl_num)	!=$hacl_mac)	{set("acl/mac:".$acl_num,	$hacl_mac);		$dirty++;}				
	}	
	else if($f_add != "") //add
	{
		if(query("acl/mode")	!=$acl_type)	
		{
			set("acl/mode", $acl_type);	
			$dirty++;
		}				
		
		if($acl_type != 0 && $hacl_mac != 0)
		{
			$acl_num=1;
			for("acl/mac"){$acl_num++;}
			if(query("acl/mac:".$acl_num)	!=$hacl_mac)	{set("acl/mac:".$acl_num,	$hacl_mac);		$dirty++;}				
		}
	}
	else //apply
	{
		if(query("acl/mode")	!=$acl_type)	
		{
			set("acl/mode", $acl_type);	
		}				
		$active_dirty++;
	}
	if(query("acl/mode") !=0)
	{
		if($switch == 1)
		{
			set("/wlan/inf:1/zonedefence",0);
		}
		else
		{
			if($cfg_ap_band == 0){set("/wlan/inf:1/zonedefence",0);}
			else if($cfg_ap_band == 1){set("/wlan/inf:2/zonedefence",0);}
		}
		$dirty++;
	}

	if($active_dirty > 0)
	{
		$SUBMIT_ACL = "submit WLAN_ACL";
		set("/runtime/web/sub_acl",$SUBMIT_ACL);
	}
	else if($dirty > 0)
	{
		set("/runtime/wlan/ch_mode",$band);
		set("/runtime/web/submit/wlan_acl", 1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}
	set("/runtime/web/next_page",$MY_NAME);
	require($G_SAVING_URL);
}
else if($ACTION_POST == "adv_schedule")
{
	echo "<!--\n";

	echo "-->\n";		
	$SUBMIT_STR = "";
	$dirty = 0;		
	
	anchor("/schedule");

	if($f_entry_del!="")
	{
		$dirty++;
		del("rule/index:".$f_entry_del);	
	}
	else
	{
		
		if($f_add_value != "")
		{
			if($entry_edit !="")
			{
		
				set("rule/index:".$entry_edit."/enable", 1);$dirty++;
				if(query("rule/index:".$entry_edit."/ssidnum")	!= $index)	{set("rule/index:".$entry_edit."/ssidnum",	$index);	$dirty++;}
				if(query("rule/index:".$entry_edit."/name")	!= $name)	{set("rule/index:".$entry_edit."/name",	$name);	$dirty++;}
				if(query("rule/index:".$entry_edit."/sun")	!= $sun)	{set("rule/index:".$entry_edit."/sun",	$sun);	$dirty++;}
				if(query("rule/index:".$entry_edit."/mon")	!= $mon)	{set("rule/index:".$entry_edit."/mon",	$mon);	$dirty++;}
				if(query("rule/index:".$entry_edit."/tue")	!= $tue)	{set("rule/index:".$entry_edit."/tue",	$tue);	$dirty++;}
				if(query("rule/index:".$entry_edit."/wed")	!= $wed)	{set("rule/index:".$entry_edit."/wed",	$wed);	$dirty++;}
				if(query("rule/index:".$entry_edit."/thu")	!= $thu)	{set("rule/index:".$entry_edit."/thu",	$thu);	$dirty++;}
				if(query("rule/index:".$entry_edit."/fri")	!= $fri)	{set("rule/index:".$entry_edit."/fri",	$fri);	$dirty++;}
				if(query("rule/index:".$entry_edit."/sat")	!= $sat)	{set("rule/index:".$entry_edit."/sat",	$sat);	$dirty++;}
		
				if(query("rule/index:".$entry_edit."/allday")	!= $all_day)	{set("rule/index:".$entry_edit."/allday",	$all_day);	$dirty++;}
		
				if($all_day != 1)
				{
					if(query("rule/index:".$entry_edit."/starttime")	!= $f_start_time)	{set("rule/index:".$entry_edit."/starttime",	$f_start_time);	$dirty++;}
					if(query("rule/index:".$entry_edit."/endtime")	!= $f_end_time)	{set("rule/index:".$entry_edit."/endtime",	$f_end_time);	$dirty++;}
          if(query("rule/index:".$entry_edit."/overnight")  != $overnight) {set("rule/index:".$entry_edit."/overnight",  $overnight);$dirty++;}				
				}
			}
			else
			{
				$dirty++;
				set("enable",	1);	
				
				$i = 1;
				for("/schedule/rule/index")
				{
					$i++;
				}
				set("rule/index:".$i."/enable", 1);
				set("rule/index:".$i."/ssidnum", $index);
				set("rule/index:".$i."/name", $name);
				set("rule/index:".$i."/sun", $sun);
				set("rule/index:".$i."/mon", $mon);
				set("rule/index:".$i."/tue", $tue);
				set("rule/index:".$i."/wed", $wed);
				set("rule/index:".$i."/thu", $thu);
				set("rule/index:".$i."/fri", $fri);
				set("rule/index:".$i."/sat", $sat);
				set("rule/index:".$i."/allday", $all_day);	
				
				if($all_day != 1)
				{
					set("rule/index:".$i."/starttime", $f_start_time);	
					set("rule/index:".$i."/endtime", $f_end_time);
					set("rule/index:".$i."/overnight", $overnight);
				}
				set("rule/index:".$i."/wirelesson", 1);
	
			}
		}
		else
		{
			if(query("enable")	!= $schedule_status)	{set("enable",	$schedule_status);	$dirty++;}
			for("/schedule/rule/index")
			{
				$schedule_index	= "s_index".$@;
				if(query("enable")	!= $$schedule_index)	{set("enable",$$schedule_index);	$dirty++;}
			}
		}
	}
	if($dirty > 0)
	{
		set("/runtime/web/submit/wlan", 1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}	
	
	set("/runtime/web/next_page",$MY_NAME);
	require($G_SAVING_URL);
}
else if($ACTION_POST == "adv_updnsetting")
{
	$dirty=0;
	$SUBMIT_STR="";
	
	if($d_eth0==1)
	{$eth0_link=1;
	 if(query("/lan/ethernet/updownlink")!=$eth0_link){set("/lan/ethernet/updownlink",$eth0_link);$dirty++;}}
	else
	{
		if($u_eth0==1)
		{$eth0_link=2;
			if(query("/lan/ethernet/updownlink")!=$eth0_link){set("/lan/ethernet/updownlink",$eth0_link);$dirty++;}}
		else
		{$eth0_link=0;
			if(query("/lan/ethernet/updownlink")!=$eth0_link){set("/lan/ethernet/updownlink",$eth0_link);$dirty++;}}
	}

	if($d_eth2==1)
    {$eth2_link=1;
     if(query("/lan/ethernet:2/updownlink")!=$eth2_link){set("/lan/ethernet:2/updownlink",$eth2_link);$dirty++;}
}
    else
    {
        if($u_eth2==1)
        {$eth2_link=2;
            if(query("/lan/ethernet:2/updownlink")!=$eth2_link){set("/lan/ethernet:2/updownlink",$eth2_link);$dirty++;}}
        else
        {$eth2_link=0;
            if(query("/lan/ethernet:2/updownlink")!=$eth2_link){set("/lan/ethernet:2/updownlink",$eth2_link);$dirty++;}}
    }
	
	if($d_pri==1)
	{$pri_link=1;
		if(query("/wlan/inf:1/updownlink")!=$pri_link){set("/wlan/inf:1/updownlink",$pri_link);$dirty++;}}
	else
	{
		if($u_pri==1)
		{$pri_link=2;
			if(query("/wlan/inf:1/updownlink")!=$pri_link){set("/wlan/inf:1/updownlink",$pri_link);$dirty++;}}
		else
		{$pri_link=0;
			if(query("/wlan/inf:1/updownlink")!=$pri_link){set("/wlan/inf:1/updownlink",$pri_link);$dirty++;}}
	}
	
	anchor("/wlan/inf:1/multi");
	if($d_multi1==1)
	{$multi1_link=1;
		if(query("index:1/updownlink")!=$multi1_link){set("index:1/updownlink",$multi1_link);$dirty++;}}
	else
	{
		if($u_multi1==1)
		{$multi1_link=2;
			if(query("index:1/updownlink")!=$multi1_link){set("index:1/updownlink",$multi1_link);$dirty++;}}
		else
		{$multi1_link=0;
			if(query("index:1/updownlink")!=$multi1_link){set("index:1/updownlink",$multi1_link);$dirty++;}}
	}
	
	if($d_multi2==1)
	{$multi2_link=1;
		if(query("index:2/updownlink")!=$multi2_link){set("index:2/updownlink",$multi2_link);$dirty++;}}
	else
	{
		if($u_multi2==1)
		{$multi2_link=2;
			if(query("index:2/updownlink")!=$multi2_link){set("index:2/updownlink",$multi2_link);$dirty++;}}
		else
		{$multi2_link=0;
			if(query("index:2/updownlink")!=$multi2_link){set("index:2/updownlink",$multi2_link);$dirty++;}}
	}
	
	if($d_multi3==1)
	{$multi3_link=1;
		if(query("index:3/updownlink")!=$multi3_link){set("index:3/updownlink",$multi3_link);$dirty++;}}
	else
	{
		if($u_multi3==1)
		{$multi3_link=2;
			if(query("index:3/updownlink")!=$multi3_link){set("index:3/updownlink",$multi3_link);$dirty++;}}
		else
		{$multi3_link=0;
			if(query("index:3/updownlink")!=$multi3_link){set("index:3/updownlink",$multi3_link);$dirty++;}}
	}
	
	if($d_multi4==1)
	{$multi4_link=1;
		if(query("index:4/updownlink")!=$multi4_link){set("index:4/updownlink",$multi4_link);$dirty++;}}
	else
	{
		if($u_multi4==1)
		{$multi4_link=2;
			if(query("index:4/updownlink")!=$multi4_link){set("index:4/updownlink",$multi4_link);$dirty++;}}
		else
		{$multi4_link=0;
			if(query("index:4/updownlink")!=$multi4_link){set("index:4/updownlink",$multi4_link);$dirty++;}}
	}
	
	if($d_multi5==1)
	{$multi5_link=1;
		if(query("index:5/updownlink")!=$multi5_link){set("index:5/updownlink",$multi5_link);$dirty++;}}
	else
	{
		if($u_multi5==1)
		{$multi5_link=2;
			if(query("index:5/updownlink")!=$multi5_link){set("index:5/updownlink",$multi5_link);$dirty++;}}
		else
		{$multi5_link=0;
			if(query("index:5/updownlink")!=$multi5_link){set("index:5/updownlink",$multi5_link);$dirty++;}}
	}
	
	if($d_multi6==1)
	{$multi6_link=1;
		if(query("index:6/updownlink")!=$multi6_link){set("index:6/updownlink",$multi6_link);$dirty++;}}
	else
	{
		if($u_multi6==1)
		{$multi6_link=2;
			if(query("index:6/updownlink")!=$multi6_link){set("index:6/updownlink",$multi6_link);$dirty++;}}
		else
		{$multi6_link=0;
			if(query("index:6/updownlink")!=$multi6_link){set("index:6/updownlink",$multi6_link);$dirty++;}}
	}
	
	if($d_multi7==1)
	{$multi7_link=1;
		if(query("index:7/updownlink")!=$multi7_link){set("index:7/updownlink",$multi7_link);$dirty++;}}
	else
	{
		if($u_multi7==1)
		{$multi7_link=2;
			if(query("index:7/updownlink")!=$multi7_link){set("index:7/updownlink",$multi7_link);$dirty++;}}
		else
		{$multi7_link=0;
			if(query("index:7/updownlink")!=$multi7_link){set("index:7/updownlink",$multi7_link);$dirty++;}}
	}
	
	anchor("/wlan/inf:1/wds/list");
	if($d_wds1==1)
	{$wds1_link=1;
		if(query("index:1/updownlink")!=$wds1_link){set("index:1/updownlink",$wds1_link);$dirty++;}}
	else
	{
		if($u_wds1==1)
		{$wds1_link=2;
			if(query("index:1/updownlink")!=$wds1_link){set("index:1/updownlink",$wds1_link);$dirty++;}}
		else
		{$wds1_link=0;
			if(query("index:1/updownlink")!=$wds1_link){set("index:1/updownlink",$wds1_link);$dirty++;}}
	}
	
	if($d_wds2==1)
	{$wds2_link=1;
		if(query("index:2/updownlink")!=$wds2_link){set("index:2/updownlink",$wds2_link);$dirty++;}}
	else
	{
		if($u_wds2==1)
		{$wds2_link=2;
			if(query("index:2/updownlink")!=$wds2_link){set("index:2/updownlink",$wds2_link);$dirty++;}}
		else
		{$wds2_link=0;
			if(query("index:2/updownlink")!=$wds2_link){set("index:2/updownlink",$wds2_link);$dirty++;}}
	}
	
	if($d_wds3==1)
	{$wds3_link=1;
		if(query("index:3/updownlink")!=$wds3_link){set("index:3/updownlink",$wds3_link);$dirty++;}}
	else
	{
		if($u_wds3==1)
		{$wds3_link=2;
			if(query("index:3/updownlink")!=$wds3_link){set("index:3/updownlink",$wds3_link);$dirty++;}}
		else
		{$wds3_link=0;
			if(query("index:3/updownlink")!=$wds3_link){set("index:3/updownlink",$wds3_link);$dirty++;}}
	}
	
	if($d_wds4==1)
	{$wds4_link=1;
		if(query("index:4/updownlink")!=$wds4_link){set("index:4/updownlink",$wds4_link);$dirty++;}}
	else
	{
		if($u_wds4==1)
		{$wds4_link=2;
			if(query("index:4/updownlink")!=$wds4_link){set("index:4/updownlink",$wds4_link);$dirty++;}}
		else
		{$wds4_link=0;
			if(query("index:4/updownlink")!=$wds4_link){set("index:4/updownlink",$wds4_link);$dirty++;}}
	}
	
	if($d_wds5==1)
	{$wds5_link=1;
		if(query("index:5/updownlink")!=$wds5_link){set("index:5/updownlink",$wds5_link);$dirty++;}}
	else
	{
		if($u_wds5==1)
		{$wds5_link=2;
			if(query("index:5/updownlink")!=$wds5_link){set("index:5/updownlink",$wds5_link);$dirty++;}}
		else
		{$wds5_link=0;
			if(query("index:5/updownlink")!=$wds5_link){set("index:5/updownlink",$wds5_link);$dirty++;}}
	}
	
	if($d_wds6==1)
	{$wds6_link=1;
		if(query("index:6/updownlink")!=$wds6_link){set("index:6/updownlink",$wds6_link);$dirty++;}}
	else
	{
		if($u_wds6==1)
		{$wds6_link=2;
			if(query("index:6/updownlink")!=$wds6_link){set("index:6/updownlink",$wds6_link);$dirty++;}}
		else
		{$wds6_link=0;
			if(query("index:6/updownlink")!=$wds6_link){set("index:6/updownlink",$wds6_link);$dirty++;}}
	}
	
	if($d_wds7==1)
	{$wds7_link=1;
		if(query("index:7/updownlink")!=$wds7_link){set("index:7/updownlink",$wds7_link);$dirty++;}}
	else
	{
		if($u_wds7==1)
		{$wds7_link=2;
			if(query("index:7/updownlink")!=$wds7_link){set("index:7/updownlink",$wds7_link);$dirty++;}}
		else
		{$wds7_link=0;
			if(query("index:7/updownlink")!=$wds7_link){set("index:7/updownlink",$wds7_link);$dirty++;}}
	}
	
	if($d_wds8==1)
	{$wds8_link=1;
		if(query("index:8/updownlink")!=$wds8_link){set("index:8/updownlink",$wds8_link);$dirty++;}}
	else
	{
		if($u_wds8==1)
		{$wds8_link=2;
			if(query("index:8/updownlink")!=$wds8_link){set("index:8/updownlink",$wds8_link);$dirty++;}}
		else
		{$wds8_link=0;
			if(query("index:8/updownlink")!=$wds8_link){set("index:8/updownlink",$wds8_link);$dirty++;}}
	}

	if($d_pria==1)
	{$pri_linka=1;
		if(query("/wlan/inf:2/updownlink")!=$pri_linka){set("/wlan/inf:2/updownlink",$pri_linka);$dirty++;}}
	else
	{
		if($u_pria==1)
		{$pri_linka=2;
			if(query("/wlan/inf:2/updownlink")!=$pri_linka){set("/wlan/inf:2/updownlink",$pri_linka);$dirty++;}}
		else
		{$pri_linka=0;
			if(query("/wlan/inf:2/updownlink")!=$pri_linka){set("/wlan/inf:2/updownlink",$pri_linka);$dirty++;}}
	}
	
	anchor("/wlan/inf:2/multi");
	if($d_multi1a==1)
	{$multi1_linka=1;
		if(query("index:1/updownlink")!=$multi1_linka){set("index:1/updownlink",$multi1_linka);$dirty++;}}
	else
	{
		if($u_multi1a==1)
		{$multi1_linka=2;
			if(query("index:1/updownlink")!=$multi1_linka){set("index:1/updownlink",$multi1_linka);$dirty++;}}
		else
		{$multi1_linka=0;
			if(query("index:1/updownlink")!=$multi1_linka){set("index:1/updownlink",$multi1_linka);$dirty++;}}
	}
	
	if($d_multi2a==1)
	{$multi2_linka=1;
		if(query("index:2/updownlink")!=$multi2_linka){set("index:2/updownlink",$multi2_linka);$dirty++;}}
	else
	{
		if($u_multi2a==1)
		{$multi2_linka=2;
			if(query("index:2/updownlink")!=$multi2_linka){set("index:2/updownlink",$multi2_linka);$dirty++;}}
		else
		{$multi2_linka=0;
			if(query("index:2/updownlink")!=$multi2_linka){set("index:2/updownlink",$multi2_linka);$dirty++;}}
	}
	
	if($d_multi3a==1)
	{$multi3_linka=1;
		if(query("index:3/updownlink")!=$multi3_linka){set("index:3/updownlink",$multi3_linka);$dirty++;}}
	else
	{
		if($u_multi3a==1)
		{$multi3_linka=2;
			if(query("index:3/updownlink")!=$multi3_linka){set("index:3/updownlink",$multi3_linka);$dirty++;}}
		else
		{$multi3_linka=0;
			if(query("index:3/updownlink")!=$multi3_linka){set("index:3/updownlink",$multi3_linka);$dirty++;}}
	}
	
	if($d_multi4a==1)
	{$multi4_linka=1;
		if(query("index:4/updownlink")!=$multi4_linka){set("index:4/updownlink",$multi4_linka);$dirty++;}}
	else
	{
		if($u_multi4a==1)
		{$multi4_linka=2;
			if(query("index:4/updownlink")!=$multi4_linka){set("index:4/updownlink",$multi4_linka);$dirty++;}}
		else
		{$multi4_linka=0;
			if(query("index:4/updownlink")!=$multi4_linka){set("index:4/updownlink",$multi4_linka);$dirty++;}}
	}
	
	if($d_multi5a==1)
	{$multi5_linka=1;
		if(query("index:5/updownlink")!=$multi5_linka){set("index:5/updownlink",$multi5_linka);$dirty++;}}
	else
	{
		if($u_multi5a==1)
		{$multi5_linka=2;
			if(query("index:5/updownlink")!=$multi5_linka){set("index:5/updownlink",$multi5_linka);$dirty++;}}
		else
		{$multi5_linka=0;
			if(query("index:5/updownlink")!=$multi5_linka){set("index:5/updownlink",$multi5_linka);$dirty++;}}
	}
	
	if($d_multi6a==1)
	{$multi6_linka=1;
		if(query("index:6/updownlink")!=$multi6_linka){set("index:6/updownlink",$multi6_linka);$dirty++;}}
	else
	{
		if($u_multi6a==1)
		{$multi6_linka=2;
			if(query("index:6/updownlink")!=$multi6_linka){set("index:6/updownlink",$multi6_linka);$dirty++;}}
		else
		{$multi6_linka=0;
			if(query("index:6/updownlink")!=$multi6_linka){set("index:6/updownlink",$multi6_linka);$dirty++;}}
	}
	
	if($d_multi7a==1)
	{$multi7_linka=1;
		if(query("index:7/updownlink")!=$multi7_linka){set("index:7/updownlink",$multi7_linka);$dirty++;}}
	else
	{
		if($u_multi7a==1)
		{$multi7_linka=2;
			if(query("index:7/updownlink")!=$multi7_linka){set("index:7/updownlink",$multi7_linka);$dirty++;}}
		else
		{$multi7_linka=0;
			if(query("index:7/updownlink")!=$multi7_linka){set("index:7/updownlink",$multi7_linka);$dirty++;}}
	}
	
	anchor("/wlan/inf:2/wds/list");
	if($d_wds1a==1)
	{$wds1_linka=1;
		if(query("index:1/updownlink")!=$wds1_linka){set("index:1/updownlink",$wds1_linka);$dirty++;}}
	else
	{
		if($u_wds1a==1)
		{$wds1_linka=2;
			if(query("index:1/updownlink")!=$wds1_linka){set("index:1/updownlink",$wds1_linka);$dirty++;}}
		else
		{$wds1_linka=0;
			if(query("index:1/updownlink")!=$wds1_linka){set("index:1/updownlink",$wds1_linka);$dirty++;}}
	}
	
	if($d_wds2a==1)
	{$wds2_linka=1;
		if(query("index:2/updownlink")!=$wds2_linka){set("index:2/updownlink",$wds2_linka);$dirty++;}}
	else
	{
		if($u_wds2a==1)
		{$wds2_linka=2;
			if(query("index:2/updownlink")!=$wds2_linka){set("index:2/updownlink",$wds2_linka);$dirty++;}}
		else
		{$wds2_linka=0;
			if(query("index:2/updownlink")!=$wds2_linka){set("index:2/updownlink",$wds2_linka);$dirty++;}}
	}
	
	if($d_wds3a==1)
	{$wds3_linka=1;
		if(query("index:3/updownlink")!=$wds3_linka){set("index:3/updownlink",$wds3_linka);$dirty++;}}
	else
	{
		if($u_wds3a==1)
		{$wds3_linka=2;
			if(query("index:3/updownlink")!=$wds3_linka){set("index:3/updownlink",$wds3_linka);$dirty++;}}
		else
		{$wds3_linka=0;
			if(query("index:3/updownlink")!=$wds3_linka){set("index:3/updownlink",$wds3_linka);$dirty++;}}
	}
	
	if($d_wds4a==1)
	{$wds4_linka=1;
		if(query("index:4/updownlink")!=$wds4_linka){set("index:4/updownlink",$wds4_linka);$dirty++;}}
	else
	{
		if($u_wds4a==1)
		{$wds4_linka=2;
			if(query("index:4/updownlink")!=$wds4_linka){set("index:4/updownlink",$wds4_linka);$dirty++;}}
		else
		{$wds4_linka=0;
			if(query("index:4/updownlink")!=$wds4_linka){set("index:4/updownlink",$wds4_linka);$dirty++;}}
	}
	
	if($d_wds5a==1)
	{$wds5_linka=1;
		if(query("index:5/updownlink")!=$wds5_linka){set("index:5/updownlink",$wds5_linka);$dirty++;}}
	else
	{
		if($u_wds5a==1)
		{$wds5_linka=2;
			if(query("index:5/updownlink")!=$wds5_linka){set("index:5/updownlink",$wds5_linka);$dirty++;}}
		else
		{$wds5_linka=0;
			if(query("index:5/updownlink")!=$wds5_linka){set("index:5/updownlink",$wds5_linka);$dirty++;}}
	}
	
	if($d_wds6a==1)
	{$wds6_linka=1;
		if(query("index:6/updownlink")!=$wds6_linka){set("index:6/updownlink",$wds6_linka);$dirty++;}}
	else
	{
		if($u_wds6a==1)
		{$wds6_linka=2;
			if(query("index:6/updownlink")!=$wds6_linka){set("index:6/updownlink",$wds6_linka);$dirty++;}}
		else
		{$wds6_linka=0;
			if(query("index:6/updownlink")!=$wds6_linka){set("index:6/updownlink",$wds6_linka);$dirty++;}}
	}
	
	if($d_wds7a==1)
	{$wds7_linka=1;
		if(query("index:7/updownlink")!=$wds7_linka){set("index:7/updownlink",$wds7_linka);$dirty++;}}
	else
	{
		if($u_wds7a==1)
		{$wds7_linka=2;
			if(query("index:7/updownlink")!=$wds7_linka){set("index:7/updownlink",$wds7_linka);$dirty++;}}
		else
		{$wds7_linka=0;
			if(query("index:7/updownlink")!=$wds7_linka){set("index:7/updownlink",$wds7_linka);$dirty++;}}
	}
	
	if($d_wds8a==1)
	{$wds8_linka=1;
		if(query("index:8/updownlink")!=$wds8_linka){set("index:8/updownlink",$wds8_linka);$dirty++;}}
	else
	{
		if($u_wds8a==1)
		{$wds8_linka=2;
			if(query("index:8/updownlink")!=$wds8_linka){set("index:8/updownlink",$wds8_linka);$dirty++;}}
		else
		{$wds8_linka=0;
			if(query("index:8/updownlink")!=$wds8_linka){set("index:8/updownlink",$wds8_linka);$dirty++;}}
	}
	
	if(query("/trafficctrl/updownlinkset/bandwidth/downlink")!=$e2w)
	{
		set("/trafficctrl/updownlinkset/bandwidth/downlink",$e2w);
		$dirty++;
	}
	if(query("/trafficctrl/updownlinkset/bandwidth/uplink")!=$w2e)
	{
		set("/trafficctrl/updownlinkset/bandwidth/uplink",$w2e);
		$dirty++;
	}
	
  	if($dirty > 0)
	{	
		set("/runtime/web/submit/commit",1);
		set("/runtime/web/submit/trafficmgrdy", 1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}	

	set("/runtime/web/next_page",$MY_NAME);
	require($G_SAVING_URL);
}	
else if($ACTION_POST == "adv_qos_user")
{
	$dirty=0;
  	$SUBMIT_STR="";
  	anchor("/trafficctrl/qos");
  	if($qos_state==""){$qos_state=0;}
  	if(query("enable")!=$qos_state){set("enable",$qos_state);$dirty++;}
  	if($qos_state==1)
  	{
		set("/tc_monitor/state", 0);
  		anchor("/trafficctrl/qos/protocol");
 		if(query("aui/priority")!=$aui_pri){set("aui/priority",$aui_pri);$dirty++;}
  		if(query("aui/limit")!=$aui_lim){set("aui/limit",$aui_lim);$dirty++;}
  		if(query("web/priority")!=$web_pri){set("web/priority",$web_pri);$dirty++;}
  		if(query("web/limit")!=$web_lim){set("web/limit",$web_lim);$dirty++;}
  		if(query("mail/priority")!=$mail_pri){set("mail/priority",$mail_pri);$dirty++;}
  		if(query("mail/limit")!=$mail_lim){set("mail/limit",$mail_lim);$dirty++;}
  		if(query("ftp/priority")!=$ftp_pri){set("ftp/priority",$ftp_pri);$dirty++;}
  		if(query("ftp/limit")!=$ftp_lim){set("ftp/limit",$ftp_lim);$dirty++;}
  
  		if(query("user1/priority")!=$user1_pri){set("user1/priority",$user1_pri);$dirty++;}
  		if(query("user1/limit")!=$user1_lim){set("user1/limit",$user1_lim);$dirty++;}
  		if(query("user1/startport")!=$user1_st_port){set("user1/startport",$user1_st_port);$dirty++;}
  		if(query("user1/endport")!=$user1_e_port){set("user1/endport",$user1_e_port);$dirty++;}
  		if(query("user2/priority")!=$user2_pri){set("user2/priority",$user2_pri);$dirty++;}
  		if(query("user2/limit")!=$user2_lim){set("user2/limit",$user2_lim);$dirty++;}
  		if(query("user2/startport")!=$user2_st_port){set("user2/startport",$user2_st_port);$dirty++;}
  		if(query("user2/endport")!=$user2_e_port){set("user2/endport",$user2_e_port);$dirty++;}
  		if(query("user3/priority")!=$user3_pri){set("user3/priority",$user3_pri);$dirty++;}
  		if(query("user3/limit")!=$user3_lim){set("user3/limit",$user3_lim);$dirty++;}
  		if(query("user3/startport")!=$user3_st_port){set("user3/startport",$user3_st_port);$dirty++;}
  		if(query("user3/endport")!=$user3_e_port){set("user3/endport",$user3_e_port);$dirty++;}
  		if(query("user4/priority")!=$user4_pri){set("user4/priority",$user4_pri);$dirty++;}
  		if(query("user4/limit")!=$user4_lim){set("user4/limit",$user4_lim);$dirty++;}
  		if(query("user4/startport")!=$user4_st_port){set("user4/startport",$user4_st_port);$dirty++;}
  		if(query("user4/endport")!=$user4_e_port){set("user4/endport",$user4_e_port);$dirty++;}
  
  		if(query("other/priority")!=$other_pri){set("other/priority",$other_pri);$dirty++;}
  		if(query("other/limit")!=$other_lim){set("other/limit",$other_lim);$dirty++;}
  	}
  
	if($dirty > 0)
	{
		set("/runtime/web/submit/trafficmgrdy", 1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}	

	
	set("/runtime/web/next_page",$MY_NAME);
	require($G_SAVING_URL);
}
else if($ACTION_POST=="adv_trafficmanage")
{
	$dirty=0;
	$SUBMIT_STR="";
	if($action=="add")
	{
		$s=1;
		set("/trafficctrl/trafficmgr/enable",1);
		set("/tc_monitor/state", 0);
		for("/trafficctrl/trafficmgr/rule/index")
		{$s++;}
		anchor("/trafficctrl/trafficmgr/rule");
		set("index:".$s."/name",$tra_name);
		set("index:".$s."/clientip",$cli_ip);
		set("index:".$s."/clientmac",$cli_mac);
		set("index:".$s."/downlink",$e2wrule);
		set("index:".$s."/uplink",$w2erule);	
		$dirty++;
	}

	if($action=="edit")
	{
		set("/trafficctrl/trafficmgr/enable",1);
		set("/tc_monitor/state", 0);
		anchor("/trafficctrl/trafficmgr/rule");
		if(query("index:".$which_edit."/name")!=$tra_name){set("index:".$which_edit."/name",$tra_name);$dirty++;}
		if(query("index:".$which_edit."/clientip")!=$cli_ip){set("index:".$which_edit."/clientip",$cli_ip);$dirty++;}
		if(query("index:".$which_edit."/clientmac")!=$cli_mac){set("index:".$which_edit."/clientmac",$cli_mac);$dirty++;}
		if(query("index:".$which_edit."/downlink")!=$e2wrule){set("index:".$which_edit."/downlink",$e2wrule);$dirty++;}
		if(query("index:".$which_edit."/uplink")!=$w2erule){set("index:".$which_edit."/uplink",$w2erule);$dirty++;}
	}

	if($action=="delete")
	{
		del("/trafficctrl/trafficmgr/rule/index:".$which_delete);
		$dirty++;
	}

  	anchor("/trafficctrl");
  	if($action=="apply")
  	{
		if(query("trafficmgr/enable")!=$tramgr_state){set("trafficmgr/enable",$tramgr_state);$dirty++;}
  		if($tramgr_state==1)
  		{
  			if(query("trafficmgr/unlistclientstraffic")!=$denyorforward){set("trafficmgr/unlistclientstraffic",$denyorforward);$dirty++;}
			set("/tc_monitor/state", 0);
  		}
	}
  
	if($dirty > 0)
	{	
		set("/runtime/web/submit/trafficmgrdy", 1);		
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}	

	set("/runtime/web/next_page",$MY_NAME);
	require($G_SAVING_URL);
}
else if($ACTION_POST=="adv_trafficmanage_dy")
{
	$dirty=0;
	$dirty_s=0;
	$dirty_a=0;
	$SUBMIT_STR="";
	echo "<!--\n";
	echo "which_edit = ". $which_edit ."\n";
	echo "averagetype = ". $averagetype ."\n";
	echo "ssid = ". $ssid ."\n";
	echo "band = ". $band ."\n";
	echo "-->\n";
	if($band == 0)
	{
			$band_index=0;
			$traff_c_index=1;
	}
	else
	{
		$band_index=16;
		$traff_c_index=9;
	}
	if($action=="add")
	{		
		
			if($tramgr_type==1)
			{
				
					$s=1;
					$ath=$band_index+$ssid;
					$s=$ssid+$traff_c_index;
					anchor("/tc_monitor");
					set("mssid:".$s."/name","ath".$ath);
					set("mssid:".$s."/nameindex",$ssid);
					set("mssid:".$s."/band",$band);
					set("mssid:".$s."/state",$averagetype);		
					set("mssid:".$s."/downrate",$e2wrule_a);
					set("mssid:".$s."/uprate",$w2erule_a);	
					set("mssid:".$s."/upratetype",$speed_w);	
					set("mssid:".$s."/downratetype",$speed_e);
						$dirty++;	
			}
			else
			{
				$s=1;
					set("/trafficctrl/trafficmgr/enable",1);
					for("/trafficctrl/trafficmgr/rule/index")
					{$s++;}
					anchor("/trafficctrl/trafficmgr/rule");
					set("index:".$s."/name",$tra_name);
					set("index:".$s."/clientip",$cli_ip);
					set("index:".$s."/clientmac",$cli_mac);
					set("index:".$s."/downlink",$e2wrule);
					set("index:".$s."/uplink",$w2erule);	
					$dirty++;	
			}
			if(query("/tc_monitor/state")!=$tramgr_type){set("/tc_monitor/state",$tramgr_type);}	
	}

	if($action=="edit")
	{
		if($tramgr_type==1)
			{
					
					$ath=$band_index+$ssid;
					anchor("/tc_monitor");
					set("mssid:".$which_edit."/state",$averagetype);
					set("mssid:".$which_edit."/name","ath".$ath);
					set("mssid:".$which_edit."/nameindex",$ssid);
					set("mssid:".$which_edit."/band",$band);
					set("mssid:".$which_edit."/downrate",$e2wrule_a);
					set("mssid:".$which_edit."/uprate",$w2erule_a);	
					set("mssid:".$which_edit."/upratetype",$speed_w);	
					set("mssid:".$which_edit."/downratetype",$speed_e);
					$dirty++;
				
			}
			else
			{		
					set("/trafficctrl/trafficmgr/enable",1);
					anchor("/trafficctrl/trafficmgr/rule");
					if(query("index:".$which_edit."/name")!=$tra_name){set("index:".$which_edit."/name",$tra_name);$dirty++;}
					if(query("index:".$which_edit."/clientip")!=$cli_ip){set("index:".$which_edit."/clientip",$cli_ip);$dirty++;}
					if(query("index:".$which_edit."/clientmac")!=$cli_mac){set("index:".$which_edit."/clientmac",$cli_mac);$dirty++;}
					if(query("index:".$which_edit."/downlink")!=$e2wrule){set("index:".$which_edit."/downlink",$e2wrule);$dirty++;}
					if(query("index:".$which_edit."/uplink")!=$w2erule){set("index:".$which_edit."/uplink",$w2erule);$dirty++;}
			}
			if(query("/tc_monitor/state")!=$tramgr_type){set("/tc_monitor/state",$tramgr_type);}
	}

	if($action=="delete")
	{
			$ath=$band_index+$ssid;
			if($tramgr_type==1)
			{
						set("/tc_monitor/mssid:".$which_delete."/state",0);
			}
			else
			{	
					del("/trafficctrl/trafficmgr/rule/index:".$which_delete);
			}
			if(query("/tc_monitor/state")!=$tramgr_type){set("/tc_monitor/state",$tramgr_type);}
			$dirty++;
		
	}

  	anchor("/trafficctrl");
  	if($action=="apply")
  	{
  		if($tramgr_state==1)
  		{
  				if(query("/trafficctrl/trafficmgr/enable")!=$tramgr_state){set("/trafficctrl/trafficmgr/enable",$tramgr_state);}
  				if(query("/tc_monitor/state")!=$tramgr_type){set("/tc_monitor/state",$tramgr_type);}
				if(query("trafficmgr/unlistclientstraffic")!=$denyorforward){set("trafficmgr/unlistclientstraffic",$denyorforward);$dirty++;}
  				$dirty++;	
	  	}	
  		else
  		{
				if(query("/trafficctrl/trafficmgr/enable")!=$tramgr_state){set("/trafficctrl/trafficmgr/enable",$tramgr_state);}
			  	$dirty++;
  				  	
  		}
  	}
 
	if($dirty > 0)
	{	
		set("/runtime/web/submit/trafficmgrdy", 1);	
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}	

	set("/runtime/web/next_page",$MY_NAME);
	require($G_SAVING_URL);
}
else if($ACTION_POST=="adv_fair")
{
    $dirty=0;
    $dirty_s=0;
    $dirty_a=0;
    $SUBMIT_STR="";
    echo "<!--\n";
    echo "averagetype = ". $averagetype ."\n";
    echo "ssid = ". $ssid ."\n";
    echo "band = ". $band ."\n";
    echo "-->\n";
	if($switch == 1)
	{
		$band_index=0;
		$traff_c_index=1;
	}
	else
	{
		if($band == 0)
		{
			$band_index=0;
			$traff_c_index=1;
		}
		else
		{
			$band_index=16;
			$traff_c_index=9;
		}
	}
	if($action=="add")
	{
		set("/tc_monitor/state",1);
		set("/trafficctrl/trafficmgr/enable", 0);
		set("/trafficctrl/qos/enable", 0);	
		$s=1;
		$ath=$band_index+$ssid;
		$s=$ssid+$traff_c_index;
		anchor("/tc_monitor");
		set("mssid:".$s."/name","ath".$ath);
		set("mssid:".$s."/nameindex",$ssid);
		set("mssid:".$s."/band",$band);
		set("mssid:".$s."/state",$averagetype);
		set("mssid:".$s."/downrate",$e2wrule_a);
		set("mssid:".$s."/uprate",$w2erule_a);
		set("mssid:".$s."/upratetype",$speed_w);
		set("mssid:".$s."/downratetype",$speed_e);
		$dirty++;
	}
	if($action=="delete")
	{
		$ath=$band_index+$ssid;
		set("/tc_monitor/mssid:".$which_delete."/state",0);
		$dirty++;
	}
	anchor("/trafficctrl");
	if($action=="apply")
	{
		if(query("/tc_monitor/state")!=$fair_state){set("/tc_monitor/state",$fair_state);}
		if($fair_state == 1)
		{
			set("/trafficctrl/trafficmgr/enable", 0);
			set("/trafficctrl/qos/enable", 0);
			if(query("/tc_monitor/uplink")!=$w2e){set("/tc_monitor/uplink",$w2e);}
			if(query("/tc_monitor/downlink")!=$e2w){set("/tc_monitor/downlink",$e2w);}
		}
		$dirty++;
	}

    if($dirty > 0)
    {
        set("/runtime/web/submit/trafficmgrdy", 1);
        set("/runtime/web/sub_str",$SUBMIT_STR);
    }

    set("/runtime/web/next_page",$MY_NAME);
    require($G_SAVING_URL);
}
else if($ACTION_POST == "adv_8021q")
{
	echo "<!--\n";
	echo "ACTION_POST=".			$ACTION_POST.			"\n";
	echo "f_vlan=".			$f_vlan.			"\n";
	echo "f_rule_del=".			$f_rule_del.			"\n";
	echo "-->\n";	
	
	$SUBMIT_STR = "";
	$dirty = 0;		
	$dirty_wlan = 0;	
	$limit_dirty = 0;
	
	if(query("/sys/vlan_state")!=$f_vlan)	
	{
		set("/sys/vlan_state",$f_vlan); 
		if (query("/sys/vlan_mode")!= 1) 
		{
			$dirty++;
		}
		else
		{
			$dirty++;
			$dirty_wlan++;
		}
	}
	if($f_vlan == 1)
	{
		if(query("/sys/adminlimit/status")==3){set("/sys/adminlimit/status",2);$limit_dirty++;}
		if(query("/sys/adminlimit/status")==1){set("/sys/adminlimit/status",0);$limit_dirty++;}

		if(query("/runtime/web/display/mssid_support_sharedkey") == 0)//mssid not support Shared Key	
		{			
			if(query("/wlan/inf:1/authentication") == 1) //Shared Key
			{
				set("/wlan/inf:1/authentication", 0); //open
				$dirty_wlan++;
			}
		}	
		
		if(query("/wlan/inf:1/ap_mode") == 1 || query("/wlan/inf:1/ap_mode") == 2) // if APC,set to AP
		{
			set("/wlan/inf:1/ap_mode", 0);
			$dirty_wlan++;
		}	
		if(query("/wlan/inf:2/ap_mode") == 1 || query("/wlan/inf:2/ap_mode") == 2) //if APC,set to AP
        {
            set("/wlan/inf:2/ap_mode", 0);
            $dirty_wlan++;
        }
		
		if($dirty_wlan != 0)	
		{
			set("/runtime/web/check/vlan/mssid_enable", 1);
		}
	}
	
	if($f_vlan == 1)
	{
		if($f_rule_del!="")
		{
			$dirty++;
			if(query("/sys/group_vlan/index:".$f_rule_del."/sys:1/egress") == 2 && $f_pvid_auto == 1)
			{
				if(query("/sys/vlan_id") == query("/sys/group_vlan/index:".$f_rule_del."/group_vid"))
				{
					$del_admin_vid = 1;
					for("/sys/group_vlan/index")
					{
						if($@!=$f_rule_del)
						{
							if(query("/sys/group_vlan/index:".$@."/sys:1/egress") == 2)
							{
								$del_admin_vid = query("/sys/group_vlan/index:".$@."/group_vid");
							}
						}
					}
					set("/sys/vlan_id",$del_admin_vid);$dirty++;
				}
			}
					
			if(query("/sys/group_vlan/index:".$f_rule_del."/eth:1/egress") == 2 && $f_pvid_auto == 1)
			{
				if(query("/lan/ethernet/vlan_id") == query("/sys/group_vlan/index:".$f_rule_del."/group_vid"))
				{
					$del_eth_vid = 1;
					for("/sys/group_vlan/index")
					{
						if($@!=$f_rule_del)
						{
							if(query("/sys/group_vlan/index:".$@."/eth:1/egress") == 2)
							{
								$del_eth_vid = query("/sys/group_vlan/index:".$@."/group_vid");
							}
						}
					}
					set("/lan/ethernet/vlan_id",$del_eth_vid);$dirty++;
				}
			}	
			if(query("/sys/group_vlan/index:".$f_rule_del."/eth:2/egress") == 2 && $f_pvid_auto == 1)
            {
                if(query("/lan/ethernet:2/vlan_id") == query("/sys/group_vlan/index:".$f_rule_del."/group_vid"))
                {
                    $del_eth_vid2 = 1;
                    for("/sys/group_vlan/index")
                    {
                        if($@!=$f_rule_del)
                        {
                            if(query("/sys/group_vlan/index:".$@."/eth:2/egress") == 2)
                            {
                                $del_eth_vid2 = query("/sys/group_vlan/index:".$@."/group_vid");
                            }
                        }
                    }
                    set("/lan/ethernet:2/vlan_id",$del_eth_vid2);$dirty++;
				}
			}	
			
			$del_ath_idx = 1;
			while($del_ath_idx < 33)
			{
				if(query("/sys/group_vlan/index:".$f_rule_del."/ath:".$del_ath_idx."/egress") == 2 && $f_pvid_auto == 1)
				{	
					if($del_ath_idx == 1)
					{
						if(query("/wlan/inf:1/vlan_id") == query("/sys/group_vlan/index:".$f_rule_del."/group_vid"))
						{
							$del_ath_vid = 1;
							for("/sys/group_vlan/index")
							{
								if($@!=$f_rule_del)
								{
									if(query("/sys/group_vlan/index:".$@."/ath:1/egress") == 2)
									{
										$del_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
									}
								}
							}
							set("/wlan/inf:1/vlan_id",$del_ath_vid);$dirty++;
						}
					}
                    if($del_ath_idx == 17)
                    {
                        if(query("/wlan/inf:2/vlan_id") == query("/sys/group_vlan/index:".$f_rule_del."/group_vid"))
                        {
                            $del_ath_vid = 1;
                            for("/sys/group_vlan/index")
                            {
                                if($@!=$f_rule_del)
                                {
                                    if(query("/sys/group_vlan/index:".$@."/ath:17/egress") == 2)
                                    {
                                        $del_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
                                    }
                                }
                            }
                            set("/wlan/inf:2/vlan_id",$del_ath_vid);$dirty++;
                        }
                    }
					else if($del_ath_idx > 1 && $del_ath_idx < 9)
					{
						$multi_idx = $del_ath_idx - 1;
						
						if(query("/wlan/inf:1/multi/index:".$multi_idx."/vlan_id") == query("/sys/group_vlan/index:".$f_rule_del."/group_vid"))
						{
							$del_ath_vid = 1;
							for("/sys/group_vlan/index")
							{
								if($@!=$f_rule_del)
								{
									if(query("/sys/group_vlan/index:".$@."/ath:".$del_ath_idx."/egress") == 2)
									{
										$del_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
									}
								}
							}
							set("/wlan/inf:1/multi/index:".$multi_idx."/vlan_id",$del_ath_vid);$dirty++;
						}						
					}
					else if($del_ath_idx > 8 && $del_ath_idx < 17)
					{
						$w_index = $del_ath_idx - 8;
						
						if(query("/wlan/inf:1/wds/index:".$w_index."/vlan_id") == query("/sys/group_vlan/index:".$f_rule_del."/group_vid"))
						{
							$del_ath_vid = 1;
							for("/sys/group_vlan/index")
							{
								if($@!=$f_rule_del)
								{
									if(query("/sys/group_vlan/index:".$@."/ath:".$del_ath_idx."/egress") == 2)
									{
										$del_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
									}
								}
							}
							set("/wlan/inf:1/wds/index:".$w_index."/vlan_id",$del_ath_vid);$dirty++;
						}						
					}
					else if($del_ath_idx > 17 && $del_ath_idx < 25)
                    {
                        $multi_idx = $del_ath_idx - 17;

                        if(query("/wlan/inf:2/multi/index:".$multi_idx."/vlan_id") == query("/sys/group_vlan/index:".$f_rule_del."/group_vid"))
                        {
                            $del_ath_vid = 1;
                            for("/sys/group_vlan/index")
                            {
                                if($@!=$f_rule_del)
                                {
                                    if(query("/sys/group_vlan/index:".$@."/ath:".$del_ath_idx."/egress") == 2)
                                    {
                                        $del_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
                                    }
                                }
                            }
                            set("/wlan/inf:2/multi/index:".$multi_idx."/vlan_id",$del_ath_vid);$dirty++;
                        }
                    }
					else if($del_ath_idx >= 25)
					{
						$w_index = $del_ath_idx - 24;
						
						if(query("/wlan/inf:2/wds/index:".$w_index."/vlan_id") == query("/sys/group_vlan/index:".$f_rule_del."/group_vid"))
						{
							$del_ath_vid = 1;
							for("/sys/group_vlan/index")
							{
								if($@!=$f_rule_del)
								{
									if(query("/sys/group_vlan/index:".$@."/ath:".$del_ath_idx."/egress") == 2)
									{
										$del_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
									}
								}
							}
							set("/wlan/inf:2/wds/index:".$w_index."/vlan_id",$del_ath_vid);$dirty++;
						}						
					}					
				}					
				$del_ath_idx++;
			}

			del("/sys/group_vlan/index:".$f_rule_del);	
		}				
		if($f_apply =="rule_add")
		{
			$index=1;
			
			if($f_rule_edit !="")
			{
				$index = $f_rule_edit;
			}
			else
			{
				for("/sys/group_vlan/index")
				{
					$index++;
				}
			}	

			if($f_rule_edit =="")
			{
				if(query("/sys/group_vlan/index:".$index."/group_vid")!=$f_vid)	{set("/sys/group_vlan/index:".$index."/group_vid",$f_vid); $dirty++;}
			}
			if(query("/sys/group_vlan/index:".$index."/group_name")!=$f_ssid)	{set("/sys/group_vlan/index:".$index."/group_name",$f_ssid); $dirty++;}
			
			if(query("/sys/group_vlan/index:".$index."/sys:1/egress")!=$f_sys_egress)	{set("/sys/group_vlan/index:".$index."/sys:1/egress",$f_sys_egress); $dirty++;}
			
			if($f_pvid_auto == 1)
			{
				if($f_sys_egress == 2)
				{	
					set("/sys/vlan_id",$f_vid);$dirty++;
				}
				else if($f_sys_egress != 2 && $f_rule_edit !="")
				{
					if(query("/sys/vlan_id") == query("/sys/group_vlan/index:".$f_rule_edit."/group_vid"))
					{
						$edit_admin_vid = 1;
						for("/sys/group_vlan/index")
						{
							if($@!=$f_rule_edit)
							{
								if(query("/sys/group_vlan/index:".$@."/sys:1/egress") == 2)
								{
									$edit_admin_vid = query("/sys/group_vlan/index:".$@."/group_vid");
								}
							}
						}
						set("/sys/vlan_id",$edit_admin_vid);$dirty++;						
					}
				}
			}
						
			if(query("/sys/group_vlan/index:".$index."/eth:1/egress")!=$f_eth_egress)	{set("/sys/group_vlan/index:".$index."/eth:1/egress",$f_eth_egress); $dirty++;}
			if(query("/sys/group_vlan/index:".$index."/eth:2/egress")!=$f_eth_egress2)   {set("/sys/group_vlan/index:".$index."/eth:2/egress",$f_eth_egress2); $dirty++;}
			
			if($f_pvid_auto == 1)
			{
				if($f_eth_egress == 2)
				{
					set("/lan/ethernet/vlan_id",$f_vid);$dirty++;
				}	
				else if($f_sys_egress != 2 && $f_rule_edit !="")
				{
					if(query("/lan/ethernet/vlan_id") == query("/sys/group_vlan/index:".$f_rule_edit."/group_vid"))
					{
						$edit_eth_vid = 1;
						for("/sys/group_vlan/index")
						{
							if($@!=$f_rule_edit)
							{
								if(query("/sys/group_vlan/index:".$@."/eth:1/egress") == 2)
								{
									$edit_eth_vid = query("/sys/group_vlan/index:".$@."/group_vid");
								}
							}
						}
						set("/lan/ethernet/vlan_id",$edit_eth_vid);$dirty++;
					}
				}					
				if($f_eth_egress2 == 2)
                {
                    set("/lan/ethernet:2/vlan_id",$f_vid);$dirty++;
                }
				else if($f_sys_egress2 != 2 && $f_rule_edit !="")
                {
                    if(query("/lan/ethernet:2/vlan_id") == query("/sys/group_vlan/index:".$f_rule_edit."/group_vid"))
                    {
                        $edit_eth_vid2 = 1;
                        for("/sys/group_vlan/index")
                        {
                            if($@!=$f_rule_edit)
                            {
                                if(query("/sys/group_vlan/index:".$@."/eth:2/egress") == 2)
                                {
                                    $edit_eth_vid2 = query("/sys/group_vlan/index:".$@."/group_vid");
                                }
                            }
                        }
                        set("/lan/ethernet:2/vlan_id",$edit_eth_vid2);$dirty++;
                    }
                }
			}			
			
			$ath_idx = 1;
			$ssid_flag=query("/runtime/web/display/mssid_index4");
			$flag=5;
			while($ath_idx< 33)
			{
				if ($ssid_flag=="1")
				{
					if($ath_idx==5)
					{
						$ath_idx=$ath_idx+4;
						while($flag <= 12 )
						{
							set("/sys/group_vlan/index:".$index."/ath:".$flag."/egress",1);
							$flag++;
						}
					}
				$ath_egress	= "f_ath".$ath_idx."_egress";
				if(query("/sys/group_vlan/index:".$index."/ath:".$ath_idx."/egress")!=$$ath_egress)	{set("/sys/group_vlan/index:".$index."/ath:".$ath_idx."/egress",$$ath_egress); $dirty++;}
				}
				else
				{
				$ath_egress	= "f_ath".$ath_idx."_egress";
				if(query("/sys/group_vlan/index:".$index."/ath:".$ath_idx."/egress")!=$$ath_egress)	{set("/sys/group_vlan/index:".$index."/ath:".$ath_idx."/egress",$$ath_egress); $dirty++;}
				}
				if(query("/sys/group_vlan/index:".$index."/ath:".$ath_idx."/egress")!=$$ath_egress)	{set("/sys/group_vlan/index:".$index."/ath:".$ath_idx."/egress",$$ath_egress); $dirty++;}
				
				if($f_pvid_auto == 1)
				{
					if($$ath_egress == 2)
					{
						if($ath_idx == 1)
						{
							set("/wlan/inf:1/vlan_id",$f_vid); $dirty++;
						}
						if($ath_idx == 17)
                        {
                            set("/wlan/inf:2/vlan_id",$f_vid); $dirty++;
                        }

						else if($ath_idx > 1 && $ath_idx < 9)
						{
							$ms_idx = $ath_idx-1;
							set("/wlan/inf:1/multi/index:".$ms_idx."/vlan_id",$f_vid); $dirty++;
						}
						else if($ath_idx > 8 && $ath_idx < 17)
						{
							$wds_idx = $ath_idx-8;
							set("/wlan/inf:1/wds/index:".$wds_idx."/vlan_id",$f_vid); $dirty++;
						}
						else if($ath_idx > 17 && $ath_idx < 25)
                        {
                            $ms_idx = $ath_idx-17;
                            set("/wlan/inf:2/multi/index:".$ms_idx."/vlan_id",$f_vid); $dirty++;
                        }
                        else if($ath_idx >=25)
                        {
                            $wds_idx = $ath_idx-24;
                            set("/wlan/inf:2/wds/index:".$wds_idx."/vlan_id",$f_vid); $dirty++;
						}
					}
					else if($$ath_egress != 2 && $f_rule_edit !="")
					{
						if($ath_idx == 1)
						{
							if(query("/wlan/inf:1/vlan_id") == query("/sys/group_vlan/index:".$f_rule_edit."/group_vid"))
							{
								$edit_ath_vid = 1;
								for("/sys/group_vlan/index")
								{
									if($@!=$f_rule_edit)
									{
										if(query("/sys/group_vlan/index:".$@."/ath:1/egress") == 2)
										{
											$edit_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
										}
									}
								}
								set("/wlan/inf:1/vlan_id",$edit_ath_vid);$dirty++;
							}
						}
                        if($ath_idx == 17)
                        {
                            if(query("/wlan/inf:2/vlan_id") == query("/sys/group_vlan/index:".$f_rule_edit."/group_vid"))
                            {
                                $edit_ath_vid = 1;
                                for("/sys/group_vlan/index")
                                {
                                    if($@!=$f_rule_edit)
                                    {
                                        if(query("/sys/group_vlan/index:".$@."/ath:17/egress") == 2)
                                        {
                                            $edit_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
                                        }
                                    }
                                }
                                set("/wlan/inf:2/vlan_id",$edit_ath_vid);$dirty++;
                            }
                        }	
						else if($ath_idx > 1 && $ath_idx < 9)
						{
							$multi_idx = $ath_idx - 1;
							if(query("/wlan/inf:1/multi/index:".$multi_idx."/vlan_id") == query("/sys/group_vlan/index:".$f_rule_edit."/group_vid"))
							{
								$edit_ath_vid = 1;
								for("/sys/group_vlan/index")
								{
									if($@!=$f_rule_edit)
									{
										if(query("/sys/group_vlan/index:".$@."/ath:".$ath_idx."/egress") == 2)
										{
											$edit_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
										}
									}
								}
								set("/wlan/inf:1/multi/index:".$multi_idx."/vlan_id",$edit_ath_vid);$dirty++;
							}								
						}	
						else if($ath_idx > 8 && $ath_idx < 17)
						{						
							$w_index = $ath_idx - 8;								
							if(query("/wlan/inf:1/wds/index:".$w_index."/vlan_id") == query("/sys/group_vlan/index:".$f_rule_edit."/group_vid"))
							{
								$edit_ath_vid = 1;
								for("/sys/group_vlan/index")
								{
									if($@!=$f_rule_edit)
									{
										if(query("/sys/group_vlan/index:".$@."/ath:".$ath_idx."/egress") == 2)
										{
											$edit_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
										}
									}
								}
								set("/wlan/inf:1/wds/index:".$w_index."/vlan_id",$edit_ath_vid);$dirty++;
							}								
						}
						else if($ath_idx > 17 && $ath_idx < 25)
						{
							$multi_idx = $ath_idx - 17;
							if(query("/wlan/inf:2/multi/index:".$multi_idx."/vlan_id") == query("/sys/group_vlan/index:".$f_rule_edit."/group_vid"))
							{
								$edit_ath_vid = 1;
								for("/sys/group_vlan/index")
								{
									if($@!=$f_rule_edit)
									{
										if(query("/sys/group_vlan/index:".$@."/ath:".$ath_idx."/egress") == 2)
										{
											$edit_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
										}
									}
								}
								set("/wlan/inf:2/multi/index:".$multi_idx."/vlan_id",$edit_ath_vid);$dirty++;
							}								
						}
						else if($ath_idx >= 25)
                        {
                            $w_index = $ath_idx - 24;
                            if(query("/wlan/inf:2/wds/index:".$w_index."/vlan_id") == query("/sys/group_vlan/index:".$f_rule_edit."/group_vid"))
                            {
                                $edit_ath_vid = 1;
                                for("/sys/group_vlan/index")
                                {
                                    if($@!=$f_rule_edit)
                                    {
                                        if(query("/sys/group_vlan/index:".$@."/ath:".$ath_idx."/egress") == 2)
                                        {
                                            $edit_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
                                        }
                                    }
                                }
                                set("/wlan/inf:2/wds/index:".$w_index."/vlan_id",$edit_ath_vid);$dirty++;
                            }
                        }
										
					}				
				}					
				if ($ssid_flag=="1")
				{
					if($ath_idx==12)
					{
						$ath_idx+=4;	
					}		
				}
				$ath_idx++;
			}
		}

		if($f_apply =="rule")
		{
			$dirty++;
		}

		if($f_apply =="pvid")
		{
			$dirty++;
			if(query("/sys/auto_set_pvid")!=$f_pvid_auto)	{set("/sys/auto_set_pvid",$f_pvid_auto); $dirty++;}
	
			if($f_pvid_auto == 0)
			{
				if(query("/sys/vlan_id")!=$f_admin_pvid)	{set("/sys/vlan_id",$f_admin_pvid); $dirty++;}
				if(query("/lan/ethernet/vlan_id")!=$f_eth_pvid)	{set("/lan/ethernet/vlan_id",$f_eth_pvid); $dirty++;}
				if(query("/lan/ethernet:2/vlan_id")!=$f_eth_pvid2)  {set("/lan/ethernet:2/vlan_id",$f_eth_pvid2); $dirty++;}
				if(query("/wlan/inf:1/vlan_id")!=$f_pri_pvid)	{set("/wlan/inf:1/vlan_id",$f_pri_pvid); $dirty++;}
				if(query("/wlan/inf:2/vlan_id")!=$f_pri_pvid_a)	{set("/wlan/inf:2/vlan_id",$f_pri_pvid_a); $dirty++;}  	
				$ms_index = 1;
				while($ms_index< 9)
				{
					$ms_pvid_value	= "f_ms_".$ms_index."_pvid";
					if(query("/wlan/inf:1/multi/index:".$ms_index."/vlan_id")!=$$ms_pvid_value)	{set("/wlan/inf:1/multi/index:".$ms_index."/vlan_id",$$ms_pvid_value); $dirty++;}
					$ms_pvid_value_a	= "f_ms_".$ms_index."_pvid_a";
					if(query("/wlan/inf:2/multi/index:".$ms_index."/vlan_id")!=$$ms_pvid_value_a)	{set("/wlan/inf:2/multi/index:".$ms_index."/vlan_id",$$ms_pvid_value_a); $dirty++;}
					$ms_index++;
				}
				
				$wds_index = 1;
				while($wds_index< 9)
				{
					$wds_pvid_value	= "f_wds_".$wds_index."_pvid";
					if(query("/wlan/inf:1/wds/index:".$wds_index."/vlan_id")!=$$wds_pvid_value)	{set("/wlan/inf:1/wds/index:".$wds_index."/vlan_id",$$wds_pvid_value); $dirty++;}
					$wds_pvid_value_a	= "f_wds_".$wds_index."_pvid_a";
					if(query("/wlan/inf:2/wds/index:".$wds_index."/vlan_id")!=$$wds_pvid_value_a)	{set("/wlan/inf:2/wds/index:".$wds_index."/vlan_id",$$wds_pvid_value_a); $dirty++;}
					$wds_index++;
				}
			}
			else
			{
				$pvid_auto_admin_vid = 1;
				for("/sys/group_vlan/index")
				{
					if(query("/sys/group_vlan/index:".$@."/sys:1/egress") == 2)
					{
						$pvid_auto_admin_vid = query("/sys/group_vlan/index:".$@."/group_vid");
					}
				}
				set("/sys/vlan_id",$pvid_auto_admin_vid);$dirty++;
					

				$del_eth_vid = 1;
				for("/sys/group_vlan/index")
				{
					if(query("/sys/group_vlan/index:".$@."/eth:1/egress") == 2)
					{
						$del_eth_vid = query("/sys/group_vlan/index:".$@."/group_vid");
					}
				}
				set("/lan/ethernet/vlan_id",$del_eth_vid);$dirty++;
				$del_eth_vid2 = 1;
                for("/sys/group_vlan/index")
                {
                    if(query("/sys/group_vlan/index:".$@."/eth:2/egress") == 2)
                    {
                        $del_eth_vid2 = query("/sys/group_vlan/index:".$@."/group_vid");
                    }
                }
                set("/lan/ethernet:2/vlan_id",$del_eth_vid2);$dirty++;
				
				$pvid_auto_ath_idx = 1;
				while($pvid_auto_ath_idx < 33)
				{
					if($pvid_auto_ath_idx == 1)
					{
						$pvid_auto_ath_vid = 1;
						for("/sys/group_vlan/index")
						{
							if(query("/sys/group_vlan/index:".$@."/ath:1/egress") == 2)
							{
								$pvid_auto_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
							}
						}
						set("/wlan/inf:1/vlan_id",$pvid_auto_ath_vid);$dirty++;
					}
                    if($pvid_auto_ath_idx == 17)
                    {
                        $pvid_auto_ath_vid = 1;
                        for("/sys/group_vlan/index")
                        {
                            if(query("/sys/group_vlan/index:".$@."/ath:17/egress") == 2)
                            {
                                $pvid_auto_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
                            }
                        }
                        set("/wlan/inf:2/vlan_id",$pvid_auto_ath_vid);$dirty++;
                    }
					else if($pvid_auto_ath_idx > 1 && $pvid_auto_ath_idx < 9)
					{
						$multi_idx = $pvid_auto_ath_idx - 1;
						$pvid_auto_ath_vid = 1;
						for("/sys/group_vlan/index")
						{
							if(query("/sys/group_vlan/index:".$@."/ath:".$pvid_auto_ath_idx."/egress") == 2)
							{
								$pvid_auto_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
							}
						}
						set("/wlan/inf:1/multi/index:".$multi_idx."/vlan_id",$pvid_auto_ath_vid);$dirty++;				
					}
					else if($pvid_auto_ath_idx > 8 && $pvid_auto_ath_idx < 17)
					{
						$w_index = $pvid_auto_ath_idx - 8;
						$pvid_auto_ath_vid = 1;
						for("/sys/group_vlan/index")
						{
							if(query("/sys/group_vlan/index:".$@."/ath:".$pvid_auto_ath_idx."/egress") == 2)
							{
								$pvid_auto_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
							}
						}
						set("/wlan/inf:1/wds/index:".$w_index."/vlan_id",$pvid_auto_ath_vid);$dirty++;					
					}						
                    else if($pvid_auto_ath_idx > 17 && $pvid_auto_ath_idx < 25)
                    {
                        $multi_idx = $pvid_auto_ath_idx - 17;
                        $pvid_auto_ath_vid = 1;
                        for("/sys/group_vlan/index")
                        {
                            if(query("/sys/group_vlan/index:".$@."/ath:".$pvid_auto_ath_idx."/egress") == 2)
                            {
                                $pvid_auto_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
                            }
                        }
                        set("/wlan/inf:2/multi/index:".$multi_idx."/vlan_id",$pvid_auto_ath_vid);$dirty++;

                    }
                    else if($pvid_auto_ath_idx >= 25)
                    {
                        $w_index = $pvid_auto_ath_idx - 24;
                        $pvid_auto_ath_vid = 1;
                        for("/sys/group_vlan/index")
                        {
                            if(query("/sys/group_vlan/index:".$@."/ath:".$pvid_auto_ath_idx."/egress") == 2)
                            {
                                $pvid_auto_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
                            }
                        }
                        set("/wlan/inf:2/wds/index:".$w_index."/vlan_id",$pvid_auto_ath_vid);$dirty++;

                    }		
					$pvid_auto_ath_idx++;
				}
			}
		}
	}
	
	if($dirty > 0)
	{
		if($limit_dirty > 0)
		{
			set("/runtime/web/submit/limit", 1);
		}
		if($f_apply =="rule_add" || $f_rule_del!="")
		{
			set("/runtime/web/submit/vlan", 1);
			set("/runtime/web/submit/vlanportlist", 1);
		}	
		else
		{
			if(query("/runtime/web/check/vlan/mssid_enable") == 1 || $dirty_wlan > 0)
			{	
				set("/runtime/web/submit/wlan", 1);
				set("/runtime/web/submit/vlanportlist", 1);
			}
			else
			{
				set("/runtime/web/submit/vlan", 1);
				set("/runtime/web/submit/vlanportlist", 1);
			}
		}
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}	

	
	set("/runtime/web/next_page",$ACTION_POST);
	require($G_SAVING_URL);
}
else if($ACTION_POST == "adv_wtp")
{
	echo "<!--\n";
	echo "ACTION_POST=".			$ACTION_POST.			"\n";
	echo "-->\n";	
	
	$SUBMIT_STR = "";

	if($f_wtp_del!="")//del
	{
		$dirty++;
		del("/sys/wtp/acstatic/ip:".$f_wtp_del);	
	}	
	else if($f_add != "") //add
	{
		if(query("/sys/wtp/enable")	!=$f_wtp_enable)	{set("/sys/wtp/enable", $f_wtp_enable);	$dirty++;}				
		
		$ip_num=1;
		for("/sys/wtp/acstatic/ip"){$ip_num++;}
		if(query("/sys/wtp/acstatic/ip:".$ip_num)	!=$ac_ipaddr)	{set("/sys/wtp/acstatic/ip:".$ip_num,	$ac_ipaddr);		$dirty++;}				
	}
	else //apply
	{
		$dirty++;
		if(query("/sys/wtp/enable")	!=$f_wtp_enable)	{set("/sys/wtp/enable", $f_wtp_enable);	$dirty++;}				
	}
		
	if($dirty > 0)
	{
		if($f_add != "" || $f_wtp_del!="")
		{
			set("/runtime/web/submit/commit",1);
		}
		else
		{
			set("/runtime/web/submit/wtp", 1);
		}		
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}	

	
	set("/runtime/web/next_page",$MY_NAME);
	require($G_SAVING_URL);
}		
else if($ACTION_POST == "adv_ap_array")
{
	echo "<!--\n";
	echo "ACTION_POST=".			$ACTION_POST.			"\n";
	echo "-->\n";	
	
	$SUBMIT_STR = "";
	$dirty=0;
	if($f_scan_value == "1")
	{
		set("/runtime/web/check_scan_value", 1);
		set("/runtime/web/ap_array/scan",1);
		$dirty++;	
		anchor("/runtime/web/wlan/inf:1");
	}
	else
	{
		anchor("/wlan/inf:1");
	}
	if(query("aparray_enable")	!=$f_ap_array_enable)	
	{
		set("aparray_enable", $f_ap_array_enable);	
		$dirty++;
		if($f_ap_array_enable==1)
        {
            set("/sys/adminlimit/status", 0);
	    set("/wlan/inf:1/aparray_cfg_version",1);
            $limit_dirty++;
        }
	}				
	if(query("arrayrole_original")	!=$f_role)	{set("arrayrole_original", $f_role);	$dirty++;}			
	if(query("arrayname")	!=$ap_array_name)	{set("arrayname", $ap_array_name);	$dirty++;}			
	if($f_scan_value != "1")
	{
		if(queryEnc("aparray_password")	!=$ap_array_pwd)	{setEnc("aparray_password", $ap_array_pwd);	$dirty++;}
		anchor("/aparray/sync");
	}
	if(query("ssid")	!=$bsc_network_name)	{set("ssid", $bsc_network_name);	$dirty++;}				
	if(query("ssidhidden")	!=$bsc_ssid_visibility)	{set("ssidhidden", $bsc_ssid_visibility);	$dirty++;}			
	if(query("autochannel")	!=$bsc_auto_chann)	{set("autochannel", $bsc_auto_chann);	$dirty++;}			
	if(query("channelwidth")	!=$bsc_channel_width)	{set("channelwidth", $bsc_channel_width);	$dirty++;}			
	if(query("security")	!=$bsc_security)	{set("security", $bsc_security);	$dirty++;}				
	if(query("band")    !=$bsc_band)    {set("band", $bsc_band);    $dirty++;}
	if(query("fixedrate")	!=$adv_data_rate)	{set("fixedrate", $adv_data_rate);	$dirty++;}			
	if(query("beaconinterval")	!=$adv_beacon_interval)	{set("beaconinterval", $adv_beacon_interval);	$dirty++;}			
	if(query("dtim")	!=$adv_dtim)	{set("dtim", $adv_dtim);	$dirty++;}			
	if(query("txpower")	!=$adv_transmit_power)	{set("txpower", $adv_transmit_power);	$dirty++;}				
	if(query("wmm")	!=$adv_wmm_wifi)	{set("wmm", $adv_wmm_wifi);	$dirty++;}			
	if(query("acktimeout")	!=$adv_ack_timeout)	{set("acktimeout", $adv_ack_timeout);	$dirty++;}			
	if(query("shortgi")	!=$adv_short_gi)	{set("shortgi", $adv_short_gi);	$dirty++;}			
	if(query("igmpsnoop")	!=$adv_igmp)	{set("igmpsnoop", $adv_igmp);	$dirty++;}				
	if(query("connectionlimit")	!=$adv_conn_limit)	{set("connectionlimit", $adv_conn_limit);	$dirty++;}			
	if(query("linkintegrity")	!=$adv_link_integrity)	{set("linkintegrity", $adv_link_integrity);	$dirty++;}			
	if(query("multi/ssid")	!=$mssid)	{set("multi/ssid", $mssid);	$dirty++;}			
	if(query("multi/ssid_hidden")	!=$mssid_visibility)	{set("multi/ssid_hidden", $mssid_visibility);	$dirty++;}				
	if(query("multi/security")	!=$msecurity)	{set("multi/security", $msecurity);	$dirty++;}			
	if(query("multi/wmm")	!=$mwmm)	{set("multi/wmm", $mwmm);	$dirty++;}			
	if(query("qos")	!=$qos_setting)	{set("qos", $qos_setting);	$dirty++;}			
	if(query("vlan")	!=$vlan)	{set("vlan", $vlan);	$dirty++;}				
	if(query("schedule")	!=$schedule_settings)	{set("schedule", $schedule_settings);	$dirty++;}			
	if(query("time")	!=$time_date)	{set("time", $time_date);	$dirty++;}			
	if(query("log")	!=$log_setting)	{set("log", $log_setting);	$dirty++;}			
	if(query("adminlimit")	!=$limit_admin)	{set("adminlimit", $limit_admin);	$dirty++;}				
	if(query("system")	!=$sys_name_setting)	{set("system", $sys_name_setting);	$dirty++;}			
	if(query("consoleprotocol")	!=$console_setting)	{set("consoleprotocol", $console_setting);	$dirty++;}			
	if(query("snmp")	!=$snmp_setting)	{set("snmp", $snmp_setting);	$dirty++;}			
	if(query("pingctl")	!=$ping_control_setting)	{set("pingctl", $ping_control_setting);	$dirty++;}				
	if(query("dhcp")	!=$dhcp_svr_setting)	{set("dhcp", $dhcp_svr_setting);	$dirty++;}			
	if(query("login")	!=$login_setting)	{set("login", $login_setting);	$dirty++;}			
	if(query("acl")	!=$adv_acl)	{set("acl", $adv_acl);	$dirty++;}			
	if($dirty > 0)
	{
		if($f_scan_value == "1")
		{
	//		$SUBMIT_STR="submit COMMIT";	
		}
		else
		{
		//	$SUBMIT_STR="submit AP_ARRAY";
			set("/runtime/web/submit/ap_array",1);
		}	
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}
	if($limit_dirty > 0 )
	{
			set("/runtime/web/submit/limit", 1);
	}
	set("/runtime/web/next_page",$MY_NAME);
	if($dirty>0 && $f_scan_value=="1")	{require($G_SCAN_URL);}
	else /* if($SUBMIT_STR!="" && $f_scan_value!="1")*/	{require($G_SAVING_URL);}	
	//else				{require($G_NO_CHANGED_URL);}
}
else if($ACTION_POST == "adv_arpspoofing")
{
    $dirty=0;
    $SUBMIT_STR="";

    if(query("/arpspoofing/enable")!=$arp_state){set("/arpspoofing/enable",$arp_state);$dirty++;}
    if($action=="add")
    {
        $s=1;
        for("/arpspoofing/static/index")
        {
            $s++;
        }
        anchor("/arpspoofing/static");
        set("index:".$s."/ip",$ip_addr);
        set("index:".$s."/mac",$harp_mac);
        $dirty++;
    }

    if($action=="edit")
    {
        anchor("/arpspoofing/static");
        if(query("index:".$which_edit."/ip")!=$ip_addr){set("index:".$which_edit."/ip",$ip_addr);$dirty++;}
        if(query("index:".$which_edit."/mac")!=$harp_mac){set("index:".$which_edit."/mac",$harp_mac);$dirty++;}
    }

    if($action=="delete")
    {
        del("/arpspoofing/static/index:".$which_delete);
        $dirty++;
    }
    if($action=="delete_all")
    {
        del("/arpspoofing/static");
        $dirty++;
    }

    if($dirty > 0)
    {

        set("/runtime/web/submit/arpspoofing", 1);
        set("/runtime/web/sub_str",$SUBMIT_STR);
    }

    set("/runtime/web/next_page",$MY_NAME);
    require($G_SAVING_URL);
}else if($ACTION_POST=="adv_radiusserver")	
{
	$dirty=0;
	$SUBMIT_STR="";
	
	if($action=="add")
	{
		$s=1;
		for("/wlan/inf:1/wpa/embradius/eap_user/index")
		{
			$s++;
		}
		anchor("/wlan/inf:1/wpa/embradius/eap_user");
		set("index:".$s."/name",$r_name);
		setEnc("index:".$s."/passwd",$r_password);
		set("index:".$s."/enable",$account_status);
		$dirty++;
	}
	
	if($action=="edit")
	{
		anchor("/wlan/inf:1/wpa/embradius/eap_user");
		if(query("index:".$which_edit."/name")!=$r_name){set("index:".$which_edit."/name",$r_name);$dirty++;}
		if(queryEnc("index:".$which_edit."/passwd")!=$r_password){setEnc("index:".$which_edit."/passwd",$r_password);$dirty++;}
		if(query("index:".$which_edit."/enable")!=$account_status){set("index:".$which_edit."/enable",$account_status);$dirty++;}
	}
	
	if($action=="delete")
	{
		del("/wlan/inf:1/wpa/embradius/eap_user/index:".$which_delete);
		$dirty++;
	}
	if($action=="save")
	{
		for("/wlan/inf:1/wpa/embradius/eap_user/index")
		{
			anchor("/wlan/inf:1/wpa/embradius/eap_user/index:".$@);
			$account_status	= "a_s".$@;
			if(query("enable")!=$$account_status){set("enable",$$account_status);$dirty++;}
			$dirty++;
		}
	}	
  
	if($dirty > 0)
	{	
		set("/runtime/web/submit/wlan", 1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}	

	set("/runtime/web/next_page",$MY_NAME);
	require($G_SAVING_URL);
}else if($ACTION_POST == "adv_url")
{
	echo "<!--\n";
	echo "ACTION_POST=".			$ACTION_POST.			"\n";
	echo "-->\n";	

	$dirty=0;
	$SUBMIT_STR = "";
	
	if($action=="add")
	{
		$s=1;
		for("/wlan/inf:1/webredirect/index")
		{
			$s++;
		}
		anchor("/wlan/inf:1/webredirect");
		set("index:".$s."/name",$r_name);
		setEnc("index:".$s."/passwd",$r_password);
		set("index:".$s."/enable",$account_status);
		$dirty++;
	}
	
	if($action=="edit")
	{
		anchor("/wlan/inf:1/webredirect");
		if(query("index:".$which_edit."/name")!=$r_name){set("index:".$which_edit."/name",$r_name);$dirty++;}
		if(queryEnc("index:".$which_edit."/passwd")!=$r_password){setEnc("index:".$which_edit."/passwd",$r_password);$dirty++;}
		if(query("index:".$which_edit."/enable")!=$account_status){set("index:".$which_edit."/enable",$account_status);$dirty++;}
	}

	if($action=="delete")
	{
		del("/wlan/inf:1/webredirect/index:".$which_delete);
		$dirty++;
	}
	else
	{
		if(query("/wlan/inf:1/webredirect/enable")!=$url_enable)
		{
			set("/wlan/inf:1/webredirect/enable",$url_enable);
			$dirty++;
			if($url_enable == 1)
            {
                if(query("/sys/adminlimit/status")==1){set("/sys/adminlimit/status", 0);$limit_dirty++;}/*limit_admin_status*/
                if(query("/sys/adminlimit/status")==3){set("/sys/adminlimit/status", 2);$limit_dirty++;}/*limit_admin_status*/
            }
		}
	}
	if($action=="save")
	{	
		for("/wlan/inf:1/webredirect/index")
		{
			$account_status	= "a_s".$@;
			if(query("enable")!=$$account_status){set("enable",$$account_status);$dirty++;}
			$dirty++;
		}
	}	
		
	if($dirty > 0)
	{
		//$SUBMIT_STR="submit COMMIT";
		//set("/runtime/web/submit/commit", 1);
		set("/runtime/web/submit/webredirect", 1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}

	set("/runtime/web/next_page",$MY_NAME);
	/*if($dirty > 0) {*/require($G_SAVING_URL);/*}
	else				{require($G_NO_CHANGED_URL);}*/
}		
else if($ACTION_POST == "adv_url_addr")
{
		echo "<!--\n";
	echo "ACTION_POST=".			$ACTION_POST.			"\n";
	echo "-->\n";	

	$dirty=0;
	$SUBMIT_STR = "";
	$runtime_ipv6 = query("/runtime/web/display/ipv6");
	
	if(query("/wlan/inf:1/webredirect/auth/enable")!=$f_auth)
	{
		set("/wlan/inf:1/webredirect/auth/enable",$f_auth);
		$dirty++;
	}
	if(query("/wlan/inf:1/webredirect/url/enable")!=$f_url)
    {
        set("/wlan/inf:1/webredirect/url/enable",$f_url);
        $dirty++;
    }
    if($f_url== 1)
    {
        if(query("/wlan/inf:1/webredirect/url")!=$url_addr){set("/wlan/inf:1/webredirect/url",$url_addr); $dirty++;}
        set("/wlan/inf:1/webredirect/enable", 1);
    }
    if($f_url== 0 && $f_auth== 0)
    {
        set("/wlan/inf:1/webredirect/enable", 0);
        $dirty++;
    }
	if($f_url== 1 || $f_auth== 1)
	{
		if(query("/sys/adminlimit/status")==1){set("/sys/adminlimit/status", 0);$limit_dirty++;}/*limit_admin_status*/
    	if(query("/sys/adminlimit/status")==3){set("/sys/adminlimit/status", 2);$limit_dirty++;}/*limit_admin+status*/
	}
	if($f_auth== 1)
	{
		if($action=="add")
		{
			$s=1;
			for("/wlan/inf:1/webredirect/index")
			{
				$s++;
			}	
			anchor("/wlan/inf:1/webredirect");
			set("index:".$s."/name",$r_name);
			setEnc("index:".$s."/passwd",$r_password);
			set("index:".$s."/enable",$account_status);
			$dirty++;
		}
	
		if($action=="edit")
		{
			anchor("/wlan/inf:1/webredirect");
			if(query("index:".$which_edit."/name")!=$r_name){set("index:".$which_edit."/name",$r_name);$dirty++;}
			if(queryEnc("index:".$which_edit."/passwd")!=$r_password){setEnc("index:".$which_edit."/passwd",$r_password);$dirty++;}
			if(query("index:".$which_edit."/enable")!=$account_status){set("index:".$which_edit."/enable",$account_status);$dirty++;}
		}

		if($action=="delete")
		{
			del("/wlan/inf:1/webredirect/index:".$which_delete);
			$dirty++;
		}
		if($action=="save")
		{
			for("/wlan/inf:1/webredirect/index")
        	{
    	        $account_status = "a_s".$@;
	            if(query("enable")!=$$account_status){set("enable",$$account_status);$dirty++;}
            	$dirty++;
        	}
		}
		set("/wlan/inf:1/webredirect/enable", 1);
        $dirty++;
	}

	if($dirty > 0)
	{
		//$SUBMIT_STR="submit COMMIT";
		//set("/runtime/web/submit/commit", 1);
		set("/runtime/web/submit/webredirect", 1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}

	set("/runtime/web/next_page",$MY_NAME);
	/*if($dirty > 0) {*/require($G_SAVING_URL);/*}
	else				{require($G_NO_CHANGED_URL);}*/
}
else if($ACTION_POST == "adv_captivalu")
{
	set("/captival/mtd/picture/state", 0);
	$SUBMIT_CAPU = "submit CAPTIVAL_PORTAL;submit APNEAPS_V2";
	set("/runtime/web/sub_capu",$SUBMIT_CAPU);
	set("/runtime/web/next_page",$MY_NAME);
	require($G_SAVING_URL);
}
else if($ACTION_POST == "adv_captivals")
{
		echo "<!--\n";
	echo "ACTION_POST=".			$ACTION_POST.			"\n";
	echo "-->\n";	

	$dirty=0;
	$SUBMIT_STR = "";
	if(query("/captival/captival_timeout") != $timeout){set("/captival/captival_timeout", $timeout);$dirty++;}
	set("/runtime/captival/if_userpwd", 0);
	if($f_action == "add")
	{
		$s=1;
		for("/captival/user")
		{
			$s++;
		}
		set("/captival/user:".$s."/name",$r_name);
		setEnc("/captival/user:".$s."/pass",$r_password);
		set("/captival/user:".$s."/group",$r_group);
		$dirty++;
		set("/runtime/captival/if_userpwd", 1);
	}
	else if($f_action == "edit")
	{
		if(query("/captival/user:".$which_edit."/name")!=$r_name){set("/captival/user:".$which_edit."/name",$r_name);$dirty++;}
		if(queryEnc("/captival/user:".$which_edit."/pass")!=$r_password){setEnc("/captival/user:".$which_edit."/pass",$r_password);$dirty++;}
		if(query("/captival/user:".$which_edit."/group")!=$r_group){set("/captival/user:".$which_edit."/group",$r_group);$dirty++;}
		set("/runtime/captival/if_userpwd", 1);
	}
	else if($f_action == "delete")
	{
		del("/captival/user:".$which_delete);$dirty++;
		set("/runtime/captival/if_userpwd", 1);
	}
	else if($f_action == "add_s")
	{
		$s=1;
		for("/captival/serial")
		{
			$s++;
		}
		$i = 1;
		while($i <= $serial_cnt)
		{
			$random_value = "random_s".$i;
			set("/captival/serial:".$s."/number",$$random_value);
			set("/captival/serial:".$s."/duration",$duration);
			set("/captival/serial:".$s."/end",$end_date);
			set("/captival/serial:".$s."/device",$device);
			$s++;
			$i++;
		}
		$dirty++;
	}
	else if($f_action == "delete_s")
	{
		del("/captival/serial:".$which_delete);$dirty++;
	}
	else if($f_action == "del_all_s")
	{
		$tmp = 0;
		for("/captival/serial")
		{
			$tmp++;
		}
		$i = 0;
		while($i<$tmp)
		{
			del("/captival/serial:1");$i++;
		}
		$dirty++;
	}
	else if($f_action == "delete_p")
	{
		set("/captival/auth:".$which_delete."/state", 0);$dirty++;
		if(query("/wlan/inf:1/captival/state") == $which_delete){set("/wlan/inf:1/captival/state", 0);$dirty++;}
		if(query("/wlan/inf:2/captival/state") == $which_delete){set("/wlan/inf:2/captival/state", 0);$dirty++;}
		for("/wlan/inf:1/multi/index")
		{
			if(query("captival/state") == $which_delete){set("captival/state", 0);$dirty++;}
		}
		for("/wlan/inf:2/multi/index")
		{
			if(query("captival/state") == $which_delete){set("captival/state", 0);$dirty++;}
		}
	}
	else
	{
		if($encry_type == 1)
		{
			$index = 1;
			while($index < 5)
			{
				$tmp_filter_ip = "f_filter_ip".$index;
				$tmp_filter_mask = "f_filter_mask".$index;
				if(query("/captival/ipfilter:".$index."/ip") != $$tmp_filter_ip){set("/captival/ipfilter:".$index."/ip", $$tmp_filter_ip);$dirty++;}
				if(query("/captival/ipfilter:".$index."/mask") != $$tmp_filter_mask){set("/captival/ipfilter:".$index."/mask", $$tmp_filter_mask);$dirty++;}
				$index++;
			}
			set("/captival/auth:1/state", 1);$dirty++;
		}
		else if($encry_type == 2)
		{
			set("/captival/auth:2/state", 1);$dirty++;
			if(query("/captival/radius/auth_mode") != $remote_type){set("/captival/radius/auth_mode", $remote_type);$dirty++;}
			if(query("/captival/radius/auth_server_ip") != $radius_ip){set("/captival/radius/auth_server_ip",$radius_ip);$dirty++;}
			if(query("/captival/radius/auth_server_port") != $radius_port){set("/captival/radius/auth_server_port",$radius_port);$dirty++;}
			if(queryEnc("/captival/radius/auth_server_secret") != $radius_sec){setEnc("/captival/radius/auth_server_secret",$radius_sec);$dirty++;}
			if(query("/captival/radius/acct_mode") != $acc_mode){set("/captival/radius/acct_mode",$acc_mode);$dirty++;}
			if($acc_mode == 1)
			{
				if(query("/captival/radius/acct_server_ip") != $acc_ip){set("/captival/radius/acct_server_ip",$acc_ip);$dirty++;}
				if(query("/captival/radius/acct_server_port") != $acc_port){set("/captival/radius/acct_server_port",$acc_port);$dirty++;}
				if(queryEnc("/captival/radius/acct_server_secret") != $acc_sec){setEnc("/captival/radius/acct_server_secret",$acc_sec);$dirty++;}
			}
		}
		else if($encry_type == 3)
		{
			set("/captival/auth:3/state", 1);$dirty++;
			if(query("/captival/ldap/server_ip") != $ldap_ip){set("/captival/ldap/server_ip",$ldap_ip);$dirty++;}
			if(query("/captival/ldap/port") != $ldap_port){set("/captival/ldap/port",$ldap_port);$dirty++;}
			if(query("/captival/ldap/auth") != $ldap_auth){set("/captival/ldap/auth",$ldap_auth);$dirty++;}	
			if(query("/captival/ldap/name") != $ldap_name){set("/captival/ldap/name",$ldap_name);$dirty++;}
			if(queryEnc("/captival/ldap/passwd") != $ldap_password){setEnc("/captival/ldap/passwd",$ldap_password);$dirty++;}
			if(query("/captival/ldap/base") != $base_dn){set("/captival/ldap/base",$base_dn);$dirty++;}
			if(query("/captival/ldap/attribute") != $attribute){set("/captival/ldap/attribute",$attribute);$dirty++;}
			if(query("/captival/ldap/identity") != $identity){set("/captival/ldap/identity",$identity);$dirty++;}
			if(query("/captival/ldap/autoid") != $auto_copy){set("/captival/ldap/autoid",$auto_copy);$dirty++;}
		}
		else if($encry_type == 4)
		{
			set("/captival/auth:4/state", 1);$dirty++;
			if(query("/captival/pop3/server_ip") != $pop3_ip){set("/captival/pop3/server_ip",$pop3_ip);$dirty++;}
			if(query("/captival/pop3/server_port") != $pop3_port){set("/captival/pop3/server_port",$pop3_port);$dirty++;}
			if(query("/captival/pop3/ssl_state") != $ssl_state){set("/captival/pop3/ssl_state",$ssl_state);$dirty++;}
		}
		else if($encry_type == 7)
		{
			set("/captival/auth:7/state", 1);$dirty++;
		}
	}

	if($dirty > 0)
	{
		set("/runtime/web/submit/captival", 1);
		set("/runtime/web/submit/apneaps_v2", 1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}

	set("/runtime/web/next_page",$MY_NAME);
	require($G_SAVING_URL);
}
else if($ACTION_POST == "adv_url_cap")
{
    echo "<!--\n";
    echo "url_enable = ". $url_enable ."\n";
    echo "url_addr = ". $url_addr ."\n";
    echo "-->\n";

    $SUBMIT_STR = "";
    $dirty = 0;

	if(query("/captival/url_state") != $url_enable){set("/captival/url_state",$url_enable);$dirty++;}
	if($url_enable == 1)
	{
		if($site_begin == 1){$url_addr = "http:\/\/".$url_addr;}
		else{$url_addr = "https:\/\/".$url_addr;}
		if(query("/captival/http_url") != $url_addr){set("/captival/http_url",$url_addr);$dirty++;}
	}

	if($dirty > 0)
	{
		set("/runtime/web/submit/captival", 1);
		set("/runtime/web/submit/apneaps_v2", 1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}

	set("/runtime/web/next_page",$MY_NAME);
	require($G_SAVING_URL);
}
else if($ACTION_POST == "adv_cap_wr")
{
	echo "<!--\n";
	echo "url_enable = ". $url_enable ."\n";
	echo "url_addr = ". $url_addr ."\n";
	echo "-->\n";

	$SUBMIT_STR = "";
	$dirty = 0;

	if(query("/captival/url_state") != $url_enable){set("/captival/url_state",$url_enable);$dirty++;}
	if($url_enable == 1)
	{
		if(query("/captival/http_url") != $url_addr){set("/captival/http_url",$url_addr);$dirty++;}
	}
	if(query("/captival/radius/auth_server_ip") != $radius_ip){set("/captival/radius/auth_server_ip",$radius_ip);$dirty++;}
	if(query("/captival/radius/auth_server_port") != $radius_port){set("/captival/radius/auth_server_port",$radius_port);$dirty++;}
	if(queryEnc("/captival/radius/auth_server_secret") != $radius_sec){setEnc("/captival/radius/auth_server_secret",$radius_sec);$dirty++;}

	if($dirty > 0)
	{
		set("/runtime/web/submit/captival", 1);
		set("/runtime/web/submit/apneaps_v2", 1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}

	set("/runtime/web/next_page",$MY_NAME);
	require($G_SAVING_URL);
}
else if($ACTION_POST == "adv_array_scan")
{
	echo "<!--\n";
	echo "-->\n";

	$SUBMIT_STR = "";
	$dirty = 0;

	if($f_scan_value == "1")
	{
		set("/runtime/web/check_scan_value", 1);
		$dirty++;
		anchor("/runtime/web/wlan/inf:1");
	}
	else
	{
		anchor("/wlan/inf:1");
	}
	if(query("aparray_enable")  !=$f_ap_array_enable)
	{
		set("aparray_enable", $f_ap_array_enable);
		$dirty++;
		if($f_ap_array_enable==1)
		{
			set("/sys/adminlimit/status", 0);
			$limit_dirty++;
			set("/sys/swcontroller/enable", 0);
			set("/sys/b_swcontroller/enable", 0);
			set("/wlan/inf:1/aparray_cfg_version",1);
			$dirty++;
		}
		else
		{
			set("/sys/autorf/enable", 0);
			set("/sys/loadbalance/enable", 0);
		}
	}
	if(query("arrayrole_original")  !=$f_role)  {set("arrayrole_original", $f_role);    $dirty++;}
	if(query("arrayname")   !=$ap_array_name)   {set("arrayname", $ap_array_name);  $dirty++;}
	if($f_scan_value != "1")
	{
		if(queryEnc("aparray_password") !=$ap_array_pwd)    {setEnc("aparray_password", $ap_array_pwd); $dirty++;}
	}
	if($dirty > 0)
	{
		set("/runtime/web/submit/ap_array",1);
		set("/runtime/web/submit/captival",1);
		set("/runtime/web/submit/autorf",1);
		set("/runtime/web/submit/loadbalance",1);
		set("/runtime/web/submit/apneaps_v2",1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}
	if($limit_dirty > 0 )
	{
		set("/runtime/web/submit/limit", 1);
	}
	set("/runtime/web/next_page",$MY_NAME);
	if($dirty>0 && $f_scan_value=="1")  {require($G_SCAN_URL);}
	else {require($G_SAVING_URL);}
}
else if($ACTION_POST == "adv_array_config")
{
	echo "<!--\n";
	echo "-->\n";

	$SUBMIT_STR = "";
	$dirty = 0;

	if(query("/sys/aparray/syncconfig/enable") != $enable_config){set("/sys/aparray/syncconfig/enable", $enable_config);$dirty++;}
	if($enable_config == 1)
	{
		anchor("/aparray/sync");
		if(query("ssid")    !=$bsc_network_name)    {set("ssid", $bsc_network_name);    $dirty++;}
		if(query("ssidhidden")  !=$bsc_ssid_visibility) {set("ssidhidden", $bsc_ssid_visibility);   $dirty++;}
		if(query("autochannel") !=$bsc_auto_chann)  {set("autochannel", $bsc_auto_chann);   $dirty++;}
		if(query("channelwidth")    !=$bsc_channel_width)   {set("channelwidth", $bsc_channel_width);   $dirty++;}
		if(query("security")    !=$bsc_security)    {set("security", $bsc_security);    $dirty++;}
		if(query("band")    !=$bsc_band)    {set("band", $bsc_band);    $dirty++;}
		if(query("captivalstate")	!=$bsc_captival_profile)	{set("captivalstate", $bsc_captival_profile);	$dirty++;}
		if(query("wlanstate")	!=$adv_wlan_enable)	{set("wlanstate", $adv_wlan_enable);	$dirty++;}
		if(query("wlmode")	!=$adv_wlan_mode)	{set("wlmode", $adv_wlan_mode);	$dirty++;}
		if(query("fixedrate")   !=$adv_data_rate)   {set("fixedrate", $adv_data_rate);  $dirty++;}
		if(query("beaconinterval")  !=$adv_beacon_interval) {set("beaconinterval", $adv_beacon_interval);   $dirty++;}
		if(query("dtim")    !=$adv_dtim)    {set("dtim", $adv_dtim);    $dirty++;}
		if(query("txpower") !=$adv_transmit_power)  {set("txpower", $adv_transmit_power);   $dirty++;}
		if(query("wmm") !=$adv_wmm_wifi)    {set("wmm", $adv_wmm_wifi); $dirty++;}
		if(query("acktimeout")  !=$adv_ack_timeout) {set("acktimeout", $adv_ack_timeout);   $dirty++;}
		if(query("shortgi") !=$adv_short_gi)    {set("shortgi", $adv_short_gi); $dirty++;}
		if(query("igmpsnoop")   !=$adv_igmp)    {set("igmpsnoop", $adv_igmp);   $dirty++;}
		if(query("connectionlimit") !=$adv_conn_limit)  {set("connectionlimit", $adv_conn_limit);   $dirty++;}
		if(query("linkintegrity")   !=$adv_link_integrity)  {set("linkintegrity", $adv_link_integrity); $dirty++;}
		if(query("multi/ssid")  !=$mssid)   {set("multi/ssid", $mssid); $dirty++;}
		if(query("multi/ssid_hidden")   !=$mssid_visibility)    {set("multi/ssid_hidden", $mssid_visibility);   $dirty++;}
		if(query("multi/security")  !=$msecurity)   {set("multi/security", $msecurity); $dirty++;}
		if(query("multi/wmm")   !=$mwmm)    {set("multi/wmm", $mwmm);   $dirty++;}
		if(query("multi/captival")   !=$mcaptival_profile)    {set("multi/captival", $mcaptival_profile); $dirty++;}
		if(query("qos") !=$qos_setting) {set("qos", $qos_setting);  $dirty++;}
		if(query("vlan")    !=$vlan)    {set("vlan", $vlan);    $dirty++;}
		if(query("schedule")    !=$schedule_settings)   {set("schedule", $schedule_settings);   $dirty++;}
		if(query("time")    !=$time_date)   {set("time", $time_date);   $dirty++;}
		if(query("captivalportal")	!=$captival_portal)	{set("captivalportal", $captival_portal);	$dirty++;}
		if(query("autorf")  !=$autorf) {set("autorf", $autorf);   $dirty++;}
		if(query("loadbalance")  !=$loadbalance) {set("loadbalance", $loadbalance);   $dirty++;}
		if(query("arpspoofing")	!=$arp_spoofing)	{set("arpspoofing", $arp_spoofing);	$dirty++;}
		if(query("fairairtime")	!=$fair_air_time)	{set("fairairtime", $fair_air_time);	$dirty++;}
		if(query("log") !=$log_setting) {set("log", $log_setting);  $dirty++;}
		if(query("adminlimit")  !=$limit_admin) {set("adminlimit", $limit_admin);   $dirty++;}
		if(query("system")  !=$sys_name_setting)    {set("system", $sys_name_setting);  $dirty++;}
		if(query("consoleprotocol") !=$console_setting) {set("consoleprotocol", $console_setting);  $dirty++;}
		if(query("snmp")    !=$snmp_setting)    {set("snmp", $snmp_setting);    $dirty++;}
		if(query("pingctl") !=$ping_control_setting)    {set("pingctl", $ping_control_setting); $dirty++;}
		if(query("dhcp")    !=$dhcp_svr_setting)    {set("dhcp", $dhcp_svr_setting);    $dirty++;}
		if(query("login")   !=$login_setting)   {set("login", $login_setting);  $dirty++;}
		if(query("acl") !=$adv_acl) {set("acl", $adv_acl);  $dirty++;}
	}

	if($dirty > 0)
	{
		set("/runtime/web/submit/commit",1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}
	set("/runtime/web/next_page",$MY_NAME);
	require($G_SAVING_URL);
}
else if($ACTION_POST == "adv_auto_rf")
{
	echo "<!--\n";
	echo "-->\n";

	$SUBMIT_STR = "";
	$dirty = 0;

	if($f_action == 1){set("/runtime/sys/autorf/init/button", 1);}
	else
	{
		anchor("/sys/autorf");
		if(query("enable") != $auto_rf){set("enable", $auto_rf);$dirty++;}
		if($auto_rf == 1)
		{
			if(query("init/auto") != $init_auto){set("init/auto", $init_auto);$dirty++;}
			if($init_auto == 1)
			{
				if(query("init/period") != $init_period){set("init/period", $init_period);$dirty++;}
			}
			if(query("server/thresold/rssi") != $rssi){set("server/thresold/rssi", $rssi);$dirty++;}
			if(query("client/time/loop") != $freq){set("client/time/loop", $freq);$dirty++;}
		}
	}

	if($dirty > 0)
	{
		set("/runtime/web/submit/autorf", 1);
		set("/runtime/web/submit/apneaps_v2", 1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}
	set("/runtime/web/next_page",$MY_NAME);
	require($G_SAVING_URL);
}
else if($ACTION_POST == "adv_load_balance")
{
	echo "<!--\n";
	echo "-->\n";

	$SUBMIT_STR = "";
	$dirty = 0;

	if(query("/sys/loadbalance/enable") != $load_balance){set("/sys/loadbalance/enable", $load_balance);$dirty++;}
	if(query("/sys/loadbalance/threshold") != $thre){set("/sys/loadbalance/threshold", $thre);$dirty++;}

	if($dirty > 0)
	{
		set("/runtime/web/submit/loadbalance", 1);
		set("/runtime/web/submit/apneaps_v2", 1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}
	set("/runtime/web/next_page",$MY_NAME);
	require($G_SAVING_URL);
}
else if($ACTION_POST == "adv_macbypass")
{
	echo "<!--\n";
	echo "-->\n";

	$SUBMIT_STR = "";
	$dirty = 0;

	anchor("/captival/ssid:".$f_ssid);
	if($f_ssid > 8){set("/wlan/ch_mode", 1);}
	else{set("/wlan/ch_mode", 0);}

	if($f_action == "add")
	{
		$s=1;
		for("white_list/index")
		{$s++;}
		set("white_list/index:".$s."/mac", $f_mac);
		$dirty++;
	}
	else if($f_action == "del")
	{
		del("white_list/index:".$which_del);
		$dirty++;
	}

	if($dirty > 0)
	{
		set("/runtime/web/submit/captival", 1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}
	set("/runtime/web/next_page",$MY_NAME);
	require($G_SAVING_URL);
}
else if($ACTION_POST == "adv_8021q_s")
{
	echo "<!--\n";
	echo "ACTION_POST=".			$ACTION_POST.			"\n";
	echo "f_vlan=".			$f_vlan.			"\n";
	echo "f_rule_del=".			$f_rule_del.			"\n";
	echo "-->\n";	
	
	$SUBMIT_STR = "";
	$dirty = 0;		
	$dirty_wlan = 0;	
	$limit_dirty = 0;
	
	if(query("/sys/vlan_state")!=$f_vlan)	
	{
		set("/sys/vlan_state",$f_vlan); 
		if (query("/sys/vlan_mode")!= 1) 
		{
			$dirty++;
		}
		else
		{
			$dirty++;
			$dirty_wlan++;
		}
	}
	if($f_vlan == 1)
	{
		if(query("/sys/adminlimit/status")==3){set("/sys/adminlimit/status",2);$limit_dirty++;}
		if(query("/sys/adminlimit/status")==1){set("/sys/adminlimit/status",0);$limit_dirty++;}
	
		if(query("/runtime/web/display/mssid_support_sharedkey") == 0)//mssid not support Shared Key	
		{			
			if(query("/wlan/inf:1/authentication") == 1) //Shared Key
			{
				set("/wlan/inf:1/authentication", 0); //open
				$dirty_wlan++;
			}
		}	
		
		if(query("/wlan/inf:1/ap_mode") == 1 || query("/wlan/inf:1/ap_mode") == 2) // if APC,set to AP
		{
			set("/wlan/inf:1/ap_mode", 0);
			$dirty_wlan++;
		}	
		if($dirty_wlan != 0)	
		{
			set("/runtime/web/check/vlan/mssid_enable", 1);
		}
	}
	
	if($f_vlan == 1)
	{
		if($f_rule_del!="")
		{
			$dirty++;
			if(query("/sys/group_vlan/index:".$f_rule_del."/sys:1/egress") == 2 && $f_pvid_auto == 1)
			{
				if(query("/sys/vlan_id") == query("/sys/group_vlan/index:".$f_rule_del."/group_vid"))
				{
					$del_admin_vid = 1;
					for("/sys/group_vlan/index")
					{
						if($@!=$f_rule_del)
						{
							if(query("/sys/group_vlan/index:".$@."/sys:1/egress") == 2)
							{
								$del_admin_vid = query("/sys/group_vlan/index:".$@."/group_vid");
							}
						}
					}
					set("/sys/vlan_id",$del_admin_vid);$dirty++;
				}
			}
					
			if(query("/sys/group_vlan/index:".$f_rule_del."/eth:1/egress") == 2 && $f_pvid_auto == 1)
			{
				if(query("/lan/ethernet/vlan_id") == query("/sys/group_vlan/index:".$f_rule_del."/group_vid"))
				{
					$del_eth_vid = 1;
					for("/sys/group_vlan/index")
					{
						if($@!=$f_rule_del)
						{
							if(query("/sys/group_vlan/index:".$@."/eth:1/egress") == 2)
							{
								$del_eth_vid = query("/sys/group_vlan/index:".$@."/group_vid");
							}
						}
					}
					set("/lan/ethernet/vlan_id",$del_eth_vid);$dirty++;
				}
			}	
			if(query("/sys/group_vlan/index:".$f_rule_del."/eth:2/egress") == 2 && $f_pvid_auto == 1)
            {
                if(query("/lan/ethernet:2/vlan_id") == query("/sys/group_vlan/index:".$f_rule_del."/group_vid"))
                {
                    $del_eth_vid2 = 1;
                    for("/sys/group_vlan/index")
                    {
                        if($@!=$f_rule_del)
                        {
                            if(query("/sys/group_vlan/index:".$@."/eth:2/egress") == 2)
                            {
                                $del_eth_vid2 = query("/sys/group_vlan/index:".$@."/group_vid");
                            }
                        }
                    }
                    set("/lan/ethernet:2/vlan_id",$del_eth_vid2);$dirty++;
				}
			}	
			
			$del_ath_idx = 1;
			while($del_ath_idx < 33)
			{
				if(query("/sys/group_vlan/index:".$f_rule_del."/ath:".$del_ath_idx."/egress") == 2 && $f_pvid_auto == 1)
				{	
					if($del_ath_idx == 1)
					{
						if(query("/wlan/inf:1/vlan_id") == query("/sys/group_vlan/index:".$f_rule_del."/group_vid"))
						{
							$del_ath_vid = 1;
							for("/sys/group_vlan/index")
							{
								if($@!=$f_rule_del)
								{
									if(query("/sys/group_vlan/index:".$@."/ath:1/egress") == 2)
									{
										$del_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
									}
								}
							}
							set("/wlan/inf:1/vlan_id",$del_ath_vid);$dirty++;
						}
					}
					if($del_ath_idx > 1 && $del_ath_idx < 9)
					{
						$multi_idx = $del_ath_idx - 1;
						
						if(query("/wlan/inf:1/multi/index:".$multi_idx."/vlan_id") == query("/sys/group_vlan/index:".$f_rule_del."/group_vid"))
						{
							$del_ath_vid = 1;
							for("/sys/group_vlan/index")
							{
								if($@!=$f_rule_del)
								{
									if(query("/sys/group_vlan/index:".$@."/ath:".$del_ath_idx."/egress") == 2)
									{
										$del_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
									}
								}
							}
							set("/wlan/inf:1/multi/index:".$multi_idx."/vlan_id",$del_ath_vid);$dirty++;
						}						
					}
					else if($del_ath_idx > 8 && $del_ath_idx < 17)
					{
						$w_index = $del_ath_idx - 8;
						
						if(query("/wlan/inf:1/wds/index:".$w_index."/vlan_id") == query("/sys/group_vlan/index:".$f_rule_del."/group_vid"))
						{
							$del_ath_vid = 1;
							for("/sys/group_vlan/index")
							{
								if($@!=$f_rule_del)
								{
									if(query("/sys/group_vlan/index:".$@."/ath:".$del_ath_idx."/egress") == 2)
									{
										$del_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
									}
								}
							}
							set("/wlan/inf:1/wds/index:".$w_index."/vlan_id",$del_ath_vid);$dirty++;
						}						
					}
				}					
				$del_ath_idx++;
			}

			del("/sys/group_vlan/index:".$f_rule_del);	
		}				
		if($f_apply =="rule_add")
		{
			$index=1;
			
			if($f_rule_edit !="")
			{
				$index = $f_rule_edit;
			}
			else
			{
				for("/sys/group_vlan/index")
				{
					$index++;
				}
			}	

			if($f_rule_edit =="")
			{
				if(query("/sys/group_vlan/index:".$index."/group_vid")!=$f_vid)	{set("/sys/group_vlan/index:".$index."/group_vid",$f_vid); $dirty++;}
			}
			if(query("/sys/group_vlan/index:".$index."/group_name")!=$f_ssid)	{set("/sys/group_vlan/index:".$index."/group_name",$f_ssid); $dirty++;}
			
			if(query("/sys/group_vlan/index:".$index."/sys:1/egress")!=$f_sys_egress)	{set("/sys/group_vlan/index:".$index."/sys:1/egress",$f_sys_egress); $dirty++;}
			
			if($f_pvid_auto == 1)
			{
				if($f_sys_egress == 2)
				{	
					set("/sys/vlan_id",$f_vid);$dirty++;
				}
				else if($f_sys_egress != 2 && $f_rule_edit !="")
				{
					if(query("/sys/vlan_id") == query("/sys/group_vlan/index:".$f_rule_edit."/group_vid"))
					{
						$edit_admin_vid = 1;
						for("/sys/group_vlan/index")
						{
							if($@!=$f_rule_edit)
							{
								if(query("/sys/group_vlan/index:".$@."/sys:1/egress") == 2)
								{
									$edit_admin_vid = query("/sys/group_vlan/index:".$@."/group_vid");
								}
							}
						}
						set("/sys/vlan_id",$edit_admin_vid);$dirty++;						
					}
				}
			}
						
			if(query("/sys/group_vlan/index:".$index."/eth:1/egress")!=$f_eth_egress)	{set("/sys/group_vlan/index:".$index."/eth:1/egress",$f_eth_egress); $dirty++;}
			if(query("/sys/group_vlan/index:".$index."/eth:2/egress")!=$f_eth_egress2)   {set("/sys/group_vlan/index:".$index."/eth:2/egress",$f_eth_egress2); $dirty++;}
			
			if($f_pvid_auto == 1)
			{
				if($f_eth_egress == 2)
				{
					set("/lan/ethernet/vlan_id",$f_vid);$dirty++;
				}	
				else if($f_sys_egress != 2 && $f_rule_edit !="")
				{
					if(query("/lan/ethernet/vlan_id") == query("/sys/group_vlan/index:".$f_rule_edit."/group_vid"))
					{
						$edit_eth_vid = 1;
						for("/sys/group_vlan/index")
						{
							if($@!=$f_rule_edit)
							{
								if(query("/sys/group_vlan/index:".$@."/eth:1/egress") == 2)
								{
									$edit_eth_vid = query("/sys/group_vlan/index:".$@."/group_vid");
								}
							}
						}
						set("/lan/ethernet/vlan_id",$edit_eth_vid);$dirty++;
					}
				}					
				if($f_eth_egress2 == 2)
                {
                    set("/lan/ethernet:2/vlan_id",$f_vid);$dirty++;
                }
				else if($f_sys_egress2 != 2 && $f_rule_edit !="")
                {
                    if(query("/lan/ethernet:2/vlan_id") == query("/sys/group_vlan/index:".$f_rule_edit."/group_vid"))
                    {
                        $edit_eth_vid2 = 1;
                        for("/sys/group_vlan/index")
                        {
                            if($@!=$f_rule_edit)
                            {
                                if(query("/sys/group_vlan/index:".$@."/eth:2/egress") == 2)
                                {
                                    $edit_eth_vid2 = query("/sys/group_vlan/index:".$@."/group_vid");
                                }
                            }
                        }
                        set("/lan/ethernet:2/vlan_id",$edit_eth_vid2);$dirty++;
                    }
                }
			}			
			
			$ath_idx = 1;
			$ssid_flag=query("/runtime/web/display/mssid_index4");
			$flag=5;
			while($ath_idx< 17)
			{
				if ($ssid_flag=="1")
				{
					if($ath_idx==5)
					{
						$ath_idx=$ath_idx+4;
						while($flag <= 12 )
						{
							set("/sys/group_vlan/index:".$index."/ath:".$flag."/egress",1);
							$flag++;
						}
					}
				$ath_egress	= "f_ath".$ath_idx."_egress";
				if(query("/sys/group_vlan/index:".$index."/ath:".$ath_idx."/egress")!=$$ath_egress)	{set("/sys/group_vlan/index:".$index."/ath:".$ath_idx."/egress",$$ath_egress); $dirty++;}
				}
				else
				{
				$ath_egress	= "f_ath".$ath_idx."_egress";
				if(query("/sys/group_vlan/index:".$index."/ath:".$ath_idx."/egress")!=$$ath_egress)	{set("/sys/group_vlan/index:".$index."/ath:".$ath_idx."/egress",$$ath_egress); $dirty++;}
				}
				if(query("/sys/group_vlan/index:".$index."/ath:".$ath_idx."/egress")!=$$ath_egress)	{set("/sys/group_vlan/index:".$index."/ath:".$ath_idx."/egress",$$ath_egress); $dirty++;}
				
				if($f_pvid_auto == 1)
				{
					if($$ath_egress == 2)
					{
						if($ath_idx == 1)
						{
							set("/wlan/inf:1/vlan_id",$f_vid); $dirty++;
						}
						if($ath_idx > 1 && $ath_idx < 9)
						{
							$ms_idx = $ath_idx-1;
							set("/wlan/inf:1/multi/index:".$ms_idx."/vlan_id",$f_vid); $dirty++;
						}
						else if($ath_idx > 8 && $ath_idx < 17)
						{
							$wds_idx = $ath_idx-8;
							set("/wlan/inf:1/wds/index:".$wds_idx."/vlan_id",$f_vid); $dirty++;
						}
					}
					else if($$ath_egress != 2 && $f_rule_edit !="")
					{
						if($ath_idx == 1)
						{
							if(query("/wlan/inf:1/vlan_id") == query("/sys/group_vlan/index:".$f_rule_edit."/group_vid"))
							{
								$edit_ath_vid = 1;
								for("/sys/group_vlan/index")
								{
									if($@!=$f_rule_edit)
									{
										if(query("/sys/group_vlan/index:".$@."/ath:1/egress") == 2)
										{
											$edit_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
										}
									}
								}
								set("/wlan/inf:1/vlan_id",$edit_ath_vid);$dirty++;
							}
						}
						if($ath_idx > 1 && $ath_idx < 9)
						{
							$multi_idx = $ath_idx - 1;
							if(query("/wlan/inf:1/multi/index:".$multi_idx."/vlan_id") == query("/sys/group_vlan/index:".$f_rule_edit."/group_vid"))
							{
								$edit_ath_vid = 1;
								for("/sys/group_vlan/index")
								{
									if($@!=$f_rule_edit)
									{
										if(query("/sys/group_vlan/index:".$@."/ath:".$ath_idx."/egress") == 2)
										{
											$edit_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
										}
									}
								}
								set("/wlan/inf:1/multi/index:".$multi_idx."/vlan_id",$edit_ath_vid);$dirty++;
							}								
						}	
						else if($ath_idx > 8 && $ath_idx < 17)
						{						
							$w_index = $ath_idx - 8;								
							if(query("/wlan/inf:1/wds/index:".$w_index."/vlan_id") == query("/sys/group_vlan/index:".$f_rule_edit."/group_vid"))
							{
								$edit_ath_vid = 1;
								for("/sys/group_vlan/index")
								{
									if($@!=$f_rule_edit)
									{
										if(query("/sys/group_vlan/index:".$@."/ath:".$ath_idx."/egress") == 2)
										{
											$edit_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
										}
									}
								}
								set("/wlan/inf:1/wds/index:".$w_index."/vlan_id",$edit_ath_vid);$dirty++;
							}								
						}
					}				
				}					
				if ($ssid_flag=="1")
				{
					if($ath_idx==12)
					{
						$ath_idx+=4;	
					}		
				}
				$ath_idx++;
			}
		}

		if($f_apply =="rule")
		{
			$dirty++;
		}

		if($f_apply =="pvid")
		{
			$dirty++;
			if(query("/sys/auto_set_pvid")!=$f_pvid_auto)	{set("/sys/auto_set_pvid",$f_pvid_auto); $dirty++;}
	
			if($f_pvid_auto == 0)
			{
				if(query("/sys/vlan_id")!=$f_admin_pvid)	{set("/sys/vlan_id",$f_admin_pvid); $dirty++;}
				if(query("/lan/ethernet/vlan_id")!=$f_eth_pvid)	{set("/lan/ethernet/vlan_id",$f_eth_pvid); $dirty++;}
				if(query("/lan/ethernet:2/vlan_id")!=$f_eth_pvid2)  {set("/lan/ethernet:2/vlan_id",$f_eth_pvid2); $dirty++;}
				if(query("/wlan/inf:1/vlan_id")!=$f_pri_pvid)	{set("/wlan/inf:1/vlan_id",$f_pri_pvid); $dirty++;}
				$ms_index = 1;
				while($ms_index< 9)
				{
					$ms_pvid_value	= "f_ms_".$ms_index."_pvid";
					if(query("/wlan/inf:1/multi/index:".$ms_index."/vlan_id")!=$$ms_pvid_value)	{set("/wlan/inf:1/multi/index:".$ms_index."/vlan_id",$$ms_pvid_value); $dirty++;}
					$ms_pvid_value_a	= "f_ms_".$ms_index."_pvid_a";
					$ms_index++;
				}
				
				$wds_index = 1;
				while($wds_index< 9)
				{
					$wds_pvid_value	= "f_wds_".$wds_index."_pvid";
					if(query("/wlan/inf:1/wds/index:".$wds_index."/vlan_id")!=$$wds_pvid_value)	{set("/wlan/inf:1/wds/index:".$wds_index."/vlan_id",$$wds_pvid_value); $dirty++;}
					$wds_pvid_value_a	= "f_wds_".$wds_index."_pvid_a";
					$wds_index++;
				}
			}
			else
			{
				$pvid_auto_admin_vid = 1;
				for("/sys/group_vlan/index")
				{
					if(query("/sys/group_vlan/index:".$@."/sys:1/egress") == 2)
					{
						$pvid_auto_admin_vid = query("/sys/group_vlan/index:".$@."/group_vid");
					}
				}
				set("/sys/vlan_id",$pvid_auto_admin_vid);$dirty++;
					

				$del_eth_vid = 1;
				for("/sys/group_vlan/index")
				{
					if(query("/sys/group_vlan/index:".$@."/eth:1/egress") == 2)
					{
						$del_eth_vid = query("/sys/group_vlan/index:".$@."/group_vid");
					}
				}
				set("/lan/ethernet/vlan_id",$del_eth_vid);$dirty++;
				$del_eth_vid2 = 1;
                for("/sys/group_vlan/index")
                {
                    if(query("/sys/group_vlan/index:".$@."/eth:2/egress") == 2)
                    {
                        $del_eth_vid2 = query("/sys/group_vlan/index:".$@."/group_vid");
                    }
                }
                set("/lan/ethernet:2/vlan_id",$del_eth_vid2);$dirty++;
				
				$pvid_auto_ath_idx = 1;
				while($pvid_auto_ath_idx < 33)
				{
					if($pvid_auto_ath_idx == 1)
					{
						$pvid_auto_ath_vid = 1;
						for("/sys/group_vlan/index")
						{
							if(query("/sys/group_vlan/index:".$@."/ath:1/egress") == 2)
							{
								$pvid_auto_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
							}
						}
						set("/wlan/inf:1/vlan_id",$pvid_auto_ath_vid);$dirty++;
					}
					if($pvid_auto_ath_idx > 1 && $pvid_auto_ath_idx < 9)
					{
						$multi_idx = $pvid_auto_ath_idx - 1;
						$pvid_auto_ath_vid = 1;
						for("/sys/group_vlan/index")
						{
							if(query("/sys/group_vlan/index:".$@."/ath:".$pvid_auto_ath_idx."/egress") == 2)
							{
								$pvid_auto_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
							}
						}
						set("/wlan/inf:1/multi/index:".$multi_idx."/vlan_id",$pvid_auto_ath_vid);$dirty++;				
					}
					else if($pvid_auto_ath_idx > 8 && $pvid_auto_ath_idx < 17)
					{
						$w_index = $pvid_auto_ath_idx - 8;
						$pvid_auto_ath_vid = 1;
						for("/sys/group_vlan/index")
						{
							if(query("/sys/group_vlan/index:".$@."/ath:".$pvid_auto_ath_idx."/egress") == 2)
							{
								$pvid_auto_ath_vid = query("/sys/group_vlan/index:".$@."/group_vid");
							}
						}
						set("/wlan/inf:1/wds/index:".$w_index."/vlan_id",$pvid_auto_ath_vid);$dirty++;					
					}						
					$pvid_auto_ath_idx++;
				}
			}
		}
	}
	
	if($dirty > 0)
	{
		if($limit_dirty > 0)
		{
			set("/runtime/web/submit/limit", 1);
		}
		if($f_apply =="rule_add" || $f_rule_del!="")
		{
			set("/runtime/web/submit/vlan", 1);
			set("/runtime/web/submit/vlanportlist", 1);
		}	
		else
		{
			if(query("/runtime/web/check/vlan/mssid_enable") == 1 || $dirty_wlan > 0)
			{
				set("/runtime/web/submit/wlan", 1);
				set("/runtime/web/submit/vlanportlist", 1);
			}
			else
			{
				set("/runtime/web/submit/vlan", 1);
				set("/runtime/web/submit/vlanportlist", 1);
			}
		}
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}	

	
	set("/runtime/web/next_page",$ACTION_POST);
	require($G_SAVING_URL);
}
else if($ACTION_POST == "adv_captivals_v2")
{
		echo "<!--\n";
	echo "ACTION_POST=".			$ACTION_POST.			"\n";
	echo "-->\n";	

	$dirty=0;
	$SUBMIT_STR = "";
	if(query("/captival/captival_timeout") != $timeout){set("/captival/captival_timeout", $timeout);$dirty++;}
	if($band == 0)
	{
		if($ssid_index == 0){$this_ssid = query("/wlan/inf:1/ssid");}
		else {$this_ssid = query("/wlan/inf:1/multi/index:".$ssid_index."/ssid");}
		$ssid_index = $ssid_index + 1;
	}
	else if($band == 1)
	{
		if($ssid_index == 0){$this_ssid = query("/wlan/inf:2/ssid");}
		else {$this_ssid = query("/wlan/inf:2/multi/index:".$ssid_index."/ssid");}
		$ssid_index = $ssid_index + 9;
	}

	$tmp_ps = 1;
	$that_ssid = "";
	while($tmp_ps < 17)
	{
		if(query("/captival/ssid:".$tmp_ps."/state") == 3 && $tmp_ps != $ssid_index)
		{
			if($tmp_ps == 1){$that_ssid = query("/wlan/inf:1/ssid");}
			else if($tmp_ps < 9){$tmp_psss = $tmp_ps - 1; $that_ssid = query("/wlan/inf:1/multi/index:".$tmp_psss."/ssid");}
			else if($tmp_ps == 9){$that_ssid = query("/wlan/inf:2/ssid");}
			else if($tmp_ps > 9){$tmp_psss = $tmp_ps - 9; $that_ssid = query("/wlan/inf:2/multi/index:".$tmp_psss."/ssid");}
			$ps_nnn = $tmp_ps;
		}
		$tmp_ps++;
	}

	set("/runtime/captival/if_userpwd", 0);
	anchor("/captival/ssid:".$ssid_index);
	if($f_action == "add")
	{
		$s=1;
		for("/captival/ssid:".$ssid_index."/local/user")
		{
			$s++;
		}
		set("local/user:".$s."/name",$r_name);
		setEnc("local/user:".$s."/passwd",$r_password);
		set("local/user:".$s."/group",$r_group);
		set("/runtime/captival/if_userpwd", 2);
		set("/runtime/captival/return_index", $ssid_index);
		$dirty++;
	}
	else if($f_action == "edit")
	{
		if(query("local/user:".$which_edit."/name")!=$r_name){set("local/user:".$which_edit."/name",$r_name);$dirty++;}
		if(queryEnc("local/user:".$which_edit."/passwd")!=$r_password){setEnc("local/user:".$which_edit."/passwd",$r_password);$dirty++;}
		if(query("local/user:".$which_edit."/group")!=$r_group){set("local/user:".$which_edit."/group",$r_group);$dirty++;}
		set("/runtime/captival/if_userpwd", 2);
		set("/runtime/captival/return_index", $ssid_index);
	}
	else if($f_action == "delete")
	{
		del("local/user:".$which_delete);$dirty++;
		set("/runtime/captival/if_userpwd", 2);
		set("/runtime/captival/return_index", $ssid_index);
	}
	else if($f_action == "add_s")
	{
		$s=1;
		for("/captival/ssid:".$ssid_index."/passcode/index")
		{
			$s++;
		}
		$i = 1;
		while($i <= $serial_cnt)
		{
			$random_value = "random_s".$i;
			set("passcode/index:".$s."/number",$$random_value);
			set("passcode/index:".$s."/duration",$duration);
			set("passcode/index:".$s."/end",$end_date);
			set("passcode/index:".$s."/device",$device);
			$s++;
			$i++;
		}
		set("/runtime/captival/if_userpwd", 3);
		set("/runtime/captival/return_index", $ssid_index);
		$dirty++;

		if(query("/captival/ssid:".$ssid_index."/state") == 3 && $this_ssid == $that_ssid)
		{
			$s=1;
			for("/captival/ssid:".$ps_nnn."/passcode/index")
			{
				$s++;
			}
			$i = 1;
			while($i <= $serial_cnt)
			{
				$random_value = "random_s".$i;
				set("/captival/ssid:".$ps_nnn."/passcode/index:".$s."/number",$$random_value);
				set("/captival/ssid:".$ps_nnn."/passcode/index:".$s."/duration",$duration);
				set("/captival/ssid:".$ps_nnn."/passcode/index:".$s."/end",$end_date);
				set("/captival/ssid:".$ps_nnn."/passcode/index:".$s."/device",$device);
				$s++;
				$i++;
			}	
		}
	}
	else if($f_action == "delete_s")
	{
		del("passcode/index:".$which_delete);$dirty++;
		if(query("/captival/ssid:".$ssid_index."/state") == 3 && $this_ssid == $that_ssid)
		{
			del("/captival/ssid:".$ps_nnn."/passcode/index:".$which_delete);
		}
		set("/runtime/captival/if_userpwd", 3);
		set("/runtime/captival/return_index", $ssid_index);
	}
	else if($f_action == "del_all_s")
	{
		$tmp = 0;
		for("/captival/ssid:".$ssid_index."/passcode/index")
		{
			$tmp++;
		}
		$i = 0;
		while($i < $tmp)
		{
			del("passcode/index:1");$i++;
		}
		if(query("/captival/ssid:".$ssid_index."/state") == 3 && $this_ssid == $that_ssid)
		{
			$tmp = 0;
			for("/captival/ssid:".$ps_nnn."/passcode/index")
			{
				$tmp++;
			}
			$i = 0;
			while($i < $tmp)
			{
				del("/captival/ssid:".$ps_nnn."/passcode/index:1");$i++;
			}
		}
		set("/runtime/captival/if_userpwd", 3);
		set("/runtime/captival/return_index", $ssid_index);
		$dirty++;
	}
	else if($f_action == "delete_p")
	{
		set("/captival/ssid:".$which_delete."/state", 0);$dirty++;
	}
	else
	{
		if(query("state") != $encry_type){set("state", $encry_type);$dirty++;}
		if(query("url_state") != $url_state){set("url_state", $url_state);$dirty++;}
		if($url_state == 1)
		{
			if($site_begin == 1){$url_path = "http:\/\/".$url_path;}
			else{$url_path = "https:\/\/".$url_path;}
			if(query("url_path") != $url_path){set("url_path",$url_path);$dirty++;}
		}
		if(query("vlanif/status") != $vlanif_status){set("vlanif/status",$vlanif_status);$dirty++;}
		if($vlanif_status == 1)
		{
			if(query("vlanif/group") != $vlanif_group){set("vlanif/group",$vlanif_group);$dirty++;}
			if(query("vlanif/mode") != $lantype){set("vlanif/mode",$lantype);$dirty++;}
			if($lantype == 1)
			{
				if(query("vlanif/static/ip") != $ipaddr){set("vlanif/static/ip",$ipaddr);$dirty++;}
				if(query("vlanif/static/netmask") != $ipmask){set("vlanif/static/netmask",$ipmask);$dirty++;}
				if(query("vlanif/static/gateway") != $gateway){set("vlanif/static/gateway",$gateway);$dirty++;}
				if(query("vlanif/static/dns") != $dns){set("vlanif/static/dns",$dns);$dirty++;}
			}
			if($con_changessid == 1)
			{
				$index = 1;
				while($index < 17)
				{
					if(query("/captival/ssid:".$index."/vlanif/group") == $vlanif_group)
					{
						set("/captival/ssid:".$index."/vlanif/mode",$lantype);
						if($lantype == 1)
						{
							set("/captival/ssid:".$index."/vlanif/static/ip",$ipaddr);
							set("/captival/ssid:".$index."/vlanif/static/netmask",$ipmask);
							set("/captival/ssid:".$index."/vlanif/static/gateway",$gateway);
							set("/captival/ssid:".$index."/vlanif/static/dns",$dns);
						}
					}
					$index++;
				}
			}
		}
		if($encry_type == 3)
		{
			if($this_ssid == $that_ssid)
			{
				$tmp = 0; //delete all passcode of that ssid
				for("/captival/ssid:".$ps_nnn."/passcode/index")
				{$tmp++;}
				$i = 0;
				while($i < $tmp)
				{
					del("/captival/ssid:".$ps_nnn."/passcode/index:1");$i++;
				}

				$i=0;$j=0; //copy this ssid's passcode to that ssid
				for("/captival/ssid:".$ssid_index."/passcode/index"){$i++;}
				while($j<$i)
				{
					$j++;
					$ps_number = query("/captival/ssid:".$ssid_index."/passcode/index:".$j."/number");
					$ps_duration = query("/captival/ssid:".$ssid_index."/passcode/index:".$j."/duration");
					$ps_end = query("/captival/ssid:".$ssid_index."/passcode/index:".$j."/end");
					$ps_device = query("/captival/ssid:".$ssid_index."/passcode/index:".$j."/device");
					set("/captival/ssid:".$ps_nnn."/passcode/index:".$j."/number", $ps_number);
					set("/captival/ssid:".$ps_nnn."/passcode/index:".$j."/duration", $ps_duration);
					set("/captival/ssid:".$ps_nnn."/passcode/index:".$j."/end", $ps_end);
					set("/captival/ssid:".$ps_nnn."/passcode/index:".$j."/device", $ps_device);
				}	
			}
		}
		if($encry_type == 4)
		{
			if(query("radius/index:1/ip") != $radius_ip1){set("radius/index:1/ip",$radius_ip1);$dirty++;}
			if(query("radius/index:1/port") != $radius_port1){set("radius/index:1/port",$radius_port1);$dirty++;}
			if(queryEnc("radius/index:1/secret") != $radius_sec1){setEnc("radius/index:1/secret",$radius_sec1);$dirty++;}
			if(query("radius/index:1/mode") != $remote_type1){set("radius/index:1/mode",$remote_type1);$dirty++;}
			if($radius_ip2 != "")
			{
				if(query("radius/index:2/ip") != $radius_ip2){set("radius/index:2/ip",$radius_ip2);$dirty++;}
				if(query("radius/index:2/port") != $radius_port2){set("radius/index:2/port",$radius_port2);$dirty++;}
				if(queryEnc("radius/index:2/secret") != $radius_sec2){setEnc("radius/index:2/secret",$radius_sec2);$dirty++;}
				if(query("radius/index:2/mode") != $remote_type2){set("radius/index:2/mode",$remote_type2);$dirty++;}
				if($radius_ip3 != "")
				{
					if(query("radius/index:3/ip") != $radius_ip3){set("radius/index:3/ip",$radius_ip3);$dirty++;}
					if(query("radius/index:3/port") != $radius_port3){set("radius/index:3/port",$radius_port3);$dirty++;}
					if(queryEnc("radius/index:3/secret") != $radius_sec3){setEnc("radius/index:3/secret",$radius_sec3);$dirty++;}
					if(query("radius/index:3/mode") != $remote_type3){set("radius/index:3/mode",$remote_type3);$dirty++;}
				}
			}
		}
		else if($encry_type == 5)
		{
			if(query("ldap/server_ip") != $ldap_ip){set("ldap/server_ip",$ldap_ip);$dirty++;}
			if(query("ldap/port") != $ldap_port){set("ldap/port",$ldap_port);$dirty++;}
			if(query("ldap/auth") != $ldap_auth){set("ldap/auth",$ldap_auth);$dirty++;}	
			if(query("ldap/name") != $ldap_name){set("ldap/name",$ldap_name);$dirty++;}
			if(queryEnc("ldap/passwd") != $ldap_password){setEnc("ldap/passwd",$ldap_password);$dirty++;}
			if(query("ldap/base") != $base_dn){set("ldap/base",$base_dn);$dirty++;}
			if(query("ldap/attribute") != $attribute){set("ldap/attribute",$attribute);$dirty++;}
			if(query("ldap/identity") != $identity){set("ldap/identity",$identity);$dirty++;}
			if(query("ldap/autoid") != $auto_copy){set("ldap/autoid",$auto_copy);$dirty++;}
		}
		else if($encry_type == 6)
		{
			if(query("pop3/server_ip") != $pop3_ip){set("pop3/server_ip",$pop3_ip);$dirty++;}
			if(query("pop3/server_port") != $pop3_port){set("pop3/server_port",$pop3_port);$dirty++;}
			if(query("pop3/ssl_state") != $ssl_state){set("pop3/ssl_state",$ssl_state);$dirty++;}
		}
	}

	if($dirty > 0)
	{
		set("/runtime/web/submit/captival", 1);
		set("/runtime/web/submit/apneaps_v2", 1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}

	set("/runtime/web/next_page",$MY_NAME);
	require($G_SAVING_URL);
}
else if($ACTION_POST == "adv_ip_filter")
{
	echo "<!--\n";
	echo "ACTION_POST=".            $ACTION_POST.           "\n";
	echo "-->\n";

	$dirty=0;
	$SUBMIT_STR = "";


	anchor("/captival/ssid:".$f_ssid);
    if($f_ssid > 8){set("/wlan/ch_mode", 1);}
    else{set("/wlan/ch_mode", 0);}

	if($f_action == "add")
	{
		$s=1;
		for("ipfilter/index")
		{$s++;}
		set("ipfilter/index:".$s."/ip", $ip);
		set("ipfilter/index:".$s."/mask", $mask);
		$dirty++;
	}
	else if($f_action == "del")
	{
		del("ipfilter/index:".$which_del);
		$dirty++;
	}

	if($dirty > 0)
	{
		set("/runtime/web/submit/captival", 1);
		set("/runtime/web/submit/apneaps_v2", 1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}

	set("/runtime/web/next_page",$MY_NAME);
	require($G_SAVING_URL);
}
else if($ACTION_POST == "adv_captivalu_v2")
{
	echo "<!--\n";
	echo "ACTION_POST=".            $ACTION_POST.           "\n";
	echo "-->\n";

	$dirty=0;
	$del_dirty=0;
	$save_dirty=0;
	$SUBMIT_STR = "";

	if($f_action == "del")
	{
		$del_style = query("/captival/mtd/tar/index:".$which_del."/name");
		del("/captival/mtd/tar/index:".$which_del);
		for("/captival/ssid")
		{
			$chosn_style_st = query("webdir");
			$chosn_style = $chosn_style_st.".tar";
			if($chosn_style == $del_style)
			{
				set("webdir", "pages_default");$dirty++;
			}
		}
		$del_dirty++;
	}
	else if($f_action == "save")
	{
		set("/wlan/ch_mode", $band);
		if($band == 0)
		{$start = 1; $end = 9;}
		else
		{$start = 9; $end = 17;}
		if(query("/captival/ssid:".$start."/webdir") != $pri){set("/captival/ssid:".$start."/webdir", $pri);$dirty++;}
		while($start < $end)
		{
			$start++;
			if($band == 1)
			{$web_id = $start - 9;}
			else
			{$web_id = $start - 1;}
			$s_index_name = "s_".$web_id;
			if(query("/captival/ssid:".$start."/webdir") != $$s_index_name){set("/captival/ssid:".$start."/webdir", $$s_index_name);$dirty++;}
		}
	}

	$save_dirty++;
	if($del_dirty > 0)
	{
		$SUBMIT_CAPU_V2 = "submit CAPTIVAL_PORTAL_TAR delet ".$del_style;
		set("/runtime/web/sub_capu_v2",$SUBMIT_CAPU_V2);
	}
	if($dirty > 0)
	{
		set("/runtime/web/submit/captival", 1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}
	if($save_dirty > 0)
	{
		set("/runtime/web/submit/captival_tar", 1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}

	set("/runtime/web/next_page",$MY_NAME);
	require($G_SAVING_URL);
}

?>
