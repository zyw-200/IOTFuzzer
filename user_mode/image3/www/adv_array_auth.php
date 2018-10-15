<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_array_auth";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_array_auth";
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
$cfg_band = query("/wlan/ch_mode");
anchor("/sys/aparray/captival");
$cfg_enable_auth = query("enable");
if($cfg_enable_auth == ""){$cfg_enable_auth = 0;}
$list_row = 0;
$serial_list_row = 0;
$cfg_filter_ip1 = query("ipfilter:1/ip");
$cfg_filter_ip2 = query("ipfilter:2/ip");
$cfg_filter_ip3 = query("ipfilter:3/ip");
$cfg_filter_ip4 = query("ipfilter:4/ip");
$cfg_filter_mask1 = query("ipfilter:1/mask");
$cfg_filter_mask2 = query("ipfilter:2/mask");
$cfg_filter_mask3 = query("ipfilter:3/mask");
$cfg_filter_mask4 = query("ipfilter:4/mask");
if($cfg_filter_ip1 != "" && $cfg_filter_mask1 != "")
{$cfg_filter1 = $cfg_filter_ip1."/".$cfg_filter_mask1;}
if($cfg_filter_ip2 != "" && $cfg_filter_mask2 != "")
{$cfg_filter2 = $cfg_filter_ip2."/".$cfg_filter_mask2;}
if($cfg_filter_ip3 != "" && $cfg_filter_mask3 != "")
{$cfg_filter3 = $cfg_filter_ip3."/".$cfg_filter_mask3;}
if($cfg_filter_ip4 != "" && $cfg_filter_mask4 != "")
{$cfg_filter4 = $cfg_filter_ip4."/".$cfg_filter_mask4;}
$cfg_role = query("/wlan/inf:1/arrayrole_original");
echo "-->";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>
var ar_auth_list=[['index','name','password','group']
<?
for("index")
{
	echo ",\n ['".$@."','".get("j","name")."','".queryEnc("pass")."','".query("group")."']";
	$list_row++;
}
?>
];

var serial_list=[['index','number','duration','enddate','device']
<?
for("serial")
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
	f.enable_auth.value = "<?=$cfg_enable_auth?>";
	on_change_auth();
	if("<?=$cfg_role?>" != 1 && "<?=$cfg_role?>" != 2)
	{
		f.enable_auth.value = 0;
		fields_disabled(f, true);
	}
}

function on_change_auth()
{
	var f=get_obj("frm");
	if(f.enable_auth.value == 1)
	{
		fields_disabled(f, false);
		get_obj("serial_setting").style.display = "none";
		get_obj("local_setting").style.display = "";
	}
	else if(f.enable_auth.value == 2)
	{
		fields_disabled(f, false);
		get_obj("local_setting").style.display = "none";
		get_obj("serial_setting").style.display = "";
	}
	else
	{
		fields_disabled(f, true);
		f.enable_auth.disabled = false;
	}
	AdjustHeight();
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

function do_add()
{
	var f=get_obj("frm");
	if("<?=$list_row?>" >=128 && f.which_edit.value=="")
	{
		alert("<?=$a_max_account_number?>");
		return false;
	}
	if(f.ar_auth_name.value == "")
	{
		alert("<?=$a_empty_user_name?>");
		f.ar_auth_name.focus();
		return false;
	}
	if(is_blank_in_first_end(f.ar_auth_name.value))
	{
		alert("<?=$a_first_end_blank?>");
		f.ar_auth_name.select();
		return false;
	}
	if(strchk_unicode(f.ar_auth_name.value))
	{
		alert("<?=$a_invalid_name?>");
		f.ar_auth_name.select();
		return false;
	}
	if(f.which_edit.value=="")
	{
		for(var s=1;s<ar_auth_list.length;s++)
		{
			if(f.ar_auth_name.value==ar_auth_list[s][1])
			{
				alert("<?=$a_can_not_same_name?>");
				f.ar_auth_name.select();
				return false;
			}
		}
	}
	else
	{
		for(var s=1;s<ar_auth_list.length;s++)
		{
			if(f.ar_auth_name.value==ar_auth_list[s][1] && f.which_edit.value!=s)
			{
				alert("<?=$a_can_not_same_name?>");
				f.ar_auth_name.select();
				return false;
			}
		}
	}
	if(f.ar_auth_pass.value == "")
	{
		alert("<?=$a_empty_password?>");
		f.ar_auth_pass.focus();
		return false;
	}
	if(is_blank_in_first_end(f.ar_auth_pass.value))
	{
		alert("<?=$a_first_end_blank?>");
		f.ar_auth_pass.value="";
		f.ar_auth_pass.select();
		return false;
	}
	if(strchk_unicode(f.ar_auth_pass.value))
	{
		alert("<?=$a_invalid_password?>");
		f.ar_auth_pass.select();
		return false;
	}
	if(f.ar_auth_pass.value.length < 1 || f.ar_auth_pass.value.length > 64)
	{
		alert("<?=$a_invalid_secret_len?>");
		f.ar_auth_pass.select();
		return false;
		}
	if(f.which_edit.value == "")
	{
		f.f_action.value = "add";
	}
	else
	{
		f.f_action.value = "edit";
	}
	f.submit();
}
	
function do_clear()
{
	var f=get_obj("frm");
	f.ar_auth_name.value = f.ar_auth_pass.value = "";
	f.ar_auth_group.value = "manager";
}

function do_edit(id)
{
	var f=get_obj("frm");
	f.which_edit.value = id;
	f.ar_auth_name.value = ar_auth_list[id][1];
	f.ar_auth_pass.value = ar_auth_list[id][2];
	f.ar_auth_group.value = ar_auth_list[id][3];
}

function do_del(id)
{
	var f=get_obj("frm");
	if(f.enable_auth.value == 1 && confirm("<?=$a_confirm_delete?>") ==  true)
	{
		f.which_delete.value = id;
		f.f_action.value = "delete";
		f.submit();
	}
}

function randomChar()
{
	var x="1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
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
	if(f.end_month.value < 10)
		end_month = "0"+f.end_month.value;
	if(f.end_day.value < 10)
		end_day = "0"+f.end_day.value;
	f.end_date.value = f.end_year.value+"-"+end_month+"-"+end_day;
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
	f.serial_cnt.value = "";
	f.duration.value = "";
	f.end_year.value = "2013";
	f.end_month.value = f.end_day.value = 1;
	f.device.value = "";
}

function do_serial_del_all()
{
	var f=get_obj("frm");
	if(f.enable_auth.value == 2 && confirm("<?=$a_confirm_delete_all?>") ==  true)
	{
		f.f_action.value="del_all_s";
		f.submit();
	}
}

function do_serial_del(id)
{
	var f=get_obj("frm");
	if(f.enable_auth.value == 2 && confirm("<?=$a_confirm_delete?>") ==  true)
	{
		f.which_delete.value=id;
		f.f_action.value="delete_s";
		f.submit();
	}
}

function check()
{
	var f=get_obj("frm");
	if(f.enable_auth.value == 1)
	{
		if(check_ip_filter() == false)
		{
			return false;
		}
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
				<td width="30%" id="td_left"><?=$m_enable_auth?></td>
				<td><?=$G_TAG_SCRIPT_START?>genSelect("enable_auth", [0,1,2], ["<?=$m_disable?>","<?=$m_userpass?>","<?=$m_passcode?>"], "on_change_auth();");<?=$G_TAG_SCRIPT_END?></td>
			</tr>
			
			<tr>
				<td colspan="2">
				<table width="100%" border="0" align="center" <?=$G_TABLE_ATTR_CELL_ZERO?>>
					<tbody id="local_setting" style="">
						<tr>
							<td bgcolor="#cccccc" colspan="2" height="18"><b><?=$m_ip_range?></b></td>
						</tr>
						<tr>
							<td colspan="2" height=25px;><?=$m_res_sub?></td>
						</tr>
						<tr>
							<td colspan="2" height=30px; style="font-family:Arial">
								1.<input type="text" id="filter1" name="filter1" size="18" maxlength="18" value="<?=$cfg_filter1?>">
								2.<input type="text" id="filter2" name="filter2" size="18" maxlength="18" value="<?=$cfg_filter2?>">
								3.<input type="text" id="filter3" name="filter3" size="18" maxlength="18" value="<?=$cfg_filter3?>">
								4.<input type="text" id="filter4" name="filter4" size="18" maxlength="18" value="<?=$cfg_filter4?>">
							</td>
						</tr>
						<tr>
							<td bgcolor="#cccccc" colspan="2" height="18"><b><?=$m_userpass_title?></b></td>
						</tr>
      				<tr>
      					<td width="30%" id="td_left"><?=$m_name?></td>
      					<td><input type="text" width="20%" maxlength="64" size="20" id="ar_auth_name" name="ar_auth_name" class="text" value=""></td>
      				</tr>
      				<tr>
      					<td width="30%" id="td_left"><?=$m_password?></td>
      					<td><input type="password" width="20%" maxlength="64" size="20" id="ar_auth_pass" name="ar_auth_pass" class="text" value=""></td>
      				</tr>
							<tr>
								<td width="30%" id="td_left"><?=$m_group?></td>
								<td>
									<select id="ar_auth_group" name="ar_auth_group">
										<option value="manager"><?=$m_manager?></option>
										<option value="guest"><?=$m_guest?></option>
									</select>
								</td>
							</tr>
      				<tr>	
								<td width="30%" id="td_left"></td>				
      					<td>
      						<input type="button" id="add" name="add" value="<?=$m_add?>" onclick="do_add()">&nbsp;&nbsp;&nbsp;
									<input type="button" id="clear" name="clear" value="<?=$m_clear?>" onclick="do_clear()">
      					</td>
      				</tr>
      				<tr>
      					<td colspan="2">
      						<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">
      							<tr class="list_head" align="left">
      								<td width="45%"><?=$m_name?></td>
      								<td width="25%" align="center"><?=$m_group?></td>
											<td width="15%" align="center"><?=$m_edit?></td>
											<td width="15%" align="center"><?=$m_delete?></td>
      							</tr>
      						</table>   						
							<div class="div_radius_tab" >
							<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">
      							
<?
$key=0;
for("index")
{
	$key++;
	$list_name=get("j","name");
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
	echo "<td width='45%'>".$G_TAG_SCRIPT_START."genTableName(\"".$list_name."\",\"15\");".$G_TAG_SCRIPT_END."</td>\n";
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
					</tbody>

					<tbody id="serial_setting" style="display:none;">
					<tr>
						<td bgcolor="#cccccc" colspan="2" height="18"><b><?=$m_passcode_title?></b></td>
					</tr>
					<tr>
						<td width="30%" id="td_left"><?=$m_serial_count?></td>
						<td><input type="text" width="20%" maxlength="2" size="7" id="serial_cnt" name="serial_cnt" class="text" value=""></td>
					</tr>
					<tr>
						<td width="30%" id="td_left"><?=$m_duration?></td>
						<td><input type="text" width="20%" maxlength="6" size="7" id="duration" name="duration" class="text" value=""><?=$m_hours?></td>
					</tr>
					<tr>
						<td width="30%" id="td_left"><?=$m_end_date?></td>
						<td>
							<?=$m_year?><?=$G_TAG_SCRIPT_START?>genSelect("end_year", [2013,2014,2015,2016,2017,2018], ["<?=$m_2013?>","<?=$m_2014?>","<?=$m_2015?>","<?=$m_2016?>","<?=$m_2017?>","<?=$m_2018?>"], "");<?=$G_TAG_SCRIPT_END?>
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
                            <input type="button" id="serial_clear" name="serial_clear" value="<?=$m_clear?>" onclick="do_serial_clear()"></td>
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
							<div class="div_radius_tab" >
							<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">
<?
$key=0;
for("/sys/aparray/captival/serial")
{
	$key++;
	$number= query("number");
	$duration=query("duration");
	$enddate=query("end");
	$device=query("device");
	if($key%2==1)
	{
		echo "<tr style='background:#CCCCCC;'>\n";
	}
	else
	{
		echo "<tr style='background:#B3B3B3;'>\n";
	}
	echo "<td width='20%' align='center'>".$number."</td>\n";
	echo "<td width='20%' align='center'>".$duration."</td>\n";
	echo "<td width='35%' align='center'>".$enddate."</td>\n";
	echo "<td width='15%' align='center'>".$device."</td>\n";
	echo "<td width='10%' align='center'><a href='javascript:do_serial_del(\"".$@."\")'><img src='/pic/delete.jpg' border=0></a></td>\n";
	echo "</tr>\n";
}
?>
							</table>
							</div>
						</td>
					</tr>
					</tbody>
				</table>
      		</td>
      	</tr>
<?=$G_APPLY_BUTTON?>
      </table>
<!-- ________________________________  Main Content End _______________________________ -->
		</td>
	</tr>
</table>
</form>
</body>
</html>	
