<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_captivals_v2";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_captivals_v2";
set("/runtime/web/help_page",$MY_NAME);
/* --------------------------------------------------------------------------- */
if($ACTION_POST != "")
{
	require("/www/model/__admin_check.php");
	require("/www/__action_adv.php");
	$ACTION_POST = "";
	exit;
}

/* --------------------------------------------------------------------------- */
require("/www/model/__html_head.php");
require("/www/comm/__js_ip.php");
set("/runtime/web/next_page",$first_frame);
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
echo "<!--debug\n";

$if_userpwd = query("/runtime/captival/if_userpwd");
set("/runtime/captival/if_userpwd", "");
$return_index = query("/runtime/captival/return_index");
set("/runtime/captival/return_index", "");

if($return_index != "")
{
	$cfg_ssid_index = $return_index;
	if($return_index > 8)
	{
		$cfg_band = 1;
		$cfg_dis_index = $return_index - 9;
	}
	else
	{
		$cfg_band = 0;
		$cfg_dis_index = $return_index - 1;
	}
}
else
{
	if($reload_ssid != "")
	{$cfg_dis_index = $reload_ssid;}
	else
	{$cfg_dis_index = 0;}

	if($reload_band != "")
	{$cfg_band = $reload_band;}
	else
	{$cfg_band = query("/wlan/ch_mode");}

	if($cfg_band == 0)
	{$cfg_ssid_index = $cfg_dis_index + 1;}
	else
	{$cfg_ssid_index = $cfg_dis_index + 9;}
}

$cfg_mode_g = query("/wlan/inf:1/ap_mode");
$cfg_mode_a = query("/wlan/inf:2/ap_mode");
$check_band = query("/wlan/inf:2/ap_mode");

$cfg_server_ip = query("/sys/swcontroller/server_ip");
$cfg_service_port = query("/sys/swcontroller/service_port");
$cfg_live_port = query("/sys/swcontroller/live_port");
$cfg_group_id = query("/sys/swcontroller/group_id");
$cfg_timeout = query("/captival/captival_timeout");
if($cfg_timeout == ""){$cfg_timeout = 60;}

$list_row=0;
$serial_list_row=0;

anchor("/captival/ssid:".$cfg_ssid_index);
$cfg_type = query("state");
if($cfg_type == ""){$cfg_type = 0;}

$cfg_url_state = query("url_state");
if($cfg_url_state == ""){$cfg_url_state = 0;}
$cfg_url_path = query("url_path");

$cfg_vlanif_status = query("vlanif/status");
if($cfg_vlanif_status == ""){$cfg_vlanif_status = 0;}
$cfg_vlanif_group = query("vlanif/group");
$cfg_lantype = query("vlanif/mode");
if($cfg_lantype == ""){$cfg_lantype = 1;}
$cfg_static_ip = query("vlanif/static/ip");
$cfg_static_mask = query("vlanif/static/netmask");
$cfg_static_gw = query("vlanif/static/gateway");
$cfg_static_dns = query("vlanif/static/dns");
$cfg_dy_ip = query("/runtime/captival/ssid:".$cfg_ssid_index."/vlanif/dynamic/ip");
$cfg_dy_mask = query("/runtime/captival/ssid:".$cfg_ssid_index."/vlanif/dynamic/netmask");
$cfg_dy_gw = query("/runtime/captival/ssid:".$cfg_ssid_index."/vlanif/dynamic/gateway");
$cfg_dy_dns = query("/runtime/captival/ssid:".$cfg_ssid_index."/vlanif/dynamic/dns");

$cfg_radius_ip1 = query("radius/index:1/ip");
$cfg_radius_port1 = query("radius/index:1/port");
if($cfg_radius_port1 == ""){$cfg_radius_port1 = 1812;}
$cfg_radius_sec1 = queryEnc("radius/index:1/secret");
$cfg_remote_type1 = query("radius/index:1/mode");
$cfg_radius_ip2 = query("radius/index:2/ip");
$cfg_radius_port2 = query("radius/index:2/port");
if($cfg_radius_port2 == ""){$cfg_radius_port2 = 1812;}
$cfg_radius_sec2 = queryEnc("radius/index:2/secret");
$cfg_remote_type2 = query("radius/index:2/mode");
$cfg_radius_ip3 = query("radius/index:3/ip");
$cfg_radius_port3 = query("radius/index:3/port");
if($cfg_radius_port3 == ""){$cfg_radius_port3 = 1812;}
$cfg_radius_sec3 = queryEnc("radius/index:3/secret");
$cfg_remote_type3 = query("radius/index:3/mode");

$cfg_ldap_ip = get("h","ldap/server_ip");
$cfg_ldap_port = query("ldap/port");
if($cfg_ldap_port == ""){$cfg_ldap_port = 389;}
$cfg_ldap_auth = query("ldap/auth");
$cfg_ldap_name = get("h","ldap/name");
$cfg_ldap_pass = queryEnc("ldap/passwd");
$cfg_base_dn = get("h","ldap/base");
$cfg_attribute = query("ldap/attribute");
$cfg_identity = get("j","ldap/identity");
$cfg_auto_copy = query("ldap/autoid");

$cfg_pop3_ip = get("j","pop3/server_ip");
$cfg_pop3_port = query("pop3/server_port");
$cfg_ssl_state = query("pop3/ssl_state");

$this_passcode = 0;
if($cfg_band == 0){$ps_m = 1; $ps_max = 9;}
else {$ps_m = 9; $ps_max = 17;}
while($ps_m < $ps_max)
{
	if(query("/captival/ssid:".$ps_m."/state") == 3)
	{$this_passcode++; $this_passcode_index = $ps_m;}
	$ps_m++;
}
echo "-->";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>

var r_list=[['index','name','password','group']
<?
for("/captival/ssid:".$cfg_ssid_index."/local/user")
{
	echo ",\n ['".$@."','".get("j","name")."','".queryEnc("passwd")."','".query("group")."']";
	$list_row++;
}
?>
];

var serial_list=[['index','number','duration','enddate','device']
<?
for("/captival/ssid:".$cfg_ssid_index."/passcode/index")
{
	echo ",\n ['".$@."','".query("number")."','".query("duration")."','".query("end")."','".query("device")."']";
	$serial_list_row++;
}
?>
];

var vlanif_list=[['index','state','group','lantype','ipaddr','ipmask','gateway','dns']
<?
$index=1;
while($index < 17)
{
	anchor("/captival/ssid:".$index."/vlanif");
	$tmp_state = query("/captival/ssid:".$index."/state");
	if($tmp_state == ""){$tmp_state = 0;}
	$tmp_group = query("group");
	if($tmp_group == ""){$tmp_group = 0;}
	$tmp_lantype = query("mode");
	if($tmp_lantype == 1)
	{
		$tmp_ipaddr = query("static/ip");
		$tmp_ipmask = query("static/netmask");
		$tmp_gateway = query("static/gateway");
		$tmp_dns = query("static/dns");
	}
	else
	{
		$tmp_ipaddr = query("/runtime/captival/ssid:".$index."/vlanif/dynamic/ip");
		$tmp_ipmask = query("/runtime/captival/ssid:".$index."/vlanif/dynamic/netmask");
		$tmp_gateway = query("/runtime/captival/ssid:".$index."/vlanif/dynamic/gateway");
		$tmp_dns = query("/runtime/captival/ssid:".$index."/vlanif/dynamic/dns");
	}
	echo ",\n ['".$index."','".$tmp_state."','".$tmp_group."','".$tmp_lantype."','".$tmp_ipaddr."','".$tmp_ipmask."','".$tmp_gateway."','".$tmp_dns."']";
	$index++;
}
?>
];

/* page init functoin */
function init()
{
	var f=get_obj("frm");
	f.timeout.value = "<?=$cfg_timeout?>";
	f.ssid_index.value = "<?=$cfg_dis_index?>";
	f.band.value = "<?=$cfg_band?>";
	f.encry_type.value = "<?=$cfg_type?>";
	if("<?=$if_userpwd?>" == 2)
		f.encry_type.value = 2;
	else if("<?=$if_userpwd?>" == 3)
		f.encry_type.value = 3;
	if("<?=$check_band?>" == "")
	{
		f.band.disabled = true;
		if("<?=$cfg_mode_g?>" != 0 && "<?=$cfg_mode_g?>" != 3)
			fields_disabled(f, true);
	}
	else
	{
		if("<?=$cfg_mode_g?>" == 4 && "<?=$cfg_mode_a?>" == 4)
			fields_disabled(f, true);
	}
	on_change_type();
}

function ip_and_mask(ip, mask)
{
	var tmp_ip=get_ip(ip);
	var tmp_mask=get_ip(mask);
	var sub=new Array();
	for(var i=1; i<5; i++)
	{
		sub[i]= tmp_ip[i] & tmp_mask[i];
	}
	var ipmask = sub[1] + "." + sub[2] + "." + sub[3] + "." + sub[4];
	return ipmask;
}

function on_change_index()
{
	var f=get_obj("frm");
	self.location.href = "adv_captivals_v2.php?reload_band=" + f.band.value + "&reload_ssid=" + f.ssid_index.value;
}

function on_change_type()
{
	var f=get_obj("frm");
	get_obj("url_zone").style.display = "";
	get_obj("local_zone").style.display = "none";
	get_obj("ticket_zone").style.display = "none";
	get_obj("radius_zone").style.display = "none";
	get_obj("ldap_zone").style.display = "none";
	get_obj("pop3_zone").style.display = "none";

	f.url_state.value = "<?=$cfg_url_state?>";
	f.url_state.disabled = false;
	on_change_url();
	var tmp_url = "<?=$cfg_url_path?>";
	if(tmp_url.substring(0,5) == "https")
	{
		f.url_path.value = tmp_url.substring(8);
		f.site_begin.value = 2;
	}
	else
	{
		f.url_path.value = tmp_url.substring(7);
		f.site_begin.value = 1;
	}
	f.vlanif_status.value = "<?=$cfg_vlanif_status?>";
	f.lantype.value = "<?=$cfg_lantype?>";
	on_change_vlanif_status();

	if(f.encry_type.value == 0)
	{
		get_obj("url_zone").style.display = "none";
	}
	else if(f.encry_type.value == 1)
	{
		f.url_state.value = 1;
		f.url_state.disabled = true;
		on_change_url();
	}
	else if(f.encry_type.value == 2)
	{
		get_obj("local_zone").style.display = "";
	}
	else if(f.encry_type.value == 3)
	{
		get_obj("ticket_zone").style.display = "";
/*		if("<?=$this_passcode?>" != 0 && "<?=$cfg_ssid_index?>" != "<?=$this_passcode_index?>")
			f.serial_cnt.disabled = f.duration.disabled = f.end_year.disabled = f.end_month.disabled = f.end_day.disabled = f.end_hour.disabled = f.device.disabled = f.serial_add.disabled = f.serial_clear.disabled = f.serial_del_all.disabled = true;*/
	}
	else if(f.encry_type.value == 4)
	{
		get_obj("radius_zone").style.display = "";
	}
	else if(f.encry_type.value == 5)
	{
		get_obj("ldap_zone").style.display = "";
		on_change_auto_copy();
	}
	else if(f.encry_type.value == 6)
	{
		get_obj("pop3_zone").style.display = "";
		f.pop3_ip.value = "<?=$cfg_pop3_ip?>";
		f.pop3_port.value = "<?=$cfg_pop3_port?>";
		if(f.pop3_port.value == "")
			on_change_ssl();
	}
	AdjustHeight();
}

function on_change_url()
{
	var f=get_obj("frm");
	if(f.url_state.value == 1)
	{
		f.url_path.disabled = f.site_begin.disabled = false;
	}
	else
	{
		f.url_path.disabled = f.site_begin.disabled = true;
	}
}

function on_change_vlanif_status()
{
	var f = get_obj("frm");
	if(f.vlanif_status.value == 1)
	{
		f.lantype.disabled = f.vlanif_group.disabled = false;
		on_change_lan_type();
	}
	else
		f.lantype.disabled = f.vlanif_group.disabled = f.ipaddr.disabled = f.ipmask.disabled = f.gateway.disabled = f.dns.disabled = true;
}

function on_change_lan_type()
{
	var f = get_obj("frm");
	if(f.lantype.value == 1)
	{
		f.ipaddr.value = "<?=$cfg_static_ip?>";
		f.ipmask.value = "<?=$cfg_static_mask?>";
		f.gateway.value = "<?=$cfg_static_gw?>";
		f.dns.value = "<?=$cfg_static_dns?>";
		f.ipaddr.disabled = f.ipmask.disabled = f.gateway.disabled = f.dns.disabled = false;
	}
	else
	{
		f.ipaddr.value = ("<?=$cfg_dy_ip?>" == "")?"<?=$cfg_static_ip?>":"<?=$cfg_dy_ip?>";
		f.ipmask.value = ("<?=$cfg_dy_mask?>" == "")?"<?=$cfg_static_mask?>":"<?=$cfg_dy_mask?>";
		f.gateway.value = ("<?=$cfg_dy_gw?>" == "")?"<?=$cfg_static_gw?>":"<?=$cfg_dy_gw?>";
		f.dns.value = ("<?=$cfg_dy_dns?>" == "")?"<?=$cfg_static_dns?>":"<?=$cfg_dy_dns?>";
		f.ipaddr.disabled = f.ipmask.disabled = f.gateway.disabled = f.dns.disabled = true;
	}
}

function on_change_auto_copy()
{
	var f = get_obj("frm");
	if(f.auto_copy.checked)
	{
		f.identity.disabled = true;
		f.identity.value = f.attribute.value+"="+f.ldap_name.value+","+f.base_dn.value;
	}
	else
	{
		f.identity.disabled = false;
		f.identity.value = "<?=$cfg_identity?>";
	}
}

function on_change_ssl()
{
	var f = get_obj("frm");
	if(f.ssl_state.value == 1)
		f.pop3_port.value = 995;
	else
		f.pop3_port.value = 110;
}

function do_add_rule()
{
	var f = get_obj("frm");
	if(check_local_rules() == true)
	{
		if(f.which_edit.value != "")
			f.f_action.value = "edit";
		else
			f.f_action.value = "add";
		fields_disabled(f,false);
		f.submit();
	}
}

function do_clear()
{
	var f=get_obj("frm");
	f.r_name.value = f.r_password.value = "";
}

function do_del(id)
{
	var f=get_obj("frm");
	if(confirm("<?=$a_confirm_delete?>") ==  true)
	{
		f.which_delete.value=id;
		f.f_action.value="delete";
		fields_disabled(f,false);
		f.submit();
	}
}

function do_edit(id)
{
	var f=get_obj("frm");
	f.which_edit.value=id;
	f.r_name.value=r_list[id][1];
	f.r_password.value=r_list[id][2];
}

function page_table_serial(index)
{
	var f=get_obj("frm");
	var str="";
	var end_time = serial_list[index][3].split('-');
	var end_date = end_time[0]+'-'+end_time[1]+'-'+end_time[2];
	var end_hour = end_time[3]+"<?=$m_timeend?>";
	if(index%2 == 1)
	{
		str+= "<tr style='background:#CCCCCC;'>\n";
	}
	else
	{
		str+= "<tr style='background:#B3B3B3;'>\n";
	}
	str+= "<td width='20%' align='center'>" + serial_list[index][1] + "<td>\n";
	str+= "<td width='20%' align='center'>" + serial_list[index][2] + "</td>\n";
	str+= "<td width='35%' align='center'>" + end_date + "&nbsp;" + end_hour + "</td>\n";
	str+= "<td width='15%' align='center'>" + serial_list[index][4] + "</td>\n";
	str+= "<td width='10%' align='center'><a href='javascript:do_serial_del(\""+index+"\")'><img src='/pic/delete.jpg' border=0></a></td>\n";
	str+="</tr>\n";
	document.write(str);
}

function randomChar()
{
	var x="1234567890";
	var ran_string="";
	for(var i=0;i<8;i++)
	{
		ran_string+=x.charAt(Math.ceil(Math.random()*100000000)%x.length);
	}
	return ran_string;
}

function do_serial_add()
{
	var f=get_obj("frm");
	var current_list = "<?=$serial_list_row?>";
	if(!is_digit(f.serial_cnt.value))
	{
		alert("<?=$a_invalid_cnt?>");
		f.serial_cnt.select();
		return false;
	}
	if(f.serial_cnt.value < 1 || f.serial_cnt.value > 100)
	{
		alert("<?=$a_invalid_cnt_range?>");
		f.serial_cnt.select();
		return false;
	}
	var extra_list = 100 - current_list;
	var total_list = parseInt(f.serial_cnt.value, [10]) + parseInt(current_list, [10]);
	if(total_list > 100)
	{
		alert("<?=$a_max_serial_number_start?>"+extra_list+"<?=$a_max_serial_number_end?>");
		return false;
	}
	for(var i=1;i<=f.serial_cnt.value;i++)
	{
		get_obj("random_s"+i).value = randomChar();
	}
	if(f.duration.value == "")
	{
		alert("<?=$a_empty_duration?>");
		f.duration.focus();
		return false;
	}
	if(!is_digit(f.duration.value))
	{
		alert("<?=$a_invalid_duration?>");
		f.duration.select();
		return false;
	}
	if(f.duration.value < 1 || f.duration.value > 100000)
	{
		alert("<?=$a_invalid_duration_range?>");
		f.duration.select();
		return false;
	}
	if((f.end_month.value == "4" || f.end_month.value == "6" || f.end_month.value == "9" || f.end_month.value == "11") && f.end_day.value == "31")
	{
		alert("<?=$a_invalid_end_date?>");
		return false;
	}
	if(f.end_month.value == 2)
	{
		if(f.end_day.value == "30" || f.end_day.value == "31")
		{
			alert("<?=$a_invalid_end_date?>");
			return false;
		}
		if(f.end_day.value == "29" && f.end_year.value != "2016")
		{
			alert("<?=$a_invalid_end_date?>");
			return false;
		}
	}
	var end_month = f.end_month.value;
	var end_day = f.end_day.value;
	var end_hour = f.end_hour.value;
	if(f.end_month.value < 10)
		end_month = "0"+f.end_month.value;
	if(f.end_day.value < 10)
		end_day = "0"+f.end_day.value;
	if(f.end_hour.value < 10)
		end_hour = "0"+f.end_hour.value;
	f.end_date.value = f.end_year.value+"-"+end_month+"-"+end_day+"-"+end_hour;
	if(f.device.value == "")
	{
		alert("<?=$a_empty_device?>");
		f.device.focus();
		return false;
	}
	if(!is_digit(f.device.value))
	{
		alert("<?=$a_invalid_device?>");
		f.device.select();
		return false;
	}
	if(f.device.value < 1 || f.device.value > 32)
	{
		alert("<?=$a_invalid_device_range?>");
		f.device.select();
		return false;
	}
	f.f_action.value = "add_s";
	fields_disabled(f,false);
	f.submit();
}

function do_serial_clear()
{
	var f=get_obj("frm");
	f.serial_cnt.value = f.duration.value = f.device.value = "";
	f.end_year.value = 2015;
	f.end_month.value = f.end_day.value = f.end_hour.value = 1;
}

function do_serial_del_all()
{
	var f=get_obj("frm");
	if(confirm("<?=$a_confirm_delete_all?>") ==  true)
	{
		f.f_action.value="del_all_s";
		fields_disabled(f,false);
		f.submit();
	}
}

function do_serial_del(id)
{
	var f=get_obj("frm");
	if(confirm("<?=$a_confirm_delete?>") ==  true)
	{
		f.which_delete.value=id;
		f.f_action.value="delete_s";
		fields_disabled(f,false);
		f.submit();
	}
}

function check_url()
{
	var f=get_obj("frm");
	if(f.url_state.value == 1)
	{
		if(f.url_path.value == "")
		{
			alert("<?=$a_empty_url_addr?>");
			f.url_path.focus();
			return false;
		}
		if(strchk_url(f.url_path.value)==false)
		{
			alert("<?=$a_invalid_url_addr?>");
			f.url_path.select();
			return false;
		}
		for(var k=0; k < f.url_path.value.length; k++)
		{
			if(f.url_path.value.charAt(k) == '.' && f.url_path.value.charAt(k+1) == '.')
			{
				alert("<?=$a_invalid_url_addr?>");
				f.url_path.select();
				return false;
			}
		}
		if(f.url_path.value.substring(0,4) == "http")
		{
			alert("<?=$a_invalid_http?>");
			f.url_path.select();
			return false;
		}
	}
	return true;
}

function check_local_rules()
{
	var f=get_obj("frm");
	if("<?=$list_row?>" >=128 && f.which_edit.value=="")
	{
		alert("<?=$a_max_account_number?>");
		return false;
	}
	if(f.r_name.value == "")
	{
		alert("<?=$a_empty_user_name?>");
		f.r_name.focus();
		return false;
	}	
	if(is_blank_in_first_end(f.r_name.value))
	{
		alert("<?=$a_first_end_blank?>");
		f.r_name.select();
		return false;
	}
	if(strchk_unicode(f.r_name.value))
	{
		alert("<?=$a_invalid_name?>");
		f.r_name.select();
		return false;
	}
	if(f.which_edit.value=="")
	{
		for(var s=1;s<r_list.length;s++)
		{
			if(f.r_name.value==r_list[s][1])
			{
				alert("<?=$a_can_not_same_name?>");
				f.r_name.select();
				return false;
			}
		}
	}
	else
	{
		for(var s=1;s<r_list.length;s++)
		{
			if(f.r_name.value==r_list[s][1] && f.which_edit.value!=s)
			{
				alert("<?=$a_can_not_same_name?>");
				f.r_name.select();
				return false;
			}
		}
	}
	if(f.r_password.value == "")
	{
		alert("<?=$a_empty_password?>");
		f.r_password.focus();
		return false;
	}
	if(is_blank_in_first_end(f.r_password.value))
	{
		alert("<?=$a_first_end_blank?>");
		f.r_password.value="";
		f.r_password.select();
		return false;
	}
	if(strchk_unicode(f.r_password.value))
	{
		alert("<?=$a_invalid_password?>");
		f.r_password.select();
		return false;
	}
	if(f.r_password.value.length < 1 || f.r_password.value.length > 64)
	{
		alert("<?=$a_invalid_secret_len?>");
		f.r_password.select();
		return false;
	}
	return true;
}

function check_radius()
{
	var f=get_obj("frm");
	if(f.radius_ip1.value == "")
	{
		alert("<?=$a_empty_server?>");
		f.radius_ip1.focus();
		return false;
	}
	if(is_blank(f.radius_port1.value))
	{
		alert("<?=$a_empty_port?>");
		f.radius_port1.focus();
		return false;
	}
	if(!is_valid_port_str(f.radius_port1.value))
	{
		alert("<?=$a_invalid_port?>");
		f.radius_port1.select();
		return false;
	}
	if(f.radius_sec1.value == "")
	{
		alert("<?=$a_empty_sec?>");
		f.radius_sec1.focus();
		return false;
	}
	for(var i=0;i<f.radius_sec1.value.length;i++)
	{
		if(f.radius_sec1.value.charAt(i) == " ")
		{
			alert("<?=$a_invalid_radius_sec?>");
			f.radius_sec1.select();
			return false;
		}
	}
	if(f.radius_sec1.value.length<1 || f.radius_sec1.value.length>64)
	{
		alert("<?=$a_invalid_secret_len?>");
		f.radius_sec1.select();
		return false;
	}
	if(f.radius_ip2.value != "")
	{
		if(is_blank(f.radius_port2.value))
		{
			alert("<?=$a_empty_port?>");
			f.radius_port2.focus();
			return false;
		}
		if(!is_valid_port_str(f.radius_port2.value))
		{
			alert("<?=$a_invalid_port?>");
			f.radius_port2.select();
			return false;
		}
		if(f.radius_sec2.value == "")
		{
			alert("<?=$a_empty_sec?>");
			f.radius_sec2.focus();
			return false;
		}
		for(var i=0;i<f.radius_sec2.value.length;i++)
		{
			if(f.radius_sec2.value.charAt(i) == " ")
			{
				alert("<?=$a_invalid_radius_sec?>");
				f.radius_sec2.select();
				return false;
			}
		}
		if(f.radius_sec2.value.length<1 || f.radius_sec2.value.length>64)
		{
			alert("<?=$a_invalid_secret_len?>");
			f.radius_sec2.select();
			return false;
		}
		if(f.radius_ip3.value != "")
		{
			if(is_blank(f.radius_port3.value))
			{
				alert("<?=$a_empty_port?>");
				f.radius_port3.focus();
				return false;
			}
			if(!is_valid_port_str(f.radius_port3.value))
			{
				alert("<?=$a_invalid_port?>");
				f.radius_port3.select();
				return false;
			}
			if(f.radius_sec3.value == "")
			{
				alert("<?=$a_empty_sec?>");
				f.radius_sec3.focus();
				return false;
			}
			for(var i=0;i<f.radius_sec3.value.length;i++)
			{
				if(f.radius_sec3.value.charAt(i) == " ")
				{
					alert("<?=$a_invalid_radius_sec?>");
					f.radius_sec3.select();
					return false;
				}
			}
			if(f.radius_sec3.value.length<1 || f.radius_sec3.value.length>64)
			{
				alert("<?=$a_invalid_secret_len?>");
				f.radius_sec3.select();
				return false;
			}		
		}
	}
	return true;
}

function check_vlanif()
{
	var f=get_obj("frm");
	var this_ssid = "<?=$cfg_ssid_index?>";
	if(f.vlanif_status.value == 1)
	{
		if(f.vlanif_group.value == "")
		{
			alert("<?=$a_empty_group?>");
			f.vlanif_group.focus();
			return false;
		}
		if(!is_in_range(f.vlanif_group.value,1,4094))
		{
			alert("<?=$a_invalid_vlanif_group?>");
			field_select(f.vlanif_group);
			return false;
		}
		if(f.lantype.value == 1)
		{
			if(!is_valid_ip3(f.ipaddr.value, 0))
			{
				alert("<?=$a_invalid_ip?>");
				f.ipaddr.select();
				return false;
			}
			if(!is_valid_mask(f.ipmask.value))
			{
				alert("<?=$a_invalid_mask?>");
				f.ipmask.select();
				return false;
			}
			for(var i=1; i<17; i++)
			{
				if(vlanif_list[i][1] != 0 && ip_and_mask(f.ipaddr.value, f.ipmask.value) == ip_and_mask(vlanif_list[i][4], vlanif_list[i][5]) && vlanif_list[i][2] != f.vlanif_group.value)
				{
					alert("<?=$a_invalid_ipmask?>");
					f.ipaddr.select();
					return false;
				}
			}
			if(!is_valid_ip3(f.gateway.value, 0))
			{
				alert("<?=$a_invalid_gateway?>");
				f.gateway.select();
				return false;
			}
			if(!is_valid_ip3(f.dns.value, 0))
			{
				alert("<?=$a_invalid_dns?>");
				f.dns.select();
				return false;
			}
		}
		f.con_changessid.value = 0;
		for(var i=1; i<17; i++)
		{
			if(vlanif_list[i][1] != 0 && f.vlanif_group.value == vlanif_list[i][2] && i != this_ssid)
			{
				if(f.lantype.value != vlanif_list[i][3] || f.ipaddr.value != vlanif_list[i][4] || f.ipmask.value != vlanif_list[i][5] || f.gateway.value != vlanif_list[i][6] || f.dns.value != vlanif_list[i][7])
				{
					if(confirm("<?=$a_confirm_change_ssid?>") == true)
					{
						f.con_changessid.value = 1;
						break;
					}
					else
					{
						alert("<?=$a_input_another_vid?>");
						f.vlanif_group.select();
						return false;
					}
				}		
			}
		}
	}
	return true;
}

function onkeyup_vid()
{
	var f=get_obj("frm");
	var same_vid;
	var this_ssid = "<?=$cfg_ssid_index?>";
	for(var i=1; i<17; i++)
	{
		if(vlanif_list[i][1] != 0 && f.vlanif_group.value == vlanif_list[i][2] && i != this_ssid)
		{
			var same_vid = 1;
			break;
		}
	}
	if(same_vid == 1)
	{
		get_obj("copy_ip").innerHTML = "<?=$m_copy_ip?>";
		f.lantype.value = vlanif_list[i][3];
		f.ipaddr.value = vlanif_list[i][4];
		f.ipmask.value = vlanif_list[i][5];
		f.gateway.value = vlanif_list[i][6];
		f.dns.value = vlanif_list[i][7];
		f.lantype.disabled = f.ipaddr.disabled = f.ipmask.disabled = f.gateway.disabled = f.dns.disabled = true;
	}
	else
	{
		get_obj("copy_ip").innerHTML = "";
		f.lantype.disabled = f.ipaddr.disabled = f.ipmask.disabled = f.gateway.disabled = f.dns.disabled = false;
	}
}

function check_ldap()
{
	var f=get_obj("frm");
	if(f.ldap_ip.value == "")
	{
		alert("<?=$a_empty_server?>");
		f.ldap_ip.focus();
		return false;
	}
	if(is_blank(f.ldap_port.value))
	{
		alert("<?=$a_empty_port?>");
		f.ldap_port.focus();
		return false;
	}
	if(!is_valid_port_str(f.ldap_port.value))
	{
		alert("<?=$a_invalid_port?>");
		f.ldap_port.select();
		return false;
	}
	if(f.ldap_name.value == "")
	{
		alert("<?=$a_empty_user_name?>");
		f.ldap_name.focus();
		return false;
	}
	if(is_blank_in_first_end(f.ldap_name.value))
	{
		alert("<?=$a_first_end_blank?>");
		f.ldap_name.select();
		return false;
	}
	if(is_blank(f.ldap_password.value))
	{
		alert("<?=$a_empty_sec?>");
		f.ldap_password.focus();
		return false;
	}
	if(is_blank_in_first_end(f.ldap_password.value))
	{
		alert("<?=$a_first_end_blank?>");
		f.ldap_password.select();
		return false;
	}
	if(f.ldap_password.value.length<1 || f.ldap_password.value.length>64)
	{
		alert("<?=$a_invalid_secret_len?>");
		f.ldap_password.select();
		return false;
	}
	if(f.base_dn.value == "")
	{
		alert("<?=$a_empty_base_dn?>");
		f.base_dn.focus();
		return false;
	}
	if(f.attribute.value == "")
	{
		alert("<?=$a_empty_attribute?>");
		f.attribute.focus();
		return false;
	}
	if(!is_letter(f.attribute.value))
	{
		alert("<?=$a_invalid_attribute?>");
		f.attribute.select();
		return false;
	}
	if(f.identity.value == "")
	{
		alert("<?=$a_empty_identity?>");
		f.identity.focus();
		return false;
	}
	return true;
}

function check_pop3()
{
	var f=get_obj("frm");
	if(f.pop3_ip.value == "")
	{
		alert("<?=$a_empty_server?>");
		f.pop3_ip.focus();
		return false;
	}
	if(strchk_unicode(f.pop3_ip.value))
	{
		alert("<?=$a_invalid_server?>");
		f.pop3_ip.select();
		return false;
	}
	if(is_blank(f.pop3_port.value))
	{
		alert("<?=$a_empty_port?>");
		f.pop3_port.focus();
		return false;
	}
	if(!is_valid_port_str(f.pop3_port.value))
	{
		alert("<?=$a_invalid_port?>");
		f.pop3_port.select();
		return false;
	}
	return true;
}

function do_pro_edit(id)
{
	var f=get_obj("frm");
	var band_pro, index_pro;
	if(id > 8)
	{
		band_pro = 1;
		index_pro = id - 9;
	}
	else
	{
		band_pro = 0;
		index_pro = id - 1;
	}
	self.location.href = "adv_captivals_v2.php?reload_band=" + band_pro + "&reload_ssid=" + index_pro;
}

function do_pro_del(id)
{
	var f=get_obj("frm");
	if(confirm("<?=$a_confirm_delete_pro?>") == true)
	{
		f.which_delete.value=id;
		f.f_action.value="delete_p";
		fields_disabled(f,false);
		f.submit();
	}
}

function check()
{
	var f=get_obj("frm");
	if(!is_in_range(f.timeout.value,1,1440))
	{
		alert("<?=$a_invalid_timeout_range?>");
		f.timeout.select();
		return false;
	}
	if(f.encry_type.value != 0 && f.encry_type.value != "")
	{
		if(check_vlanif() == false || check_url() == false)
			return false;
	}
	if(f.encry_type.value == 3)
	{
		if("<?=$this_passcode?>" != 0 && "<?=$cfg_ssid_index?>" != "<?=$this_passcode_index?>")
		{
			alert("<?=$a_passcode_already_selected?>");
			return false;
		}
	}
	if(f.encry_type.value == 4 && check_radius() == false)
	{
		return false;
	}
	else if(f.encry_type.value == 5 && check_ldap() == false)
	{
		return false;
	}
	else if(f.encry_type.value == 6 && check_pop3() == false)
	{
		return false;
	}
	return true;
}

function submit()
{
	var f=get_obj("frm");
	if(check() == true)
	{
		fields_disabled(f,false);
		f.submit();
		return true;
	}
}

</script>

<body <?=$G_BODY_ATTR?> onload="init();">

<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return false;">
<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="which_delete" value="">
<input type="hidden" name="which_edit" value="">
<input type="hidden" name="con_changessid" value="">
<input type="hidden" name="f_action" value="">
<?
$i = 1;
while($i < 101)
{
	echo "<input type=\"hidden\" id=\"random_s".$i."\" name=\"random_s".$i."\" value=\"\">\n";
	$i++;
}
?>
<input type="hidden" name="end_date" value="">
<table id="table_frame" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
	<tr>
		<td valign="top">
			<table id="table_header" <?=$G_TABLE_ATTR_CELL_ZERO?>>
			<tr>
				<td id="td_header" valign="middle"><?=$m_context_title?></td>
			</tr>
			</table>
<!-- ________________________________ Main Content Start ______________________________ -->
  			<table id="table_set_main" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
			<tr>
				<td width="30%" id="td_left"><?=$m_timeout?></td>
				<td><input type="text" id="timeout" name="timeout" maxlength="4" size="7" value="<?=$cfg_timeout?>"><?=$m_mins?></td>
			</tr>
			<tr>
				<td width="30%" id="td_left"><?=$m_band?></td>
				<td><?=$G_TAG_SCRIPT_START?>genSelect("band", [0,1], ["<?=$m_2_4g?>","<?=$m_5g?>"], "on_change_index()");<?=$G_TAG_SCRIPT_END?></td>
			</tr>
				<td width="30%" id="td_left"><?=$m_ssid_index?></td>
				<td><?=$G_TAG_SCRIPT_START?>genSelect("ssid_index", [0,1,2,3,4,5,6,7], ["<?=$m_pri_ssid?>","<?=$m_ms_ssid1?>","<?=$m_ms_ssid2?>","<?=$m_ms_ssid3?>","<?=$m_ms_ssid4?>","<?=$m_ms_ssid5?>","<?=$m_ms_ssid6?>","<?=$m_ms_ssid7?>"], "on_change_index()");<?=$G_TAG_SCRIPT_END?></td>
			</tr>
			<tr>
				<td width="30%" id="td_left"><?=$m_encrption_type?></td>
				<td>
					<select id="encry_type" name="encry_type" onchange="on_change_type()">
						<option value="0"><?=$m_disable?></option>
						<option value="1"><?=$m_urlonly?></option>
						<option value="2"><?=$m_local?></option>
						<option value="3"><?=$m_ticket?></option>
						<option value="4"><?=$m_radius?></option>
						<option value="5"><?=$m_ldap?></option>
						<option value="6"><?=$m_pop3?></option>
					</select>
				</td>
			</tr>

			<tr >
				<td colspan="2">
				<fieldset id="url_zone" style="display:none;">
				<legend><?=$m_vlanif_title?></legend>
				<table width="100%" border="0" align="center" <?=$G_TABLE_ATTR_CELL_ZERO?>>
					<tr>
						<td width="30%" id="td_left"><?=$m_url_state?></td>
						<td><?=$G_TAG_SCRIPT_START?>genSelect("url_state", [0,1],["<?=$m_disable?>","<?=$m_enable?>"], "on_change_url();");<?=$G_TAG_SCRIPT_END?></td>
					</tr>
					<tr>
						<td width="30%" id="td_left"><?=$m_url_path?></td>
						<td>
							<select id="site_begin" name="site_begin">
								<option value="1"><?=$m_http?></option>
								<option value="2"><?=$m_https?></option>
							</select>
							<input type="text" id="url_path" name="url_path" size="30" maxlength="120" value=""></td>
					</tr>
					<tr style="display:none;">
						<td bgcolor="#cccccc" colspan="2" height="18"><b><?=$m_ip_title?></b></td>
					</tr>
					<tr height="25px"  style="display:none;">
						<td><?=$m_vlanif_status?></td>
						<td><?=$G_TAG_SCRIPT_START?>genSelect("vlanif_status", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"],"on_change_vlanif_status()");<?=$G_TAG_SCRIPT_END?></td>
					</tr>
					<tr height="25px"  style="display:none;">
						<td><?=$m_vlanif_group?></td>
						<td><input type="text" class="text" id="vlanif_group" name="vlanif_group" class="flatL" size="10" maxlength="4" value="<?=$cfg_vlanif_group?>" onkeyup="onkeyup_vid()"><span id="copy_ip"></span></td>
					</tr>
					<tr height="25px"  style="display:none;">
						<td><?=$m_lan_type?></td>
						<td><?=$G_TAG_SCRIPT_START?>genSelect("lantype", [1,2], ["<?=$m_static_ip?>","<?=$m_dhcp?>"],"on_change_lan_type(this)");<?=$G_TAG_SCRIPT_END?></td>
					</tr>
					<tr height="25px"  style="display:none;">
						<td><?=$m_local_ip?></td>
						<td><input type="text" class="text" id="ipaddr" name="ipaddr" class="flatL" size="15" maxlength="15" value=""></td>
					</tr>
					<tr height="25px"  style="display:none;">
						<td><?=$m_local_mask?></td>
						<td><input type="text" class="text" id="ipmask" name="ipmask" class="flatL" size="15" maxlength="15" value=""></td>
					</tr>
					<tr height="25px"  style="display:none;">
						<td><?=$m_local_gateway?></td>
						<td><input type="text" class="text" id="gateway" name="gateway" class="flatL" size="15" maxlength="15" value=""></td>
					</tr>
					<tr height="25px"  style="display:none;">
						<td><?=$m_local_dns?></td>
						<td><input type="text" class="text" id="dns" name="dns" class="flatL" size="15" maxlength="15" value=""></td>
					</tr>
				</table>
				</fieldset>
			</tr>
			
			<tr>
				<td colspan="2">
					<fieldset id="local_zone" style="display:none;">
					<legend><?=$m_local_title?></legend>
					<table width="100%" border="0" align="center" <?=$G_TABLE_ATTR_CELL_ZERO?>>
						<tr>
							<td width="30%" id="td_left"><?=$m_name?></td>
							<td><input type="text" width="20%" maxlength="64" size="20" id="r_name" name="r_name" class="text" value=""></td>
						</tr>
						<tr>
							<td width="30%" id="td_left"><?=$m_password?></td>
							<td><input type="password" width="20%" maxlength="64" size="20" id="r_password" name="r_password" class="text" value=""></td>
						</tr>
						<tr>
							<td width="30%" id="td_left"></td>
							<td>
								<input type="button" id="r_add" name="r_add" value="<?=$m_add?>" onclick="do_add_rule()">&nbsp;&nbsp;&nbsp;
								<input type="button" id="r_clear" name="r_clear" value="<?=$m_clear?>" onclick="do_clear()">
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">
									<tr class="list_head" align="left">
										<td width="70%" align="center"><?=$m_name?></td>
										<td width="15%" align="center"><?=$m_edit?></td>
										<td width="15%" align="center"><?=$m_delete?></td>
									</tr>
								</table>
								<div class="div_tab" >
								<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">
	<?
	$key=0;
	for("/captival/ssid:".$cfg_ssid_index."/local/user")
	{
		$key++;
		$table_name = get("j","name");
		if($key%2==1)
		{
			echo "<tr style='background:#CCCCCC;'>\n";
		}
		else
		{
			echo "<tr style='background:#B3B3B3;'>\n";
		}
		echo "<td width='70%' align='center'>".$G_TAG_SCRIPT_START."genTableName(\"".$table_name."\",\"15\");".$G_TAG_SCRIPT_END."</td>\n";
		echo "<td width='15%' align='center'><a href='javascript:do_edit(\"".$@."\")'><img src='/pic/edit.jpg' border=0></a></td>\n";
		echo "<td width='15%' align='center'><a href='javascript:do_del(\"".$@."\")'><img src='/pic/delete.jpg' border=0></a></td>\n";
		echo "</tr>\n";
	}
	?>
								</table>
								</div>
							</td>
						</tr>
					</table>
					</fieldset>
				</td>
			</tr>

			<tr>
				<td colspan="2">
				<fieldset id="ticket_zone" style="display:none;">
				<legend><?=$m_ticket_title?></legend>
		      	<table width="100%" border="0" align="center" <?=$G_TABLE_ATTR_CELL_ZERO?>>
					<tr>
						<td width="30%" id="td_left"><?=$m_serial_count?></td>
						<td><input type="text" width="20%" maxlength="3" size="7" id="serial_cnt" name="serial_cnt" class="text" value=""></td>
					</tr>
					<tr>
						<td width="30%" id="td_left"><?=$m_duration?></td>
						<td><input type="text" width="20%" maxlength="6" size="7" id="duration" name="duration" class="text" value="">&nbsp;<?=$m_hours?></td>
					</tr>
					<tr>
						<td width="30%" id="td_left"><?=$m_end_date?></td>
						<td>
							<?=$m_year?><?=$G_TAG_SCRIPT_START?>genSelect("end_year", [2015,2016,2017,2018,2019], ["<?=$m_2015?>","<?=$m_2016?>","<?=$m_2017?>","<?=$m_2018?>","<?=$m_2019?>"], "");<?=$G_TAG_SCRIPT_END?>
							<?=$m_month?><?=$G_TAG_SCRIPT_START?>genSelect("end_month", [1,2,3,4,5,6,7,8,9,10,11,12],["<?=$m_jan?>","<?=$m_feb?>","<?=$m_mar?>","<?=$m_apr?>","<?=$m_may?>","<?=$m_jun?>","<?=$m_jul?>","<?=$m_aug?>","<?=$m_sep?>","<?=$m_oct?>","<?=$m_nov?>","<?=$m_dec?>"], "");<?=$G_TAG_SCRIPT_END?>
							<?=$m_day?><select size=1 id="end_day" name="end_day">
<?
$i=0;
while ($i<31)
{
	$i++;
	echo "<option value=".$i.">".$i."</option>\n";
}
?>
										</select>
							<?=$m_hours?><select size=1 id="end_hour" name="end_hour">
<?
$i=1;
while ($i<25)
{
	echo "<option value=".$i.">".$i.$m_timeend."</option>\n";
	$i++;
}
?>
										</select>
						</td>
					</tr>
					<tr>
						<td width="30%" id="td_left"><?=$m_device?></td>
						<td><input type="text" width="20%" maxlength="2" size="7" id="device" name="device" class="text" value=""></td>
					</tr>
					<tr>
						<td width="30%" id="td_left"></td>
						<td>
							<input type="button" id="serial_add" name="serial_add" value="<?=$m_add?>" onclick="do_serial_add()">&nbsp;&nbsp;&nbsp;
							<input type="button" id="serial_clear" name="serial_clear" value="<?=$m_clear?>" onclick="do_serial_clear()">
						</td>
					</tr>
					<tr>
						<td colspan="2"><input type="button" id="serial_del_all" name="serial_del_all" value="<?=$m_del_all?>" onclick="do_serial_del_all()"></td>
					</tr>
					<tr>
						<td colspan="2">
							<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">
								<tr class="list_head" align="left">
									<td width="20%" align="center"><?=$m_serial?></td>
									<td width="20%" align="center"><?=$m_duration?></td>
									<td width="35%" align="center"><?=$m_end_date?></td>
									<td width="15%" align="center"><?=$m_device?></td>
									<td width="10%" align="center"><?=$m_delete?></td>
								</tr>
							</table>
							<div class="div_tab" >
							<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">
<?
$key=1;
$serial_num_1 = $serial_list_row + 1;
while($key < $serial_num_1)
{
	echo $G_TAG_SCRIPT_START."page_table_serial(".$key.");".$G_TAG_SCRIPT_END;
	$key++;
}
?>
							</table>
							</div>
						</td>
					</tr>
				</table>
				</fieldset>
      </td>
		</tr>

		<tr>
			<td colspan="2">
				<fieldset id="radius_zone" style="display:none;">
				<legend><?=$m_radius_title?></legend>
				<table width="100%" border="0" align="center" <?=$G_TABLE_ATTR_CELL_ZERO?>>
					<tr>
						<td bgcolor="#cccccc" colspan="2" height="18"><b><?=$m_radius_server_title?></b></td>
					</tr>
					<tr height="25px">
						<td><?=$m_radius_server?></td>
						<td>
							&nbsp;<input type="text" class="text" id="radius_ip1" name="radius_ip1" value="<?=$cfg_radius_ip1?>" size="16" maxlength="15" />
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$m_radius_port?>&nbsp;
							<input type="text" class="text" id="radius_port1" name="radius_port1" value="<?=$cfg_radius_port1?>" maxlength="5" size="7" />
						</td>
					</tr>
					<tr height="25px">
						<td><?=$m_radius_secret?></td>
						<td>&nbsp;<input type="password" class="text" id="radius_sec1" name="radius_sec1" value="<?=$cfg_radius_sec1?>" size="40"  maxlength="64" /></td>
					</tr>
					<tr height="25px">
						<td><?=$m_remote_type?></td>
						<td>
							<select id="remote_type1" name="remote_type1">
								<option value="0" <?if($cfg_remote_type1 == 0){echo "selected";}?>><?=$m_spap?></option>
								<option value="1" <?if($cfg_remote_type1 == 1){echo "selected";}?>><?=$m_mschapv2?>
							</select>
						</td>
					</tr>
					<tr>
						<td bgcolor="#cccccc" colspan="2" height="18"><b><?=$m_second_radius_server_title?></b></td>
					</tr>
					<tr height="25px">
						<td><?=$m_radius_server?></td>
						<td>
							&nbsp;<input type="text" class="text" id="radius_ip2" name="radius_ip2" value="<?=$cfg_radius_ip2?>" size="16" maxlength="15" />
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$m_radius_port?>&nbsp;
							<input type="text" class="text" id="radius_port2" name="radius_port2" value="<?=$cfg_radius_port2?>" maxlength="5" size="7" />
						</td>
					</tr>
					<tr height="25px">
						<td><?=$m_radius_secret?></td>
						<td>&nbsp;<input type="password" class="text" id="radius_sec2" name="radius_sec2" value="<?=$cfg_radius_sec2?>" size="40"  maxlength="64" /></td>
					</tr>
					<tr height="25px">
						<td><?=$m_remote_type?></td>
						<td>
							<select id="remote_type2" name="remote_type2">
								<option value="0" <?if($cfg_remote_type2 == 0){echo "selected";}?>><?=$m_spap?></option>
								<option value="1" <?if($cfg_remote_type2 == 1){echo "selected";}?>><?=$m_mschapv2?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td bgcolor="#cccccc" colspan="2" height="18"><b><?=$m_third_radius_server_title?></b></td>
					</tr>
					<tr height="25px">
						<td><?=$m_radius_server?></td>
						<td>
							&nbsp;<input type="text" class="text" id="radius_ip3" name="radius_ip3" value="<?=$cfg_radius_ip3?>" size="16" maxlength="15" />
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$m_radius_port?>&nbsp;
							<input type="text" class="text" id="radius_port3" name="radius_port3" value="<?=$cfg_radius_port3?>" maxlength="5" size="7" />
						</td>
					</tr>
					<tr height="25px">
						<td><?=$m_radius_secret?></td>
						<td>&nbsp;<input type="password" class="text" id="radius_sec3" name="radius_sec3" value="<?=$cfg_radius_sec3?>" size="40"  maxlength="64" /></td>
					</tr>
					<tr height="25px">
						<td><?=$m_remote_type?></td>
						<td>
							<select id="remote_type3" name="remote_type3">
								<option value="0" <?if($cfg_remote_type3 == 0){echo "selected";}?>><?=$m_spap?></option>
								<option value="1" <?if($cfg_remote_type3 == 1){echo "selected";}?>><?=$m_mschapv2?></option>
							</select>
						</td>
					</tr>
				</table>
				</fieldset>
			</td>
		</tr>

		<tr>
			<td colspan="2">
				<fieldset id="ldap_zone" style="display:none;">
				<legend><?=$m_ldap_title?></legend>
				<table width="100%" border="0" align="center" <?=$G_TABLE_ATTR_CELL_ZERO?>>
					<tr>
						<td width="30%" id="td_left"><?=$m_server_state?></td>
						<td><input type="text" size="20" id="ldap_ip" name="ldap_ip" class="text" value="<?=$cfg_ldap_ip?>"></td>
					</tr>
					<tr>
						<td width="30%" id="td_left"><?=$m_port?></td>
						<td><input type="text" maxlength="5" size="7" id="ldap_port" name="ldap_port" class="text" value="<?=$cfg_ldap_port?>"></td>
					</tr>
					<tr>
						<td width="30%" id="td_left"><?=$m_auth?></td>
						<td>
							<select id="ldap_auth" name="ldap_auth">
								<option value="0" <?if($cfg_ldap_auth != 1){echo "selected";}?>><?=$m_simple?></option>
								<option value="1" <?if($cfg_ldap_auth == 1){echo "selected";}?>><?=$m_tls?></option>
							</select>
						</td>
					</tr>
					<tr>
						<td width="30%" id="td_left"><?=$m_name?></td>
						<td><input type="text" maxlength="64" size="20" id="ldap_name" name="ldap_name" class="text" value="<?=$cfg_ldap_name?>"></td>
					</tr>
					<tr>
						<td width="30%" id="td_left"><?=$m_password?></td>
						<td><input type="password" maxlength="64" size="20" id="ldap_password" name="ldap_password" class="text" value="<?=$cfg_ldap_pass?>"></td>
					</tr>
					<tr>
						<td width="30%" id="td_left"><?=$m_base_dn?></td>
						<td><input type="text" maxlength="64" size="30" id="base_dn" name="base_dn" class="text" value="<?=$cfg_base_dn?>">(ou=,dc=)</td>
					</tr>
					<tr>
						<td width="30%" id="td_left"><?=$m_attribute?></td>
						<td><input type="text" maxlength="64" size="30" id="attribute" name="attribute" class="text" value="<?=$cfg_attribute?>">(ex.cn)</td>
					</tr>
					<tr>
						<td width="30%" id="td_left"><?=$m_identity?></td>
						<td><input type="text" maxlength="64" size="30" id="identity" name="identity" class="text" value="">
						&nbsp;<input type="checkbox" id="auto_copy" name="auto_copy" value="1" onclick="on_change_auto_copy()" <? if($cfg_auto_copy == 1){echo "checked";}?>><?=$m_auto_copy?></td>
					</tr>
				</table>
				</fieldset>
			</td>
		</tr>

		<tr>
			<td colspan="2">
				<fieldset id="pop3_zone" style="display:none;">
				<legend><?=$m_pop3_title?></legend>
				<table width="100%" border="0" align="center" <?=$G_TABLE_ATTR_CELL_ZERO?>>
					<tr>
						<td width="30%" id="td_left"><?=$m_server_state?></td>
						<td><input type="text" size="20" id="pop3_ip" name="pop3_ip" class="text" value=""></td>
					</tr>
					<tr>
						<td width="30%" id="td_left"><?=$m_port?></td>
						<td><input type="text" maxlength="5" size="7" id="pop3_port" name="pop3_port" class="text" value="<?=$cfg_pop3_port?>"></td>
					</tr>
					<tr>
						<td width="30%" id="td_left"><?=$m_connection_type?></td>
						<td>
							<select id="ssl_state" name="ssl_state" onchange="on_change_ssl()">
								<option value="0" <?if($cfg_ssl_state != 1){echo "selected";}?>><?=$m_none?></option>
								<option value="1" <?if($cfg_ssl_state == 1){echo "selected";}?>><?=$m_ssl?></option>
							</select>
						</td>
					</tr>
				</table>
				</fieldset>
			</td>
		</tr>

<?=$G_APPLY_BUTTON?>
		<tr>
			<td colspan="2">
				<table width="100%" border="0" align="center" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">
					<tr class="list_head" align="left">
						<td width="15%" align="center"><?=$m_band?></td>
						<td width="25%" align="center"><?=$m_ssid_index?></td>
						<!--td width="15%" align="center"><?=$m_vlanif_group?></td-->
						<td width="40%" align="center"><?=$m_captival_profile?></td>
						<td width="10%" align="center"><?=$m_edit?><td>
						<td width="10%" align="center"><?=$m_delete?></td>
					</tr>
				</table>
				<div class="div_cap_tab">
				<table width="100%" border="0" align="center" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">
<?
$key=1;
$index=1;
if($check_band == ""){$index_num = 9;}else{$index_num = 17;}
while($key < $index_num)
{
	$ssid_index = "";
	$captival = query("/captival/ssid:".$key."/state");
	if($captival > 0 && $captival < 8)
	{		
		if($key < 9){$band = $m_2_4g;}else{$band = $m_5g;}
		if($key == 1 || $key == 9){$ssid_index = $m_pri_ssid;}
		else if($key < 9){$tmp_key = $key - 1; $ssid_index = $m_ssid.$tmp_key;}
		else{$tmp_key =  $key - 9; $ssid_index = $m_ssid.$tmp_key;}
		
		$vlanif_group = query("/captival/ssid:".$key."/vlanif/group");
		if($vlanif_group == ""){$vlanif_group = "N/A";}

		if($captival == 1){$captival_profile = $m_url_profile;}
		else if($captival == 2){$captival_profile = $m_local_profile;}
		else if($captival == 3){$captival_profile = $m_ticket_profile;}
		else if($captival == 4){$captival_profile = $m_radius_profile;}
		else if($captival == 5){$captival_profile = $m_ldap_profile;}
		else if($captival == 6){$captival_profile = $m_pop3_profile;}
		else if($captival == 7){$captival_profile = $m_sw_profile;}
		if($index%2==1)
		{
			echo "<tr style='background:#CCCCCC;'>\n";
		}
		else
		{
			echo "<tr style='background:#B3B3B3;'>\n";
		}
		$index++;
		echo "<td width=15% align='center'>".$band."</td>\n";
		echo "<td width=25% align='center'>".$ssid_index."</td>\n";
//		echo "<td width=15% align='center'>".$vlanif_group."</td>\n";
		echo "<td width=40% align='center'>".$captival_profile."</td>\n";
		echo "<td width=10% align='center'><a href='javascript:do_pro_edit(\"".$key."\")'><img src='/pic/edit.jpg' border=0></a></td>\n";
		echo "<td width=10% align='center'><a href='javascript:do_pro_del(\"".$key."\")'><img src='/pic/delete.jpg' border=0></a></td>\n";
		echo "</tr>\n";
	}
	$key++;
}
?>
				</table>
				</div>
			</td>
		</tr>
		<tr><td height="50px;">&nbsp;</td></tr>
      </table>
<!-- ________________________________  Main Content End _______________________________ -->
		</td>
	</tr>
</table>
</form>
</body>
</html>	
