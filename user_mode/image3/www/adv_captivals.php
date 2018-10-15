<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_captivals";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_captivals";
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
$cfg_timeout = query("/captival/captival_timeout");
if($cfg_timeout == ""){$cfg_timeout = 60;}
$cfg_filter_ip1 = query("/captival/ipfilter:1/ip");
$cfg_filter_ip2 = query("/captival/ipfilter:2/ip");
$cfg_filter_ip3 = query("/captival/ipfilter:3/ip");
$cfg_filter_ip4 = query("/captival/ipfilter:4/ip");
$cfg_filter_mask1 = query("/captival/ipfilter:1/mask");
$cfg_filter_mask2 = query("/captival/ipfilter:2/mask");
$cfg_filter_mask3 = query("/captival/ipfilter:3/mask");
$cfg_filter_mask4 = query("/captival/ipfilter:4/mask");
if($cfg_filter_ip1 != "" && $cfg_filter_mask1 != "")
{$cfg_filter1 = $cfg_filter_ip1."/".$cfg_filter_mask1;}
if($cfg_filter_ip2 != "" && $cfg_filter_mask2 != "")
{$cfg_filter2 = $cfg_filter_ip2."/".$cfg_filter_mask2;}
if($cfg_filter_ip3 != "" && $cfg_filter_mask3 != "")
{$cfg_filter3 = $cfg_filter_ip3."/".$cfg_filter_mask3;}
if($cfg_filter_ip4 != "" && $cfg_filter_mask4 != "")
{$cfg_filter4 = $cfg_filter_ip4."/".$cfg_filter_mask4;}
$cfg_remote_type = query("/captival/radius/auth_mode");
$cfg_radius_ip = query("/captival/radius/auth_server_ip");
$cfg_radius_port = query("/captival/radius/auth_server_port");
$cfg_radius_sec = queryEnc("/captival/radius/auth_server_secret");
$cfg_acc_mode = query("/captival/radius/acct_mode");
$cfg_acc_ip = query("/captival/radius/acct_server_ip");
$cfg_acc_port = query("/captival/radius/acct_server_port");
$cfg_acc_sec = queryEnc("/captival/radius/acct_server_secret");
$cfg_ldap_ip = get("h","/captival/ldap/server_ip");
$cfg_ldap_port = query("/captival/ldap/port");
if($cfg_ldap_port == ""){$cfg_ldap_port = 389;}
$cfg_ldap_auth = query("/captival/ldap/auth");
$cfg_ldap_name = get("h","/captival/ldap/name");
$cfg_ldap_password = queryEnc("/captival/ldap/passwd");
$cfg_base_dn = get("h","/captival/ldap/base");
$cfg_attribute = query("/captival/ldap/attribute");
$cfg_identity = get("j","/captival/ldap/identity");
$cfg_auto_copy = query("/captival/ldap/autoid");
$cfg_pop3_ip = get("j","/captival/pop3/server_ip");
$cfg_pop3_port = query("/captival/pop3/server_port");
$cfg_ssl_state = query("/captival/pop3/ssl_state");
$list_row=0;
$serial_list_row=0;

$cfg_if_userpwd = query("/runtime/captival/if_userpwd");
set("/runtime/captival/if_userpwd", "");
echo "-->";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>
var r_list=[['index','name','password','mac','desc','group']
<?
for("/captival/user")
{
	echo ",\n ['".$@."','".get("j","name")."','".queryEnc("pass")."','".query("mac")."','".query("desc")."','".query("group")."']";
	$list_row++;
}
?>
];

var serial_list=[['index','number','duration','enddate','device']
<?
for("/captival/serial")
{
	echo ",\n ['".$@."','".query("number")."','".query("duration")."','".query("end")."','".query("device")."']";
	$serial_list_row++;
}
?>
];
/* page init functoin */
function init()
{
	var f=get_obj("frm");
	f.timeout.value = "<?=$cfg_timeout?>";
	if("<?=$cfg_if_userpwd?>" == 1)
		f.encry_type.value = 1;
	on_change_type(f.encry_type);
}

function on_change_type(s)
{
	var f=get_obj("frm");
	get_obj("ticket_zone").style.display = "none";
	get_obj("local_zone").style.display = "none";
	get_obj("radius_zone").style.display = "none";
	get_obj("ldap_zone").style.display = "none";
	get_obj("pop3_zone").style.display = "none";
	if(s.value == 7)
	{
		get_obj("ticket_zone").style.display = "";
	}
	else if(s.value == 1)
	{
		get_obj("local_zone").style.display = "";
	}
	else if(s.value == 2)
	{
		get_obj("radius_zone").style.display = "";
		on_change_account_mode();
	}
	else if(s.value == 3)
	{
		get_obj("ldap_zone").style.display = "";
		on_change_auto_copy();
	}
	else if(s.value == 4)
	{
		get_obj("pop3_zone").style.display = "";
		f.pop3_ip.value = "<?=$cfg_pop3_ip?>";
		f.pop3_port.value = "<?=$cfg_pop3_port?>";
		if(f.pop3_port.value == "")
			on_change_ssl();
	}
	AdjustHeight();
}

function on_change_account_mode()
{
	var f = get_obj("frm");
	f.acc_ip.disabled = false;
	f.acc_port.disabled = false;
	f.acc_sec.disabled = false;
	if(f.acc_mode.value == 0)
	{
		f.acc_ip.disabled = true;
		f.acc_port.disabled = true;
		f.acc_sec.disabled = true;
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
		f.submit();
	}
}

function do_clear()
{
	var f=get_obj("frm");
	f.r_name.value = f.r_password.value = "";
	f.r_group.value = "manager";
}

function do_edit(id)
{
	var f=get_obj("frm");
	f.which_edit.value=id;
	f.r_name.value=r_list[id][1];
	f.r_password.value=r_list[id][2];
	f.r_group.value = r_list[id][5];
}

function do_del(id)
{
	var f=get_obj("frm");
	if(confirm("<?=$a_confirm_delete?>") ==  true)
	{
		f.which_delete.value=id;
		f.f_action.value="delete";
		f.submit();	
	}
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
	var x="1234567890abcdefghijklmnopqrstuvwxyz";
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
	if(f.serial_cnt.value < 1 || f.serial_cnt.value > 20)
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
	var end_hour=f.hour.value;
	if(f.end_month.value < 10)
		end_month = "0"+f.end_month.value;
	if(f.end_day.value < 10)
		end_day = "0"+f.end_day.value;
			if(f.hour.value < 10)
		end_hour = "0"+f.hour.value;
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
	f.submit();
}

function do_serial_clear()
{
	var f=get_obj("frm");
	f.serial_cnt.value = f.duration.value = f.device.value = "";
	f.end_year.value = 2014;
	f.end_month.value = f.end_day.value = f.hour.value =1;
}

function do_serial_del_all()
{
	var f=get_obj("frm");
	if(confirm("<?=$a_confirm_delete_all?>") ==  true)
	{
		f.f_action.value="del_all_s";
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
		f.submit();
	}
}

function is_valid_filter_ip(ipaddr, optional)
{
	var ip = get_ip(ipaddr);
	if (optional != 0)
	{
		if (ip[1]=="" && ip[2]=="" && ip[3]=="" && ip[4]=="") return true;
	}
	if (is_in_range(ip[1], 1, 223)==false) return false;
	if (decstr2int(ip[1]) == 127) return false;
	if (is_in_range(ip[2], 0, 255)==false) return false;
	if (is_in_range(ip[3], 0, 255)==false) return false;
	if (is_in_range(ip[4], 0, 254)==false) return false;
	ip[0] = parseInt(ip[1],[10])+"."+parseInt(ip[2],[10])+"."+parseInt(ip[3],[10])+"."+parseInt(ip[4],[10]);
	if (ip[0] != ipaddr) return false;
	return true;
}

function check_ip_filter()
{
	var f=get_obj("frm");
	for(var s=1; s<5; s++)
	{
		if(get_obj("filter"+s).value != "")
		{
			$cnt=0;
			for(var i=0;i<get_obj("filter"+s).value.length;i++)
			{
				if(get_obj("filter"+s).value.charAt(i) == "/"){$cnt++;}
			}
			if($cnt != 1)
			{
				alert("<?=$a_invalid_ip_filter?>");
				get_obj("filter"+s).select();
				return false;
			}
			var tmp=get_obj("filter"+s).value.split("/");
			if(!is_valid_filter_ip(tmp[0],0))
			{
				alert("<?=$a_invalid_ip_filter?>");
				get_obj("filter"+s).select();
				return false;
			}
			if(!is_digit(tmp[1]) || tmp[1] < 1 || tmp[1] > 32)
			{
				alert("<?=$a_invalid_ip_filter?>");
				get_obj("filter"+s).select();
				return false;
			}
			get_obj("f_filter_ip"+s).value = tmp[0];
			get_obj("f_filter_mask"+s).value = tmp[1];
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
	if(!is_valid_ip3(f.radius_ip.value,0))
	{
		alert("<?=$a_invalid_ip?>");
		f.radius_ip.select();
		return false;
	}
	if(is_blank(f.radius_port.value))
	{
		alert("<?=$a_empty_port?>");
		f.radius_port.focus();
		return false;
	}
	if(!is_valid_port_str(f.radius_port.value))
	{
		alert("<?=$a_invalid_port?>");
		f.radius_port.select();
		return false;
	}
	if(is_blank(f.radius_sec.value))
	{
		alert("<?=$a_empty_sec?>");
		f.radius_sec.focus();
		return false;
	}
	if(is_blank_in_first_end(f.radius_sec.value))
	{
		alert("<?=$a_first_end_blank?>");
		f.radius_sec.select();
		return false;
	}
	if(f.radius_sec.value.length<1 || f.radius_sec.value.length>64)
	{
		alert("<?=$a_invalid_secret_len?>");
		f.radius_sec.select();
		return false;
	}
	if(f.acc_mode.value == 1)
	{
		if(!is_valid_ip3(f.acc_ip.value,0))
		{
			alert("<?=$a_invalid_ip?>");
			f.acc_ip.select();
			return false;
		}
		if(is_blank(f.acc_port.value))
		{
			alert("<?=$a_empty_port?>");
			f.acc_port.focus();
			return false;
		}
		if(!is_valid_port_str(f.acc_port.value))
		{
			alert("<?=$a_invalid_port?>");
			f.acc_port.select();
			return false;
		}
		if(is_blank(f.acc_sec.value))
		{
			alert("<?=$a_empty_sec?>");
			f.acc_sec.focus();
			return false;
		}
		if(is_blank_in_first_end(f.acc_sec.value))
		{
			alert("<?=$a_first_end_blank?>");
			f.acc_sec.select();
			return false;
		}
		if(f.acc_sec.value.length<1 || f.acc_sec.value.length>64)
		{
			alert("<?=$a_invalid_secret_len?>");
			f.acc_sec.select();
			return false;
		}
	}
	return true;
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
	f.encry_type.value = id;
	init();
}

function do_pro_del(id)
{
	var f=get_obj("frm");
	if(confirm("<?=$a_confirm_delete?>") ==  true)
	{
		f.which_delete.value=id;
		f.f_action.value="delete_p";
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
	if(f.encry_type.value == 1)
	{
		if(check_ip_filter() == false)
		{
			return false;
		}
	}
	else if(f.encry_type.value == 2 && check_radius() == false)
	{
		return false;
	}
	else if(f.encry_type.value == 3 && check_ldap() == false)
	{
		return false;
	}
	else if(f.encry_type.value == 4 && check_pop3() == false)
	{
		return false;
	}
	return true;
}

function submit_do_add()
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
<input type="hidden" id="f_filter_ip1" name="f_filter_ip1" value="">
<input type="hidden" id="f_filter_ip2" name="f_filter_ip2" value="">
<input type="hidden" id="f_filter_ip3" name="f_filter_ip3" value="">
<input type="hidden" id="f_filter_ip4" name="f_filter_ip4" value="">
<input type="hidden" id="f_filter_mask1"  name="f_filter_mask1" value="">
<input type="hidden" id="f_filter_mask2" name="f_filter_mask2" value="">
<input type="hidden" id="f_filter_mask3" name="f_filter_mask3" value="">
<input type="hidden" id="f_filter_mask4" name="f_filter_mask4" value="">
<input type="hidden" name="which_delete" value="">
<input type="hidden" name="which_edit" value="">
<input type="hidden" name="f_action" value="">
<?
$i = 1;
while($i < 21)
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
				<td>
					<input type="text" id="timeout" name="timeout" maxlength="4" size="7" value="<?=$cfg_timeout?>"><?=$m_mins?>
				</td>
			</tr>
			<tr>
				<td width="30%" id="td_left"><?=$m_encrption_type?></td>
				<td>
					<select id="encry_type" name="encry_type" onchange="on_change_type(this)">
						<option value="7"><?=$m_ticket?></option>
						<option value="1"><?=$m_local?></option>
						<option value="2"><?=$m_radius?></option>
						<option value="3"><?=$m_ldap?></option>
						<option value="4"><?=$m_pop3?></option>
					</select>
				</td>
			</tr>
			
			<tr>
				<td colspan="2">
				<fieldset id="ticket_zone" style="display:none;">
				<legend><?=$m_ticket_title?></legend>
      	<table width="100%" border="0" align="center" <?=$G_TABLE_ATTR_CELL_ZERO?>>
					<tr>
						<td width="30%" id="td_left"><?=$m_serial_count?></td>
						<td><input type="text" width="20%" maxlength="2" size="7" id="serial_cnt" name="serial_cnt" class="text" value=""></td>
					</tr>
					<tr>
						<td width="30%" id="td_left"><?=$m_duration?></td>
						<td><input type="text" width="20%" maxlength="6" size="7" id="duration" name="duration" class="text" value="">&nbsp;<?=$m_hours?></td>
					</tr>
					<tr>
						<td width="30%" id="td_left"><?=$m_end_date?></td>
						<td>
							<?=$m_year?><?=$G_TAG_SCRIPT_START?>genSelect("end_year", [2014,2015,2016,2017,2018], ["<?=$m_2014?>","<?=$m_2015?>","<?=$m_2016?>","<?=$m_2017?>","<?=$m_2018?>"], "");<?=$G_TAG_SCRIPT_END?>
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
						<?=$m_hours?><select size=1 id="hour" name="hour">
<?
															$i=1;
															while ($i<=24)
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
				<fieldset id="local_zone" style="display:none;">
				<legend><?=$m_local_title?></legend>
				<table width="100%" border="0" align="center" <?=$G_TABLE_ATTR_CELL_ZERO?>>
					<tr>
						<td bgcolor="#cccccc" colspan="2" height="18"><b><?=$m_ip_range?></b></td>
					</tr>
					<tr>
						<td colspan="2" height=25px;><?=$m_res_sub?></td>
					</tr>
					<tr>
						<td colspan="2" height=30px; style="font-family:Arial">
							1.<input type="text" id="filter1" name="filter1" size="17" maxlength="18" value="<?=$cfg_filter1?>">
							2.<input type="text" id="filter2" name="filter2" size="17" maxlength="18" value="<?=$cfg_filter2?>">
							3.<input type="text" id="filter3" name="filter3" size="17" maxlength="18" value="<?=$cfg_filter3?>">
							4.<input type="text" id="filter4" name="filter4" size="17" maxlength="18" value="<?=$cfg_filter4?>">
						</td>
					</tr>
					<tr>
						<td bgcolor="#cccccc" colspan="2" height="18"><b><?=$m_local_rule_title?></b></td>
					</tr>
					<tr>
						<td width="30%" id="td_left"><?=$m_name?></td>
						<td><input type="text" width="20%" maxlength="64" size="20" id="r_name" name="r_name" class="text" value=""></td>
					</tr>
					<tr>
						<td width="30%" id="td_left"><?=$m_password?></td>
						<td><input type="password" width="20%" maxlength="64" size="20" id="r_password" name="r_password" class="text" value=""></td>
					</tr>
					<tr>
						<td width="30%" id="td_left"><?=$m_group?></td>
						<td>
							<select id="r_group" name="r_group">
								<option value="manager"><?=$m_manager?></option>
								<option value="guest"><?=$m_guest?></option>
							</select>
						</td>
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
									<td width="45%" align="center"><?=$m_name?></td>
									<td width="25%" align="center"><?=$m_group?></td>
									<td width="15%" align="center"><?=$m_edit?></td>
									<td width="15%" align="center"><?=$m_delete?></td>
								</tr>
							</table>
							<div class="div_tab" >
							<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">
<?
$key=0;
for("/captival/user")
{
	$key++;
	$table_name = get("j","name");
	if(query("group") == "manager")
	{
		$table_group = "Manager";
	}
	else
	{
		$table_group = "Guest";
	}
	if($key%2==1)
	{
		echo "<tr style='background:#CCCCCC;'>\n";
	}
	else
	{
		echo "<tr style='background:#B3B3B3;'>\n";
	}
	echo "<td width='45%' align='center'>".$G_TAG_SCRIPT_START."genTableName(\"".$table_name."\",\"15\");".$G_TAG_SCRIPT_END."</td>\n";
	echo "<td width='25%' align='center'>".$table_group."</td>\n";
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
				<fieldset id="radius_zone" style="display:none;">
				<legend><?=$m_radius_title?></legend>
				<table width="100%" border="0" align="center" <?=$G_TABLE_ATTR_CELL_ZERO?>>
					<tr height="25px">
						<td><?=$m_remote_type?></td>
						<td>
							<select id="remote_type" name="remote_type">
								<option value="0" <?if($cfg_remote_type == 0){echo "selected";}?>><?=$m_spap?>
								<option value="1" <?if($cfg_remote_type == 1){echo "selected";}?>><?=$m_mschapv2?>
							</select>
						</td>
					</tr>
					<tr>
						<td bgcolor="#cccccc" colspan="2" height="18"><b><?=$m_radius_server_title?></b></td>
					</tr>
					<tr height="25px">
						<td><?=$m_radius_server?></td>
						<td>
							&nbsp;<input type="text" class="text" id="radius_ip" name="radius_ip" value="<?=$cfg_radius_ip?>" size="16" maxlength="15" />
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$m_radius_port?>&nbsp;
							<input type="text" class="text" id="radius_port" name="radius_port" value="<?=$cfg_radius_port?>" maxlength="5" size="7" />
						</td>
					</tr>
					<tr height="25px">
						<td><?=$m_radius_secret?></td>
						<td>&nbsp;<input type="password" class="text" id="radius_sec" name="radius_sec" value="<?=$cfg_radius_sec?>" size="40" /></td>
					</tr>
					<tr>
						<td bgcolor="#cccccc" colspan="2" height="18"><b><?=$m_accounting_server_title?></b></td>
					</tr>
					<tr height="25px">
						<td><?=$m_accounting_mode?></td>
						<td>
							<select id="acc_mode" name="acc_mode" onchange="on_change_account_mode()">
								<option value="0" <?if($cfg_acc_mode == 0){echo "selected";}?>><?=$m_disable?></option>
								<option value="1" <?if($cfg_acc_mode == 1){echo "selected";}?>><?=$m_enable?></option>
							</select>
						</td>
					</tr>
					<tr height="25px">
						<td><?=$m_accounting_server?></td>
						<td>
							&nbsp;<input type="text" class="text" id="acc_ip" name="acc_ip" value="<?=$cfg_acc_ip?>" size="16" maxlength="15" />
							&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$m_accounting_port?>&nbsp;<input type="text" class="text" id="acc_port" name="acc_port" value="<?=$cfg_acc_port?>" size="7" maxlength="5" />
						</td>
					</tr>
					<tr height="25px">
						<td><?=$m_accounting_secret?></td>
						<td>&nbsp;<input type="password" class="text" id="acc_sec" name="acc_sec" value="<?=$cfg_acc_sec?>" size="40" /></td>
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
						<td><input type="text" size="20" maxlength="255" id="ldap_ip" name="ldap_ip" class="text" value="<?=$cfg_ldap_ip?>"></td>
					</tr>
					<tr>
						<td width="30%" id="td_left"><?=$m_port?></td>
						<td><input type="text" maxlength="5" size="7" id="ldap_port" name="ldap_port" class="text" value="<?=$cfg_ldap_port?>"></td>
					</tr>
					<tr>
						<td width="30%" id="td_left"><?=$m_auth?></td>
						<td>
							<select id="ldap_auth" name="ldap_auth">
								<option value="0" <?if($cfg_ldap_auth == 0){echo "selected";}?>><?=$m_simple?></option>
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
						<td><input type="password" maxlength="64" size="20" id="ldap_password" name="ldap_password" class="text" value="<?=$cfg_ldap_password?>"></td>
					</tr>
					<tr>
						<td width="30%" id="td_left"><?=$m_base_dn?></td>
						<td><input type="text" maxlength="255" size="30" id="base_dn" name="base_dn" class="text" value="<?=$cfg_base_dn?>">(ou=,dc=)</td>
					</tr>
					<tr>
						<td width="30%" id="td_left"><?=$m_attribute?></td>
						<td><input type="text" maxlength="255" size="30" id="attribute" name="attribute" class="text" value="<?=$cfg_attribute?>">(ex.cn)</td>
					</tr>
					<tr>
						<td width="30%" id="td_left"><?=$m_identity?></td>
						<td><input type="text" maxlength="255" size="30" id="identity" name="identity" class="text" value="<?=$cfg_identity?>">
						&nbsp;<input type="checkbox" id="auto_copy" name="auto_copy" value="1" onclick="on_change_auto_copy()" <?if($cfg_auto_copy == 1){echo "checked";}?>><?=$m_auto_copy?></td>
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
								<option value="0" <?if($cfg_ssl_state == 0){echo "selected";}?>><?=$m_none?></option>
								<option value="1" <?if($cfg_ssl_state == 1){echo "selected";}?>><?=$m_ssl?></option>
							</select>
						</td>
					</tr>
				</table>
				</fieldset>
			</td>
		</tr>
<?=$G_ADD_BUTTON?>
		<tr>
			<td colspan="2">
				<table width="100%" border="0" align="center" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">
					<tr class="list_head" align="left">
						<td width="60%" align="center"><?=$m_captival_profile?></td>
						<td width="20%" align="center"><?=$m_edit?></td>
						<td width="20%" align="center"><?=$m_delete?></td>
					</tr>
				</table>
				<div class="div_cap_tab">
				<table width="100%" border="0" align="center" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">
<?
$key=0;
for("/captival/auth")
{
	if(query("state") == "1" && $@ != 5 && $@ != 6)
	{
		if($@ == 1){$pro_name = $m_local_profile;}
		else if($@ == 2){$pro_name = $m_radius_profile;}
		else if($@ == 3){$pro_name = $m_ldap_profile;}
		else if($@ == 4){$pro_name = $m_pop3_profile;}
		else if($@ == 7){$pro_name = $m_ticket_profile;}
		$key++;
		if($key%2==1)
		{
			echo "<tr style='background:#CCCCCC;'>\n";
		}
		else
		{
			echo "<tr style='background:#B3B3B3;'>\n";
		}
		echo "<td width=60% align='center'>".$pro_name."</td>\n";
		echo "<td width=20% align='center'><a href='javascript:do_pro_edit(\"".$@."\")'><img src='/pic/edit.jpg' border=0></a></td>\n";
		echo "<td width=20% align='center'><a href='javascript:do_pro_del(\"".$@."\")'><img src='/pic/delete.jpg' border=0></a></td>\n";
		echo "</tr>\n";
	}
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
