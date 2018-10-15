<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "bsc_wlan";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "bsc_wlan";
set("/runtime/web/help_page",$MY_NAME);
/* --------------------------------------------------------------------------- */
if($ACTION_POST != "")
{
	require("/www/model/__admin_check.php");
	require("/www/__action_bsc.php");
	$ACTION_POST = "";
	//exit;
}

/* --------------------------------------------------------------------------- */
require("/www/model/__html_head.php");
require("/www/comm/__js_ip.php");
set("/runtime/web/next_page",$first_frame);
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
echo "<!--debug\n";
$check_band = query("/wlan/inf:2/ap_mode");
$if_outdoor = query("/sys/outdoor");
$cfg_outdoor_eu = query("/runtime/web/display/outdoor_eu");
if($band_reload == 0 || $band_reload == 1) // change band
{
	$cfg_band = $band_reload;
}
else // first init
{
	if(query("/runtime/web/check_scan_value")=="1")
	{
		$cfg_band = query("/runtime/web/wlan/ch_mode");
	}
	else
	{
		$cfg_band = query("/wlan/ch_mode");
	}
}
$cfg_index = $cfg_band + 1;
$switch = query("/runtime/web/display/switchable");
if($switch == 1)
{
	if(query("/runtime/web/check_scan_value")=="1")
	{	
		anchor("/runtime/web/wlan/inf:1");
	}
	else
	{
		anchor("/wlan/inf:1");
	}
	$another_band_mode = ""; 
	$cfg_index = 1;
}
else
{
	if($cfg_band == 0) // 11g
	{
		echo "anchor 11g";
		if(query("/runtime/web/check_scan_value")=="1")
		{	
			anchor("/runtime/web/wlan/inf:1");
		}
		else
		{
			anchor("/wlan/inf:1");
		}
		$another_band_mode = query("/wlan/inf:2/ap_mode");
			$another_auth = query("/wlan/inf:2/authentication");
	}
	else
	{
		echo "anchor 11a";
		if(query("/runtime/web/check_scan_value")=="1")
		{	
			anchor("/runtime/web/wlan/inf:2");
		}
		else
		{
			anchor("/wlan/inf:2");
		}
		$another_band_mode = query("/wlan/inf:1/ap_mode");
			$another_auth = query("/wlan/inf:1/authentication");
	}
}

$cfg_wl_enable = query("enable");
if($rapplication == 0 || $rapplication ==1)
{
	$application = $rapplication;
}else	{
	$application =  query("/wlan/application");	
 }
if($application == "")
{
	$application = 0;
}

$runtime_backup_radius_server = query("/runtime/web/display/backup_radius_server");
$runtime_mssid_support_sharedkey = query("/runtime/web/display/mssid_support_sharedkey");
$cfg_outdoor = query("/runtime/web/display/outdoor");
$cfg_mode = query("ap_mode");
$cfg_real_mode = query("ap_mode");
$cfg_ssid =get("j", "ssid");
$cfg_ssid_hidden = query("ssidhidden");
$cfg_auto_ch_scan = query("autochannel");
$cfg_auto_rf = query("/sys/autorf/enable");
$cfg_wlmode = query("wlmode");
$cfg_dfschannel = query("/runtime/stats/wlan/inf:2/dfschannelchange");
if($cfg_wlmode == "")
{
$cfg_wlmode = "4";
}
//if ($cfg_auto_ch_scan==1 || $cfg_mode==1 || $cfg_mode==5 || $cfg_dfschannel ==1)
//{
//	$cfg_channel =query("/runtime/stats/wlan/inf:".$cfg_index."/channel");
//}
//else
//{
$cfg_channel = query("channel");
//}
$cfg_channel_width = query("cwmmode");
$cfg_cwm_flag = query("/wlan/inf:1/cwm_def_flag");
$cap_v1 = query("/runtime/web/display/cap_v1");
$cap_v2 = query("/runtime/web/display/cap_v2");
$cfg_captival_profile = query("captival/state");
$cfg_local = query("/captival/auth:1/state");
$cfg_radius = query("/captival/auth:2/state");
$cfg_ldap = query("/captival/auth:3/state");
$cfg_pop3 = query("/captival/auth:4/state");
$cfg_ticket = query("/captival/auth:7/state");
$cfg_auth = query("authentication");
$cfg_cipher = query("wpa/wepmode");
$security_type = $cfg_auth;
if($cfg_auth=="2" || $cfg_auth=="4" || $cfg_auth=="6") {$security_type="2";} //eap
else if ($cfg_auth=="3" || $cfg_auth=="5" || $cfg_auth=="7") {$security_type="3";} //psk

$t_auth = query("/wlan/inf:1/authentication");
$security_eap = "";
if($t_auth=="2" || $t_auth=="4" || $t_auth=="6")
{$security_eap="1";} //eap
$t_auth_a = query("/wlan/inf:2/authentication");
$security_eap_a = "";
if($t_auth_a=="2" || $t_auth_a=="4" || $t_auth_a=="6")
{$security_eap_a="1";} //eap

$cfg_wep_defkey	= query("/wlan/inf:".$cfg_index."/defkey");
$cfg_wep1_keytype = query("/wlan/inf:".$cfg_index."/wepkey:1/keyformat");/* 1:ASCII 2:HEX */
$cfg_wep2_keytype = query("/wlan/inf:".$cfg_index."/wepkey:2/keyformat");
$cfg_wep3_keytype = query("/wlan/inf:".$cfg_index."/wepkey:3/keyformat");
$cfg_wep4_keytype = query("/wlan/inf:".$cfg_index."/wepkey:4/keyformat");
$cfg_wep1_length = query("/wlan/inf:".$cfg_index."/wepkey:1/keylength");
$cfg_wep2_length = query("/wlan/inf:".$cfg_index."/wepkey:2/keylength");
$cfg_wep3_length = query("/wlan/inf:".$cfg_index."/wepkey:3/keylength");
$cfg_wep4_length = query("/wlan/inf:".$cfg_index."/wepkey:4/keylength");
$cfg_wep1		= queryEnc("/wlan/inf:".$cfg_index."/wepkey:1");
$cfg_wep2		= queryEnc("/wlan/inf:".$cfg_index."/wepkey:2");
$cfg_wep3		= queryEnc("/wlan/inf:".$cfg_index."/wepkey:3");
$cfg_wep4		= queryEnc("/wlan/inf:".$cfg_index."/wepkey:4");
$cfg_wpapsk		= queryEnc("/wlan/inf:".$cfg_index."/wpa/wpapsk");
$cfg_key_interval	= query("wpa/grp_rekey_interval");
$cfg_enable_rekey	= query("autorekey/ssid/enable");
$cfg_time_interuol = query("/wlan/inf:1/autorekey/time");
$cfg_start_time =query("/wlan/inf:1/autorekey/starttime");
$cfg_start_week =query("/wlan/inf:1/autorekey/startweek");
$cfg_ntp_enable=query("/time/syncwith");/*disabled=0,enabled=2*/
if($cfg_band==0)
{
	$cfg_rekey		= get("j","/runtime/wlan/inf:1/wpa/wpapsk");
}
else
{
	$cfg_rekey		= get("j","/runtime/wlan/inf:2/wpa/wpapsk");
}
$cfg_radius_srv_1	= query("wpa/radiusserver");
$cfg_radius_port_1	= query("wpa/radiusport");
$cfg_radius_sec_1	= queryEnc("/wlan/inf:".$cfg_index."/wpa/radiussecret");
$cfg_radius_srv_2	= query("wpa/b_radiusserver");
$cfg_radius_port_2	= query("wpa/b_radiusport");
$cfg_radius_sec_2	= queryEnc("/wlan/inf:".$cfg_index."/wpa/b_radiussecret");
$cfg_acc_mode	= query("wpa/acctstate");
$cfg_acc_srv_1	= query("wpa/acctserver");
$cfg_acc_port_1	= query("wpa/acctport");
$cfg_acc_sec_1	= queryEnc("/wlan/inf:".$cfg_index."/wpa/acctsecret");
$cfg_acc_srv_2	= query("wpa/b_acctserver");
$cfg_acc_port_2	= query("wpa/b_acctport");
$cfg_acc_sec_2	= queryEnc("/wlan/inf:".$cfg_index."/wpa/b_acctsecret");
$cfg_m1_au = query("multi/index:1/auth");
$cfg_m2_au = query("multi/index:2/auth");
$cfg_m3_au = query("multi/index:3/auth");
$cfg_m4_au = query("multi/index:4/auth");
$cfg_m5_au = query("multi/index:5/auth");
$cfg_m6_au = query("multi/index:6/auth");
$cfg_m7_au = query("multi/index:7/auth");
if($cfg_m1_au == 2 || $cfg_m1_au == 4 || $cfg_m1_au == 6 || $cfg_m1_au == 9){$cfg_m1_cha = 1;}
if($cfg_m2_au == 2 || $cfg_m2_au == 4 || $cfg_m2_au == 6 || $cfg_m2_au == 9){$cfg_m2_cha = 1;}
if($cfg_m3_au == 2 || $cfg_m3_au == 4 || $cfg_m3_au == 6 || $cfg_m3_au == 9){$cfg_m3_cha = 1;}
if($cfg_m4_au == 2 || $cfg_m4_au == 4 || $cfg_m4_au == 6 || $cfg_m4_au == 9){$cfg_m4_cha = 1;}
if($cfg_m5_au == 2 || $cfg_m5_au == 4 || $cfg_m5_au == 6 || $cfg_m5_au == 9){$cfg_m5_cha = 1;}
if($cfg_m6_au == 2 || $cfg_m6_au == 4 || $cfg_m6_au == 6 || $cfg_m6_au == 9){$cfg_m6_cha = 1;}
if($cfg_m7_au == 2 || $cfg_m7_au == 4 || $cfg_m7_au == 6 || $cfg_m7_au == 9){$cfg_m7_cha = 1;}
if(query("/runtime/web/display/mssid_index4")=="1")
{
	$cfg_wds_mac_num = 4;
}
else
{
$cfg_wds_mac_num = 8;
}
$cfg_nap_enable = query("/sys/vlan_mode");
$cfg_vlan = query("/sys/vlan_state");
$cfg_limit_admin_status = query("/sys/adminlimit/status");
$cfg_mssid_status = query("multi/state");
$cfg_1x_key_interval = query("d_wep_rekey_interval");
$cfg_radius_type = query("wpa/embradius/state");

$cfg_apc_eap_method=query("wpa/eap_phase1");
$cfg_apc_inner_auth=query("wpa/eap_phase2");
$cfg_apc_eap_username=query("wpa/eap_identity");
$cfg_apc_eap_password=queryEnc("/wlan/inf:".$cfg_index."/wpa/eap_passwd");

$cfg_wlan_domain = query("/runtime/layout/countrycode");
$maclone_type = query("/wlan/inf:1/macclone/type");
if($maclone_type==1)
{
	$maclone_addr = query("/runtime/macclone/addr");
	$cfg_clone_mac_enable = 1;
	$cfg_mac_source=1;
}
elseif($maclone_type==2)
{
	$maclone_addr = query("/wlan/inf:1/macclone/addr");
	$cfg_clone_mac_enable = 1;
	$cfg_mac_source=0;
}
else
{
	$maclone_addr = query("/wlan/inf:1/macclone/addr");
	$cfg_clone_mac_enable = 0;
}

$cfg_apmode_changed = query("/runtime/ui/apmode_status");
$cfg_apmode_changed_a = query("/runtime/ui/apmode_status_a");
$flag_msg_autorekey =query("/runtime/web/msg_autorekey");
set("/runtime/web/msg_autorekey","none" );
if($cfg_wlan_domain != 392)
	{
		$application = 0;
 	}
$cfg_aparray = query("/wlan/inf:1/aparray_enable");
$cfg_url = query("/wlan/inf:1/webredirect/enable");
$cfg_arpspoofing = query("/arpspoofing/enable");
$count_mac=0;
for("/runtime/macaddr_ethernet")
{
	$count_mac++;
}
$cfg_channel_flag = query("/runtime/stats/wlan/inf:2/channeltable/wdschannel:1/channel");
$cfg_display_repeater = query("/runtime/web/display/ap_repeater");
$clone_list_row = 0;
echo "band_reload = ".$band_reload."\n";
echo "cfg_band = ".$cfg_band."\n";
echo "cfg_mode = ".$cfg_mode."\n";
echo "cfg_ssid_hidden = ".$cfg_ssid_hidden."\n";
echo "cfg_channel = ".$cfg_channel."\n";
echo "cfg_channel_width = ".$cfg_channel_width."\n";
echo "cfg_auth = ".$cfg_auth."\n";
echo "cfg_wlmode = ".$cfg_wlmode."\n";
echo "-->";

set("/runtime/web/check_scan_value",	0);
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>

var clone_mac;
var max_mac=<?=$count_mac?>;
var check_mssid_msg = 0;
var check_vlan_msg = 0;
var w_chan = "";
var w_auth = "";
var w_cipher = "";
var w_wpa_mode = "";
var start_time = "<?=$cfg_start_time?>";
var shour="";
var smin="";
var mode_value = "";
var w_chw = "";

var clone_list=[['index','mac']
<?
for("/runtime/macaddr_ethernet")
{
    echo ",\n ['".$@."','".query("addr")."']";
    $clone_list_row++;
}
?>
];

function page_table_clone(index)
{
	var str="";
	str+= "<input type=\"hidden\" id=\"scan_mac_clone"+index+"\" name=\"scan_mac_clone"+index+"\" value=\""+clone_list[index][1]+"\">\n";
	if(index%2== 1)
	{
		str+= "<tr style=\"background:#CCCCCC;\">\n";
	}
	else
	{
		str+= "<tr style=\"background:#B3B3B3;\">\n";
	}
	str+= "<td width=\"40\" align='center'><input type=\"radio\" name=\"scan_table\" id=\"scan_mac_table"+index+"\" onclick=\"on_click_scan_mac_table("+index+")\"></td>\n";
	str+= "<td width=\"150\">"+clone_list[index][1]+"</td>\n";
	str+="</tr>\n";
	document.write(str);
}

function print_rssi(f_rssi)
{
    rssi = f_rssi - 95;
    str = "";
    str += "<td width=\"70\">"+rssi+"</td>\n";
    document.write(str);
}

function on_click_nap_enable()
{
	var f = get_obj("frm");
	if(f.nap_enable[1].checked == true && "<?=$cfg_nap_enable?>" == 0  && "<?=$cfg_limit_admin_status?>"==1 || "<?=$cfg_limit_admin_status?>"==3)
	{
		alert("<?=$a_enable_nap?>");
	}
	f.radius_type[0].disabled = f.radius_type[1].disabled = false;
	if(f.nap_enable[1].checked && w_auth.value == 2)
	{
		f.radius_type[0].disabled = f.radius_type[1].disabled = true;
		f.radius_type[0].checked = true;
	}
	on_change_radius_type();
}
function put_mac(mac)
{
	var f = get_obj("frm");
	var substring1 = mac.split(":");
	var j=0;
	for(var i=0 ; i<6 ; i++)
	{
		j=i+1;	
		eval("f.clone_mac"+j).value=substring1[i];
	}
}
function on_click_scan_mac_table(id)
{
	var f = get_obj("frm");
	var str = get_obj("scan_mac_clone"+id).value;
	put_mac(str);
	
}
function do_scan_mac()
{
	var f_final	=get_obj("final_form");
	f_final.f_scan_mac_value.value="1";	
	submit();	
}
function on_change_mac_source()
{
	var f = get_obj("frm");
	if(f.mac_source.value==0)
	{
		f.clone_mac1.disabled = f.clone_mac2.disabled = f.clone_mac3.disabled = f.clone_mac4.disabled = f.clone_mac5.disabled = f.clone_mac6.disabled = false;
		f.scan_mac.disabled = false;
		for(var i=1; i<=max_mac; i++)
    	{
    	    get_obj("scan_mac_table"+i).disabled=false;
	    }
	}
	else
	{
		f.clone_mac1.disabled = f.clone_mac2.disabled = f.clone_mac3.disabled = f.clone_mac4.disabled = f.clone_mac5.disabled = f.clone_mac6.disabled  = true;	
		f.scan_mac.disabled = true;
		for(var i=1; i<=max_mac; i++)
    	{
    	    get_obj("scan_mac_table"+i).disabled=true;
	    }

	}
}

function on_click_clone_mac_enable()
{
	var f = get_obj("frm");
	if(f.clone_mac_enable.checked)
	{
		f.mac_source.disabled = false;
		f.clone_mac1.disabled = f.clone_mac2.disabled = f.clone_mac3.disabled = f.clone_mac4.disabled = f.clone_mac5.disabled = f.clone_mac6.disabled = false;
		f.scan_mac.disabled = false;
		on_change_mac_source();
	}
	else
	{
		f.mac_source.disabled = true;
		f.clone_mac1.disabled = f.clone_mac2.disabled = f.clone_mac3.disabled = f.clone_mac4.disabled = f.clone_mac5.disabled = f.clone_mac6.disabled = true;
		f.scan_mac.disabled = true;
		for(var i=1; i<=max_mac; i++)
		{
        	get_obj("scan_mac_table"+i).disabled=true;
	    }
	}
	
}

function on_change_method()
{
	var f = get_obj("frm");
	if(f.apc_eap_method.value==2)
	{
		f.apc_inner_auth.value=2;
		f.apc_inner_auth.disabled=true;
	}
	else
	{
		f.apc_inner_auth.disabled=false;
	}
}

function init_time()
{
	var f = get_obj("frm");
	for(var i=0; i < start_time.length; i++)
	{
		if(i<2)
		{
			shour+=start_time.charAt(i);
		}
		if(i>2)
		{
			smin+=start_time.charAt(i);
		}
	}
	f.shour.value=shour;
	f.smin.value=smin;
	select_index(f.sweek, "<?=$cfg_start_week?>");
}
function on_click_enable_rekey()
{
	var f = get_obj("frm");
	get_obj("psk_setting1").style.display = "none";
	get_obj("key_change_psk_setting").style.display = "none";
	if(get_obj("psk_setting").style.display != "none")
	{
		if(f.enable_rekey[0].checked==true)
		{
			get_obj("psk_setting1").style.display = "";
			f.time_interuol.disabled=true;
			f.sweek.disabled=true;
			f.shour.disabled=true;
			f.smin.disabled=true;
			f.rekey.disabled=true;
		}
		else
		{
			get_obj("key_change_psk_setting").style.display = "";
			f.time_interuol.disabled=false;
			f.sweek.disabled=false;
			f.shour.disabled=false;
			f.smin.disabled=false;
			f.rekey.disabled=true;
		}	
	}
	AdjustHeight();
}
function on_change_band(s)
{
	var f = get_obj("frm");
	self.location.href = "bsc_wlan.php?band_reload=" + s.value;
}

function on_change_application(s)
{
	var f = get_obj("frm");	
	self.location.href = "bsc_wlan.php?rapplication=" + s.value +"&band_reload="+f.band.value;
}
function on_change_mode(s)
{
	var f = get_obj("frm");
	var f_final	=get_obj("final_form");
	var another_band_mode = "<?=$another_band_mode?>";		
	if("<?=$switch?>" != 1)
	{
		if(s.value == 1)
		{
			 if(another_band_mode != 0 )
			 {
			 			alert("<?=$a_otherband_to_apmode?>");
			 			s.value = "<?=$cfg_mode?>";
			 			//return false;
			 }
		}		
	if(another_band_mode == 1)
		{
			 if(s.value != 0 )
			 {
			 			alert("<?=$a_otherband_to_apmode?>");
			 			s.value = "<?=$cfg_mode?>";
			 			//return false;
			 }
		}
	}	
	if("<?=$cfg_aparray?>"==1)
	{
		if(s.value == 1 || another_band_mode == 1 || s.value == 4 || another_band_mode == 4 || s.value == 3 || another_band_mode == 3)
		{alert("<?=$a_disable_ap_array?>");}
	}
	if("<?=$cfg_url?>"==1)
	{
		if(s.value == 1 || another_band_mode == 1 || s.value == 4 || another_band_mode == 4)
		{alert("<?=$a_disable_url?>");}
	}
	if("<?=$cfg_vlan?>"==1)
    {
        if(s.value == 1 || another_band_mode == 1)
        {alert("<?=$a_disable_vlan?>");}
    }
	if("<?=$cfg_arpspoofing?>"==1)
	{
		if(s.value == 1)
		{alert("<?=$a_disable_arpspoofing?>");}
	}
	get_obj("wds_mac_fieldset").style.display = "none";
	get_obj("site_survey_fieldset").style.display = "none";
	get_obj("auth").style.display = "none";
	get_obj("auth_11n_only").style.display = "none";
	get_obj("auth_wds").style.display = "none";	
	get_obj("auth_apc_apr").style.display = "none";	
	get_obj("auth_apc").style.display = "none";	
	get_obj("auth_mssid").style.display = "none";
	get_obj("auth_wds_mssid").style.display = "none";		
	get_obj("cipher").style.display = "none";
	get_obj("cipher_11n_only").style.display = "none";
	get_obj("cipher_wds").style.display = "none";	
	get_obj("wpa_mode").style.display = "none";
	get_obj("wpa_mode_wds").style.display = "none";	
	get_obj("ch_a").style.display = "none";
	get_obj("ch_a_wds").style.display = "none";
	get_obj("ch_g").style.display = "none";	
	get_obj("apc_scan").style.display = "none";	
	get_obj("wds_scan").style.display = "none";	
	get_obj("mac_clone").style.display = "none";	

	if(f.band.value == 1)
	{
		if(mode_value.value == 3 || mode_value.value == 4)
		{
			get_obj("ch_a_wds").style.display = "";
			w_chan = get_obj("ch_a_wds");
		}
		else
		{
			get_obj("ch_a").style.display = "";
			w_chan = get_obj("ch_a");			
		}
		select_index(w_chan, "<?=$cfg_channel?>");	
	}
	else
	{
		get_obj("ch_g").style.display = "";
		w_chan = get_obj("ch_g");
		select_index(w_chan, "<?=$cfg_channel?>");
			
	}	
	w_chan.disabled = false;
	f.ap_hidden.disabled = false;
	f.auto_ch_scan.disabled = false;
	
	if(f.captival_profile != null)
    {
		select_index(f.captival_profile, "<?=$cfg_captival_profile?>");
		f.captival_profile.disabled = false;
		if("<?=$cfg_captival_profile?>" != 1 && "<?=$cfg_captival_profile?>" != 2 && "<?=$cfg_captival_profile?>" != 3 && "<?=$cfg_captival_profile?>" != 4 && "<?=$cfg_captival_profile?>" != 7)
			select_index(f.captival_profile, 0);
		if("<?=$cfg_ticket?>" != 1 && "<?=$cfg_local?>" != 1 && "<?=$cfg_radius?>" != 1 && "<?=$cfg_ldap?>" != 1 && "<?=$cfg_pop3?>" != 1)
			f.captival_profile.disabled = true;
    }
	if(s.value != 0) //wds with ap, wds, APR, APC
	{
		get_obj("site_survey_fieldset").style.display = "";
		
		if(s.value == 3 || s.value == 4)//wds with ap, wds
		{
			get_obj("wds_mac_fieldset").style.display = "";
			get_obj("wds_scan").style.display = "";	
	
			if(s.value == 3 && "<?=$cfg_mssid_status?>" == 1 && "<?=$runtime_mssid_support_sharedkey?>" == 0)//wds with ap, mssid enable
			{
				get_obj("auth_wds_mssid").style.display = "";
				w_auth = get_obj("auth_wds_mssid");
			}		
			else
			{
				get_obj("auth_wds").style.display = "";
				w_auth = get_obj("auth_wds");				
			}	
			if(s.value == 3)
            {
                if("<?=$cfg_m1_cha?>" == 1 || "<?=$cfg_m2_cha?>" == 1 || "<?=$cfg_m3_cha?>" == 1 || "<?=$cfg_m4_cha?>" == 1 || "<?=$cfg_m5_cha?>" == 1 || "<?=$cfg_m6_cha?>" == 1 || "<?=$cfg_m7_cha?>" == 1)
                alert("<?=$a_auth_to_open?>");
            }
			get_obj("cipher_wds").style.display = "";			
			w_cipher = get_obj("cipher_wds");
			
			get_obj("wpa_mode_wds").style.display = "";	
			w_wpa_mode = get_obj("wpa_mode_wds");
					
			if(s.value == 4) //wds
			{
				w_cipher.disabled = true;
				select_index(w_cipher, "3");
				w_wpa_mode.disabled = true;
				select_index(w_wpa_mode, "1");
				if(f.captival_profile != null)
				{
					f.captival_profile.value = 0;
					f.captival_profile.disabled = true;
				}
			}	
			else //wds with ap
			{
				w_cipher.disabled = false;	
				w_wpa_mode.disabled = false;				
			}
		}
		else // APC, APR
		{
			get_obj("apc_scan").style.display = "";	
			if(s.value == 1)
				get_obj("mac_clone").style.display = "";
			w_chan.disabled = true;
			f.ap_hidden.disabled = true;
			select_index(f.ap_hidden, "0");						
			get_obj("auth_apc_apr").style.display = "";
			w_auth = get_obj("auth_apc_apr");
			if("<? echo query("/wlan/inf:".$cfg_index."/wlmode");?>"=="3")
			{
				get_obj("cipher_11n_only").style.display = "";			
				w_cipher = get_obj("cipher_11n_only");		
			}
			else
			{
				get_obj("cipher").style.display = "";			
				w_cipher = get_obj("cipher");		
			}
			get_obj("wpa_mode").style.display = "";			
			w_wpa_mode = get_obj("wpa_mode");								
			if(f.captival_profile != null)
			{
				f.captival_profile.value = 0;
				f.captival_profile.disabled = true;
			}
		}		
		
		f.auto_ch_scan.disabled = true;
		if(s.value == 1)
		{
			select_index(f.auto_ch_scan, "1");
		}
		else
		{
		select_index(f.auto_ch_scan, "0");
	}
	}
	else // Access
	{
		if("<?=$cfg_mssid_status?>" == 1 && "<?=$runtime_mssid_support_sharedkey?>" == 0)
		{
			if("<? echo query("/wlan/inf:".$cfg_index."/wlmode");?>"=="3")
			{
				get_obj("auth_11n_only").style.display = "";
				w_auth = get_obj("auth_11n_only");
			}
			else
			{
				get_obj("auth_mssid").style.display = "";
				w_auth = get_obj("auth_mssid");
			}
		}
		else
		{
			if("<? echo query("/wlan/inf:".$cfg_index."/wlmode");?>"=="3")
			{
				get_obj("auth_11n_only").style.display = "";
				w_auth = get_obj("auth_11n_only");			
			}
			else
			{
				get_obj("auth").style.display = "";
				w_auth = get_obj("auth");			
			}
		}
		if("<? echo query("/wlan/inf:".$cfg_index."/wlmode");?>"=="3")
		{
			get_obj("cipher_11n_only").style.display = "";			
			w_cipher = get_obj("cipher_11n_only");	
		}
		else
		{
			get_obj("cipher").style.display = "";			
			w_cipher = get_obj("cipher");	
		}
		get_obj("wpa_mode").style.display = "";			
		w_wpa_mode = get_obj("wpa_mode");			
	}
	
	if("<?=$cfg_mssid_status?>" == 1 && (s.value == 1 || s.value == 2 ||s.value == 4)&& check_mssid_msg == 0)
	{
		 alert("<?=$a_invalid_mssid_status?>");	
		 check_mssid_msg =1;
	}	
	
	select_index(w_auth, "<?=$security_type?>");
	on_change_authentication(w_auth);
	on_change_auto_channel(f.auto_ch_scan);	
	AdjustHeight();
}

function on_change_auto_channel(s)
{
	var f = get_obj("frm");
	var f_final	=get_obj("final_form");
	w_chan.disabled = false;
	
	if(s.value == 1 || mode_value.value == 1 || mode_value.value == 2)
		w_chan.disabled = true;			
	if(mode_value.value == 0 && "<?=$cfg_auto_rf?>" == 1 && "<?=$cfg_aparray?>" == 1 && "<?=$cfg_auto_ch_scan?>" == 1 && f.auto_ch_scan.value == 0)
	{
		alert("<?=$a_auto_turn_to_enable?>");	
		f.auto_ch_scan.value = 1;
	}
	on_change_channel();	
}

function on_change_authentication(s)
{
	var f = get_obj("frm");
	var f_final	=get_obj("final_form");
	var eap_psk_table_title_obj = get_obj("eap_psk_table_title");
	
	get_obj("wep_key_setting_fieldset").style.display = "none";
	get_obj("eap_psk_fieldset").style.display = "none";
	get_obj("psk_setting").style.display = "none";
	get_obj("eap_setting").style.display = "none";	
	get_obj("apc_eap_setting").style.display = "none";	
	get_obj("key_change_psk_setting").style.display = "none";
	get_obj("periodrical_key_change_setting").style.display = "none";
	get_obj("wpa_setting").style.display = "none";
	get_obj("dot_1x_setting").style.display = "none";
	get_obj("nap_setting").style.display = "none";
	f.encrypt[0].disabled = false;
	
	if("<?=$security_type?>" == 2 && "<?=$cfg_nap_enable?>" == 1)	
	{
		if("<?=$cfg_vlan?>" == 1 && s.value != 2 && check_vlan_msg == 0)
		{
			 alert("<?=$a_invalid_vlan_status?>");	
			 check_vlan_msg =1;
		}
	}	
	
	if(s.value == 0 || s.value == 1 || s.value == 8) // open, shared, both
	{
		get_obj("wep_key_setting_fieldset").style.display = "";
		if(s.value != 0)
		{
			f.encrypt[0].disabled = true;
			f.encrypt[1].checked = true;
		}	
		if("<? echo query("/wlan/inf:".$cfg_index."/wlmode");?>"=="3")
        {
            f.encrypt[1].disabled = true;
			f.encrypt[0].checked = true;
        }
		on_click_encryption();
	}
    else if(s.value == 9)// 802.1x  
	{
		get_obj("eap_psk_fieldset").style.display = "";			
		get_obj("dot_1x_setting").style.display = "";
		get_obj("eap_setting").style.display = "";	
		eap_psk_table_title_obj.innerHTML = "<?=$m_radius_field_title?>";
		if(f.acc_mode != null && f.radius_type == null)
		{
			on_change_account_mode(f.acc_mode);
		}

		if("<? echo query("/runtime/web/display/local_radius_server");?>"=="1")
		{
			f.radius_type[0].disabled = f.radius_type[1].disabled = false;
			on_change_radius_type();
		}
		w_chw.disabled = true;
		w_chw.value = 0;
	}
	else
	{
		get_obj("eap_psk_fieldset").style.display = "";	
		get_obj("wpa_setting").style.display = "";	
		if(s.value == 2) //eap
		{
			
			if(f.acc_mode != null && f.radius_type == null)
			{
				on_change_account_mode(f.acc_mode);
			}
                        if(mode_value.value==1 || mode_value.value == 2)
			{
				get_obj("apc_eap_setting").style.display = "";
				on_change_method();
				eap_psk_table_title_obj.innerHTML = "<?=$m_eap_field_title?>";	
			}
			else
			{				
				get_obj("eap_setting").style.display = "";
				get_obj("nap_setting").style.display = "";	
			if("<? echo query("/runtime/web/display/local_radius_server");?>"=="1")
			{
				on_click_nap_enable();
			}
			eap_psk_table_title_obj.innerHTML = "<?=$m_radius_field_title?>";	
		}	
		}	
		else //psk
		{
			if(mode_value.value !=1 && mode_value.value!=4 && mode_value.value!=3 && mode_value.value != 2)
			{
				get_obj("periodrical_key_change_setting").style.display = "";	
			}
			else
			{
				f.enable_rekey[0].checked=true;	
			}
			get_obj("psk_setting").style.display = "";	
			eap_psk_table_title_obj.innerHTML = "<?=$m_passphrase_field_title?>";		
			f.gkui.disabled = false;
			if(mode_value.value == 1 || mode_value.value == 2)
				f.gkui.disabled = true;
		}
		on_change_cipher();
	}
	on_change_size();
	if(mode_value.value ==1 || mode_value.value == 2)
		f.gkui.disabled=true;
	else
		f.gkui.disabled=false;
	on_click_enable_rekey();
	on_change_channel();
	AdjustHeight();
}

function disable_w_chw()
{
	var f = get_obj("frm");
	if(w_auth.value == 9 || ((w_auth.value == 3 || w_auth.value == 2) && w_cipher.value == 2) || (w_auth.value == 0 && f.encrypt[1].checked == true) || w_auth.value == 1 || "<?=$cfg_wlmode?>" == 2)
//802.1x, TKIP, open+encryption enabled, shared key, wlmode
	{
		w_chw.value=0;
		w_chw.disabled = true;
	}
	if(w_chan.value == "165" || w_chan.value == "140" || w_chan.value == "12" || w_chan.value == "13" || w_chan.value == "14")
	{
		w_chw.value = 0;
		w_chw.disabled = true;
	}
	if(mode_value.value ==1 || mode_value.value == 2)
	{
		w_chw.disabled=true;
		w_chw.value = 1;
		if("<?=$cfg_wlmode?>" == 4 && f.band.value == 1)
			w_chw.value=2;
	}
}

function on_change_def_key(s)
{
	var f = get_obj("frm");
	if(f.defkey.value == 1)
	{
		select_index(f.keytype, "<?=$cfg_wep1_keytype?>");
		select_index(f.keysize, "<?=$cfg_wep1_length?>");
		f.key.value = "<?=$cfg_wep1?>";
		f.confirm_key.value = "<?=$cfg_wep1?>";
	}
	else if(f.defkey.value == 2)
	{
		select_index(f.keytype, "<?=$cfg_wep2_keytype?>");
		select_index(f.keysize, "<?=$cfg_wep2_length?>");
		f.key.value = "<?=$cfg_wep2?>";
		f.confirm_key.value = "<?=$cfg_wep2?>";
	}
	else if(f.defkey.value == 3)
	{
		select_index(f.keytype, "<?=$cfg_wep3_keytype?>");
		select_index(f.keysize, "<?=$cfg_wep3_length?>");
		f.key.value = "<?=$cfg_wep3?>";
		f.confirm_key.value = "<?=$cfg_wep3?>";
	}
	else
	{
		select_index(f.keytype, "<?=$cfg_wep4_keytype?>");
		select_index(f.keysize, "<?=$cfg_wep4_length?>");
		f.key.value = "<?=$cfg_wep4?>";
		f.confirm_key.value = "<?=$cfg_wep4?>";
	}
}

function on_change_radius_type()   
{
	var f = get_obj("frm");
	if(f.radius_srv_1 != null)
	f.radius_srv_1.disabled = true;
	if(f.radius_port_1 != null)
	f.radius_port_1.disabled = true;
	if(f.radius_sec_1 != null)
	f.radius_sec_1.disabled = true;
	if(f.radius_srv_2 != null)
	f.radius_srv_2.disabled = true;
	if(f.radius_port_2 != null)
	f.radius_port_2.disabled = true;
	if(f.radius_sec_2 != null)
	f.radius_sec_2.disabled = true;
	if(f.acc_mode != null)
	f.acc_mode.disabled = true;
	if(f.acc_srv_1 != null)
	f.acc_srv_1.disabled = true;
	if(f.acc_port_1 != null)
	f.acc_port_1.disabled = true;
	if(f.acc_sec_1 != null)
	f.acc_sec_1.disabled = true;
	if(f.acc_srv_2 != null)
	f.acc_srv_2.disabled = true;
	if(f.acc_port_2 != null)
	f.acc_port_2.disabled = true;
	if(f.acc_sec_2 != null)
	f.acc_sec_2.disabled = true;		
	if(f.radius_type != null)
	if(f.radius_type[0].checked == true)
	{
		if(f.radius_srv_1 != null)
		f.radius_srv_1.disabled = false;
		if(f.radius_port_1 != null)
		f.radius_port_1.disabled = false;
		if(f.radius_sec_1 != null)
		f.radius_sec_1.disabled = false;
		if(f.radius_srv_2 != null)
		f.radius_srv_2.disabled = false;
		if(f.radius_port_2 != null)
		f.radius_port_2.disabled = false;
		if(f.radius_sec_2 != null)
		f.radius_sec_2.disabled = false;
		if(f.acc_mode != null)
		f.acc_mode.disabled = false;
		on_change_account_mode(f.acc_mode);		
	}
}
function on_change_account_mode(s)
{
	var f = get_obj("frm");
		if(f.acc_srv_1 != null)
		f.acc_srv_1.disabled = false;
		if(f.acc_port_1 != null)
		f.acc_port_1.disabled = false;
		if(f.acc_sec_1 != null)
		f.acc_sec_1.disabled = false;
		if(f.acc_srv_2 != null)
		f.acc_srv_2.disabled = false;
		if(f.acc_port_2 != null)
		f.acc_port_2.disabled = false;
		if(f.acc_sec_2 != null)
		f.acc_sec_2.disabled = false;
	
	if(s.value == 0)
	{
		if(f.acc_srv_1 != null)
		f.acc_srv_1.disabled = true;
		if(f.acc_port_1 != null)
		f.acc_port_1.disabled = true;
		if(f.acc_sec_1 != null)
		f.acc_sec_1.disabled = true;
		if(f.acc_srv_2 != null)
		f.acc_srv_2.disabled = true;
		if(f.acc_port_2 != null)
		f.acc_port_2.disabled = true;
		if(f.acc_sec_2 != null)
		f.acc_sec_2.disabled = true;
	}
}	
	
function on_change_scan_table_height()
{
	var x = get_obj("scan_tab").offsetHeight;
	
	if(get_obj("adjust_td") != null)
	{
		if(x <= 120)
			get_obj("adjust_td").width="140";
		else
			get_obj("adjust_td").width="120";
	}
}	

function on_click_encryption()
{
	var f = get_obj("frm");
	var f_final = get_obj("final_form");
	f.keytype.disabled = false;
	f.keysize.disabled = false;
	f.defkey.disabled = false;
	f.key.disabled = false;	
	f.confirm_key.disabled = false;	
	if(w_auth.value == 0 || w_auth.value == 1) // open or shared key
	{
		if(f.encrypt[0].checked)
		{
			f.keytype.disabled = true;
			f.keysize.disabled = true;
			f.defkey.disabled = true;
			f.key.disabled = true;
			f.confirm_key.disabled = true;
			if("<?=$cfg_wlmode?>" != 2)
				w_chw.disabled=false;	
		}
		else
		{
			w_chw.value=0;
			w_chw.disabled=true;	
		}	
	}	
	disable_w_chw();
}


var apc_list=[['index','ssid','ch','mac','rssi','auth']
<?
$apc_num=0;
if($cfg_mode == 1 || $cfg_mode == 2)
{
		for("/runtime/wlan/inf:".$cfg_index."/apscanlistinfo/apscan")
		{
						
			if(query("chan:") != "-2")
			{
				if($apc_num < 30)
					{
						if(query("/runtime/web/display/stinfo_rssi") != "1") 
						{
							echo ",\n['".$@."','".get("j","ssid")."','".query("chan")."','".query("mac")."','".query("rssi")."','".query("auth")."']";
						}
						else
						{	
							echo ",\n['".$@."','".get("j","ssid")."','".query("chan")."','".query("mac")."','".query("signalstrength")."','".query("auth")."']";
						}
						
						$apc_num++;
				}
			}
		}
}
?>];
function on_click_scan_table(id)
{
	var f = get_obj("frm");
	var wds_mac_id = 0;
	
	if(mode_value.value == 1 || mode_value.value == 2)
	{
		f.ssid.value = apc_list[id][1];
		
		if(f.band.value == 0)
		{
			f.ch_g.value = get_obj("scan_ch"+id).value;
		}
		else
		{
			f.ch_a.value = get_obj("scan_ch"+id).value;	
		}
	}	
	else
	{
		for(var i=0; i<"<?=$cfg_wds_mac_num?>" ; i++)
		{
			if(get_obj("scan_mac"+id).value == get_obj("wds_mac"+(i+1)).value.toUpperCase())
			{
				alert("<?=$a_same_wds_mac?>");
				return false;
			}
			else if(get_obj("wds_mac"+(i+1)).value == "" && wds_mac_id == 0)
			{
				get_obj("wds_mac"+(i+1)).value = get_obj("scan_mac"+id).value;
				wds_mac_id++;
			}
		}			
	}
}

function on_change_channel()
{
	var f = get_obj("frm");
	var f_final	=get_obj("final_form");
	if(w_chan.value == "165" || w_chan.value == "140" || w_chan.value == "12" || w_chan.value == "13" || w_chan.value == "14")
	{
		w_chw.value = 0;
		w_chw.disabled = true;
	}
	else
	{
		w_chw.disabled = false;
	}
	disable_w_chw();
}

function on_change_size()
{
	var f = get_obj("frm");
	if(f.keysize.value ==128 && f.keytype.value == 1)
	{
		get_obj("key").setAttribute('maxLength','13');	
		get_obj("confirm_key").setAttribute('maxLength','13');
	}
	else if(f.keysize.value ==128 && f.keytype.value == 2)
	{
		get_obj("key").setAttribute('maxLength','26');	
		get_obj("confirm_key").setAttribute('maxLength','26');
	}
	else if(f.keysize.value ==64 && f.keytype.value == 1)
	{
		get_obj("key").setAttribute('maxLength','5');	
		get_obj("confirm_key").setAttribute('maxLength','5');
	}	
	else if(f.keysize.value ==64 && f.keytype.value == 2)
	{
		get_obj("key").setAttribute('maxLength','10');	
		get_obj("confirm_key").setAttribute('maxLength','10');
	}		
}
/* page init functoin */
function init()
{
	var f = get_obj("frm");
	var f_final	=get_obj("final_form");
	get_obj("channel_width").style.display = "none";
	get_obj("channel_width_ac").style.display = "none";
	get_obj("mode").style.display = "none";
	get_obj("mode_eg").style.display = "none";
	get_obj("mode_apr").style.display = "none";
		if("<?=$cfg_outdoor?>"!="1")
	{
		if(("<?=$cfg_band?>"== 1 && "<?=$cfg_wlan_domain?>" == 818)||("<?=$cfg_band?>" != 0 && "<?=$application?>" != 0))
				{
			get_obj("mode").style.display = "none";
	    get_obj("mode_eg").style.display = "";
			mode_value = get_obj("mode_eg");
		}
		else
		{
			if("<?=$cfg_display_repeater?>" == 1)
			{
				get_obj("mode_apr").style.display = "";
				mode_value = get_obj("mode_apr");
			}
			else
			{
				get_obj("mode").style.display = "";
	                get_obj("mode_eg").style.display = "none";
	                mode_value = get_obj("mode");
			}
		}
	}
else
{
	if("<?=$cfg_outdoor_eu?>" == 1)
	{
		get_obj("mode").style.display = "";
		get_obj("mode_eg").style.display = "none";
		mode_value = get_obj("mode");
	}
	else
	{
	if(("<?=$cfg_band?>"== 1 && "<?=$cfg_wlan_domain?>" == 826)||("<?=$cfg_band?>"== 1 && "<?=$cfg_wlan_domain?>" == 818 && "<?=$if_outdoor?>" != 1)||("<?=$cfg_band?>" != 0 && "<?=$cfg_wlan_domain?>" == 392))
	{
		get_obj("mode").style.display = "none";
    get_obj("mode_eg").style.display = "";
		mode_value = get_obj("mode_eg");
	}
	else
	{
		if("<?=$cfg_display_repeater?>" == 1)
		{
			get_obj("mode_apr").style.display = "";
			mode_value = get_obj("mode_apr");
		}
		else
		{
			get_obj("mode").style.display = "";
                get_obj("mode_eg").style.display = "none";
                mode_value = get_obj("mode");
		}
	}
	}
}
	if("<?=$cfg_band?>"== 1 && "<?=$cfg_wlmode?>" == 4) // 11g
	{
		get_obj("channel_width_ac").style.display = "";
		get_obj("channel_width").style.display = "none";
		w_chw=get_obj("channel_width_ac");
	}
	else
	{
		get_obj("channel_width").style.display = "";
		get_obj("channel_width_ac").style.display = "none";
		w_chw=get_obj("channel_width");
	}
	w_chw.value = "<?=$cfg_channel_width?>";
	w_chw.disabled = false;
	on_change_scan_table_height();
	select_index(f.band, "<?=$cfg_band?>");
	if("<?=$check_band?>" == "" && "<?=$switch?>" != 1)
		f.band.disabled = true;
	if(f.application != null)
	select_index(f.application, "<?=$application?>");
	select_index(mode_value, "<?=$cfg_mode?>");
	f.ssid.value = "<?=$cfg_ssid?>";
	select_index(f.ap_hidden, "<?=$cfg_ssid_hidden?>");
	select_index(f.auto_ch_scan, "<?=$cfg_auto_ch_scan?>");	
	for(var i=0; i<"<?=$cfg_wds_mac_num?>" ; i++)
	{
		if(get_obj("h_wds_mac"+(i+1)) != null)
			get_obj("wds_mac"+(i+1)).value = get_obj("h_wds_mac"+(i+1)).value;
	}
	
	if("<?=$cfg_cipher?>" == 0)
		f.encrypt[0].checked = true;
	else 
		f.encrypt[1].checked = true;	
			
	select_index(f.defkey, "<?=$cfg_wep_defkey?>");
	
	if("<?=$cfg_wep_defkey?>" == "1")
	{
		select_index(f.keytype, "<?=$cfg_wep1_keytype?>");
		select_index(f.keysize, "<?=$cfg_wep1_length?>");		
		f.key.value = "<?=$cfg_wep1?>";
		f.confirm_key.value = "<?=$cfg_wep1?>";
	}
	else if("<?=$cfg_wep_defkey?>" == "2")
	{
		select_index(f.keytype, "<?=$cfg_wep2_keytype?>");
		select_index(f.keysize, "<?=$cfg_wep2_length?>");			
		f.key.value = "<?=$cfg_wep2?>";
		f.confirm_key.value = "<?=$cfg_wep2?>";
	}
	else if("<?=$cfg_wep_defkey?>" == "3")
	{
		select_index(f.keytype, "<?=$cfg_wep3_keytype?>");
		select_index(f.keysize, "<?=$cfg_wep3_length?>");			
		f.key.value = "<?=$cfg_wep3?>";
		f.confirm_key.value = "<?=$cfg_wep3?>";
	}
	else
	{
		select_index(f.keytype, "<?=$cfg_wep4_keytype?>");
		select_index(f.keysize, "<?=$cfg_wep4_length?>");			
		f.key.value = "<?=$cfg_wep4?>";
		f.confirm_key.value = "<?=$cfg_wep4?>";
	}
	if("<?=$cfg_enable_rekey?>"!="1")
	{
		f.enable_rekey[0].checked=true;
	}
	else
	{
		f.enable_rekey[1].checked=true;
	}
	f.time_interuol.value="<?=$cfg_time_interuol?>";
        f.rekey.value ="<?=$cfg_rekey?>";
	init_time();
     
        //victor add for eap apc setting
	if("<?=$cfg_apc_eap_method?>"!="")
	{
		select_index(f.apc_eap_method, "<?=$cfg_apc_eap_method?>");
	}
	else
	{
		f.apc_eap_method.value=2;
	}
	f.apc_eap_method.disabled=true;
	select_index(f.apc_inner_auth, "<?=$cfg_apc_inner_auth?>");
	f.apc_eap_username.value="<?=$cfg_apc_eap_username?>";
	f.apc_eap_password.value="<?=$cfg_apc_eap_password?>";	
	
	f.gkui.value = "<?=$cfg_key_interval?>";	
	if(f.gkui.value == "")
	{f.gkui.value = 3600;}		
	f.kui.value = "<?=$cfg_1x_key_interval?>";	
	if(f.gkui.value == "")
	{f.kui.value = 1800;}	

	f.passphrase.value = "<?=$cfg_wpapsk?>";
	f.confirm_passphrase.value = "<?=$cfg_wpapsk?>";
	f.radius_srv_1.value = "<?=$cfg_radius_srv_1?>";
	f.radius_port_1.value = "<?=$cfg_radius_port_1?>";
	f.radius_sec_1.value = "<?=$cfg_radius_sec_1?>";
	if(f.radius_srv_2 != null)
		f.radius_srv_2.value = "<?=$cfg_radius_srv_2?>";
	if(f.radius_port_2 != null)
		f.radius_port_2.value = "<?=$cfg_radius_port_2?>";
	if(f.radius_sec_2 != null)		
	{
		if("<?=$cfg_radius_srv_2?>" == "")
		{
			f.radius_sec_2.value = "";
		}
		else
		{
			f.radius_sec_2.value = "<?=$cfg_radius_sec_2?>";
		}
	}
	if(f.nap_enable != null)
	{
		if("<?=$cfg_nap_enable?>" == 0)
			f.nap_enable[0].checked = true;
		else
			f.nap_enable[1].checked = true;	
	}
	if(f.radius_type != null)
	{
		if("<?=$cfg_radius_type?>" == 0)
			f.radius_type[0].checked = true;
		else
			f.radius_type[1].checked = true;	
	}

	if(f.acc_mode != null)
		select_index(f.acc_mode, "<?=$cfg_acc_mode?>");
	if(f.acc_srv_1 != null)
		f.acc_srv_1.value = "<?=$cfg_acc_srv_1?>";
	if(f.acc_port_1 != null)
		f.acc_port_1.value = "<?=$cfg_acc_port_1?>";
	if(f.acc_sec_1 != null)
		f.acc_sec_1.value = "<?=$cfg_acc_sec_1?>";
	if(f.acc_srv_2 != null)
		f.acc_srv_2.value = "<?=$cfg_acc_srv_2?>";
	if(f.acc_port_2 != null)
		f.acc_port_2.value = "<?=$cfg_acc_port_2?>";
	if(f.acc_sec_2 != null)
	{
		if("<?=$cfg_acc_srv_2?>" == "")
		{
			f.acc_sec_2.value = "";
		}
		else
		{
			f.acc_sec_2.value = "<?=$cfg_acc_sec_2?>";	
		}
	}
	
	if("<?=$cfg_clone_mac_enable?>"==1)
	{
		f.clone_mac_enable.checked=true;
	}
	else
	{
		f.clone_mac_enable.checked=false;
	}
	select_index(f.mac_source, "<?=$cfg_mac_source?>");	
	if("<?=$maclone_addr?>"!="")
	{
		put_mac("<?=$maclone_addr?>");
	}
	on_change_mode(mode_value);
	if("<?=$cfg_auth?>" == "2"  || "<?=$cfg_auth?>" == "3" )
		w_wpa_mode.value = 2;
	else if("<?=$cfg_auth?>" == "4" || "<?=$cfg_auth?>" == "5")
		w_wpa_mode.value = 1;
	else if("<?=$cfg_auth?>" == "6" || "<?=$cfg_auth?>" == "7")
		w_wpa_mode.value = 0;
	select_index(w_cipher, "<?=$cfg_cipher?>");
//	w_cipher.value = "<?=$cfg_cipher?>";
	on_change_auto_channel(f.auto_ch_scan);
	on_change_authentication(w_auth);
	if("<?=$cfg_wlan_domain?>" == 392 && f.band.value==0)
	{
		f.application.disabled=true;	
	}
	else if("<?=$cfg_wlan_domain?>" == 392 && f.band.value==1 && "<?=$cfg_outdoor?>" == 1)
	{
		f.application.value=1;	
		f.application.disabled=true;	
	}
	if("<?=$cfg_wl_enable?>"==1 && w_auth.value==3 && f.enable_rekey[1].checked && "<?=$flag_msg_autorekey?>"=="display")
	{
		alert("<?=$a_check_mail_for_rekey?>");
	}
	on_click_clone_mac_enable();
	disable_w_chw();
	if("<?=$cfg_cwm_flag?>" == 1 && f.band.value == 1)
		w_chw.value = 1;
	AdjustHeight();
}

/* parameter checking */

var ms_key_index_list=[['index','key_index']
<?
for("multi/index")
{
	echo ",\n['".$@."','".query("wep_key_index")."']";
}
?>];

var ms_enable_list=[['index','state']
<?
for("/wlan/inf:".$cfg_index."/multi/index")
{
	echo ",\n['".$@."','".query("state")."']";
}
?>];

function on_change_cipher()
{
	var f = get_obj("frm");
	var f_final = get_obj("final_form");
	if(w_cipher.value == 2)
	{
		w_chw.value = 0;
		w_chw.disabled = true;
	}
	else
		w_chw.disabled = false;
	disable_w_chw();
}

function is_checkoutHex()
{
	var f = get_obj("frm");
	for(var k=0; k < f.passphrase.value.length; k++)
			{
				
				if((f.passphrase.value.charAt(k) >= '0' && f.passphrase.value.charAt(k) <= '9') ||
					(f.passphrase.value.charAt(k) >= 'a' && f.passphrase.value.charAt(k) <= 'f') ||
					(f.passphrase.value.charAt(k) >= 'A' && f.passphrase.value.charAt(k) <= 'F'))
					continue;
				else
						return false;
			}
			return true;	
}
function check()
{
	var f = get_obj("frm");
	var f_final	=get_obj("final_form");
	var WDS_MAC="";
		
	f_final.f_chan.value = w_chan.value;
	f_final.f_auth.value = w_auth.value;
	f_final.f_cipher.value = w_cipher.value;
	f_final.f_wpa_mode.value = w_wpa_mode.value;
	
	if(f.nap_enable != null && f_final.f_auth.value == 2)
	{
		if(f.nap_enable[0].checked == true)
		{
			f_final.f_nap_enable.value  = 0;
		}
		else
		{
			f_final.f_nap_enable.value  = 1;
		}
	}
	if("<?=$check_band?>" != "")
	{
	if("<?=$cfg_band?>" == 0 && "<?=$cfg_vlan?>" == 1)
	{
		if("<?=$security_eap_a?>" == 1 && "<?=$cfg_nap_enable?>" == 1 && f_final.f_auth.value != 2 && f_final.f_auth.value != 4 && f_final.f_auth.value != 6)
		{
			alert("<?=$a_nap_dynamic?>");	
			return false;
		}
		if("<?=$security_eap_a?>" != 1 && f_final.f_nap_enable.value == 1 && (f_final.f_auth.value == 2 || f_final.f_auth.value == 4 || f_final.f_auth.value == 6))
		{
			alert("<?=$a_nap_dynamic?>");
			return false;
		}
	}
	if("<?=$cfg_band?>" == 1 && "<?=$cfg_vlan?>" == 1)
	{
		if("<?=$security_eap?>" == 1 && "<?=$cfg_nap_enable?>" == 1 && f_final.f_auth.value != 2 && f_final.f_auth.value != 4 && f_final.f_auth.value != 6)
		{
			alert("<?=$a_nap_dynamic?>");
			return false;
		}
		if("<?=$security_eap?>" != 1 && f_final.f_nap_enable.value == 1 && (f_final.f_auth.value == 2 || f_final.f_auth.value == 4 || f_final.f_auth.value == 6))
		{
			alert("<?=$a_nap_dynamic?>");
			return false;
		}
	}
	}
	
	if(f_final.f_scan_value.value != 1)
	{
		if(is_blank(f.ssid.value))
		{
			alert("<?=$a_empty_ssid?>");
			f.ssid.focus();
			return false;
		}		
			
		if(first_blank(f.ssid.value))
		{
			alert("<?=$a_first_blank_ssid?>");
			f.ssid.select();
			return false;
		}
		if(is_blank_in_first_end(f.ssid.value))
		{
			alert("<?=$a_first_end_blank?>");
			f.ssid.select();
			return false;
		}
		if(strchk_unicode(f.ssid.value))
		{
			alert("<?=$a_invalid_ssid?>");
			f.ssid.select();
			return false;
		}
	}
	
	f_final.f_band.value = f.band.value;
	if(f.application != null)
	f_final.f_application.value = f.application.value;	
	
	if (f_final.f_application.value != 1 )
	 			f_final.f_application.value = 0;
	f_final.f_mode.value = mode_value.value;
	f_final.f_ssid.value = f.ssid.value;
	f_final.f_ap_hidden.value = f.ap_hidden.value;
	f_final.f_auto_ch_scan.value = f.auto_ch_scan.value;
	f_final.f_channel_width.value = w_chw.value;
	if(f.captival_profile != null)
		f_final.f_captival_profile.value = f.captival_profile.value;
	
	var wds_mac_flag=0; 
		
	for(var i=1; i<="<?=$cfg_wds_mac_num?>" ;i++)
	{   
		if(mode_value.value == "3" || mode_value.value == "4") //wds with ap ,wds
		{
			if(get_obj("wds_mac"+i).value == "")
			{
				wds_mac_flag++;
				if(wds_mac_flag == <?=$cfg_wds_mac_num?>)
				{
					if(f_final.f_scan_value.value != 1)
					{
						alert("<?=$a_blank_wds_mac?>");
						f.wds_mac1.focus();
						return false;	
					}					
				}
			}
			
	    if(get_obj("wds_mac"+i).value != "")
		{
			if(f_final.f_scan_value.value != 1)
			{
				if(!macCheck(get_obj("wds_mac"+i).value))
				{
					if(f_final.f_scan_value.value != 1)
					{
						alert("<?=$a_invalid_wds_mac?>");
				    	get_obj("wds_mac"+i).value = "";
				   		get_obj("wds_mac"+i).focus();
			 	    	return false;
			 	    }
				}
			}
			var num_mac1=parseInt("0x"+get_obj("wds_mac"+i).value.substring(0,2));
            var str_mac1=num_mac1.toString(2);
            if(str_mac1.charAt(str_mac1.length-1) == "1" || get_obj("wds_mac"+i).value == "00:00:00:00:00:00")
            {
                alert("<?=$a_invalid_mac?>");
				get_obj("wds_mac"+i).focus();
                return false;
            }	
			for(var j=1; j<="<?=$cfg_wds_mac_num?>"; j++)
			{
				if(f_final.f_scan_value.value != 1)
				{			
					WDS_MAC = get_obj("wds_mac"+j).value.toUpperCase();
					if(get_obj("wds_mac"+i).value.toUpperCase() == WDS_MAC && i!=j)
					{
						if(f_final.f_scan_value.value != 1)
						{
							alert("<?=$a_same_wds_mac?>");
				   	 		get_obj("wds_mac"+j).value = "";
				   	 		get_obj("wds_mac"+j).focus();					
							return false;
						}	
					}
				}
			}
			get_obj("f_wds_mac"+i).value = get_obj("wds_mac"+i).value.toUpperCase();
		}
		}
	}
	
	f_final.f_encrypt.value = (f.encrypt[1].checked?1:0);
	
	if(f.clone_mac1.value=="" && f.clone_mac2.value=="" && f.clone_mac3.value=="" && f.clone_mac4.value=="" && f.clone_mac5.value=="" && f.clone_mac6.value=="" )
	{
		clone_mac="";
	}
	else
		{
		clone_mac=f.clone_mac1.value+":"+f.clone_mac2.value+":"+f.clone_mac3.value+":"+f.clone_mac4.value+":"+f.clone_mac5.value+":"+f.clone_mac6.value;
		}
	
	
	if(f.clone_mac_enable.checked)
	{
		f_final.f_mac_enable.value=1;
	}
	else
	{
		f_final.f_mac_enable.value=0;	
	}
	f_final.f_mac_source.value=f.mac_source.value;
	if(mode_value.value == 1 && f.mac_source.value==0 && f.clone_mac_enable.checked && f_final.f_scan_value.value!="1" && f_final.f_scan_mac_value.value!="1")
	{
		if(clone_mac !="")
		{
                        var num_mac1=parseInt("0x"+f.clone_mac1.value);
                        var str_mac1=num_mac1.toString(2);
                        if(str_mac1.charAt(str_mac1.length-1) == "1")
			{
				alert("<?=$a_invalid_mac?>");
				field_focus(f.clone_mac1, "**");
				return false;
			}
			else
			{
				if(f.clone_mac1.value=="00" && f.clone_mac2.value=="00" && f.clone_mac3.value=="00" && f.clone_mac4.value=="00" && f.clone_mac5.value=="00" && f.clone_mac6.value=="00")
				{
					alert("<?=$a_invalid_mac?>");
					field_focus(f.clone_mac1, "**");
					return false;
				}
			}
			if (!macCheck(clone_mac))
			{
				alert("<?=$a_invalid_mac?>");
				field_focus(f.clone_mac1, "**");
				return false;
			}
		}
		else
		{
			alert("<?=$a_invalid_mac?>");
			field_focus(f.clone_mac1, "**");
			return false;
		}
	}
	f_final.f_clone_mac.value=clone_mac.toUpperCase();
	
	if(f_final.f_auth.value == 0 || f_final.f_auth.value == 1 || f_final.f_auth.value == 8) // open ,shared ,both
	{
		if(f_final.f_scan_value.value != 1)
		{			
			if(f_final.f_encrypt.value == 1)
			{
				var check_key = "";
				var check_type = "";
				var check_length = "";
				if(f.defkey.value == 1)
				{
					check_key = "<?=$cfg_wep1?>";
					check_type = "<?=$cfg_wep1_keytype?>";
					check_length = "<?=$cfg_wep1_length?>";
				}
				else if(f.defkey.value == 2)
				{
					check_key = "<?=$cfg_wep2?>";
					check_type = "<?=$cfg_wep2_keytype?>";
					check_length = "<?=$cfg_wep2_length?>";
				}
				else if(f.defkey.value == 3)
				{
					check_key = "<?=$cfg_wep3?>";
					check_type = "<?=$cfg_wep3_keytype?>";
					check_length = "<?=$cfg_wep3_length?>";
				}
				else if(f.defkey.value == 4)
				{
					check_key = "<?=$cfg_wep4?>";
					check_type = "<?=$cfg_wep4_keytype?>";
					check_length = "<?=$cfg_wep4_length?>";
				}
				if(f.key.value == "" || f.key.value != check_key || f.keytype.value != check_type || f.keysize.value != check_length)
				{
				if(f.key.value == "")
				{
					alert("<?=$a_empty_key?>");
					return false;
				}
				else
				{
					if(is_blank_in_first_end(f.key.value))
					{
						alert("<?=$a_first_end_blank?>");
						f.key.select();
						return false;
					}
					if(f.keytype.value == 2) // hex	
					{
						for(var k=0; k < f.key.value.length; k++)
						{
							
							if((f.key.value.charAt(k) >= '0' && f.key.value.charAt(k) <= '9') ||
								(f.key.value.charAt(k) >= 'a' && f.key.value.charAt(k) <= 'f') ||
								(f.key.value.charAt(k) >= 'A' && f.key.value.charAt(k) <= 'F'))
								continue;
							alert("<?=$a_valid_hex_char?>");
							f.key.select();
							return false;
						}	
						
						if(f.keysize.value == 64 && f.key.value.length != 10)
						{
							alert("<?=$a_invalid_len_hex_64?>");
							f.key.select();
							return false;
						}
						else if	(f.keysize.value == 128 && f.key.value.length != 26)
						{
							alert("<?=$a_invalid_len_hex_128?>");
							f.key.select();
							return false;
						}											
						f.key.value = f.key.value.toUpperCase();
						f.confirm_key.value = f.confirm_key.value.toUpperCase();
					}
					else //ascii
					{
						if(strchk_unicode(f.key.value))
						{
							alert("<?=$a_valid_asc_char?>");
							f.key.select();
							return false;
						}				
								
						if(f.keysize.value == 64 && f.key.value.length != 5)
						{
							alert("<?=$a_invalid_len_asc_64?>");
							f.key.select();
							return false;
						}				
						else if (f.keysize.value == 128 && f.key.value.length != 13)
						{
							alert("<?=$a_invalid_len_asc_128?>");
							f.key.select();
							return false;
						}							
					}
				}
				}
				if(f.key.value != f.confirm_key.value)
				{
					alert("<?=$a_key_not_matched?>");
					f.confirm_key.select();	
					return false;
				}
				
				var check_ms_key_index = 0;
				
				for(var l=1; l < 8; l++)
				{
					if(ms_enable_list[l][1] == 1)
					{
						if("<?=$cfg_mssid_status?>" == 1 && f.defkey.value == ms_key_index_list[l][1] && check_ms_key_index != 1 && check_key != f.key.value)
						{
							alert("<?=$a_key_index_matched_mssid_index?>");
							check_ms_key_index = 1;
						}
					}
				}
			}
		}
		f_final.f_keytype.value = f.keytype.value;
		f_final.f_keysize.value = f.keysize.value;
		f_final.f_defkey.value = f.defkey.value;
		f_final.f_key.value = f.key.value;		
	}	
	else if(f_final.f_auth.value == 3) //psk
	{
		if(f_final.f_scan_value.value != 1)
		{		
			if(mode_value.value != 1 && mode_value.value != 2) //not apc
			{
				if(is_blank(f.gkui.value) ||is_blank_in_string(f.gkui.value)||!is_digit(f.gkui.value) || (f.gkui.value < 300) || (f.gkui.value > 9999999))
				{
					alert("<?=$a_invalid_key_interval?>");
					f.gkui.focus();
					return false;
				}	
			}	
			if(f.enable_rekey[0].checked==true)
			{
				if(is_blank_in_first_end(f.passphrase.value))
				{
					alert("<?=$a_first_end_blank?>");
					f.passphrase.value="";
					f.passphrase.select();
					return false;
				}
				if(f.passphrase.value.length <8 || f.passphrase.value.length > 63 )
				{
					
					if(f.passphrase.value.length ==64 )
					{
						if(is_checkoutHex() == false )
						{
							alert("<?=$m_invalid_psk_len?>");
							f.passphrase.value="";
							f.confirm_passphrase.value="";
							f.passphrase.select();
							return false;
						}
						else
						{
							f_final.f_wpa_passphraseformat.value=0;
						}
					}
					else
					{
						alert("<?=$a_invalid_psk_len?>");
						f.passphrase.value="";
						f.passphrase.select();
						return false;
					}
				}
				else
				{
					f_final.f_wpa_passphraseformat.value=1;
				}
				
				if(strchk_unicode(f.passphrase.value))
				{
					alert("<?=$a_invalid_psk?>");
					f.passphrase.select();
					return false;
				}
				if(f.passphrase.value != f.confirm_passphrase.value)
				{
					alert("<?=$a_passphrase_not_matched?>");
					f.confirm_passphrase.select();	
					return false;
				}
			}
			else
			{
				if( !is_digit(f.time_interuol.value) || f.time_interuol.value < 1 || f.time_interuol.value > 168)
				{
					alert("<?=$a_invalid_time_range?>");
					f.time_interuol.select();
					return false;
				}	
			}
		}
		f_final.f_gkui.value = f.gkui.value;
		f_final.f_passphrase.value = f.passphrase.value;								
		if(f.enable_rekey[0].checked==true)
		{	
			f_final.f_enable_rekey.value = 0;
		}
		else
		{
			f_final.f_enable_rekey.value = 1;
			if("<?=$check_band?>" != "")
				alert("<?=$a_two_band_same_time?>");
			if("<?=$cfg_ntp_enable?>"!="2")
			{
				var f_date = new Date();
				f_final.f_date.value=(f_date.getMonth()+1)+"/"+(f_date.getDate())+"/"+f_date.getFullYear();
				f_final.f_time.value=f_date.getHours()+":"+f_date.getMinutes()+":"+f_date.getSeconds();
			}
		}
		f_final.f_time_interuol.value = f.time_interuol.value;	
		f_final.f_start_time.value = f.shour.value+":"+f.smin.value;
		f_final.f_start_week.value = f.sweek.value;	      
	}		
	else if(f_final.f_auth.value == 2 || f_final.f_auth.value == 9) //eap or 802.1x 
	{
		if (f_final.f_auth.value == 2)
		{
			if(mode_value.value == 1 || mode_value.value == 2) // eap for apc victor add
			{
				if(is_blank(f.apc_eap_username.value))
				{
					alert("<?=$a_empty_username?>");
					f.apc_eap_username.focus();
					return false;
				}		
					
				if(first_blank(f.apc_eap_username.value))
				{
					alert("<?=$a_first_blank_username?>");
					f.apc_eap_username.select();
					return false;
				}
				if(is_blank_in_first_end(f.apc_eap_username.value))
				{
					alert("<?=$a_first_end_blank?>");
					f.apc_eap_username.select();
					return false;
				}
				if(strchk_unicode(f.apc_eap_username.value))
				{
					alert("<?=$a_invalid_username?>");
					f.apc_eap_username.select();
					return false;
				}
				if(is_blank_in_first_end(f.apc_eap_password.value))
				{
					alert("<?=$a_first_end_blank?>");
					f.apc_eap_password.value="";
					f.apc_eap_password.select();
					return false;
				}
							
				if(f.apc_eap_password.value.length <8 || f.apc_eap_password.value.length > 64)
				{
					alert("<?=$a_invalid_eap_pass_len?>");
					f.apc_eap_password.value="";
					f.passphrase.select();
					return false;
				}
				
				if(strchk_unicode(f.apc_eap_password.value))
				{
					alert("<?=$a_invalid_pass?>");
					f.apc_eap_password.select();
					return false;
				}
			}
			if(is_blank(f.gkui.value) ||is_blank_in_string(f.gkui.value)|| !is_digit(f.gkui.value) || (f.gkui.value < 300) || (f.gkui.value > 9999999))
			{
				alert("<?=$a_invalid_key_interval?>");
				f.gkui.focus();
				return false;
			}
		}
		if (f_final.f_auth.value == 9)
		{
			if(is_blank(f.kui.value) ||is_blank_in_string(f.kui.value)|| !is_digit(f.kui.value) || (f.kui.value < 300) || (f.kui.value > 9999999))
			{
				alert("<?=$a_invalid_1x_key_interval?>");
				f.kui.focus();
				return false;
			}		
		}
		if(mode_value.value != 1 && mode_value.value != 2) // not eap for apc victor add
		{
		var srv_obj = "radius";
		var radius_srv_number = 2;
		
		if("<?=$runtime_backup_radius_server?>" == 0)
			radius_srv_number = 1;
		<? if(query("/runtime/web/display/local_radius_server") =="1")	
			{	echo "if(f.radius_type[0].checked == true){";}//remote radius server, need check
			?>			
		for(var l=1; l<=radius_srv_number; l++) // two server
		{
			if(!(get_obj(srv_obj+"_srv_"+l).value=="" && get_obj(srv_obj+"_sec_"+l).value=="" && l==2))
			{
				if(!is_valid_ip3(get_obj(srv_obj+"_srv_"+l).value,0) && !is_valid_ipv6(get_obj(srv_obj+"_srv_"+l).value,0))
				{
					alert("<?=$a_invalid_radius_srv?>");
					get_obj((srv_obj+"_srv_"+l)).select();
					return false;
				}
				if(is_blank(get_obj(srv_obj+"_port_"+l).value))
				{
					alert("<?=$a_invalid_radius_port?>");
					get_obj(srv_obj+"_port_"+l).focus();
					return false;
				}
				if(!is_valid_port_str(get_obj(srv_obj+"_port_"+l).value))
				{
					alert("<?=$a_invalid_radius_port?>");
					get_obj(srv_obj+"_port_"+l).select();
					return false;
				}
				if(is_blank(get_obj(srv_obj+"_sec_"+l).value))
				{
					alert("<?=$a_empty_radius_sec?>");
					get_obj(srv_obj+"_sec_"+l).focus();
					return false;
				}
				if(strchk_unicode(get_obj(srv_obj+"_sec_"+l).value))
				{
					alert("<?=$a_invalid_radius_sec?>");
					get_obj(srv_obj+"_sec_"+l).select();
					return false;
				}		
			}	
		}	
		if(f.radius_sec_1.value.length<1 || f.radius_sec_1.value.length>64)
		{
			alert("<?=$a_invalid_secret_len?>");
			f.radius_sec_1.select();
			return false;
		}
		if(is_blank_in_first_end(f.radius_sec_1.value))
		{
			alert("<?=$a_first_end_blank?>");
			f.radius_sec_1.select();
			return false;
		}
		f_final.f_radius_sec_1.value = f.radius_sec_1.value;
		if(f.radius_srv_2.value != "")
		{
			if(f.radius_sec_2.value.length<1 || f.radius_sec_2.value.length>64)
        	{
            	alert("<?=$a_invalid_secret_len?>");
	            f.radius_sec_2.select();
    	        return false;
        	}
			if(is_blank_in_first_end(f.radius_sec_2.value))
			{
				alert("<?=$a_first_end_blank?>");
				f.radius_sec_2.select();
				return false;
			}
			f_final.f_radius_srv_2.value = f.radius_srv_2.value;
		}
		if(f.radius_port_2 != null)	
		{
			if(f.radius_port_2.value != "" && !is_valid_port_str(f.radius_port_2.value))
			{
				alert("<?=$a_invalid_radius_port?>");
				f.radius_port_2.select();
				return false;
			}
			f_final.f_radius_port_2.value = f.radius_port_2.value;
		}
		if(f.radius_sec_2 != null)
			f_final.f_radius_sec_2.value = f.radius_sec_2.value;	
	
		srv_obj = "acc";
		if(f.acc_mode!= null )
		{
			if(f.acc_mode.value != 0) // accounting server enable
			{	
				for(var l=1; l<=2; l++) // two server
				{
					if(!(get_obj(srv_obj+"_srv_"+l).value=="" && get_obj(srv_obj+"_sec_"+l).value=="" && l==2))
					{
						if(!is_valid_ip3(get_obj(srv_obj+"_srv_"+l).value,0) && !is_valid_ipv6(get_obj(srv_obj+"_srv_"+l).value,0))
						{
							alert("<?=$a_invalid_acc_srv?>");
							get_obj((srv_obj+"_srv_"+l)).select();
							return false;
						}
						if(is_blank(get_obj(srv_obj+"_port_"+l).value))
						{
							alert("<?=$a_invalid_acc_port?>");
							get_obj(srv_obj+"_port_"+l).focus();
							return false;
						}
						if(!is_valid_port_str(get_obj(srv_obj+"_port_"+l).value))
						{
							alert("<?=$a_invalid_acc_port?>");
							get_obj(srv_obj+"_port_"+l).select();
							return false;
						}
						if(is_blank(get_obj(srv_obj+"_sec_"+l).value))
						{
							alert("<?=$a_empty_acc_sec?>");
							get_obj(srv_obj+"_sec_"+l).focus();
							return false;
						}
						if(strchk_unicode(get_obj(srv_obj+"_sec_"+l).value))
						{
							alert("<?=$a_invalid_acc_sec?>");
							get_obj(srv_obj+"_sec_"+l).select();
							return false;
						}		
						if(is_blank_in_first_end(get_obj(srv_obj+"_sec_"+l).value))
						{           
							alert("<?=$a_first_end_blank?>");
							get_obj(srv_obj+"_sec_"+l).select();
							return false;   
						}
					}					
				}		
		if(f.acc_mode != null)
			f_final.f_acc_mode.value = f.acc_mode.value;	
		if(f.acc_srv_1 != null)
			f_final.f_acc_srv_1.value = f.acc_srv_1.value;
		if(f.acc_port_1 != null)
			f_final.f_acc_port_1.value = f.acc_port_1.value;
		if(f.acc_sec_1.value != "" && f.acc_mode.value==1)
		{
			if(f.acc_sec_1.value.length<1 || f.acc_sec_1.value.length>64)
            {
                alert("<?=$a_invalid_secret_len?>");
                f.acc_sec_1.select();
                return false;
            }
			f_final.f_acc_sec_1.value = f.acc_sec_1.value;
		}
		if(f.acc_srv_2 != null)
			f_final.f_acc_srv_2.value = f.acc_srv_2.value;
		if(f.acc_port_2 != null)
		{
			if(f.acc_port_2.value != "" && !is_valid_port_str(f.acc_port_2.value))
			{
				alert("<?=$a_invalid_acc_port?>");
				f.acc_port_2.select();
				return false;
			}
			f_final.f_acc_port_2.value = f.acc_port_2.value;
		}
		if(f.acc_sec_2.value !="" && f.acc_mode.value==1)
		{
			if(f.acc_sec_2.value.length<1 || f.acc_sec_2.value.length>64)
            {
                alert("<?=$a_invalid_secret_len?>");
                f.acc_sec_2.select();
                return false;
            }
			f_final.f_acc_sec_2.value = f.acc_sec_2.value;
		}

			}	
		}	
		<? if(query("/runtime/web/display/local_radius_server") =="1")	
			{echo "}";}
		?>	   	
		if(f.radius_type != null)   
		{
			if(f.radius_type[0].checked == true)
			{
				f_final.f_radius_type.value  = 0;
			}
			else
			{
				f_final.f_radius_type.value  = 1;
			}
		}
		}
		f_final.f_apc_eap_method.value = f.apc_eap_method.value;
		f_final.f_apc_inner_auth.value = f.apc_inner_auth.value;
		f_final.f_apc_eap_username.value = f.apc_eap_username.value;
		f_final.f_apc_eap_password.value = f.apc_eap_password.value;
		f_final.f_gkui.value = f.gkui.value;
		f_final.f_kui.value = f.kui.value;  
		f_final.f_radius_srv_1.value = f.radius_srv_1.value;
		f_final.f_radius_port_1.value = f.radius_port_1.value;
	}	
	else
	{
		alert("<?=$a_unknown_auth?>");
		return false;
	}			

	if(f_final.f_auth.value == 0 || f_final.f_auth.value == 1 || f_final.f_auth.value == 8) //open, shared, both
	{
		if(f_final.f_encrypt.value == 0)
			f_final.f_cipher.value = 0; //cipher = none
		else
			f_final.f_cipher.value = 1; // cipher = wep	
	}
	else if(f_final.f_auth.value == 3) //psk
	{
		if(f_final.f_wpa_mode.value == 0)
			f_final.f_auth.value = 7; //:wap2-auto-psk
		else if(f_final.f_wpa_mode.value == 1)	
			f_final.f_auth.value = 5; //wpa2-psk 
		else
			f_final.f_auth.value = 3; //wpa-psk	
	}
	else if(f_final.f_auth.value == 2) //eap
	{
		if(f_final.f_nap_enable.value != "<?=$cfg_nap_enable?>")
        {
              if("<?=$another_auth?>" == 2 || "<?=$another_auth?>" == 4 || "<?=$another_auth?>" == 6)//eap
                    alert("<?=$a_two_band_share_the_same_nap?>");
        }
		if(f_final.f_wpa_mode.value == 0)
			f_final.f_auth.value = 6; //:wap2-auto-eap
		else if(f_final.f_wpa_mode.value == 1)	
			f_final.f_auth.value = 4; //wpa2-eap 
		else
			f_final.f_auth.value = 2; //wpa-eap	
	}	
	fields_disabled(f, false);
	return true;
}

function submit()
{
	if(check()) 
	{get_obj("final_form").submit();}
}

function do_scan()
{
	var f_final	=get_obj("final_form");
	f_final.f_scan_value.value="1";	
	submit();	
}
	
function genSSID(ssid)
{
				var str = "";
        for(var i=0; i < ssid.length; i++)
        {
                if(i!=0 && (i%11)==0)// change line
                {
                        str+="<br \>";
                }
                if(ssid.charAt(i)==" ")
                {
                str+="&nbsp;";
                }
                else if(ssid.charAt(i)=="<")
                {
                str+="&lt;";
                }
                else
                {
                str+=ssid.charAt(i);
        				}
				}
       	return(str);
}

function page_table(index)
{
	var chan = apc_list[index][2];
	if(chan != "-2")   
	{
		var str="";
		var ssid = apc_list[index][1];
  	var mac = apc_list[index][3];
  	var rssi = apc_list[index][4];
  	var auth = apc_list[index][5];
  	
		str+="<input type='hidden' id='scan_ch"+index+"' name='scan_ch"+index+"' value='"+chan+"'>";
		str+="<input type='hidden' id='scan_mac"+index+"' name='scan_mac"+index+"' value='"+mac+"'>";	
		if(index%2==1)
		{
			str+="<tr style='background:#CCCCCC;'>";
		}
		else
		{
			str+="<tr style='background:#B3B3B3;'>";
		}
		
		str+="<td width='30'><input type='radio' name='scan_table' id='scan_table"+index+"' onclick='on_click_scan_table(\""+index+"\")'></td>";			
		str+="<td width='40'>"+chan+"</td>";
		str+="<td width='70'>"+rssi+"</td>";
		str+="<td width='120'>"+mac+"</td>";		
		str+="<td width='110'>"+auth+"</td>";		
		str+="<td id='adjust_td'>"+	genSSID(ssid)+"</td>";
		str+= "</tr>\n";
		
		document.write(str);
	}
}
</script>

<body <?=$G_BODY_ATTR?> onload="init();">
<form name="final_form" id="final_form" method="post" action="<?=$MY_NAME?>.php"  onsubmit="return check();">
<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="f_scan_value"		value="">
<input type="hidden" name="f_chan"		value="">
<input type="hidden" name="f_auth"		value="">
<input type="hidden" name="f_cipher"	value="">
<input type="hidden" name="f_wpa_mode"	value="">
<input type="hidden" name="f_encrypt"	value="">
<input type="hidden" name="f_wds_mac1"	id="f_wds_mac1"		value="">
<input type="hidden" name="f_wds_mac2"	id="f_wds_mac2"		value="">
<input type="hidden" name="f_wds_mac3"	id="f_wds_mac3"		value="">
<input type="hidden" name="f_wds_mac4"	id="f_wds_mac4"		value="">
<input type="hidden" name="f_wds_mac5"	id="f_wds_mac5"		value="">
<input type="hidden" name="f_wds_mac6"	id="f_wds_mac6"		value="">
<input type="hidden" name="f_wds_mac7"	id="f_wds_mac7"		value="">
<input type="hidden" name="f_wds_mac8"	id="f_wds_mac8"		value="">
<input type="hidden" name="f_band"		value="">
<input type="hidden" name="f_ssid"		value="">
<input type="hidden" name="f_application"		value="">
<input type="hidden" name="f_mode"		value="">
<input type="hidden" name="f_auto_ch_scan"		value="">
<input type="hidden" name="f_ap_hidden"		value="">
<input type="hidden" name="f_channel_width"		value="">
<input type="hidden" name="f_captival_profile"     value="">
<input type="hidden" name="f_keytype"		value="">
<input type="hidden" name="f_keysize"		value="">
<input type="hidden" name="f_defkey"		value="">
<input type="hidden" name="f_key"		value="">
<input type="hidden" name="f_gkui"		value="">
<input type="hidden" name="f_passphrase"		value="">
<input type="hidden" name="f_radius_srv_1"		value="">
<input type="hidden" name="f_radius_port_1"		value="">
<input type="hidden" name="f_radius_sec_1"		value="">
<input type="hidden" name="f_radius_srv_2"		value="">
<input type="hidden" name="f_radius_port_2"		value="">
<input type="hidden" name="f_radius_sec_2"		value="">
<input type="hidden" name="f_acc_mode"		value="">
<input type="hidden" name="f_acc_srv_1"		value="">
<input type="hidden" name="f_acc_port_1"		value="">
<input type="hidden" name="f_acc_sec_1"		value="">
<input type="hidden" name="f_acc_srv_2"		value="">
<input type="hidden" name="f_acc_port_2"		value="">
<input type="hidden" name="f_acc_sec_2"		value="">
<input type="hidden" name="f_nap_enable"		value="">
<input type="hidden" name="f_enable_rekey"		value="">
<input type="hidden" name="f_time_interuol"		value="">
<input type="hidden" name="f_date" value="">
<input type="hidden" name="f_time" value="">
<input type="hidden" name="f_start_time" value="">
<input type="hidden" name="f_start_week" value="">
<input type="hidden" name="f_kui"		value="">
<input type="hidden" name="f_radius_type"		value="">
<input type="hidden" name="f_apc_eap_method"		value="">
<input type="hidden" name="f_apc_inner_auth"		value="">
<input type="hidden" name="f_apc_eap_username"		value="">
<input type="hidden" name="f_apc_eap_password"		value="">
<input type="hidden" name="f_clone_mac"		value="">
<input type="hidden" name="f_scan_mac_value"		value="">
<input type="hidden" name="f_mac_enable"		value="">
<input type="hidden" name="f_mac_source"		value="">
<input type="hidden" name="f_wpa_passphraseformat"		value="">
</form>
<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return false;">
<table id="table_frame" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
	<tr>
		<td valign="top">
			<table id="table_header" <?=$G_TABLE_ATTR_CELL_ZERO?>>
			<tr>
				<td id="td_header" valign="middle"><?=$m_context_title?></td>
			</tr>
			</table>
<!-- ________________________________ Main Content Start ______________________________ -->
			<table id="table_set_main"  border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
				<tr>
					<td width="30%" id="td_left">
						<?=$m_band?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("band", [0,1], ["<?=$m_band_2.4G?>","<?=$m_band_5G?>"], "on_change_band(this)");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
				<? if(  $cfg_band == 0 || $cfg_wlan_domain != 392  )	{echo "<!--";} ?>	
				<tr>
					<td width="30%" id="td_left">
						<?=$m_application?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("application", [0,1], ["<?=$m_indoor?>","<?=$m_outdoor?>"], "on_change_application(this)");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
				<? if(  $cfg_band == 0 || $cfg_wlan_domain != 392 )	{echo "-->";} ?>				
				<? if(  $cfg_band == 1 || $cfg_wlan_domain != 392 )	{echo "<!--";} ?>	
				<tr>
					<td width="30%" id="td_left">
						<?=$m_application?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("application", [0], ["<?=$m_in_out_door?>"], "on_change_application(this)");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
				<? if( $cfg_band == 1 || $cfg_wlan_domain != 392 )	{echo "-->";} ?>	

				<tr>
					<td id="td_left">
						<?=$m_mode?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>
							genSelect("mode", [0,3,4,1], ["<?=$m_ap_access?>","<?=$m_ap_wds_with_ap?>","<?=$m_ap_wds?>","<?=$m_ap_client?>"], "on_change_mode(this)");
							genSelect("mode_eg", [0,1], ["<?=$m_ap_access?>","<?=$m_ap_client?>"], "on_change_mode(this)");
							genSelect("mode_apr", [0,3,4,1,2], ["<?=$m_ap_access?>","<?=$m_ap_wds_with_ap?>","<?=$m_ap_wds?>","<?=$m_ap_client?>","<?=$m_ap_repeater?>"], "on_change_mode(this)");
						<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>

				<tr>
					<td id="td_left">
						<?=$m_ssid?>
					</td>
					<td id="td_right">
						<input name="ssid" class="text" id="ssid" type="text" size="20" maxlength="32" value="">
					</td>
				</tr>				
				<tr>
					<td id="td_left">
						<?=$m_ap_hidden?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("ap_hidden", [1,0], ["<?=$m_disable?>","<?=$m_enable?>"], "");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>				
				<tr>
					<td id="td_left">
						<?=$m_auto_ch_scan?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("auto_ch_scan", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"], "on_change_auto_channel(this)");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_channel?>
					</td>
					
					<? if($cfg_wlan_domain == 392 && $application == 1)	{echo "<!--";} ?>		
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genChannel("g","ch_g");genChannel("a","ch_a");genChannel("a","ch_a_wds");<?=$G_TAG_SCRIPT_END?>
					</td>
					<? if($cfg_wlan_domain == 392 && $application == 1)	{echo "-->";} ?>		
				
				  <? 
				  if( $cfg_wlan_domain == 392 && $application != 1 )	
				    {echo "<!--";}
				    else if($cfg_wlan_domain != 392){
				    	echo "<!--";
				   }				    
				  ?>						  
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genOdChannel("g","ch_g");genOdChannel("a","ch_a");genOdChannel("a","ch_a_wds");<?=$G_TAG_SCRIPT_END?>
					</td>
					<?
					if( $cfg_wlan_domain == 392 && $application != 1 )	
				     {echo "-->";}
				    else if($cfg_wlan_domain != 392){
				    	echo "-->\n";
				   }
					?>	
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_channel_width?>
					</td>
					<td id="td_right">
					<?=$G_TAG_SCRIPT_START?>
						genSelect("channel_width", [0,1], ["<?=$m_cw_20?>","<?=$m_cw_auto?>"], "");
						genSelect("channel_width_ac", [0,1,2], ["<?=$m_cw_20?>","<?=$m_cw_auto?>","<?=$m_cw_auto_80?>"], "");
						<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
<? if($cap_v1 != 1) {echo "<!--";} ?>
				<tr>
					<td id="td_left">
						<?=$m_captival_profile?>
					</td>
					<td id="td_right">
						<select id="captival_profile" name="captival_profile" <?if($cfg_ticket != 1 && $cfg_local != 1 && $cfg_radius != 1 && $cfg_ldap != 1 && $cfg_pop3 != 1) {echo "disabled";}?>>
							<option value="0"><?=$m_disable?></option>
<?
if($cfg_ticket == 1){echo "<option value=\"7\">".$m_ticket."</option>";}
if($cfg_local == 1){echo "<option value=\"1\">".$m_local."</option>";}
if($cfg_radius == 1){echo "<option value=\"2\">".$m_radius."</option>";}
if($cfg_ldap == 1){echo "<option value=\"3\">".$m_ldap."</option>";}
if($cfg_pop3 == 1){echo "<option value=\"4\">".$m_pop3."</option>";}
?>
						</select>
					</td>
				</tr>
<? if($cap_v1 != 1) {echo "-->";} ?>
				<tr>
					<td colspan="2">
						<fieldset id="wds_mac_fieldset" style="display:none;">		
							<legend><?=$m_wds_mac_title?></legend>
							<table width="100%" border="0">		
								<tr>
									<td colspan="4"><?=$m_remote_ap_mac?></td>
								</tr>	
								<tr>
									<td>
										<?=$G_TAG_SCRIPT_START?>gentextWDSMac("<?=$cfg_wds_mac_num?>");<?=$G_TAG_SCRIPT_END?>
									</td>
								</tr>																	
							</table>
						</fieldset>
					</td>
				</tr>		
				<tr>
					<td colspan="2">
						<fieldset id="site_survey_fieldset" style="display:none;">		
							<legend><?=$m_site_survey_title?></legend>
							<table width="100%" border="0">		
								<tr>
									<td align="right" colspan="2">
										<input type="button" value="&nbsp;&nbsp;<?=$m_b_scan?>&nbsp;&nbsp;" name="scan" onclick="do_scan()">
									</td>	
								</tr>	
								<tr>
									<td>
										<table width="100%" border="0" align="center" <?=$G_TABLE_ATTR_CELL_ZERO?>>
										<tr class="list_head" align="left">
											<td width="30">
												&nbsp;
											</td>
											<td width="40">
												<?=$m_scan_ch?>
											</td>
											<td width="70">
												<?=$m_scan_rssi?>
											</td>
											<td width="120">
												<?=$m_scan_mac?>
											</td>
											<td width="110">
												<?=$m_scan_sec?>
											</td>
											<td width="120">
												<?=$m_scan_ssid?>
											</td>		
											<td width="20">
												&nbsp;
											</td>																																																															
										</tr>	
										</table>
										<div class="div_tab">
										<div id="apc_scan" style="display:none;">	
										<table id="scan_tab" width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>						

<?
$tmp_apc = 1;
$count_list=0;
while($count_list< $apc_num)
{
	$count_list++;
	echo $G_TAG_SCRIPT_START."page_table(".$count_list.");".$G_TAG_SCRIPT_END;
}
?>																					
										</table>										
										</div>	
										<div id="wds_scan" style="display:none;">																			
										<table id="scan_tab" width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>						
<?
$tmp_wds = 1;
for("/runtime/wlan/inf:".$cfg_index."/wdslistinfo/wdsscan")
{
	echo "<input type=\"hidden\" id=\"scan_mac".$@."\" name=\"scan_mac".$@."\" value=\"".query("mac:")."\">\n";
	
	if(query("wdsmode")!="")
	{
		if($tmp_wds == 1)
		{
			echo "<tr style=\"background:#cccccc;\">\n";
			$tmp_wds =0;
		}
		else
		{
			echo "<tr style=\"background:#B3B3B3;\">\n";
			$tmp_wds =1;
		}
	
		echo "<td width=\"30\"><input type=\"radio\" name=\"scan_table\" onclick=\"on_click_scan_table(".$@.")\"></td>\n";	
		echo "<td width=\"40\">".query("chan:")."</td>\n";
		if(query("/runtime/web/display/stinfo_rssi") != "1") //%
		{
			echo "<td width=\"70\">".query("rssi:")."%</td>\n";	
		}
		else
		{
			echo "<td width=\"70\">".query("signalstrength")."</td>\n";
		}
		echo "<td width=\"120\">".query("mac:")."</td>\n";
		echo "<td width=\"110\">".query("auth:")."</td>\n";		
		echo "<td id=\"adjust_td\" >".$G_TAG_SCRIPT_START."genTableSSID(\"".get("j","ssid")."\",0);".$G_TAG_SCRIPT_END."</td>\n";
		echo "</tr>\n";
	}
}
?>																					
										</table>
										</div>
										</div>									
									</td>
								</tr>																		
							</table>
						</fieldset>
					</td>
				</tr>												
				<tr>
					<td id="td_left">
						<?=$m_authentication?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>
						genSelect("auth", [0,1,3,2,9], ["<?=$m_auth_open?>","<?=$m_auth_shared?>","<?=$m_auth_wpaper?>","<?=$m_auth_wpaent?>","<?=$m_auth_1x?>"], "on_change_authentication(this)");
						genSelect("auth_11n_only", [0,3,2], ["<?=$m_auth_open?>","<?=$m_auth_wpaper?>","<?=$m_auth_wpaent?>"], "on_change_authentication(this)");
						genSelect("auth_wds", [0,3], ["<?=$m_auth_open?>","<?=$m_auth_wpaper?>"], "on_change_authentication(this)");
						genSelect("auth_apc_apr", [0,3], ["<?=$m_auth_open?>","<?=$m_auth_wpaper?>"], "on_change_authentication(this)");
						genSelect("auth_apc", [0,3,2], ["<?=$m_auth_open?>","<?=$m_auth_wpaper?>","<?=$m_auth_wpaent?>"], "on_change_authentication(this)");
						genSelect("auth_mssid", [0,3,2,9], ["<?=$m_auth_open?>","<?=$m_auth_wpaper?>","<?=$m_auth_wpaent?>", "<?=$m_auth_1x?>"], "on_change_authentication(this)");
						genSelect("auth_wds_mssid", [0,3], ["<?=$m_auth_open?>","<?=$m_auth_wpaper?>"], "on_change_authentication(this)");
						<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<fieldset id="wep_key_setting_fieldset" style="display:none;">
							<legend><?=$m_key_field_title?></legend>
							<table width="100%" border="0">
								<tr>
									<td width="30%"><?=$m_encryption?></td>
									<td>
										<?=$G_TAG_SCRIPT_START?>genRaidoEnableDisable("encrypt", "on_click_encryption()");<?=$G_TAG_SCRIPT_END?>
									</td>
								</tr>
								<tr>
									<td>
										<?=$m_key_type?>
									</td>
									<td>
										<?=$G_TAG_SCRIPT_START?>genSelect("keytype", [2,1], ["<?=$m_hex?>","<?=$m_ascii?>"],"on_change_size()");<?=$G_TAG_SCRIPT_END?>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<?=$m_key_size?>
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<?=$G_TAG_SCRIPT_START?>genSelect("keysize", [64,128], ["<?=$m_key_64bit?>","<?=$m_key_128bit?>"], "on_change_size()");<?=$G_TAG_SCRIPT_END?>
									</td>
								</tr>
								<tr>
									<td>
										<?=$m_default_key?>
									</td>
									<td>
										<?=$G_TAG_SCRIPT_START?>genSelect("defkey", [1,2,3,4], ["<?=$m_key1?>","<?=$m_key2?>","<?=$m_key3?>","<?=$m_key4?>"], "on_change_def_key(this)");<?=$G_TAG_SCRIPT_END?>
									</td>
								</tr>
								<tr>
									<td>
										<?=$m_key?>
									</td>
									<td>
										<input type="password" class="text" id="key" name="key" value="" />
									</td>
								</tr>
								<tr>
									<td>
										<?=$m_confirm_key?>
									</td>
									<td id="td_right">
										<input name="confirm_key" id="confirm_key" class="text" type="password" value="">
									</td>
								</tr>	
								<tr><td>&nbsp;</td>
									<td style=font-size:11px>(0-9,a-z,A-Z,~!@#$%^&*()_+`-={}[];'\:"|,./<>?)</td>
								</tr>	
							</table>
						</fieldset>
						<fieldset id="eap_psk_fieldset" style="display:none;">
							<legend><span id="eap_psk_table_title"><?=$m_passphrase_field_title?></span></legend>
							<table width="100%" border="0">
								<tbody id="wpa_setting" style="display:none;">	
								<tr>
									<td width="28%">
										<?=$m_wpa_mode?>
									</td>
									<td>
										<?=$G_TAG_SCRIPT_START?>genSelect("wpa_mode", [0,1,2], ["<?=$m_wpa_mode_auto?>","<?=$m_wpa_mode_wpa2?>","<?=$m_wpa_mode_wpa?>"], "");<?=$G_TAG_SCRIPT_END?>
										<?=$G_TAG_SCRIPT_START?>genSelect("wpa_mode_wds", [0,1], ["<?=$m_wpa_mode_auto?>","<?=$m_wpa_mode_wpa2?>"], "");<?=$G_TAG_SCRIPT_END?>
									</td>
								</tr>							
								<tr>
									<td >
										<?=$m_cipher?>
									</td>
									<td>
										<?=$G_TAG_SCRIPT_START?>
										genSelect("cipher", [4,3,2], ["<?=$m_cipher_auto?>","<?=$m_cipher_aes?>","<?=$m_cipher_tkip?>"], "on_change_cipher()");
										genSelect("cipher_11n_only", [3], ["<?=$m_cipher_aes?>"], "");
										genSelect("cipher_wds", [4,3], ["<?=$m_cipher_auto?>","<?=$m_cipher_aes?>"], "");
										<?=$G_TAG_SCRIPT_END?>	
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<?=$m_gkui?>
										&nbsp;
										<input type ="text" class="text" id="gkui" name="gkui" value="" size="5" /><?=$m_sec?>
									</td>
								</tr>
                                                                </tbody>
								<tbody id="dot_1x_setting" style="display:none;">
								<tr>
									<td>
										<?=$m_kui?>
									</td>
									<td>
										<input type ="text" class="text" id="kui" name="kui" value="" size="5" /><?=$m_sec?>									</td>
								</tr>
								</tbody>
								<tbody id="psk_setting" style="display:none;">		
								<tbody id="periodrical_key_change_setting" style="display:none;">		
								<tr>
									<td>
										<input type="radio" id="manual" name="enable_rekey" value="0" onClick="on_click_enable_rekey()">&nbsp;&nbsp;<?=$m_manual?>
									</td>
									<td>
										<input type="radio" id="periodrical_key_change" name="enable_rekey" value="1" onClick="on_click_enable_rekey()">&nbsp;&nbsp;<?=$m_periodrical_key_change?>
									</td>
								</tr>
								<tr>
									<td>
										<?=$m_activated_from?>
									</td>
									<td>
										<select size=1 id="sweek" name="sweek">
										<option value="Sun"><?=$m_sun?></option>
										<option value="Mon"><?=$m_mon?></option>
										<option value="Tue"><?=$m_tue?></option>
										<option value="Wed"><?=$m_wed?></option>
										<option value="Thu"><?=$m_thu?></option>
										<option value="Fri"><?=$m_fri?></option>
										<option value="Sat"><?=$m_sat?></option>
										</select>&nbsp;:&nbsp;	
										<select size=1 id="shour" name="shour">
<?
															$i=0;
															while ($i<24)
															{
																if($i<10)
																{
																	echo "<option value=0".$i.">0".$i."</option>\n";
																}
																else
																{
																	echo "<option value=".$i.">".$i."</option>\n";
																}
																$i++;
															}
?>
															</select>&nbsp;:&nbsp;	
															<select size=1 id="smin" name="smin">
<?
															$i=0;
															while ($i<60)
															{
																if($i<10)
																{
																	echo "<option value=0".$i.">0".$i."</option>\n";
																}
																else
																{
																	echo "<option value=".$i.">".$i."</option>\n";
																}
																$i++;
															}
?>
															</select>&nbsp;
									</td>
								</tr>
								<tr>
									<td>
										<?=$m_time?>
									</td>
									<td>
										<input type ="text" class="text" id="time_interuol" name="time_interuol" value="" size="5" maxLength="3"/><?=$m_hour?>
									</td>
								</tr>
								</tbody>
                                                                 <tbody id="apc_eap_setting" style="display:none;">								
								<tr>
									<td>
										<?=$m_eap_method?>
									</td>
									<td>
										<?=$G_TAG_SCRIPT_START?>genSelect("apc_eap_method", [2,1], ["<?=$m_peap?>","<?=$m_ttls?>"], "on_change_method()");<?=$G_TAG_SCRIPT_END?>	
									</td>
								</tr>	
								<tr>
									<td>
										<?=$m_inner_auth?>
									</td>
									<td>
										<?=$G_TAG_SCRIPT_START?>genSelect("apc_inner_auth", [3,4,2], ["<?=$m_pap?>","<?=$m_chap?>","<?=$m_mschav2?>"], "");<?=$G_TAG_SCRIPT_END?>	
									</td>
								</tr>	
								<tr>
									<td>
										<?=$m_username?>
									</td>
									<td>
										<input name="apc_eap_username" class="text" id="username" type="text" size="20" maxlength="64" value="">
									</td>
								</tr>	
								<tr>
									<td>
										<?=$m_password?>
									</td>
									<td>
										<input type="password" class="text" id="apc_eap_password" name="apc_eap_password" value="" size="20" maxLength="64" />
									</td>
								</tr>	
								</tbody>
								<tbody id="psk_setting1" style="display:none;">								
								<tr>
									<td>
										<?=$m_passphrase?>
									</td>
									<td>
										<input type="password" class="text" id="passphrase" name="passphrase" value="" size="40" maxLength="64" />
									</td>
								</tr>	
								<tr>
									<td>
										<?=$m_confirm_passphrase?>
									</td>
									<td>
										<input type="password" class="text" id="confirm_passphrase" name="confirm_passphrase" value="" size="40" maxLength="64" />
									</td>
								</tr>	
								<tr>
									<td>
									</td>
									<td>
										<?=$m_invalid_psk_len?>
									</td>
								</tr>
								<tr><td>&nbsp;</td>
									<td style=font-size:11px>(0-9,a-z,A-Z,~!@#$%^&*()_+`-={}[];'\:"|,./<>?)</td>
								</tr>	
								</tbody>
								<tbody id="key_change_psk_setting" style="display:none;">								
								<tr>
									<td>
										<?=$m_passphrase?>
									</td>
									<td>
										<input type="text" class="text" id="rekey" name="rekey" value="" size="40" maxLength="64" />
									</td>
								</tr>
								</tbody>
								</tbody>
                                               
								<tbody id="nap_setting" style="display:none;">				
<? if(query("/runtime/web/display/nap_server") =="0")	{echo "<!--";} ?>						
								<tr>
									<td bgcolor="#cccccc" colspan="2" height="16"><b><?=$m_nap_title?></b></td>
								</tr>
								<tr>
									<td>
										<?=$m_nap?>
									</td>
									<td>
										<input type="radio" name="nap_enable" value="0" onclick="on_click_nap_enable()"><?=$m_disable?>&nbsp;&nbsp;
										<input type="radio" name="nap_enable" value="1" onclick="on_click_nap_enable()"><?=$m_enable?>
									</td>
								</tr>
<? if(query("/runtime/web/display/nap_server") =="0") {echo "-->";} ?>	
								</tbody>
								<tbody id="eap_setting" style="display:none;">
<? if(query("/runtime/web/display/local_radius_server") =="0")	{echo "<!--";} ?>
								<tr>
									<td bgcolor="#cccccc" colspan="2" height="16"><b><?=$m_radius_server_mode?></b></td>
								</tr>
								<tr>
									<td>
										<?=$m_radius_server?>
									</td>
									<td>
										<input type="radio" name="radius_type" value="0" onClick="on_change_radius_type()"><?=$m_external?>&nbsp;&nbsp;
										<input type="radio" name="radius_type" value="1" onClick="on_change_radius_type()"><?=$m_internal?>
									</td>
								</tr>
<? if(query("/runtime/web/display/local_radius_server") =="0") {echo "-->";} ?>		
								<tr>
									<td bgcolor="#cccccc" colspan="2" height="16"><b><?=$m_primary_radius_title?></b></td>
								</tr>
								<tr>
									<td>
										<?=$m_radius_server?>
									</td>
									<td>
										&nbsp;<input type="text" class="text" id="radius_srv_1" name="radius_srv_1" value="" size="16" />
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<?=$m_radius_port?>
										&nbsp;
										<input type="text" class="text" id="radius_port_1" name="radius_port_1" value="" size="5" />
									</td>
								</tr>
								<tr>
									<td>
										<?=$m_radius_secret?>
									</td>
									<td>
										&nbsp;<input type="password" class="text" id="radius_sec_1" name="radius_sec_1" value="" size="40" />
									</td>
								</tr>
								<tr><td>&nbsp;</td>
									<td style=font-size:11px>(0-9,a-z,A-Z,~!@#$%^&*()_+`-={}[];'\:"|,./<>?)</td>
								</tr>
<? if(query("/runtime/web/display/backup_radius_server") =="0")	{echo "<!--";} ?>																																		
								<tr>
									<td bgcolor="#cccccc" colspan="2" height="16"><b><?=$m_secondary_radius_title?></b></td>
								</tr>
								<tr>
									<td>
										<?=$m_radius_server?>
									</td>
									<td>
										&nbsp;<input type="text" class="text" id="radius_srv_2" name="radius_srv_2" value="" size="16" />
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<?=$m_radius_port?>
										&nbsp;
										<input type="text" class="text" id="radius_port_2" name="radius_port_2" value="" size="5" />
									</td>
								</tr>
								<tr>
									<td>
										<?=$m_radius_secret?>
									</td>
									<td>
										&nbsp;<input type="password" class="text" id="radius_sec_2" name="radius_sec_2" value="" size="40" />
									</td>
								</tr>
								<tr><td>&nbsp;</td>
									<td style=font-size:11px>(0-9,a-z,A-Z,~!@#$%^&*()_+`-={}[];'\:"|,./<>?)</td>
								</tr>
<? if(query("/runtime/web/display/backup_radius_server") =="0") {echo "-->";} ?>									
<? if(query("/runtime/web/display/accounting_server") =="0")	{echo "<!--";} ?>																												
								<tr>
									<td bgcolor="#cccccc" colspan="2" height="16"><b><?=$m_primary_accounting_title?></b></td>
								</tr>
								<tr>
									<td>
										<?=$m_accounting_mode?>
									</td>
									<td>
										&nbsp;<?=$G_TAG_SCRIPT_START?>genSelect("acc_mode", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"], "on_change_account_mode(this)");<?=$G_TAG_SCRIPT_END?>									
									</td>
								</tr>
								<tr>
									<td>
										<?=$m_accounting_server?>
									</td>
									<td>
										&nbsp;<input type="text" class="text" id="acc_srv_1" name="acc_srv_1" value="" size="16" />
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<?=$m_accounting_port?>
										&nbsp;
										<input type="text" class="text" id="acc_port_1" name="acc_port_1" value="" size="5" />
									</td>
								</tr>
								<tr>
									<td>
										<?=$m_accounting_secret?>
									</td>
									<td>
										&nbsp;<input type="password" class="text" id="acc_sec_1" name="acc_sec_1" value="" size="40" />
									</td>
								</tr>
								<tr><td>&nbsp;</td>
									<td style=font-size:11px>(0-9,a-z,A-Z,~!@#$%^&*()_+`-={}[];'\:"|,./<>?)</td>
								</tr>
<? if(query("/runtime/web/display/accounting_server") =="0")	{echo "-->";} ?>									
<? if(query("/runtime/web/display/backup_accounting_server") =="0")	{echo "<!--";} ?>																
								<tr>
									<td bgcolor="#cccccc" colspan="2" height="16"><b><?=$m_secondary_accounting_title?></b></td>
								</tr>
								<tr>
									<td>
										<?=$m_accounting_server?>
									</td>
									<td>
										&nbsp;<input type="text" class="text" id="acc_srv_2" name="acc_srv_2" value="" size="16" />
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<?=$m_accounting_port?>
										&nbsp;
										<input type="text" class="text" id="acc_port_2" name="acc_port_2" value="" size="5" />
									</td>
								</tr>
								<tr>
									<td>
										<?=$m_accounting_secret?>
									</td>
									<td>
										&nbsp;<input type="password" class="text" id="acc_sec_2" name="acc_sec_2" value="" size="40" />
									</td>
								</tr>
								<tr><td>&nbsp;</td>
									<td style=font-size:11px>(0-9,a-z,A-Z,~!@#$%^&*()_+`-={}[];'\:"|,./<>?)</td>
								</tr>	
<? if(query("/runtime/web/display/backup_accounting_server") =="0")	{echo "-->";} ?>								
								</tbody>	
							</table>
						</fieldset>
					</td>
				</tr>

				<tr>
					<td colspan="2">
					<div id="mac_clone" style="display:none;">
					<fieldset >
							<legend><?=$m_clone_mac?></legend>
							<table width="100%" border="0">
								<tr>
									<td width="30%" id="td_left">
										<?=$m_enable?>
									</td>
									<td id="td_right">
										<?=$G_TAG_SCRIPT_START?>genCheckBox("clone_mac_enable", "on_click_clone_mac_enable()");<?=$G_TAG_SCRIPT_END?>
									</td>
								</tr>
								<tr>
									<td width="30%" id="td_left">
										<?=$m_mac_source?>
									</td>
									<td id="td_right">
										<?=$G_TAG_SCRIPT_START?>genSelect("mac_source", [1,0], ["<?=$m_auto?>","<?=$m_manual?>"],"on_change_mac_source()");<?=$G_TAG_SCRIPT_END?>
									</td>
								</tr>
								<tr>
									<td width="30%" id="td_left">
										<?=$m_mac_addr?>
									</td>
									<td id="td_right">
										<script>print_mac("clone_mac");</script>
										<input type="button" value="<?=$m_scan?>" name="scan_mac" onclick="do_scan_mac()">
									</td>
								</tr>
								<tr>
									<td  colspan="2" align="left">
										<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
											<tr class="list_head" align="left">
												<td width="40">
													&nbsp;
												</td>
												<td width="150">
													&nbsp;&nbsp;&nbsp;&nbsp;<?=$m_mac_addr?>
												</td>																																																															
											</tr>	
										</table>
										<div class="div_tab">
										<table id="scan_tab" width="100%" border="0"  <?=$G_TABLE_ATTR_CELL_ZERO?>>						
<?																					
$key=0;
while($key< $clone_list_row)
{
	$key++;
	echo $G_TAG_SCRIPT_START."page_table_clone(".$key.");".$G_TAG_SCRIPT_END;
}
?>
										</table>										
										</div>												
									</td>
								</tr>
							</table>
						</fieldset>
					</div>
					</td>
				</tr>	
				
				<tr>
					<td colspan="2">
<?=$G_APPLY_BUTTON?>
					</td>
				</tr>
			</table>
<!-- ________________________________  Main Content End _______________________________ -->
		</td>
	</tr>
</table>
<?
$wds_mac_index = 1;
while($wds_mac_index < 9)
{
	echo "<input type=hidden id=\"h_wds_mac".$wds_mac_index."\" name=\"h_wds_mac".$wds_mac_index."\" value=\"".query("wds/list/index:".$wds_mac_index."/mac")."\">\n";
	$wds_mac_index++;
}
?>
</form>
</body>
</html>
