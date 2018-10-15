<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_array_scan";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_array_scan";
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
set("/runtime/web/next_page",$first_frame);
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
echo "<!--debug\n";
$cfg_band = query("/wlan/ch_mode");
$cfg_mode = query("/wlan/inf:1/ap_mode");
$cfg_mode_a = query("/wlan/inf:2/ap_mode");
if($cfg_mode_a != "" && $cfg_mode_a != 0)
{
	$cfg_mode = 1;
}
if(query("/runtime/web/check_scan_value")=="1")
{
	
	anchor("/runtime/web/wlan/inf:1");
}
else
{

	//anchor("/wlan/inf:2");
	anchor("/wlan/inf:1");
}
$cfg_sw_ctrl = query("/sys/swcontroller/enable");
$cfg_ap_array_enable = query("aparray_enable");
$cfg_role		= query("arrayrole_original");
$cfg_ap_array_name = query("arrayname");
$cfg_ap_array_pwd = queryEnc("/wlan/inf:1/aparray_password");
$tmp_connect=query("/runtime/wlan/inf:1/ap_array_members/list/index:1/role");
if($tmp_connect!="")
{
	$tmp_connect=$m_connect;
}
else
{
	$tmp_connect=$m_disconnect;
}
$ap_array_ver=query("/runtime/aparray/version");
if($ap_array_ver=="")
{
	$ap_array_ver="1.0";
}
$cfg_ipv6 = query("/inet/entry:1/ipv6/valid");

set("/runtime/web/check_scan_value",	0);
$cfg_limit_admin_status = query("/sys/adminlimit/status");
$runtime_ap_array_state=query("/runtime/aparray_scan_status");
echo "-->";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>

/* page init functoin */
function do_clear_all()
{
	var f=get_obj("frm");
	f.bsc_network_name.checked=false;
	f.bsc_ssid_visibility.checked=false;
	f.bsc_auto_chann.checked=false;
	f.bsc_channel_width.checked=false;
	f.bsc_security.checked=false;
	if(f.bsc_band != null)
	{
		f.bsc_band.checked=false;
	}
}
function do_scan()
{
	var f=get_obj("frm");
	f.f_scan_value.value="1";	
	check();
	f.submit();	
}
function init()
{
	var f=get_obj("frm");
	if("<?=$cfg_ap_array_enable?>"== 1)
	{
		f.ap_array_enable.checked=true;
	}
	else
	{
		f.ap_array_enable.checked=false;
	}
	if("<?=$cfg_role?>"== 1)
	{
		f.role[0].checked=true;
	}
	else if ("<?=$cfg_role?>"== 2)
	{
		f.role[1].checked=true;
	}
	else
	{
		f.role[2].checked=true;
	}
	f.ap_array_name.value ="<?=$cfg_ap_array_name?>";
	f.ap_array_pwd.value ="<?=$cfg_ap_array_pwd?>";
	on_click_ap_array_enable();
	if("<?=$cfg_mode?>"!=0 || "<?=$cfg_ipv6?>" == 1)
	{
		fields_disabled(f,true);
	}
	AdjustHeight();

}
function on_click_scan_table(id)
{
	var f = get_obj("frm");
	var str = get_obj("scan_array_name"+id).value;
	f.ap_array_name.value=str;
}
function on_click_ap_array_enable()
{
	var f = get_obj("frm");
	if(f.ap_array_enable.checked==true)
	{
		if(<?=$cfg_limit_admin_status?> != 0)
		{
			alert("<?=$a_LimitAdministrator_change?>");
		}
		fields_disabled(f,false);
		if("<?=$runtime_ap_array_state?>"!="1")
		{
			f.scan.disabled=true;
		}
		if("<?=$cfg_ap_array_enable?>" != 1 && "<?=$cfg_sw_ctrl?>" == 1)
		{alert("<?=$a_disable_sw_ctrl?>");}
	}
	else
	{
		fields_disabled(f,true);
		f.ap_array_enable.disabled=false;
	}
}
function on_check_div_display(obj,div_obj)
{
	var f = get_obj("frm");
	
	for(var i=0; i<obj.length; i++)
	{
		get_obj(div_obj[i]).style.display = "none";
		if(get_obj(obj[i]).checked)
		{
			get_obj(div_obj[i]).style.display = "";
		
		}
	}	
	AdjustHeight();
}

/* parameter checking */
function check()
{
	var f=get_obj("frm");
	if(f.ap_array_enable.checked==true)
	{
		f.f_ap_array_enable.value	= 1;
	}
	else
	{
		f.f_ap_array_enable.value	= 0;
	}
	if(f.role[0].checked== true )
	{
		f.f_role.value=1;
	}
	else if(f.role[1].checked== true )
	{
		f.f_role.value=2;
	}
	else
	{
		f.f_role.value=3;
	}
	if(f.f_scan_value.value!="1" && f.ap_array_enable.checked == true)	
		{
	if(is_blank(f.ap_array_name.value))
	{
		alert("<?=$a_empty_name?>");
		f.ap_array_name.focus();
		return false;
	}		
			
	if(first_blank(f.ap_array_name.value))
	{
		alert("<?=$a_first_blank_name?>");
		f.ap_array_name.select();
		return false;
	}
	if(is_blank_in_first_end(f.ap_array_name.value))
	{
		alert("<?=$a_first_end_blank?>");
		f.ap_array_name.select();
		return false;
	}
	if(strchk_hostname(f.ap_array_name.value)==false)
	{
		alert("<?=$a_invalid_name?>");
		f.ap_array_name.select();
		return false;
	}
	if(strchk_unicode(f.ap_array_pwd.value)==true)
	{
		alert("<?=$a_invalid_password?>");
		f.ap_array_pwd.select();
		return false;
	}
	if(is_blank_in_first_end(f.ap_array_pwd.value))
	{
		alert("<?=$a_first_end_blank?>");
		f.ap_array_pwd.select();
		return false;
	}
	}
	fields_disabled(f,false);
	return true;
}

function submit()
{
	if(check()) get_obj("frm").submit();
}
</script>

<body <?=$G_BODY_ATTR?> onload="init();">

<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return false;">
<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="f_ap_array_enable"		value="">
<input type="hidden" name="f_role"		value="">
<input type="hidden" name="f_scan_value"		value="">
<input type="hidden" name="f_wlmode"		value="">

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
					<td width="35%" id="td_left">
						<?=$G_TAG_SCRIPT_START?>genCheckBox("ap_array_enable", "on_click_ap_array_enable(this)");<?=$G_TAG_SCRIPT_END?>
						<?=$m_enable?>
					</td>
					<td id="td_right">
						<?=$m_version?>:<?=$ap_array_ver?>
					</td>
				</tr>
				<tr>
					<td  colspan="2" align="left">
							<input type="radio" id="mode_master" name="role"value="1" onClick="">
							<?=$m_master?>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" id="mode_backup_master" name="role"value="2" onClick="">
							<?=$m_backup_master?>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="radio" id="mode_slave" name="role" value="3" onClick="">
							<?=$m_slave?>			
					</td>
				</tr>
				<tr>
					<td width="35%" id="td_left">
						<?=$m_ap_array_name?>
					</td>
					<td id="td_right">
						<input name="ap_array_name" id="ap_array_name" class="text" type="text" size="20" maxlength="32" value="">
					</td>
				</tr>
				<tr>
					<td width="35%" id="td_left">
						<?=$m_ap_array_pwd?>
					</td>
					<td id="td_right">
						<input name="ap_array_pwd" id="ap_array_pwd" class="text" type="password" size="20" maxlength="32" value="">
					</td>
				</tr>
				<tr>
					<td width="35%" id="td_left">
						<?=$m_scan_ap_array?>
					</td>
					<td id="td_right">
						<input type="button" value="&nbsp;&nbsp;<?=$m_scan?>&nbsp;&nbsp;" name="scan" onclick="do_scan()">
					</td>
				</tr>
				<tr>
					<td width="35%" id="td_left">
						<?=$m_connection_status?>
					</td>
					<td id="td_right">
						<?=$tmp_connect?>
					</td>
				</tr>
				<tr>
					<td width="35%" id="td_left">
						<?=$m_ap_array_list?>
					</td>
					<td id="td_right">
					
					</td>
				</tr>										
				<tr>
					<td  colspan="2" align="left">
						<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr class="list_head" align="left">
								<td width="5%">
									&nbsp;
								</td>
								<td width="15%">
									<?=$m_array_name?>
								</td>
								<td width="23%" align="center">
									<?=$m_master_ip?>
								</td>
								<td width="25%" align="center">
									<?=$m_mac?>
								</td>	
								<td width="8%" >
									<?=$m_master?>
								</td>			
								<td width="8%" >
									<?=$m_backup_master?>
								</td>			
								<td width="8%" >
									<?=$m_slave?>
								</td>			
								<td width="8%" >
									<?=$m_total?>
								</td>																																																																				
							</tr>	
						</table>
						<div class="div_tab">
						<table id="scan_tab" width="100%" border="0"  <?=$G_TABLE_ATTR_CELL_ZERO?>>						
<?
$tmp_mac = 1;
for("/runtime/wlan/inf:1/scan_table/index")
{
	$array_time=query("timer");
	$sys_time=query("/runtime/sys/uptime");
	$temp_time=$sys_time-$array_time;
	if($temp_time>=100) 
	{
		del("/runtime/wlan/inf:1/scan_table/index:".$@);
	}
	else
	{
	echo "<input type=\"hidden\" id=\"scan_array_name".$@."\" name=\"scan_array_name".$@."\" value=\"".query("arrayname")."\">\n";
	
	
	
			if($tmp_mac == 1)
			{
				echo "<tr style=\"background:#CCCCCC;\">\n";
				$tmp_mac =0;
			}
			else
			{
				echo "<tr style=\"background:#B3B3B3;\">\n";
				$tmp_mac =1;
			}
		$master_num=query("master_number");
		$backup_num=query("backup_number");
		$slave_num =query("slaver_number");
		$total=$master_num+$backup_num+$slave_num;
		echo "<td width=\"5%\" align='left'><input type=\"radio\" name=\"scan_table\" onclick=\"on_click_scan_table(".$@.")\"></td>\n";	
			echo "<td width=\"15%\" align=\"left\">".$G_TAG_SCRIPT_START."genTableSSID(\"".get(j,"arrayname")."\",0);".$G_TAG_SCRIPT_END."</td>\n";
		echo "<td width=\"23%\" align=\"center\">".query("ip")."</td>\n";
		echo "<td width=\"25%\" align=\"center\">".query("mac")."</td>\n";
		echo "<td width=\"8%\" align=\"center\">".query("master_number")."</td>\n";
		echo "<td width=\"8%\" align=\"center\">".query("backup_number")."</td>\n";
		echo "<td width=\"8%\" align=\"center\">".query("slaver_number")."</td>\n";
		echo "<td width=\"8%\" align=\"center\">".$total."</td>\n";
		echo "</tr>\n";
	}
}
?>																					
						</table>										
						</div>												
					</td>
				</tr>
				<tr>		
				<tr>	
					<td  colspan="2" align="left">
						<?=$m_current_ap_array_tlb?>
					</td>
				</tr>
				<tr>
					<td  colspan="2" align="left">
						<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr class="list_head" align="left">
								<td width="5" align="left">
									<?=$m_index?>
								</td>
								<td width="45" align="left">
									<?=$m_rule?>
								</td>
								<td width="80" align="left">
									<?=$m_ip_addr?>
								</td>
								<td width="100" align="left">
									<?=$m_mac_addr?>
								</td>						
								<td width="60" align="left">
									<?=$m_location?>
								</td>																																																																	
							</tr>	
						</table>
						<div class="div_tab">
						<table id="current_tab" width="100%" border="0"  <?=$G_TABLE_ATTR_CELL_ZERO?>>						
<?
$tmp_mac = 1;

for("/runtime/wlan/inf:1/ap_array_members/list/index")
{
			if($tmp_mac == 1)
			{
				echo "<tr style=\"background:#CCCCCC;\">\n";
				$tmp_mac =0;
			}
			else
			{
				echo "<tr style=\"background:#B3B3B3;\">\n";
				$tmp_mac =1;
			}
		$tmp_role="";
		$tmp = query("role");
		if($tmp==1)
		{
			$tmp_role=$m_master;
		}
		else if($tmp==2)
		{
			$tmp_role=$m_backup_master;
		}
		else
		{
			$tmp_role=$m_slave;
		}
		echo "<td width=\"30\" align=\"left\">".$@."</td>\n";
		echo "<td width=\"45\" align=\"left\">".$tmp_role."</td>\n";
		echo "<td width=\"80\" align=\"left\">".query("ip")."</td>\n";
		echo "<td width=\"100\" align=\"left\">".query("mac")."</td>\n";
		echo "<td width=\"50\" align=\"left\">".$G_TAG_SCRIPT_START."genTableSSID(\"".query("location")."\",0);".$G_TAG_SCRIPT_END."</td>\n";
		echo "</tr>\n";
	
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
