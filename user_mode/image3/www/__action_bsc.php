<?
$AUTH_GROUP=fread("/var/proc/web/session:".$sid."/user/group");
if ($AUTH_GROUP!="0") {require("/www/permission_deny.php");exit;}
$switch = query("/runtime/web/display/switchable");
if($ACTION_POST == "bsc_lan")
{
	echo "<!--\n";
	echo "lantype = ". $lantype ."\n";
	echo "ipaddr = ". $ipaddr ."\n";
	echo "ipmask = ". $ipmask ."\n";
	echo "gateway = ". $gateway ."\n";
	echo "-->\n";

	$SUBMIT_STR = "";
	$dirty = 0;
	$flag_dhcp_restart=0;
	$mode = "/wan/rg/inf:1/mode";
	$dhcp_srv_enable = query("/lan/dhcp/server/enable");
	
	if(query($mode) != $lantype)
	{
		$dirty++; set($mode, $lantype);
		set("/runtime/web/check/change_ip", 1);	
	}

	if ($lantype == 1) // static ip
	{
		$entry="/wan/rg/inf:1/static/";
		if (query($entry."ip")      != $ipaddr)  
		{
			$dirty++; set($entry."ip", $ipaddr);
			set("/runtime/web/check/change_ip", 1);
			if($dhcp_srv_enable=="1")
			{
				$flag_dhcp_restart++;
			}
		}
		if (query($entry."netmask") != $ipmask)  {$dirty++; set($entry."netmask", $ipmask);}
		if (query($entry."gateway") != $gateway) {$dirty++; set($entry."gateway", $gateway);}
		$entry="/dnsrelay/server/";
		if (query($entry."primarydns") != $dns) {$dirty++; set($entry."primarydns", $dns);}
	}

	if($dirty > 0)
	{
		/*victor modify for enos 2009-11-9 start*/	
		$change_ip = query("/runtime/web/check/change_ip");
		if($change_ip== 1)
		{
			set("/runtime/web/submit/wlan", 1);
		}
		/*victor modify for enos 2009-11-9 start*/	
		if(query("/sys/stunnel/enable") == "1") {/*$SUBMIT_STR="; submit WAN; submit CONSOLE; submit STUNNEL";*/set("/runtime/web/submit/stunnel", 1);set("/runtime/web/submit/console", 1);set("/runtime/web/submit/wan", 1);}
		else {/*$SUBMIT_STR=";submit WAN;submit CONSOLE";*/set("/runtime/web/submit/console", 1);set("/runtime/web/submit/wan", 1);}
		if($flag_dhcp_restart > 0)
		{
			/*$SUBMIT_STR=$SUBMIT_STR.";submit DHCPD_RESTART;submit DHCP_COMPARE";*/
//			set("/runtime/web/submit/dhcpd_restart",1);
			set("/runtime/web/submit/dhcpd", 1); //shelley 10-04-28
			set("/runtime/web/submit/dhcp_compare",1);
		}
		set("/runtime/web/sub_str",$SUBMIT_STR);
		set("/runtime/web/submit/wan", 1);
		set("/runtime/web/submit/webredirect", 1);
		set("/runtime/web/submit/console", 1);
		if(query("/wlan/inf:1/aparray_enable") ==1)
		{
			set("/runtime/web/submit/ap_array", 1);
		}
		if(query("/sys/loadbalance/enable") == 1)
		{
			set("/runtime/web/submit/loadbalance", 1);
		}
	}

	set("/runtime/web/next_page",$ACTION_POST);
/*	if($SUBMIT_STR != "") {*/require($G_SAVING_URL);/*}
	else                  {require($G_NO_CHANGED_URL);}*/
}
else if($ACTION_POST == "bsc_wlan")
{
	echo "<!--\n";
	echo "f_scan_value = ". $f_scan_value ."\n";
	echo "band = ". $f_band ."\n";
	echo "mode = ". $f_mode ."\n";
	echo "ssid = ". $f_ssid ."\n";
	echo "ap_hidden = ". $f_ap_hidden ."\n";	
	echo "auto_ch_scan = ". $f_auto_ch_scan ."\n";
	echo "f_chan = ". $f_chan ."\n";
	echo "captival_profile = ". $captival_profile."\n";
	echo "f_auth = ". $f_auth ."\n";	
	echo "f_cipher = ". $f_cipher ."\n";
	echo "keytype = ". $f_keytype ."\n";
	echo "keysize = ". $f_keysize ."\n";
	echo "defkey = ". $f_defkey ."\n";
	echo "key = ". $f_key ."\n";
	echo "gkui = ". $f_gkui ."\n";
	echo "radius_srv_1 = ". $f_radius_srv_1 ."\n";
	echo "radius_port_1 = ". $f_radius_port_1 ."\n";
	echo "radius_sec_1 = ". $f_radius_sec_1 ."\n";
	echo "radius_srv_2 = ". $f_radius_srv_2 ."\n";
	echo "radius_port_2 = ". $f_radius_port_2 ."\n";
	echo "radius_sec_2 = ". $f_radius_sec_2 ."\n";	
	echo "acc_mode = ". $f_acc_mode ."\n";
	echo "acc_srv_1 = ". $f_acc_srv_1 ."\n";
	echo "acc_port_1 = ". $f_acc_port_1 ."\n";
	echo "acc_sec_1 = ". $f_acc_sec_1 ."\n";
	echo "acc_srv_2 = ". $f_acc_srv_2 ."\n";
	echo "acc_port_2 = ". $f_acc_port_2 ."\n";
	echo "acc_sec_2 = ". $f_radius_sec_2 ."\n";			
	echo "passphrase = ". $f_passphrase ."\n";	
	echo "nap_enable = ".$f_nap_enable."\n";
	echo "f_wpa_passphraseformat.value= ".$f_wpa_passphraseformat."\n";
	echo "-->\n";

	$tmp_band = $f_band;
	$SUBMIT_STR = "";
	$dirty = 0;
	$scan_flag=0;
	$limit_dirty=0;
	$url_dirty=0;
	$array_dirty=0;
	$captive_dirty=0;
	if($f_scan_value == "1" || $f_scan_mac_value== "1")
	{
		set("/runtime/web/check_scan_value", 1);

		$scan_flag=1;
		$dirty++;	
		if(query("/runtime/web/wlan/ch_mode")	!=$f_band)	{set("/runtime/web/wlan/ch_mode", $f_band);	$dirty++;}
	
		if($f_band == 0) //11g
		{
			set("/wlan/inf:1/instruction",0);//add for dennis to difference at apc scan or instruction scan
			anchor("/runtime/web/wlan/inf:1");
		}
		else //11a
		{
			set("/wlan/inf:2/instruction",0);//add for dennis to difference at apc scan or instruction scan	
			anchor("/runtime/web/wlan/inf:2");

		}	
		if($switch == 1)
		{
			set("/wlan/inf:1/instruction",0);
			anchor("/runtime/web/wlan/inf:1");
		}
	}
	else
	{
		if(query("/wlan/ch_mode")	!=$f_band)	
		{
			set("/wlan/ch_mode", $f_band);	
			$dirty++;
		}
	
		if($f_band == 0) //11g
		{
			anchor("/wlan/inf:1");
		}
		else //11a
		{
			anchor("/wlan/inf:2");

		}
		if($switch == 1)
		{
			anchor("/wlan/inf:1");
		}		
	}
		
if(query("ap_mode")	!=$f_mode)	
	{
		set("ap_mode", $f_mode);
		if($f_mode == "1" || $f_mode == "4")
		{
			set("/wlan/inf:1/webredirect/enable", 0);$dirty++;
		}
		if($f_mode == "1" || $f_mode == "4" || $f_mode == "3" || $f_mode == "2")
        {
            set("/wlan/inf:1/aparray_enable", 0);$dirty++;
        }
		if($f_mode == "1")
        {
			set("/arpspoofing/enable", 0);
            set("/sys/vlan_state", 0);$dirty++;
        }
		if($f_scan_value != "1" && $f_scan_mac_value!= "1")
		{
			if($f_band==0)
			{set("/runtime/ui/apmode_status", "changed");	}
			else
			{set("/runtime/ui/apmode_status_a", "changed"); }
		}
		$dirty++;
	}
	if($tmp_band == 1)
	{  
		if(query("/wlan/application")	!=$f_application)	{set("/wlan/application", $f_application);	$dirty++;}	
	}
	if(query("ssid")	!=$f_ssid)	
	{
		set("ssid", $f_ssid);   $dirty++;
		if($f_scan_value != "1" && $f_scan_mac_value!= "1")
		{
		if($switch == 1)
		{
			$ps_band = 1;
		}
		else
		{
			if($f_band == 0){$ps_band = 1;}
			else {$ps_band = 9;}
		}
		if(query("/captival/ssid:".$ps_band."/state") == 3)	//check whether this ssid is passcode, if yes, delect the nodes
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
				if($f_ssid == $that_ssid) //if yes, copy the another's passcode to this ssid
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
	}
	if(query("ssidhidden")	!=$f_ap_hidden)	{set("ssidhidden", $f_ap_hidden);	$dirty++;}
	if(query("autochannel")	!=$f_auto_ch_scan)	{set("autochannel", $f_auto_ch_scan);	$dirty++;}
	if($f_auto_ch_scan != 1)
	{	
		if(query("channel")	!=$f_chan)	
		{
			set("channel", $f_chan);	$dirty++;
			if($f_band!=0)
			{
				set("/runtime/stats/wlan/inf:2/dfschannelchange",0);
			}
		}
	}
	if(query("cwmmode")	!=$f_channel_width)	{set("cwmmode", $f_channel_width);	$dirty++;}
	set("/wlan/inf:1/cwm_def_flag",0); //2553b 5G def 20/40
	if(query("captival/state")  !=$f_captival_profile)     {set("captival/state", $f_captival_profile);   $dirty++;}
	if($f_mode != 0)
	{
		set("assoc_limit/enable",0);
		set("/wlan/inf:1/aparray_enable","0");$array_dirty++;
		if($f_mode == 1) //apc
		{
			if($f_band == 0) {set("wlmode",1);} // 11g, mode = mixed n,g,b
			else
			{
				if($switch == 1 || $TITLE == "DAP-2690b") {set("wlmode",1);}
				else{set("wlmode",  4);}
			}
			set("fixedrate",31);
			set("/qos/enable",0);
			set("w_partition",0);
			set("e_partition",0);
			//set("/lan/dhcp/server/enable",0);
			//set("/schedule/enable",0);
			//set("webredirect/enable",0);//Web Redirectionmust close in APC mode
		}
		if($f_mode == 1 || $f_mode == 2 || $f_mode == 4) //if AP Mode is not 'Access Point' or 'WDS with AP', MSSID should be disabled
		{
			set("multi/state",	0);
			set("igmpsnoop",0);			
		}
	}
        if($f_mode == 1 || $f_mode == 4)
        {
        				 if(query("/wlan/inf:1/multicast_bwctrl") != 0) {set("/wlan/inf:1/multicast_bwctrl",0);}//apc and wds mcastrate must disabled
                if(query("mcastrate") != 0) {set("mcastrate",0);}//apc and wds mcastrate must disabled
                if(query("/wlan/inf:1/webredirect/enable")      != 0)   {set("/wlan/inf:1/webredirect/enable",      0);     $url_dirty++;}//Web Redirectionmust close in APC and wds mode
				if(query("/wlan/inf:1/webredirect/auth/enable")  != 0)   {set("/wlan/inf:1/webredirect/auth/enable",  0);$url_dirty++;}
		        if(query("/wlan/inf:1/webredirect/url/enable")  != 0)   {set("/wlan/inf:1/webredirect/url/enable",  0);$url_dirty++;}
        }

	if($f_mode == 3 || $f_mode == 4) //wds with ap, wds
	{
		if(query("wds/list/index:1/mac")	!= $f_wds_mac1)	{set("wds/list/index:1/mac",	$f_wds_mac1);	$dirty++;}
		if(query("wds/list/index:2/mac")	!= $f_wds_mac2)	{set("wds/list/index:2/mac",	$f_wds_mac2);	$dirty++;}
		if(query("wds/list/index:3/mac")	!= $f_wds_mac3)	{set("wds/list/index:3/mac",	$f_wds_mac3);	$dirty++;}
		if(query("wds/list/index:4/mac")	!= $f_wds_mac4)	{set("wds/list/index:4/mac",	$f_wds_mac4);	$dirty++;}
		if(query("wds/list/index:5/mac")	!= $f_wds_mac5)	{set("wds/list/index:5/mac",	$f_wds_mac5);	$dirty++;}
		if(query("wds/list/index:6/mac")	!= $f_wds_mac6)	{set("wds/list/index:6/mac",	$f_wds_mac6);	$dirty++;}
		if(query("wds/list/index:7/mac")	!= $f_wds_mac7)	{set("wds/list/index:7/mac",	$f_wds_mac7);	$dirty++;}
		if(query("wds/list/index:8/mac")	!= $f_wds_mac8)	{set("wds/list/index:8/mac",	$f_wds_mac8);	$dirty++;}
	}
	if($f_mode == 3)
    {
        if(query("multi/index:1/auth") == 2 || query("multi/index:1/auth") == 4 || query("multi/index:1/auth") == 6 || query("multi/index:1/auth") == 9) {set("multi/index:1/auth", 0); set("multi/index:1/cipher", 0);}
        if(query("multi/index:2/auth") == 2 || query("multi/index:2/auth") == 4 || query("multi/index:2/auth") == 6 || query("multi/index:2/auth") == 9) {set("multi/index:2/auth", 0); set("multi/index:2/cipher", 0);}
        if(query("multi/index:3/auth") == 2 || query("multi/index:3/auth") == 4 || query("multi/index:3/auth") == 6 || query("multi/index:3/auth") == 9) {set("multi/index:3/auth", 0); set("multi/index:3/cipher", 0);}
        if(query("multi/index:4/auth") == 2 || query("multi/index:4/auth") == 4 || query("multi/index:4/auth") == 6 || query("multi/index:4/auth") == 9) {set("multi/index:4/auth", 0); set("multi/index:4/cipher", 0);}
        if(query("multi/index:5/auth") == 2 || query("multi/index:5/auth") == 4 || query("multi/index:5/auth") == 6 || query("multi/index:5/auth") == 9) {set("multi/index:5/auth", 0); set("multi/index:5/cipher", 0);}
        if(query("multi/index:6/auth") == 2 || query("multi/index:6/auth") == 4 || query("multi/index:6/auth") == 6 || query("multi/index:6/auth") == 9) {set("multi/index:6/auth", 0); set("multi/index:6/cipher", 0);}
        if(query("multi/index:7/auth") == 2 || query("multi/index:7/auth") == 4 || query("multi/index:7/auth") == 6 || query("multi/index:7/auth") == 9) {set("multi/index:7/auth", 0); set("multi/index:7/cipher", 0);}
    }
	if(query("authentication")	!=$f_auth)	{set("authentication", $f_auth);	$dirty++;}
	if(query("wpa/wepmode")	!=$f_cipher)	{set("wpa/wepmode", $f_cipher);	$dirty++;}	
	if($f_auth == 0 || $f_auth == 1 || $f_auth == 8) //open, shared, both
	{
		if($f_scan_value != "1" && $f_scan_mac_value != "1")
		{
		if($f_cipher == 1)
		{	
			if(query("defkey")	!=$f_defkey)	{set("defkey", $f_defkey);	$dirty++;}
			if($f_defkey == 1)
			{
				if(query("wepkey:1/keylength")	!=$f_keysize)	{set("wepkey:1/keylength", $f_keysize);	$dirty++;}	
				if(query("wepkey:1/keyformat")	!=$f_keytype)	{set("wepkey:1/keyformat", $f_keytype);	$dirty++;}	
				if(queryEnc("wepkey:1")	!=$f_key)	{setEnc("wepkey:1", $f_key);	$dirty++;}	
			}
			else if($f_defkey == 2)
			{
				if(query("wepkey:2/keylength")	!=$f_keysize)	{set("wepkey:2/keylength", $f_keysize);	$dirty++;}	
				if(query("wepkey:2/keyformat")	!=$f_keytype)	{set("wepkey:2/keyformat", $f_keytype);	$dirty++;}	
				if(queryEnc("wepkey:2")	!=$f_key)	{setEnc("wepkey:2", $f_key);	$dirty++;}	
			}	
			else if($f_defkey == 3)
			{
				if(query("wepkey:3/keylength")	!=$f_keysize)	{set("wepkey:3/keylength", $f_keysize);	$dirty++;}	
				if(query("wepkey:3/keyformat")	!=$f_keytype)	{set("wepkey:3/keyformat", $f_keytype);	$dirty++;}	
				if(queryEnc("wepkey:3")	!=$f_key)	{setEnc("wepkey:3", $f_key);	$dirty++;}	
			}
			else if($f_defkey == 4)
			{
				if(query("wepkey:4/keylength")	!=$f_keysize)	{set("wepkey:4/keylength", $f_keysize);	$dirty++;}	
				if(query("wepkey:4/keyformat")	!=$f_keytype)	{set("wepkey:4/keyformat", $f_keytype);	$dirty++;}	
				if(queryEnc("wepkey:4")	!=$f_key)	{setEnc("wepkey:4", $f_key);	$dirty++;}	
			}	

			for("multi/index")
			{
				$check_ms_index = query("wep_key_index");
				if($check_ms_index == $f_defkey)
				{
					set("wep_keylength",	$f_keysize);	$dirty++;
					set("wep_keyformat",	$f_keytype);	$dirty++;
					set("wep_key",			$f_key);		$dirty++;			
				}
			}														
		}
		}
	}
	else if($f_auth == 2 || $f_auth == 4 || $f_auth == 6 || $f_auth == 9) //eap or 802.1x
	{
		if(query("/runtime/web/display/nap_server") !="0" && $f_auth != 9)
		{		
			if(query("/sys/vlan_mode")	!= $f_nap_enable)	{set("/sys/vlan_mode", $f_nap_enable);	$dirty++;}
				if($f_nap_enable==1)
				{
					if(query("/sys/adminlimit/status")==3){set("/sys/adminlimit/status",2);$limit_dirty++;}
			        if(query("/sys/adminlimit/status")==1){set("/sys/adminlimit/status",0);$limit_dirty++;}

				}
		}
		if($f_auth != 9)	//802.1x don't have the follow settings
		{
			if($f_mode == 1)//eap apc setting 
			{
				if(query("wpa/eap_phase1")	!= $f_apc_eap_method)	{set("wpa/eap_phase1", $f_apc_eap_method);	$dirty++;}
				if(query("wpa/eap_phase2")	!= $f_apc_inner_auth)	{set("wpa/eap_phase2", $f_apc_inner_auth);	$dirty++;}
				if(query("wpa/eap_identity")	!= $f_apc_eap_username)	{set("wpa/eap_identity", $f_apc_eap_username);	$dirty++;}
				if($f_scan_value != "1" && $f_scan_mac_value != "1")
				{if(queryEnc("wpa/eap_passwd")	!= $f_apc_eap_password)	{setEnc("wpa/eap_passwd", $f_apc_eap_password);	$dirty++;}}
			}
			if(query("/runtime/web/display/nap_server") !="0" && $f_auth != 9)
			{		
				if(query("/sys/vlan_mode")	!= $f_nap_enable)	{set("/sys/vlan_mode", $f_nap_enable);	$dirty++;}
			}
			if(query("wpa/grp_rekey_interval")	!=$f_gkui)	{set("wpa/grp_rekey_interval", $f_gkui);	$dirty++;}
		}
		else	//802.1x key update interval
		{
			if(query("d_wep_rekey_interval")	!=$f_kui)	{set("d_wep_rekey_interval", $f_kui);	$dirty++;}
		}

		if(query("/runtime/web/display/local_radius_server") !="0") //Remote or Local RADIUS Server
		{		
			if(query("wpa/embradius/state")	!= $f_radius_type)	{set("wpa/embradius/state", $f_radius_type);	$dirty++;}
		}
		
		
		
		if($f_radius_type == 0 || $f_radius_type == "")
		{
		if(query("wpa/radiusserver")	!=$f_radius_srv_1)	{set("wpa/radiusserver", $f_radius_srv_1);	$dirty++;}	
		if(query("wpa/radiusport")	!=$f_radius_port_1)	{set("wpa/radiusport", $f_radius_port_1);	$dirty++;}	
		if($f_scan_value != "1" && $f_scan_mac_value != "1")
		{if(queryEnc("wpa/radiussecret")	!=$f_radius_sec_1)	{setEnc("wpa/radiussecret", $f_radius_sec_1);	$dirty++;}}
		if(query("/runtime/web/display/backup_radius_server") !="0")	
		{
			if(query("wpa/b_radiusserver")	!=$f_radius_srv_2)	{set("wpa/b_radiusserver", $f_radius_srv_2);	$dirty++;}	
			if(query("wpa/b_radiusport")	!=$f_radius_port_2)	{set("wpa/b_radiusport", $f_radius_port_2);	$dirty++;}	
			if($f_scan_value != "1" && $f_scan_mac_value != "1")
			{if(queryEnc("wpa/b_radiussecret")	!=$f_radius_sec_2)	{setEnc("wpa/b_radiussecret", $f_radius_sec_2);	$dirty++;}}
		}	
		
		if(query("/runtime/web/display/accounting_server") != "0")
		{		
			if(query("wpa/acctstate")	!=$f_acc_mode)	{set("wpa/acctstate", $f_acc_mode);	$dirty++;}	
			if($f_acc_mode == 1)
			{
				if(query("wpa/acctserver")	!=$f_acc_srv_1)	{set("wpa/acctserver", $f_acc_srv_1);	$dirty++;}	
				if(query("wpa/acctport")	!=$f_acc_port_1)	{set("wpa/acctport", $f_acc_port_1);	$dirty++;}	
				if($f_scan_value != "1" && $f_scan_mac_value != "1")
				{if(queryEnc("wpa/acctsecret")	!=$f_acc_sec_1)	{setEnc("wpa/acctsecret", $f_acc_sec_1);	$dirty++;}}
				if(query("wpa/b_acctserver")	!=$f_acc_srv_2)	{set("wpa/b_acctserver", $f_acc_srv_2);	$dirty++;}	
				if(query("wpa/b_acctport")	!=$f_acc_port_2)	{set("wpa/b_acctport", $f_acc_port_2);	$dirty++;}	
				if($f_scan_value != "1" && $f_scan_mac_value != "1")
				{if(queryEnc("wpa/b_acctsecret")	!=$f_acc_sec_2)	{setEnc("wpa/b_acctsecret", $f_acc_sec_2);	$dirty++;}}
			}
		}
		}					
	}
	else //psk
	{
		if($f_mode != 1) // not apc
		{
			if(query("wpa/grp_rekey_interval")	!=$f_gkui)	{set("wpa/grp_rekey_interval", $f_gkui);	$dirty++;}
		}	
		if(query("autorekey/ssid/enable")       !=$f_enable_rekey)      {set("autorekey/ssid/enable", $f_enable_rekey); $dirty++;}
		if($f_enable_rekey == 1)
		{
			if(query("/wlan/inf:1/autorekey/starttime") !=$f_start_time)        {set("/wlan/inf:1/autorekey/starttime", $f_start_time);     $dirty++;}
			if(query("/wlan/inf:1/autorekey/startweek") !=$f_start_week)        {set("/wlan/inf:1/autorekey/startweek", $f_start_week);     $dirty++;}
		if(query("/wlan/inf:1/autorekey/time")      !=$f_time_interuol)     {set("/wlan/inf:1/autorekey/time", $f_time_interuol);       $dirty++;}
		}
		else
		{
			if($f_scan_value != "1" && $f_scan_mac_value != "1")
			{
				if(queryEnc("wpa/wpapsk")  !=$f_passphrase)
				{
					if(query("wpa/passphraseformat")!= $f_wpa_passphraseformat){set("wpa/passphraseformat", $f_wpa_passphraseformat);      $dirty++;}
					setEnc("wpa/wpapsk", $f_passphrase);      $dirty++;
				}
			}
		}
	}
	if($f_mode == 1)
	{
	if($f_mac_enable ==0)
	{
		if (query("/wlan/inf:1/macclone/type") != 0)		{$dirty++; set("/wlan/inf:1/macclone/type", 0);}
	}
	else
	{
		if($f_mac_source==1)
		{
			if (query("/wlan/inf:1/macclone/type") != 1)		{$dirty++; set("/wlan/inf:1/macclone/type", 1);}
		}
		else
		{
			if (query("/wlan/inf:1/macclone/type") != 2)		{$dirty++; set("/wlan/inf:1/macclone/type", 2);}
		}
	}	
	if (query("/wlan/inf:1/macclone/addr") != $f_clone_mac)		{$dirty++; set("/wlan/inf:1/macclone/addr", $f_clone_mac);}				
	}
	if($dirty > 0)
	{
		if($f_scan_value == "1")
		{
			if($f_mode == 3 || $f_mode == 4)
			{
				if($f_band == 0)
				{$SUBMIT_STR="submit WDS_SCAN_G";}
				else if($f_band == 1)
				{$SUBMIT_STR="submit WDS_SCAN_A";}
			}
			else
			{
				if($f_band == 0)
                {$SUBMIT_STR="submit SCAN_G";}
                else if($f_band == 1)
                {$SUBMIT_STR="submit SCAN_A";}
			}	
		}
		else if($f_scan_mac_value == "1")
		{
			$SUBMIT_STR="submit MAC_SCAN";
		}
		else
		{
			// $SUBMIT_STR="submit WLAN";
     			if($f_enable_rekey==1)
                        {
                                set("/runtime/web/msg_autorekey", "display");
                        }
                        if($url_dirty > 0)
                        {
                                set("/runtime/web/submit/webredirect", 1);
                        }
                        if($limit_dirty > 0)
                        {
                                set("/runtime/web/submit/limit", 1);
                        }
						if($array_dirty > 0)
						{
							set("/runtime/web/submit/ap_array","1");
						}

			set("/runtime/web/submit/wlan", 1);
		}	
		set("/runtime/web/sub_str",$SUBMIT_STR);
		set("/runtime/web/sub_xgi_str",$XGISET_STR);
	}
	if($captive_dirty > 0)
	{
		set("/runtime/web/submit/captival", 1);
		set("/runtime/web/submit/apneaps_v2", 1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}

	set("/runtime/web/next_page",$ACTION_POST);
	if($SUBMIT_STR!="" && $scan_flag=="1")	{require($G_SCAN_URL);}
	else /* if($SUBMIT_STR!="" && $f_scan_value!="1")	*/{require($G_SAVING_URL);}	
//	else				{require($G_NO_CHANGED_URL);}
}
else if($ACTION_POST == "bsc_ipv6")
{
	echo "<!--\n";

	echo "-->\n";

	$SUBMIT_STR = "";
	$dirty = 0;
//	$ipv6_dirty = 0;
	anchor("/inet/entry:1/ipv6");
	if (query("valid") != $f_ipv6_enable)  {$dirty++; set("valid", $f_ipv6_enable);}
	if(query("mode") != $ipv6_lantype)	{$dirty++; set("mode", $ipv6_lantype);}
	if ($ipv6_lantype == "static") // static ip
	{
		if (query("ipaddr")      != $ipaddr6)  {$dirty++; set("ipaddr", $ipaddr6);}
		if (query("prefix") != $prefix)  {$dirty++; set("prefix", $prefix);}
//		if (query("dns") != $dns6) {$dirty++; set("dns", $dns6);}
		if (query("gateway") != $gateway6) {$dirty++; set("gateway", $gateway6);}
	}

	if($f_ipv6_enable == 1)
	{
		if(query("/wlan/inf:1/ap_mode") == 1)
		{
			set("/wlan/inf:1/ap_mode", 0);$dirty++;
		}
		set("/wlan/inf:1/aparray_enable", 0);
		set("/sys/autorf/enable", 0);
		set("/sys/loadbalance/enable", 0);
		$dirty++;
	}

	if($dirty > 0)
	{
		set("/runtime/web/submit/ap_array", 1);
		set("/runtime/web/submit/loadbalance", 1);
		set("/runtime/web/submit/autorf", 1);
		set("/runtime/web/submit/apneaps_v2", 1);
		set("/runtime/web/submit/console", 1);
		set("/runtime/web/submit/wan", 1);
		set("/runtime/web/submit/wlan", 1);
		set("/runtime/web/submit/ipv6", 1);
		set("/runtime/web/sub_str",$SUBMIT_STR);
	}

	set("/runtime/web/next_page",$ACTION_POST);
	/*if($dirty > 0) {*/require($G_SAVING_URL);/*}
	else                  {require($G_NO_CHANGED_URL);}*/
}
?>
