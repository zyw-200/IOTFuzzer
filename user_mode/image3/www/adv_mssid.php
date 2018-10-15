<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_mssid";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_mssid";
set("/runtime/web/help_page",$MY_NAME);
/* --------------------------------------------------------------------------- */
if($ACTION_POST != "")
{
	require("/www/model/__admin_check.php");
	require("/www/__action_adv.php");
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
if($band_reload == 0 || $band_reload == 1) // change band
{ 	
	$cfg_band = $band_reload;
}
else
{
$cfg_band = query("/wlan/ch_mode");
}
$cfg_index = $cfg_band + 1;
$switch = query("/runtime/web/display/switchable");
if($switch == 1)
{
	anchor("/wlan/inf:1");
}
else
{
	if($cfg_band == 0) // 11g
	{	
		anchor("/wlan/inf:1");
	}
	else
	{
		$cfg_band = 1;
		anchor("/wlan/inf:2");	
	}
}

$runtime_backup_radius_server = query("/runtime/web/display/backup_radius_server");
$runtime_mssid_support_sharedkey = query("/runtime/web/display/mssid_support_sharedkey");
$cfg_ssid = get("j", "ssid");
$cfg_ssid_hidden = query("ssidhidden");
$cfg_auth = query("authentication");
$cfg_cipher = query("wpa/wepmode");
$security_type = $cfg_auth;
if($cfg_auth=="2" || $cfg_auth=="4" || $cfg_auth=="6") {$security_type="2";} //eap
else if ($cfg_auth=="3" || $cfg_auth=="5" || $cfg_auth=="7") {$security_type="3";} //psk

$cfg_wep_defkey	= query("defkey");
$cfg_wep1_keytype = query("wepkey:1/keyformat");/* 1:ASCII 2:HEX */
$cfg_wep2_keytype = query("wepkey:2/keyformat");
$cfg_wep3_keytype = query("wepkey:3/keyformat");
$cfg_wep4_keytype = query("wepkey:4/keyformat");
$cfg_wep1_length = query("wepkey:1/keylength");
$cfg_wep2_length = query("wepkey:2/keylength");
$cfg_wep3_length = query("wepkey:3/keylength");
$cfg_wep4_length = query("wepkey:4/keylength");
$cfg_wep1		= queryEnc("wepkey:1");
$cfg_wep2		= queryEnc("wepkey:2");
$cfg_wep3		= queryEnc("wepkey:3");
$cfg_wep4		= queryEnc("wepkey:4");
$cfg_wpapsk		= queryEnc("wpa/wpapsk");
$cfg_enable_rekey	= query("autorekey/ssid/enable");
$cfg_time_interuol = query("/wlan/inf:1/autorekey/time");
$cfg_start_time =query("/wlan/inf:1/autorekey/starttime");
$cfg_start_week =query("/wlan/inf:1/autorekey/startweek");
if($cfg_band == 0)
{
	$cfg_rekey		= get("j","/runtime/wlan/inf:1/wpa/wpapsk");
}
else
{
	$cfg_rekey      = get("j","/runtime/wlan/inf:2/wpa/wpapsk");
}
$cfg_key_interval	= query("wpa/grp_rekey_interval");
$cfg_1x_key_interval = query("d_wep_rekey_interval");
$cfg_radius_type = query("wpa/embradius/state");
$cfg_radius_srv_1	= query("wpa/radiusserver");
$cfg_radius_port_1	= query("wpa/radiusport");
$cfg_radius_sec_1	= queryEnc("wpa/radiussecret");
$cfg_radius_srv_2	= query("wpa/b_radiusserver");
$cfg_radius_port_2	= query("wpa/b_radiusport");
$cfg_radius_sec_2	= queryEnc("wpa/b_radiussecret");
$cfg_acc_mode	= query("wpa/acctstate");
$cfg_acc_srv_1	= query("wpa/acctserver");
$cfg_acc_port_1	= query("wpa/acctport");
$cfg_acc_sec_1	= queryEnc("wpa/acctsecret");
$cfg_acc_srv_2	= query("wpa/b_acctserver");
$cfg_acc_port_2	= query("wpa/b_acctport");
$cfg_acc_sec_2	= queryEnc("wpa/b_acctsecret");
$cfg_wmm = query("wmm/enable");
$cap_v1 = query("/runtime/web/display/cap_v1");
$cap_v2 = query("/runtime/web/display/cap_v2");
$cfg_captival_profile = query("captival/state");
$cfg_local = query("/captival/auth:1/state");
$cfg_radius = query("/captival/auth:2/state");
$cfg_ldap = query("/captival/auth:3/state");
$cfg_pop3 = query("/captival/auth:4/state");
$cfg_ticket = query("/captival/auth:7/state");
$cfg_mssid_state = query("multi/state");
$cfg_priority_enable = query("multi/pri_by_ssid");
$cfg_priority = query("pri_bit");

$cfg_ap_mode	=query("ap_mode");
$flag_autorekey = query("/runtime/web/display/autorekey");
$flag_msg_autorekey =query("/runtime/web/msg_autorekey");
$cfg_wl_enable = query("enable");
$cfg_wlmode = query("wlmode");
$cfg_anch = $cfg_band+1;
set("/runtime/web/msg_autorekey","none" );
echo "-->";
/* --------------------------------------------------------------------------- */
?>


<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>
var w_auth = "";
var w_cipher = "";
var w_wpa_mode = "";
var check_apmode_msg = 0;
var start_time = "<?=$cfg_start_time?>";
var shour="";
var smin="";

var mslist_radius = [['index','ms_ra_type','ms_ra_srv_1','ms_ra_port_1','ms_ra_sec_1','ms_ra_srv_2','ms_ra_port_2','ms_ra_sec_2']
<?
for("multi/index")
{
	echo ",\n['".$@."','".query("embradius_state")."','".query("radius_server")."','".query("radius_port")."','".queryEnc("radius_secret")."','".
	query("b_radius_server")."','".query("b_radius_port")."','".queryEnc("b_radius_secret")."']";
}
?>
];

var mslist_acct = [['index','ms_acct_state','ms_acc_srv_1','ms_acc_port_1','ms_acc_sec_1','ms_acc_srv_2','ms_acc_port_2','ms_acc_sec_2']
<?
for("multi/index")
{
	echo ",\n['".$@."','".query("acct_state")."','".query("acct_server")."','".query("acct_port")."','".queryEnc("acct_secret")."','".
	query("b_acct_server")."','".query("b_acct_port")."','".queryEnc("b_acct_secret")."']";
}
?>
];

var mslist_main = [['index','ms_ssid','ms_ssid_hidden','ms_auth','ms_priority','ms_wmm','ms_captival_profile']
<?
for("multi/index")
{
	echo ",\n['".$@."','".get("j","ssid")."','".query("ssid_hidden")."','".query("auth")."','".
	query("pri_bit")."','".query("wmm/enable")."','".query("captival/state")."']";
}
?>
];

var mslist_wep = [['index','ms_cipher','ms_key_idx','ms_gkui','ms_kui','ms_passphrase','ms_enable_rekey']
<?
for("multi/index")
{
	echo ",\n['".$@."','".query("cipher")."','".query("wep_key_index")."','".query("interval")."','".
	query("d_wep_rekey_interval")."','".queryEnc("passphrase")."','".query("autorekey/enable")."']";
}
?>
];

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
			if(f.f_ssid_select.value != "" && f.f_ssid_select.value!=0)
			{
				
				if("<?=$security_type?>"==3 && "<?=$cfg_enable_rekey?>" ==1)
				{
					f.sweek.disabled=true;
					f.shour.disabled=true;
					f.smin.disabled=true;
					f.time_interuol.disabled=true;
				}
				else
				{
					if(f.index.value!=0)
					{
						f.shour.disabled=false;
						f.smin.disabled=false;
						f.time_interuol.disabled=false;
						f.sweek.disabled=false;
					}
					else
					{
				f.shour.disabled=true;
				f.smin.disabled=true;
						f.time_interuol.disabled=true;
						f.sweek.disabled=true;
					}
				}
				f.rekey.disabled=true;
			}
		}	
	}
	AdjustHeight();
}
function on_change_band(s)
{
	var f = get_obj("frm");
	self.location.href = "adv_mssid.php?band_reload=" + s.value;
}
function on_obj_disable()
{
	var f = get_obj("frm");
	
	if(f.priority_enable != null)
	{
		f.priority_enable.disabled  = true;
	}
	if(f.priority != null)
	{
		f.priority.disabled  = true;
	}
	f.index.disabled = true;	
	f.ssid.disabled = true;
	f.ap_hidden.disabled = true;
	w_auth.disabled = true;
	f.wmm.disabled = true;
	if(f.captival_profile != null)
		f.captival_profile.disabled = true;
	f.encrypt[0].disabled = true;
	f.encrypt[1].disabled = true;
	f.keytype.disabled = true;	
	f.keysize.disabled = true;
	f.defkey.disabled = true;
	f.key.disabled = true;
	f.confirm_key.disabled = true;
	w_wpa_mode.disabled = true;
	w_cipher.disabled = true;
	f.gkui.disabled = true;
	f.kui.disabled = true;
	f.passphrase.disabled = true;	
	f.confirm_passphrase.disabled = true;
	if("<?=$flag_autorekey?>"==1){	
	f.enable_rekey[0].disabled = true;
	f.enable_rekey[1].disabled = true;		
	f.time_interuol.disabled = true;
	f.rekey.disabled = true;
	f.shour.disabled = true;
	f.smin.disabled = true;
	f.sweek.disabled = true;}
        if(f.radius_type != null)
	f.radius_type[0].disabled = true;	
        if(f.radius_type != null)	
	f.radius_type[1].disabled = true;	
	f.radius_srv_1.disabled = true;
	f.radius_port_1.disabled = true;
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
}

function on_click_priority_enable(s)
{
	var f = get_obj("frm");

	f.priority.disabled = true;	
	
	if(f.priority_enable.checked)
	{
		f.priority.disabled = false;	
	}
}

function on_click_mssid_enable(s)
{
	var f = get_obj("frm");
		
	on_obj_disable();	

	if(s.checked)
	{
		if("<?=$cfg_auth?>"=="1")
		{
			alert("<?=$a_auth_to_open?>");
		}
		if(("<?=$cfg_ap_mode?>" =="1" ||"<?=$cfg_ap_mode?>" =="2" ||"<?=$cfg_ap_mode?>" =="4")&& check_apmode_msg == 0)
		{
		 	alert("<?=$a_invalid_apmode?>");
			check_apmode_msg = 1;
		}		
		f.index.disabled = false;
		if(f.index.value != 0)
		{
			f.index.disabled = false;	
			f.ssid.disabled = false;
			f.ap_hidden.disabled = false;
			w_auth.disabled = false;

			if("<?=$cfg_wlmode?>" =="1" || "<?=$cfg_wlmode?>" =="3" ||  "<?=$cfg_wlmode?>" =="4")
			{
				f.wmm.value = 1;
                		f.wmm.disabled = true;
			}	
			else
			{
				f.wmm.disabled = false;
			}
			if(f.captival_profile != null)
			{
				if("<?=$cfg_ticket?>" != 1 && "<?=$cfg_local?>" != 1 && "<?=$cfg_radius?>" != 1 && "<?=$cfg_ldap?>" != 1 && "<?=$cfg_pop3?>" != 1)
					f.captival_profile.disabled = true;
				else
					f.captival_profile.disabled = false;
			}
			f.encrypt[0].disabled = false;
			f.encrypt[1].disabled = false;
			f.keytype.disabled = false;	
			f.keysize.disabled = false;
			f.defkey.disabled = false;
			f.key.disabled = false;
			f.confirm_key.disabled = false;
			w_wpa_mode.disabled = false;
			w_cipher.disabled = false;
			f.gkui.disabled = false;
			f.kui.disabled = false;
			f.passphrase.disabled = false;	
			f.confirm_passphrase.disabled = false;	
			if("<?=$flag_autorekey?>"==1){		
			f.enable_rekey[0].disabled = false;
			f.enable_rekey[1].disabled = false;		
				
				if(f.index.value==0 || "<?=$cfg_enable_rekey?>" ==1)
				{
					f.shour.disabled=true;
					f.smin.disabled=true;
					f.sweek.disabled=true;
					f.time_interuol.disabled = true;
	            }		
	            else
	            {
	            	f.shour.disabled=false;
					f.smin.disabled=false;
					f.sweek.disabled=false;
					f.time_interuol.disabled = false;
	            }
      		}
			f.radius_srv_1.disabled = false;
			f.radius_port_1.disabled = false;
			f.radius_sec_1.disabled = false;
			if(f.radius_srv_2 != null)
				f.radius_srv_2.disabled = false;
			if(f.radius_port_2 != null)
				f.radius_port_2.disabled = false;
			if(f.radius_sec_2 != null)
				f.radius_sec_2.disabled = false;		
			if(f.acc_mode != null)		
				f.acc_mode.disabled = false;
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
			if(f.radius_type != null)
			f.radius_type[0].disabled = false;	
                        if(f.radius_type != null)	
			f.radius_type[1].disabled = false;					
		}
		
		if(f.priority_enable != null)
		{
			f.priority_enable.disabled = false;
			on_click_priority_enable(f.priority_enable);
		}
		
		on_change_authentication(w_auth);	
	}	
	else
	{
		if(f.priority_enable != null)
		{
			f.priority_enable.disabled = true;
			f.priority_enable.checked = false;
			f.priority.disabled = true;
		}		
	}

}

function on_click_encryption()
{
	var f = get_obj("frm");
	if(f.index.value != 0)
	{
		f.keytype.disabled = false;
		f.keysize.disabled = false;
		f.defkey.disabled = false;
		f.key.disabled = false;	
		f.confirm_key.disabled = false;
	}	
	if(f.encrypt[0].checked)
	{
		f.keytype.disabled = true;
		f.keysize.disabled = true;
		f.defkey.disabled = true;
		f.key.disabled = true;
		f.confirm_key.disabled = true;
	}	
}


function on_change_index(s)
{
	var f=get_obj("frm")
	f.f_ssid_select.value= f.index.value;	
	f.f_mssid_enable.value		=(f.mssid_enable.checked ? "1":"0");
	if(f.priority_enable != null)
	{	
		f.f_priority_enable.value		=(f.priority_enable.checked ? "1":"0");	
	}
	init();		
}
function edit_mssid_setting(index)
{
	var f=get_obj("frm")
	f.f_ssid_select.value= index;	
	f.f_mssid_enable.value		=(f.mssid_enable.checked ? "1":"0");
	if(f.priority_enable != null)
	{	
		f.f_priority_enable.value		=(f.priority_enable.checked ? "1":"0");	
	}
	init();		
}
function on_change_ap_hidden(s)
{
}

function on_change_wmm(s)
{
}

function on_change_authentication(s)
{
	var f = get_obj("frm");
	var eap_psk_table_title_obj = get_obj("eap_psk_table_title");
	
	get_obj("wep_key_setting_fieldset").style.display = "none";
	get_obj("eap_psk_fieldset").style.display = "none";
	get_obj("psk_setting").style.display = "none";
	get_obj("eap_setting").style.display = "none";

	get_obj("wpa_setting").style.display = "none";
	get_obj("dot_1x_setting").style.display = "none";
	if("<?=$flag_autorekey?>"==1)
	{
	get_obj("periodrical_key_change_setting").style.display = "none";
	}		
	if(f.index.value != 0)
	{
		f.encrypt[0].disabled = false;	
	}
	
	if(s.value == 0 || s.value == 1 || s.value == 8) // open, shared, both
	{
		get_obj("wep_key_setting_fieldset").style.display = "";
		if(s.value != 0)
		{
			f.encrypt[0].disabled = true;
			f.encrypt[1].checked = true;
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
		if("<? echo query("/runtime/web/display/local_radius_server");?>"==1)
		{
			on_change_radius_type();
		}
	}
	else
	{
		get_obj("eap_psk_fieldset").style.display = "";
		get_obj("wpa_setting").style.display = "";
		if(s.value == 2) //eap
		{
			get_obj("eap_setting").style.display = "";	
			if(f.acc_mode != null && f.radius_type == null)
			{
				on_change_account_mode(f.acc_mode);
			}
			if("<? echo query("/runtime/web/display/local_radius_server");?>"==1)
			{
				on_change_radius_type();
			}
			eap_psk_table_title_obj.innerHTML = "<?=$m_radius_field_title?>";
		}	
		else //psk
		{
			get_obj("psk_setting").style.display = "";	
			if("<?=$flag_autorekey?>"==1)
			{	
			if("<?=$cfg_ap_mode?>" !=4 && "<?=$cfg_ap_mode?>" !=3 && "<?=$cfg_ap_mode?>" != 1)
			{
				get_obj("periodrical_key_change_setting").style.display = "";	
			}
			else
			{
				f.enable_rekey[0].checked=true;	
			}
			}
			eap_psk_table_title_obj.innerHTML = "<?=$m_passphrase_field_title?>";		
		}
	}
	on_change_size();
	if(f.index.value != 0)
	{
		if("<?=$cfg_ap_mode?>" =="1")
		{
			f.gkui.disabled=true;
		}
		else
		{
			f.gkui.disabled=false;
		}
	}
	if("<?=$flag_autorekey?>"==1){	
	on_click_enable_rekey();}
	AdjustHeight();
}

function on_change_key_type(s)
{
	//alert(s.options[s.selectedIndex].text);
}

function on_change_key_size(s)
{
	//alert(s.options[s.selectedIndex].text);
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

function on_change_cipher(s)
{
	//alert(s.options[s.selectedIndex].text);
}
function on_change_radius_type()
{
	var f = get_obj("frm");
	if(f.index.value != 0)
	{
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
			if(f.acc_mode != null)		
			on_change_account_mode(f.acc_mode);		
		}
	}
}	
function on_change_account_mode(s)
{
	var f = get_obj("frm");
	if(f.index.value != 0)
	{
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
	}
	
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
	
function on_change_mssid_table_height()
{
	var x = get_obj("mssid_tab").offsetHeight;
	
	if(get_obj("adjust_td") != null)
	{
		if(x <= 120)
			get_obj("adjust_td").width="70";
		else
			get_obj("adjust_td").width="50";
	}
}	

function print_rule_del(id)
{
	var str="";

	str+="<a href='javascript:mssid_del_confirm(\""+id+"\")'><img src='/pic/delete.jpg' border=0></a>";

	document.write(str);
}

function mssid_del_confirm(id)
{
	var f = get_obj("frm");
	if(confirm("<?=$a_mssid_del_confirm?>")==false) return;
	f.f_mssid_del.value = id;
	fields_disabled(f, false);
	get_obj("frm").submit();
}
	
/* page init functoin */

function init()
{
	var f = get_obj("frm");
	get_obj("auth").style.display = "none";
	get_obj("auth_11n_only").style.display = "none";
	get_obj("auth_wds").style.display = "none";	
	get_obj("auth_wds_mssid").style.display = "none";		
	get_obj("cipher").style.display = "none";
	get_obj("cipher_11n_only").style.display = "none";
	get_obj("cipher_wds").style.display = "none";	
	get_obj("wpa_mode").style.display = "none";
	get_obj("wpa_mode_wds").style.display = "none";	

	select_index(f.band, "<?=$cfg_band?>");
	if("<?=$check_band?>" == "")
		f.band.disabled = true;
	if(f.f_ssid_select.value == "")
	{
		f.mssid_enable.checked = <? if ($cfg_mssid_state=="1") {echo "true";} else {echo "false";}?>;
		if(f.priority_enable != null)
		{
			f.priority_enable.checked = <? if ($cfg_priority_enable=="1") {echo "true";} else {echo "false";}?>;
		}
	}
	else
	{	
		f.mssid_enable.value		=	f.f_mssid_enable.value;
		if(f.priority_enable != null)
		{		
			f.priority_enable.value		=	f.f_priority_enable.value;
		}
	}			
	on_change_mssid_table_height();
	
	if("<?=$cfg_ap_mode?>" == 3) //wds with ap
	{
		get_obj("auth_wds_mssid").style.display = "";
		w_auth = get_obj("auth_wds_mssid");
					
		get_obj("cipher_wds").style.display = "";			
		w_cipher = get_obj("cipher_wds");
		
		get_obj("wpa_mode_wds").style.display = "";	
		w_wpa_mode = get_obj("wpa_mode_wds");		
	}
	else // Access
	{
		if("<?=$cfg_wlmode?>" == "3")
		{
			get_obj("auth_11n_only").style.display = "";
			w_auth = get_obj("auth_11n_only");
		}
		else
		{
			get_obj("auth").style.display = "";
			w_auth = get_obj("auth");
		}
		if("<?=$cfg_wlmode?>" == "3")
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
	
	if(f.f_ssid_select.value == "" || f.f_ssid_select.value == "0")
	{
		f.ssid.value = "<?=$cfg_ssid?>";
		select_index(f.ap_hidden, "<?=$cfg_ssid_hidden?>");
		select_index(w_auth, "<?=$security_type?>");
		if(f.priority != null)
		{
			select_index(f.priority, "<?=$cfg_priority?>");
		}	
		select_index(f.wmm, "<?=$cfg_wmm?>");
		if(f.captival_profile != null)
		{
			select_index(f.captival_profile, "<?=$cfg_captival_profile?>");
			if("<?=$cfg_captival_profile?>" != 1 && "<?=$cfg_captival_profile?>" != 2 && "<?=$cfg_captival_profile?>" != 3 && "<?=$cfg_captival_profile?>" != 4 && "<?=$cfg_captival_profile?>" != 7)
				select_index(f.captival_profile, 0);
			f.captival_profile.disabled = true;
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

		if("<?=$cfg_auth?>" == "2"  || "<?=$cfg_auth?>" == "3" )
			w_wpa_mode.value = 2;
		else if("<?=$cfg_auth?>" == "4" || "<?=$cfg_auth?>" == "5")
			w_wpa_mode.value = 1;
		else if("<?=$cfg_auth?>" == "6" || "<?=$cfg_auth?>" == "7")
			w_wpa_mode.value = 0;	
		
		select_index(w_cipher, "<?=$cfg_cipher?>");
	
		f.gkui.value = "<?=$cfg_key_interval?>";	
		f.kui.value = "<?=$cfg_1x_key_interval?>";
		if("<?=$flag_autorekey?>"==1)
		{
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
                }
		f.passphrase.value = "<?=$cfg_wpapsk?>";
		f.confirm_passphrase.value = "<?=$cfg_wpapsk?>";
		if(f.radius_type != null)
		{
			if("<?=$cfg_radius_type?>" == 0)
				f.radius_type[0].checked = true;
			else
				f.radius_type[1].checked = true;	
		}
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
	}
	else
	{
		select_index(f.index, f.f_ssid_select.value);	
		f.ssid.value = mslist_main[f.index.value][1];
		select_index(f.ap_hidden, mslist_main[f.index.value][2]);
		var ms_auth = mslist_main[f.index.value][3];
		
		if(ms_auth == "")
			select_index(w_auth, 0);
		else if(ms_auth == "2" || ms_auth == "4" || ms_auth == "6")
			select_index(w_auth, 2);
		else if (ms_auth == "3" || ms_auth == "5" || ms_auth == "7")
			select_index(w_auth, 3);
		else
			select_index(w_auth, ms_auth);	
			
	
		if(f.priority_enable != null)
		{
			select_index(f.priority, mslist_main[f.index.value][4]);
		}
		select_index(f.wmm, mslist_main[f.index.value][5]);
		if(f.captival_profile != null)
		{
			select_index(f.captival_profile, mslist_main[f.index.value][6]);
			if(mslist_main[f.index.value][6] != 1 && mslist_main[f.index.value][6] != 2 && mslist_main[f.index.value][6] != 3 && mslist_main[f.index.value][6] != 4 && mslist_main[f.index.value][6] != 7)
				select_index(f.captival_profile, 0);
			if("<?=$cfg_ticket?>" != 1 && "<?=$cfg_local?>" != 1 && "<?=$cfg_radius?>" != 1 && "<?=$cfg_ldap?>" != 1 && "<?=$cfg_pop3?>" != 1)
				f.captival_profile.disabled = true;
			else
				f.captival_profile.disabled = false;
		}
		if(mslist_wep[f.index.value][1] == 0)
			f.encrypt[0].checked = true;
		else
			f.encrypt[1].checked = true;		

		select_index(f.defkey, mslist_wep[f.index.value][2]);
	
		if(f.defkey.value == "1")
		{
			select_index(f.keytype, "<?=$cfg_wep1_keytype?>");
			select_index(f.keysize, "<?=$cfg_wep1_length?>");
			f.key.value = "<?=$cfg_wep1?>";
			f.confirm_key.value = "<?=$cfg_wep1?>";
			
		}	
		else if(f.defkey.value == "2")
		{
			select_index(f.keytype, "<?=$cfg_wep2_keytype?>");
			select_index(f.keysize, "<?=$cfg_wep2_length?>");			
			f.key.value = "<?=$cfg_wep2?>";
			f.confirm_key.value = "<?=$cfg_wep2?>";
		}
		else if(f.defkey.value == "3")
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

		if( mslist_main[f.index.value][3] == "2"  ||  mslist_main[f.index.value][3] == "3" )
			w_wpa_mode.value = 2;
		else if( mslist_main[f.index.value][3] == "4" ||  mslist_main[f.index.value][3] == "5")
			w_wpa_mode.value = 1;
		else if( mslist_main[f.index.value][3] == "6" ||  mslist_main[f.index.value][3] == "7")
			w_wpa_mode.value = 0;	
		
		select_index(w_cipher, mslist_wep[f.index.value][1]);
	
		f.gkui.value = mslist_wep[f.index.value][3];	
		f.kui.value = mslist_wep[f.index.value][4];	
		if("<?=$flag_autorekey?>"==1)
		{
		if(mslist_wep[f.index.value][6]!="1")
		{
			f.enable_rekey[0].checked=true;
		}
		else
		{
			f.enable_rekey[1].checked=true;
		}
		f.time_interuol.value="<?=$cfg_time_interuol?>";
			f.rekey.value = get_obj("ms_rekey"+f.index.value).value;
		}
		f.passphrase.value = f.confirm_passphrase.value = mslist_wep[f.index.value][5];
		if(f.radius_type != null)
		{
			if(mslist_radius[f.index.value][1] == 0)
				f.radius_type[0].checked = true;
			else
				f.radius_type[1].checked = true;	
		}
		f.radius_srv_1.value = mslist_radius[f.index.value][2];
		f.radius_port_1.value = mslist_radius[f.index.value][3];
		f.radius_sec_1.value = mslist_radius[f.index.value][4];
		if(f.radius_srv_2 != null)
			f.radius_srv_2.value = mslist_radius[f.index.value][5];
		if(f.radius_port_2 != null)	
			f.radius_port_2.value = mslist_radius[f.index.value][6];
		if(f.radius_sec_2 != null)	
		{
			if(mslist_radius[f.index.value][7] == "")
			{
				f.radius_sec_2.value = "";
			}
			else
			{
				f.radius_sec_2.value = mslist_radius[f.index.value][7];
			}
		}
	
		if(f.acc_mode != null)	
			select_index(f.acc_mode, mslist_acct[f.index.value][1]);
		if(f.acc_srv_1 != null)	
			f.acc_srv_1.value = mslist_acct[f.index.value][2];
		if(f.acc_port_1 != null)	
			f.acc_port_1.value = mslist_acct[f.index.value][3];
		if(f.acc_sec_1 != null)		
			f.acc_sec_1.value = mslist_acct[f.index.value][4];
		if(f.acc_srv_2 != null)		
				f.acc_srv_2.value = mslist_acct[f.index.value][5];
		if(f.acc_port_2 != null)	
				f.acc_port_2.value = mslist_acct[f.index.value][6];
		if(f.acc_sec_2 != null)	
		{
			if(mslist_acct[f.index.value][7] == "")
			{
				f.acc_sec_2.value = "";
			}
			else
			{
				f.acc_sec_2.value = mslist_acct[f.index.value][7];
			}
		}
	}

	on_click_mssid_enable(f.mssid_enable);
	
	if(f.priority_enable != null)
	{
		on_click_priority_enable(f.priority_enable)
	}
	AdjustHeight();
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
/* parameter checking */
function check()
{
	// do check here ....
		if("<?=$flag_autorekey?>"==1)
		    {
		    	for(var i=1;i<8;i++)
		    	{
		    		if(ms_enable_list[i][1]!=0)
		    		{
		    			if(ms_enable_list[i][2]==3 ||ms_enable_list[i][2]==5 ||ms_enable_list[i][2]==7 )
		    			{
		    				if("<?=$cfg_wl_enable?>"==1 && mslist_wep[i][6]==1 && "<?=$flag_msg_autorekey?>"=="display")
		    				{
		    					alert("<?=$a_check_mail_for_rekey?>");
		    				}
		    			}
		    		}
		    		
		    	}
			}
	return true;
}

function submit()
{
	if(check()) 
	{
		fields_disabled(get_obj("frm"), false);
		get_obj("frm").submit();
	}
}

function submit_do_add()
{
	if(do_add()){
		fields_disabled(get_obj("frm"),false);
		get_obj("frm").submit();
	}
}


var ms_key_index_list=[['index','key_index']
<?
for("multi/index")
{
	echo ",\n['".$@."','".query("wep_key_index")."']";
}
?>];

var ms_enable_list=[['index','state','auth']
<?
for("multi/index")
{
	echo ",\n['".$@."','".query("state")."','".query("auth")."']";
}
?>];
function do_add()
{
	var f=get_obj("frm");
	f.f_auth.value = w_auth.value;
	f.f_cipher.value = w_cipher.value;
	f.f_wpa_mode.value = w_wpa_mode.value;
	if(f.priority_enable !=null)
	{
	if(f.priority_enable.checked==true)
	{
		f.priority_enable.value=1;
	}
	else
	{
		f.priority_enable.value=0;
	}
	}
	if(f.index.value != 0) //not primary ssid
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
		
		f.f_encrypt.value = (f.encrypt[1].checked?1:0);
		
		if(f.f_auth.value == 0 || f.f_auth.value == 1 || f.f_auth.value == 8) // open ,shared ,both
		{
			if(f.f_encrypt.value == 1)
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

				var check_pri_ms_key_index = 0;
				
				if("<?=$cfg_cipher?>" == 1 && f.defkey.value == "<?=$cfg_wep_defkey?>" && check_pri_ms_key_index != 1 && check_key != f.key.value)
				{
					alert("<?=$a_key_index_matched_mssid_index?>");
					check_pri_ms_key_index = 1;					
				}

				for(var l=1; l < 8; l++)
				{
					if(ms_enable_list[l][1] == 1)
					{					
						if(ms_enable_list[l][2]==0 || ms_enable_list[l][2]==1 || ms_enable_list[l][2]==8)
						{
							if(f.index.value!=l)
							{
						if( f.defkey.value == ms_key_index_list[l][1] && check_pri_ms_key_index != 1 && check_key != f.key.value)
						{
							alert("<?=$a_key_index_matched_mssid_index?>");
							check_pri_ms_key_index = 1;
						}
					}
				}				
			}
		}	
			}
		}	
		else if(f.f_auth.value == 3) //psk
		{
			if(is_blank(f.gkui.value) ||is_blank_in_string(f.gkui.value)|| !is_digit(f.gkui.value) || (f.gkui.value < 300) || (f.gkui.value > 9999999))
			{
				alert("<?=$a_invalid_key_interval?>");
				f.gkui.focus();
				return false;
			}	
			if("<?=$flag_autorekey?>"==1)
            {		
			if(f.enable_rekey[0].checked==true)
			{			
			if(is_blank_in_first_end(f.passphrase.value))
			{
				alert("<?=$a_first_end_blank?>");
				f.passphrase.value="";
				f.passphrase.select();
				return false;
				}
				if(f.passphrase.value.length <8 || f.passphrase.value.length > 63)
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
							f.f_wpa_passphraseformat.value=0;
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
						f.f_wpa_passphraseformat.value=1;
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
				if(!is_digit(f.time_interuol.value) || f.time_interuol.value < 1 || f.time_interuol.value > 168)
				{
					alert("<?=$a_invalid_time_range?>");
					f.time_interuol.focus();
					return false;
				}	
			}
            }
            else
            {	
				if(is_blank_in_first_end(f.passphrase.value))
				{
					alert("<?=$a_first_end_blank?>");
					f.passphrase.value="";
					f.passphrase.select();
					return false;
				}
				if(f.passphrase.value.length <8 || f.passphrase.value.length > 63)
				{
					alert("<?=$a_invalid_psk_len?>");
					f.passphrase.value="";
					f.passphrase.select();
					return false;
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
			if("<?=$flag_autorekey?>"==1)
       		{								
			if(f.enable_rekey[0].checked==true)
			{	
				f.f_enable_rekey.value = 0;
			}
			else
			{
				f.f_enable_rekey.value = 1;
			}
			f.f_time_interuol.value = f.time_interuol.value;	
				f.f_start_time.value = f.shour.value+":"+f.smin.value;
				f.f_start_week.value = f.sweek.value;	
     		}        							
		}		
		else if(f.f_auth.value == 2 || f.f_auth.value == 9) //eap or 802.1x
		{
		if (f.f_auth.value == 2)
		{
			if(is_blank(f.gkui.value) ||is_blank_in_string(f.gkui.value)|| !is_digit(f.gkui.value) || (f.gkui.value < 300) || (f.gkui.value > 9999999))
			{
				alert("<?=$a_invalid_key_interval?>");
				f.gkui.focus();
				return false;
			}
		}
		if (f.f_auth.value == 9)
		{
			if(is_blank(f.kui.value) ||is_blank_in_string(f.kui.value)|| !is_digit(f.kui.value) || (f.kui.value < 300) || (f.kui.value > 9999999))
			{
				alert("<?=$a_invalid_1x_key_interval?>");
				f.kui.focus();
				return false;
			}		
		}
			var srv_obj = "radius";
			var radius_srv_number = 2;
		
			if("<?=$runtime_backup_radius_server?>" != "1")
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
			if(f.radius_port_2 != null)
			{
				if(f.radius_port_2.value != "" && !is_valid_port_str(f.radius_port_2.value))
				{
					alert("<?=$a_invalid_radius_port?>");
					f.radius_port_2.select();
					return false;
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
			}
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
					if(f.acc_port_2 != null)
					{
						if(f.acc_port_2.value != "" && !is_valid_port_str(f.acc_port_2.value))
						{
							alert("<?=$a_invalid_acc_port?>");
							f.acc_port_2.select();
							return false;
						}
					}
					if(f.acc_sec_1.value != "" && f.acc_mode.value==1)
					{
						if(f.acc_sec_1.value.length<1 || f.acc_sec_1.value.length>64)
						{
							alert("<?=$a_invalid_secret_len?>");
							f.acc_sec_1.select();
							return false;
						}
					}
					if(f.acc_sec_2.value !="" && f.acc_mode.value==1)
					{
						if(f.acc_sec_2.value.length<1 || f.acc_sec_2.value.length>64)
						{
							alert("<?=$a_invalid_secret_len?>");
							f.acc_sec_2.select();
							return false;
						}
					}
				}	
			}
			<? if(query("/runtime/web/display/local_radius_server") =="1")	
			{	echo "}";}//remote radius server, need check
			?>	
		}	
		else
		{
			alert("<?=$a_unknown_auth?>");
			return false;
		}			
		
		if(f.f_auth.value == 0 || f.f_auth.value == 1 || f.f_auth.value == 8) //open, shared, both
		{
			if(f.f_encrypt.value == 0)
				f.f_cipher.value = 0; //cipher = none
			else
				f.f_cipher.value = 1; // cipher = wep	
		}
		else if(f.f_auth.value == 3) //psk
		{
			if(f.f_wpa_mode.value == 0)
				f.f_auth.value = 7; //:wap2-auto-psk
			else if(f.f_wpa_mode.value == 1)	
				f.f_auth.value = 5; //wpa2-psk 
			else
			f.f_auth.value = 3; //wpa-psk	
		}
		else if(f.f_auth.value == 2) //eap
		{
			if(f.f_wpa_mode.value == 0)
				f.f_auth.value = 6; //:wap2-auto-eap
			else if(f.f_wpa_mode.value == 1)	
				f.f_auth.value = 4; //wpa2-eap 
			else
				f.f_auth.value = 2; //wpa-eap	
		}	
						
	}
	
	f.f_add.value = 1;
	
	return true;
	//get_obj("frm").submit();	
	
}
</script>

<body <?=$G_BODY_ATTR?> onload="init();">

<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">

<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="f_mssid_del"		value="">
<input type="hidden" name="f_ssid_select"		value="">
<input type="hidden" name="f_mssid_enable"		value="">
<input type="hidden" name="f_priority_enable"		value="">
<input type="hidden" name="f_wpa_mode"	value="">
<input type="hidden" name="f_auth"		value="">
<input type="hidden" name="f_cipher"	value="">
<input type="hidden" name="f_encrypt"	value="">
<input type="hidden" name="f_add"			value="">
<input type="hidden" name="f_enable_rekey"		value="">
<input type="hidden" name="f_time_interuol"		value="">
<input type="hidden" name="f_start_time" value="">
<input type="hidden" name="f_start_week" value="">
<input type="hidden" name="f_wpa_passphraseformat" value="">
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
					<td colspan="2">
						<?=$G_TAG_SCRIPT_START?>genCheckBox("mssid_enable", "on_click_mssid_enable(this)");<?=$G_TAG_SCRIPT_END?>
						<?=$m_mssid_enable?>
<? if(query("/runtime/web/display/priority") != "1")	{echo "<!--";} ?>							
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<?=$G_TAG_SCRIPT_START?>genCheckBox("priority_enable", "on_click_priority_enable(this)");<?=$G_TAG_SCRIPT_END?>
						<?=$m_priority_enable?>		
<? if(query("/runtime/web/display/priority") != "1")	{echo "-->";} ?>		
					</td>
				</tr>	
						
				<tr colspan="2">
					<td>									
						<fieldset>		
							<legend><?=$m_wireless_setting_title?></legend>
							<table width="100%" border="0">	
								<tr>
									<td width="30%">
										<?=$m_band?>
									</td>
									<td>
										<?=$G_TAG_SCRIPT_START?>genSelect("band", [0,1], ["<?=$m_band_2_4G?>","<?=$m_band_5G?>"], "on_change_band(this)");<?=$G_TAG_SCRIPT_END?>
									</td>
								</tr>
								<tr>
									<td width="30%">
										<?=$m_index?>
									</td>
<? if(query("/runtime/web/display/mssid_index4") == "1")	{echo "<!--";} ?>										
									<td>
										<?=$G_TAG_SCRIPT_START?>genSelect("index", [0,1,2,3,4,5,6,7], ["<?=$m_pri_ssid?>","<?=$m_ms_ssid1?>","<?=$m_ms_ssid2?>","<?=$m_ms_ssid3?>","<?=$m_ms_ssid4?>","<?=$m_ms_ssid5?>","<?=$m_ms_ssid6?>","<?=$m_ms_ssid7?>"], "on_change_index(this)");<?=$G_TAG_SCRIPT_END?>
									</td>
<? if(query("/runtime/web/display/mssid_index4") == "1")	{echo "-->";} ?>										
<? if(query("/runtime/web/display/mssid_index4") != "1")	{echo "<!--";} ?>										
									<td>
										<?=$G_TAG_SCRIPT_START?>genSelect("index", [0,1,2,3], ["<?=$m_pri_ssid?>","<?=$m_ms_ssid1?>","<?=$m_ms_ssid2?>","<?=$m_ms_ssid3?>"], "on_change_index(this)");<?=$G_TAG_SCRIPT_END?>
									</td>
<? if(query("/runtime/web/display/mssid_index4") != "1")	{echo "-->";} ?>									
								</tr>
								<tr>
									<td>
										<?=$m_ssid?>
									</td>
									<td>
										<input name="ssid" id="ssid" class="text" type="text" size="20" maxlength="32" value="">
									</td>
								</tr>	
								<tr>
									<td>
										<?=$m_ap_hidden?>
									</td>
									<td>
										<?=$G_TAG_SCRIPT_START?>genSelect("ap_hidden", [1,0], ["<?=$m_disable?>","<?=$m_enable?>"], "on_change_ap_hidden(this)");<?=$G_TAG_SCRIPT_END?>
									</td>
								</tr>
								<tr>
									<td>
										<?=$m_auth?>
									</td>
<? if(query("/runtime/web/display/mssid_support_sharedkey") != "1")	{echo "<!--";} ?>	
									<td>
										<?=$G_TAG_SCRIPT_START?>
										genSelect("auth", [0,1,3,2,9], ["<?=$m_auth_open?>","<?=$m_auth_shared?>","<?=$m_auth_wpaper?>","<?=$m_auth_wpaent?>","<?=$m_auth_1x?>"], "on_change_authentication(this)");
										genSelect("auth_wds", [0,1,3,2], ["<?=$m_auth_open?>","<?=$m_auth_shared?>","<?=$m_auth_wpaper?>","<?=$m_auth_wpaent?>"], "on_change_authentication(this)");
										genSelect("auth_wds_mssid", [0,3], ["<?=$m_auth_open?>","<?=$m_auth_wpaper?>"], "on_change_authentication(this)");
										<?=$G_TAG_SCRIPT_END?>
									</td>
<? if(query("/runtime/web/display/mssid_support_sharedkey") != "1") {echo "-->";} ?>	
<? if(query("/runtime/web/display/mssid_support_sharedkey") =="1")	{echo "<!--";} ?>	
									<td>
										<?=$G_TAG_SCRIPT_START?>
										genSelect("auth", [0,3,2,9], ["<?=$m_auth_open?>","<?=$m_auth_wpaper?>","<?=$m_auth_wpaent?>","<?=$m_auth_1x?>"], "on_change_authentication(this)");
										genSelect("auth_11n_only", [0,3,2], ["<?=$m_auth_open?>","<?=$m_auth_wpaper?>","<?=$m_auth_wpaent?>"], "on_change_authentication(this)");
										genSelect("auth_wds", [0,3,2], ["<?=$m_auth_open?>","<?=$m_auth_wpaper?>","<?=$m_auth_wpaent?>"], "on_change_authentication(this)");
										genSelect("auth_wds_mssid", [0,3], ["<?=$m_auth_open?>","<?=$m_auth_wpaper?>"], "on_change_authentication(this)");
										<?=$G_TAG_SCRIPT_END?>
									</td>
<? if(query("/runtime/web/display/mssid_support_sharedkey") =="1") {echo "-->";} ?>	
								</tr>							
<? if(query("/runtime/web/display/priority") != "1")	{echo "<!--";} ?>	
								<tr>
									<td>
										<?=$m_priority?>
									</td>
									<td>
										<?=$G_TAG_SCRIPT_START?>genSelect("priority", [0,1,2,3,4,5,6,7], ["<?=$m_0?>","<?=$m_1?>","<?=$m_2?>","<?=$m_3?>","<?=$m_4?>","<?=$m_5?>","<?=$m_6?>","<?=$m_7?>"]);<?=$G_TAG_SCRIPT_END?>
									</td>
								</tr>	
<? if(query("/runtime/web/display/priority") != "1") {echo "-->";} ?>																						
								<tr>
									<td>
										<?=$m_wmm?>
									</td>
									<td>
										<?=$G_TAG_SCRIPT_START?>genSelect("wmm", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"], "on_change_wmm(this)");<?=$G_TAG_SCRIPT_END?>
									</td>
								</tr>																																		
<? if($cap_v1 != 1) {echo "<!--";} ?>
								<tr>
									<td>
										<?=$m_captival_profile?>
									</td>
									<td>
										<select id="captival_profile" name="captival_profile">
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
							</table>
						</fieldset>
					</td>
				</tr>				
				<tr colspan="2">
					<td>									
						<fieldset id="wep_key_setting_fieldset" style="display:none;">		
							<legend><?=$m_key_field_title?></legend>
							<table width="100%" border="0">	
								<tr>
									<td width="25%"><?=$m_encryption?></td>
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
					</td>
				</tr>	
				<tr colspan="2">
					<td>									
						<fieldset id="eap_psk_fieldset" style="display:none;">	
							<legend><span id="eap_psk_table_title"><?=$m_passphrase_field_title?></span></legend>
							<table width="100%" border="0">	
								<tbody id="wpa_setting" style="display:none;">
								<tr>
									<td width="20%">
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
										genSelect("cipher", [4,3,2], ["<?=$m_cipher_auto?>","<?=$m_cipher_aes?>","<?=$m_cipher_tkip?>"], "on_change_cipher(this)");
										genSelect("cipher_11n_only", [3], ["<?=$m_cipher_aes?>"], "on_change_cipher(this)");
										genSelect("cipher_wds", [4,3], ["<?=$m_cipher_auto?>","<?=$m_cipher_aes?>"], "on_change_cipher(this)");
										<?=$G_TAG_SCRIPT_END?>	
										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<?=$m_gkui?>
										&nbsp;
										<input type ="text" class="text" id="gkui" name="gkui" value="" size="5" />
										<?=$m_second?>
									</td>
								</tr>
								</tbody>
								<tbody id="dot_1x_setting" style="display:none;">
								<tr>
									<td >
										<?=$m_kui?>
									</td>
									<td>										
										<input type ="text" class="text" id="kui" name="kui" value="" size="5" />
										<?=$m_second?>
									</td>
								</tr>								
								</tbody>
								<tbody id="psk_setting" style="display:none;">		
<? if($flag_autorekey !="1")	{echo "<!--";} ?>
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
								<tbody id="psk_setting1" style="display:none;">								
<? if($flag_autorekey !="1")	{echo "-->";} ?>
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
<? if($flag_autorekey !="1")	{echo "<!--";} ?>
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
<? if($flag_autorekey !="1")	{echo "-->";} ?>
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
										<input type="text" class="text" id="radius_srv_1" name="radius_srv_1" value="" size="16" />
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
										<input type="password" class="text" id="radius_sec_1" name="radius_sec_1" value="" size="40" />
									</td>
								</tr>
								<tr><td>&nbsp;</td>
									<td style=font-size:11px>(0-9,a-z,A-Z,~!@#$%^&*()_+`-={}[];'\:"|,./<>?)</td>
								</tr>
<? if(query("/runtime/web/display/backup_radius_server") != "1")	{echo "<!--";} ?>								
								<tr>
									<td bgcolor="#cccccc" colspan="2" height="16"><b><?=$m_secondary_radius_title?></b></td>
								</tr>
								<tr>
									<td>
										<?=$m_radius_server?>
									</td>
									<td>
										<input type="text" class="text" id="radius_srv_2" name="radius_srv_2" value="" size="16" />
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
										<input type="password" class="text" id="radius_sec_2" name="radius_sec_2" value="" size="40" />
									</td>
								</tr>
								<tr><td>&nbsp;</td>
									<td style=font-size:11px>(0-9,a-z,A-Z,~!@#$%^&*()_+`-={}[];'\:"|,./<>?)</td>
								</tr>
<? if(query("/runtime/web/display/backup_radius_server") != "1") {echo "-->";} ?>									
<? if(query("/runtime/web/display/accounting_server") != "1")	{echo "<!--";} ?>								
								<tr>
									<td bgcolor="#cccccc" colspan="2" height="16"><b><?=$m_primary_accounting_title?></b></td>
								</tr>
								<tr>
									<td>
										<?=$m_accounting_mode?>
									</td>
									<td>
										<?=$G_TAG_SCRIPT_START?>genSelect("acc_mode", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"], "on_change_account_mode(this)");<?=$G_TAG_SCRIPT_END?>									
									</td>
								</tr>
								<tr>
									<td>
										<?=$m_accounting_server?>
									</td>
									<td>
										<input type="text" class="text" id="acc_srv_1" name="acc_srv_1" value="" size="16" />
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
										<input type="password" class="text" id="acc_sec_1" name="acc_sec_1" value="" size="40" />
									</td>
								</tr>
								<tr><td>&nbsp;</td>
									<td style=font-size:11px>(0-9,a-z,A-Z,~!@#$%^&*()_+`-={}[];'\:"|,./<>?)</td>
								</tr>
<? if(query("/runtime/web/display/accounting_server") != "1")	{echo "-->";} ?>									
<? if(query("/runtime/web/display/backup_accounting_server") != "1")	{echo "<!--";} ?>																
								<tr>
									<td bgcolor="#cccccc" colspan="2" height="16"><b><?=$m_secondary_accounting_title?></b></td>
								</tr>
								<tr>
									<td>
										<?=$m_accounting_server?>
									</td>
									<td>
										<input type="text" class="text" id="acc_srv_2" name="acc_srv_2" value="" size="16" />
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
										<input type="password" class="text" id="acc_sec_2" name="acc_sec_2" value="" size="40" />
									</td>
								</tr>
								<tr><td>&nbsp;</td>
									<td style=font-size:11px>(0-9,a-z,A-Z,~!@#$%^&*()_+`-={}[];'\:"|,./<>?)</td>
								</tr>
<? if(query("/runtime/web/display/backup_accounting_server") != "1")	{echo "-->";} ?>								
								</tbody>	
							</table>			
						</fieldset>
					</td>
				</tr>	
<?=$G_ADD_BUTTON?>																														
				<tr>
					<td colspan="2">
						<table width="100%" border="0" align="center" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">
							<tr class="list_head" align="left">
								<td width="115">
									<?=$m_index?>
								</td>
								<td width="125">
									<?=$m_ssid?>
								</td>
								<td width="70">
									<?=$m_band?>
								</td>
								<td width="120">
									<?=$m_sec?>
								</td>
								<td width="60">
									<?=$m_del?>
								</td>			
								<td width="20">
									&nbsp;
								</td>																																																										
							</tr>	
						</table>	
						<div class="div_tab" >
						<table id="mssid_tab" width="100%" border="0" align="center" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">						
<?
$tmp = 0;
$tr = 1;
$ms_vlan_enable = query("vlan_state");
$pri_encryption  = "";

if($cfg_band == 0) //11g
{
	$pri_band = $m_band_2_4G;
	$ms_band =$m_band_2_4G;
}
else
{
	$pri_band = $m_band_5G;
	$ms_band = $m_band_5G;
}

if(query("authentication") == 0)
{
	if(query("wpa/wepmode") == 0)	{$pri_encryption = $ms_encryption_none;}
	else	{$pri_encryption = $ms_encryption_open;}
}
else if(query("authentication") == 1)	{$pri_encryption = $ms_encryption_shared;}
else if(query("authentication") == 8)	{$pri_encryption = $ms_encryption_both;}
else if(query("authentication") == 2)	{$pri_encryption = $ms_encryption_wpa_eap;}
else if(query("authentication") == 4)	{$pri_encryption = $ms_encryption_wpa2_eap;}
else if(query("authentication") == 6)	{$pri_encryption = $ms_encryption_wpa_auto_eap;}
else if(query("authentication") == 3)	{$pri_encryption = $ms_encryption_wpa_psk;}
else if(query("authentication") == 5)	{$pri_encryption = $ms_encryption_wpa2_psk;}
else if(query("authentication") == 7)	{$pri_encryption = $ms_encryption_wpa_auto_psk;}
else if(query("authentication") == 9)	{$pri_encryption = $ms_encryption_1x;}
echo "<tr align=\"left\" style=\"background:#cccccc;\">\n";
echo "<td width=\"115\">".$m_pri_ssid."</td>\n";	
echo "<td width=\"125\">".$G_TAG_SCRIPT_START."genTableSSID(\"".$cfg_ssid."\",\"0\");".$G_TAG_SCRIPT_END."</td>\n";	
echo "<td width=\"70\">".$pri_band."</td>\n";	
echo "<td width=\"120\">".$pri_encryption."</td>\n";

	
echo "<td id=\"adjust_td\">&nbsp;</td>\n";	
echo "</tr>\n";

for("multi/index")
{
	echo "<input type=\"hidden\" id=\"ms_ssid".$@."\" name=\"ms_ssid".$@."\" value=\"".get("h","ssid")."\">\n";
	if($switch == 1)
	{
		echo "<input type=\"hidden\" id=\"ms_rekey".$@."\" name=\"ms_rekey".$@."\" value=\"".get(j,"/runtime/wlan/inf:1/multi/index:".$@."/passphrase")."\">\n";
	}
	else
	{
		if($cfg_band == 0)
		{
			echo "<input type=\"hidden\" id=\"ms_rekey".$@."\" name=\"ms_rekey".$@."\" value=\"".get(j,"/runtime/wlan/inf:1/multi/index:".$@."/passphrase")."\">\n";
		}
		else
		{
			echo "<input type=\"hidden\" id=\"ms_rekey".$@."\" name=\"ms_rekey".$@."\" value=\"".get(j,"/runtime/wlan/inf:2/multi/index:".$@."/passphrase")."\">\n";
		}
	}
	$ms_encryption  = "";

	if(query("auth") == 0)
	{
		if(query("cipher") == 0)	{$ms_encryption = $ms_encryption_none;}
		else	{$ms_encryption = $ms_encryption_open;}
	}
	else if(query("auth") == 1)	{$ms_encryption = $ms_encryption_shared;}
	else if(query("auth") == 8)	{$ms_encryption = $ms_encryption_both;}
	else if(query("auth") == 2)	{$ms_encryption = $ms_encryption_wpa_eap;}
	else if(query("auth") == 4)	{$ms_encryption = $ms_encryption_wpa2_eap;}
	else if(query("auth") == 6)	{$ms_encryption = $ms_encryption_wpa_auto_eap;}
	else if(query("auth") == 3)	{$ms_encryption = $ms_encryption_wpa_psk;}
	else if(query("auth") == 5)	{$ms_encryption = $ms_encryption_wpa2_psk;}
	else if(query("auth") == 7)	{$ms_encryption = $ms_encryption_wpa_auto_psk;}
	else if(query("auth") == 9)	{$ms_encryption = $ms_encryption_1x;}
	
	if(query("state") !="0")
	{		
		$tmp = $tr%2;
		if($tmp == 1)
		{
			echo "<tr align=\"left\" style=\"background:#b3b3b3;\">\n";
		}
		else
		{
			echo "<tr align=\"left\" style=\"background:#cccccc;\">\n";
		}
		echo "<td  class='edit' width=\"115\"><a href='javascript:edit_mssid_setting(".$@.");'>".$m_ms_ssid.$@.$m_edit."</a></td>\n";	
		echo "<td width=\"125\">".$G_TAG_SCRIPT_START."genTableSSID(\"ms_ssid\",".$@.");".$G_TAG_SCRIPT_END."</td>\n";	
		echo "<td width=\"70\">".$ms_band."</td>\n";	
		echo "<td width=\"120\">".$ms_encryption."</td>\n";	

		echo "<td id=\"adjust_td\">".$G_TAG_SCRIPT_START."print_rule_del(".$@.");".$G_TAG_SCRIPT_END."</td></tr></td>\n";		
		echo "</tr>\n";
		$tr++;
	}
}
?>																					
						</table>
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
</form>
</body>
</html>
		
