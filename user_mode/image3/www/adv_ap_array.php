<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_ap_array";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_ap_array";
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
$cfg_mode_g = query("/wlan/inf:1/ap_mode");
$cfg_mode_a = query("/wlan/inf:2/ap_mode");
if($cfg_mode_a == "")
{
	$cfg_mode = query("/wlan/inf:1/ap_mode");
}
else
{
	$cfg_mode = 1;
	if($cfg_mode_g == 0 && $cfg_mode_a == 0)
	{
		$cfg_mode = 0;	
	}
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
$cfg_ap_array_enable = query("aparray_enable");
$cfg_role		= query("arrayrole_original");
$cfg_ap_array_name = query("arrayname");
$cfg_ap_array_pwd = queryEnc("/wlan/inf:1/aparray_password");
$tmp_ap_array_scan = query("/runtime/web/ap_array/scan");
$tmp_connect=query("/runtime/wlan/inf:1/ap_array_members/list/index:1/role");
if($tmp_connect!="")
{
	$tmp_connect=$m_connect;
}
else
{
	$tmp_connect=$m_disconnect;
}
$ap_array_ver=query("/runtime/web/display/ap_array_ver");
if($ap_array_ver=="")
{
	$ap_array_ver="1.0";
}
if(query("/runtime/web/check_scan_value")=="1")
{
	
	anchor("/runtime/web/wlan/inf:1");
}
else
{
	anchor("/aparray/sync");
}
$cfg_limit_admin_status = query("/sys/adminlimit/status");//cici
$cfg_ssid_sync_status=query("ssid");
$cfg_ssidhidden_sync_status=query("ssidhidden");
$cfg_autochannel_sync_status=query("autochannel");
$cfg_channelwidth_sync_status=query("channelwidth");
$cfg_security_sync_status=query("security");
$cfg_band_sync_status=query("band");
$cfg_fixedrate_sync_status=query("fixedrate");
$cfg_beaconinterval_sync_status=query("beaconinterval");
$cfg_dtim_sync_status=query("dtim");
$cfg_txpower_sync_status=query("txpower");
$cfg_wmm_sync_status=query("wmm");
$cfg_acktimeout_sync_status=query("acktimeout");
$cfg_shortgi_sync_status=query("shortgi");
$cfg_igmpsnoop_sync_status=query("igmpsnoop");
$cfg_connectionlimit_sync_status=query("connectionlimit");
$cfg_linkintegrity_sync_status=query("linkintegrity");
//multi and Filters
$cfg_mssid_sync_status=query("multi/ssid");
$cfg_mssid_hidden_sync_status=query("multi/ssid_hidden");
$cfg_msecurity_sync_status=query("multi/security");
$cfg_mwmm_sync_status=query("multi/wmm");
//Qos and  Traffic control
$cfg_qos_sync_status=query("qos");
//VLAN
$cfg_vlan_sync_state=query("vlan");
//schedule
$cfg_schedule_sync_status=query("schedule");
//time and day
$cfg_time_sync_status=query("time");
//log	
$cfg_log_sync_status=query("log");
//limit administrator
$cfg_adminlimit_sync_status=query("adminlimit");
//system name setting
$cfg_system_sync_status=query("system");
//console setting
$cfg_consoleprotocol_sync_status=query("consoleprotocol");
//snmp setting
$cfg_snmp_sync_status=query("snmp");
//ping control
$cfg_pingctl_sync_status=query("pingctl");
//dhcp server	
$cfg_dhcp_sync_status=query("dhcp");
//login setting
$cfg_login_sync_status=query("login");
//ACL
$cfg_acl_sync_status=query("acl");
$cfg_ipv6 = query("/inet/entry:1/ipv6/valid");

set("/runtime/web/check_scan_value",	0);
$runtime_ap_array_state=query("/runtime/aparray_scan_status");
$runtime_ping=query("/runtime/web/display/ping_control");
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
	f.adv_data_rate.checked=false;
	f.adv_beacon_interval.checked=false;
	f.adv_dtim.checked=false;
	f.adv_transmit_power.checked=false;
	f.adv_wmm_wifi.checked=false;
	f.adv_ack_timeout.checked=false;
	f.adv_short_gi.checked=false;
	if("<?=$cfg_ipv6?>" != "1")
	{f.adv_igmp.checked=false;}
	f.adv_link_integrity.checked=false;
	f.adv_conn_limit.checked=false;
	f.adv_acl.checked=false;
	f.mssid.checked=false;
	f.mssid_visibility.checked=false;
	f.msecurity.checked=false;
	f.mwmm.checked=false;
	f.vlan.checked=false;
	f.schedule_settings.checked=false;
	f.qos_setting.checked=false;
	if("<?=$cfg_ipv6?>" != "1")
	{f.dhcp_svr_setting.checked=false;}
	f.log_setting.checked=false;
	f.time_date.checked=false;
	f.limit_admin.checked=false;
	f.sys_name_setting.checked=false;
	f.login_setting.checked=false;
	f.console_setting.checked=false;
	f.snmp_setting.checked=false;
	if("<?=$runtime_ping?>" == "1")
	{f.ping_control_setting.checked=false;}
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
	if("<?=$cfg_mode?>"!=0)
	{
		f.ap_array_enable.checked=false;
		fields_disabled(f,true);
	}
	f.bsc_network_name.checked=<? if ($cfg_ssid_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.bsc_ssid_visibility.checked=<? if ($cfg_ssidhidden_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.bsc_auto_chann.checked=<? if ($cfg_autochannel_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.bsc_channel_width.checked=<? if ($cfg_channelwidth_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.bsc_security.checked=<? if ($cfg_security_sync_status=="1") {echo "true";} else {echo "false";}?>;
	if(f.bsc_band != null)
	{
		f.bsc_band.checked=<? if ($cfg_band_sync_status=="1") {echo "true";} else {echo "false";}?>;
	}
	f.adv_data_rate.checked=<? if ($cfg_fixedrate_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.adv_beacon_interval.checked=<? if ($cfg_beaconinterval_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.adv_dtim.checked=<? if ($cfg_dtim_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.adv_transmit_power.checked=<? if ($cfg_txpower_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.adv_wmm_wifi.checked=<? if ($cfg_wmm_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.adv_ack_timeout.checked=<? if ($cfg_acktimeout_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.adv_short_gi.checked=<? if ($cfg_shortgi_sync_status=="1") {echo "true";} else {echo "false";}?>;
	if("<?=$cfg_ipv6?>" != "1")	
	{f.adv_igmp.checked=<? if ($cfg_igmpsnoop_sync_status=="1") {echo "true";} else {echo "false";}?>;}
	f.adv_link_integrity.checked=<? if ($cfg_linkintegrity_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.adv_conn_limit.checked=<? if ($cfg_connectionlimit_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.adv_acl.checked=<? if ($cfg_acl_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.mssid.checked=<? if ($cfg_mssid_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.mssid_visibility.checked=<? if ($cfg_mssid_hidden_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.msecurity.checked=<? if ($cfg_msecurity_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.mwmm.checked=<? if ($cfg_mwmm_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.vlan.checked=<? if ($cfg_vlan_sync_state=="1") {echo "true";} else {echo "false";}?>;
	f.schedule_settings.checked=<? if ($cfg_schedule_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.qos_setting.checked=<? if ($cfg_qos_sync_status=="1") {echo "true";} else {echo "false";}?>;
	if("<?=$cfg_ipv6?>" != "1")
	{f.dhcp_svr_setting.checked=<? if ($cfg_dhcp_sync_status=="1") {echo "true";} else {echo "false";}?>;}
	f.log_setting.checked=<? if ($cfg_log_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.time_date.checked=<? if ($cfg_time_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.limit_admin.checked=<? if ($cfg_adminlimit_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.sys_name_setting.checked=<? if ($cfg_system_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.login_setting.checked=<? if ($cfg_login_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.console_setting.checked=<? if ($cfg_consoleprotocol_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.snmp_setting.checked=<? if ($cfg_snmp_sync_status=="1") {echo "true";} else {echo "false";}?>;
	if("<?=$runtime_ping?>" == "1")
	{f.ping_control_setting.checked=<? if ($cfg_pingctl_sync_status=="1") {echo "true";} else {echo "false";}?>;}
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
//	if(strchk_unicode(f.ap_array_name.value))
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
	}
	chkall();
	fields_disabled(f,false);
	return true;
}
function chkall()
{
   var f=get_obj("frm");
    
    var objLen = f.length;
    for (var iCount = 0; iCount < objLen; iCount++)
    {
        if (f.elements[iCount].type == "checkbox")
        {
            checkbox_value(f.elements[iCount]);
        }
    }
}

function submit()
{
	if(check()) get_obj("frm").submit();
}
function checkbox_value(s)
{
	if(s.checked==false)
	{
		s.checked=true;
		s.value=0;
	}	
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
if($tmp_ap_array_scan=="1")
{
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
					<td  colspan="2" align="left">
						<?=$m_sync_parameter?>&nbsp;&nbsp;<input type="button" value="&nbsp;<?=$m_clear_all?>&nbsp;" name="clear_all" onclick="do_clear_all()">
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr>
								<td class="table_tool_td" valign="middle" colspan="2">
									<b><?=$m_wireless_basic_setting?></b>&nbsp;
									<?=$G_TAG_SCRIPT_START?>genCheckBox("basic_setting","on_check_div_display(['basic_setting'],['div_basic'])");<?=$G_TAG_SCRIPT_END?>
								</td>
							</tr>
							<tr>
								<td colspan="2">	
									<div id="div_basic" style="display:none;">
									<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>				
										<tr>
											<td width="35%" id="td_left">
												<?=$m_network_name?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("bsc_network_name","");<?=$G_TAG_SCRIPT_END?>
											</td>
											<td width="35%" id="td_left">
												<?=$m_ssid_visibility?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("bsc_ssid_visibility","");<?=$G_TAG_SCRIPT_END?>
											</td>
										</tr>
										<tr>
											<td id="td_left">
												<?=$m_auto_chann?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("bsc_auto_chann","");<?=$G_TAG_SCRIPT_END?>
											</td>
											<td id="td_left">
												<?=$m_channel_width?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("bsc_channel_width","");<?=$G_TAG_SCRIPT_END?>
											</td>
										</tr>
										<tr>	
											<td id="td_left">
												<?=$m_security?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("bsc_security","");<?=$G_TAG_SCRIPT_END?>
											</td>
<? if($TITLE == "DAP-1353" || $TITLE == "DAP-2360" || $TITLE == "DWP-2360") {echo "<!--";}?>
											<td id="td_left">
												<?=$m_band?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("bsc_band","");<?=$G_TAG_SCRIPT_END?>
											</td>
<? if($TITLE == "DAP-1353" || $TITLE == "DAP-2360" || $TITLE == "DWP-2360") {echo "-->";}?>
										</tr>		
									</table>
									</div>
								</td>
							</tr>																												
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr>
								<td class="table_tool_td" valign="middle" colspan="2">
									<b><?=$m_wireless_adv_setting?></b>&nbsp;
									<?=$G_TAG_SCRIPT_START?>genCheckBox("adv_setting","on_check_div_display(['adv_setting'],['div_adv'])");<?=$G_TAG_SCRIPT_END?>
								</td>
							</tr>
							<tr>
								<td colspan="2">	
									<div id="div_adv" style="display:none;">
									<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>				
										<tr>
											<td width="35%" id="td_left">
												<?=$m_data_rate?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("adv_data_rate","");<?=$G_TAG_SCRIPT_END?>
											</td>
											<td width="35%" id="td_left">
												<?=$m_beacon_interval?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("adv_beacon_interval","");<?=$G_TAG_SCRIPT_END?>
											</td>
										</tr>	
										<tr>
											<td id="td_left">
												<?=$m_dtim?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("adv_dtim","");<?=$G_TAG_SCRIPT_END?>
											</td>
											<td id="td_left">
												<?=$m_transmit_power?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("adv_transmit_power","");<?=$G_TAG_SCRIPT_END?>
											</td>
										</tr>
										<tr>									
											<td id="td_left">
												<?=$m_wmm_wifi?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("adv_wmm_wifi","");<?=$G_TAG_SCRIPT_END?>
											</td>
											<td id="td_left">
												<?=$m_ack_timeout?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("adv_ack_timeout","");<?=$G_TAG_SCRIPT_END?>
											</td>
										</tr>
										<tr>	
											<td id="td_left">
												<?=$m_acl?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("adv_acl","");<?=$G_TAG_SCRIPT_END?>
											</td>
											<td id="td_left">
												<?=$m_short_gi?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("adv_short_gi","");<?=$G_TAG_SCRIPT_END?>
											</td>
										</tr>
										<tr>	
											<td id="td_left">
												<?=$m_link_integrity?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("adv_link_integrity","");<?=$G_TAG_SCRIPT_END?>
											</td>
											<td id="td_left">
												<?=$m_conn_limit?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("adv_conn_limit","");<?=$G_TAG_SCRIPT_END?>
											</td>
										</tr>
<? if($cfg_ipv6 == "1") {echo "<!--";}?>
										<tr>
											<td id="td_left">
												<?=$m_igmp?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("adv_igmp","");<?=$G_TAG_SCRIPT_END?>
											</td>
										</tr>	
<? if($cfg_ipv6 == "1") {echo "-->";}?>
									</table>
									</div>
								</td>
							</tr>																												
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr>
								<td class="table_tool_td" valign="middle" colspan="2">
									<b><?=$m_mssid_vlan?></b>&nbsp;
									<?=$G_TAG_SCRIPT_START?>genCheckBox("mssid_vlan","on_check_div_display(['mssid_vlan'],['div_mssid_vlan'])");<?=$G_TAG_SCRIPT_END?>
								</td>
							</tr>
							<tr>
								<td colspan="2">	
									<div id="div_mssid_vlan" style="display:none;">
									<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>				
										<tr>
											<td width="35%" id="td_left">
												<?=$m_ssid?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("mssid","");<?=$G_TAG_SCRIPT_END?>
											</td>
											<td width="35%" id="td_left">
												<?=$m_ssid_visibility?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("mssid_visibility","");<?=$G_TAG_SCRIPT_END?>
											</td>
										</tr>	
										<tr>
											<td id="td_left">
												<?=$m_security?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("msecurity","");<?=$G_TAG_SCRIPT_END?>
											</td>
											<td id="td_left">
												<?=$m_wmm?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("mwmm","");<?=$G_TAG_SCRIPT_END?>
											</td>
										</tr>
										<tr>
											<td id="td_left">
												<?=$m_vlan?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("vlan","");<?=$G_TAG_SCRIPT_END?>
											</td>
										</tr>
									</table>
									</div>
								</td>
							</tr>																												
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr>
								<td class="table_tool_td" valign="middle" colspan="2">
									<b><?=$m_adv_func?></b>&nbsp;
									<?=$G_TAG_SCRIPT_START?>genCheckBox("adv_func","on_check_div_display(['adv_func'],['div_adv_func'])");<?=$G_TAG_SCRIPT_END?>
								</td>
							</tr>
							<tr>
								<td colspan="2">	
									<div id="div_adv_func" style="display:none;">
									<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>				
										<tr>
											<td width="35%" id="td_left">
												<?=$m_schedule_settings?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("schedule_settings","");<?=$G_TAG_SCRIPT_END?>
											</td>
											<td width="35%" id="td_left">
												<?=$m_qos_setting?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("qos_setting","");<?=$G_TAG_SCRIPT_END?>
											</td>
										</tr>	
										<tr>
											<td id="td_left">
												<?=$m_log_setting?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("log_setting","");<?=$G_TAG_SCRIPT_END?>
											</td>
											<td id="td_left">
												<?=$m_time_date?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("time_date","");<?=$G_TAG_SCRIPT_END?>
											</td>
										</tr>
<? if($cfg_ipv6 == "1") {echo "<!--";}?>
										<tr>
											<td id="td_left">
												<?=$m_dhcp_svr_setting?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("dhcp_svr_setting","");<?=$G_TAG_SCRIPT_END?>
											</td>
										</tr>
<? if($cfg_ipv6 == "1") {echo "-->";}?>
									</table>
									</div>
								</td>
							</tr>																												
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr>
								<td class="table_tool_td" valign="middle" colspan="2">
									<b><?=$m_admin_setting?></b>&nbsp;
									<?=$G_TAG_SCRIPT_START?>genCheckBox("admin_setting","on_check_div_display(['admin_setting'],['div_admin_setting'])");<?=$G_TAG_SCRIPT_END?>
								</td>
							</tr>
							<tr>
								<td colspan="2">	
									<div id="div_admin_setting" style="display:none;">
									<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>				
										<tr>
											<td width="35%" id="td_left">
                        <?=$m_sys_name_setting?>
                      </td>
                      <td id="td_right">
                         <?=$G_TAG_SCRIPT_START?>genCheckBox("sys_name_setting","");<?=$G_TAG_SCRIPT_END?>
                      </td>
											<td id="td_left">
												<?=$m_snmp_setting?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("snmp_setting","");<?=$G_TAG_SCRIPT_END?>
											</td>
										</tr>
										<tr>
											<td id="td_left">
												<?=$m_login_setting?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("login_setting","");<?=$G_TAG_SCRIPT_END?>
											</td>
											<td id="td_left">
												<?=$m_console_setting?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("console_setting","");<?=$G_TAG_SCRIPT_END?>
											</td>
										</tr>
										<tr>
											<td id="td_left">
												<?=$m_limit_admin?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("limit_admin","");<?=$G_TAG_SCRIPT_END?>
											</td>
<? if(query("/runtime/web/display/ping_control") != "1") {echo "<!--";}?>
											<td id="td_left">
												<?=$m_ping_control_setting?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("ping_control_setting","");<?=$G_TAG_SCRIPT_END?>
											</td>
<? if(query("/runtime/web/display/ping_control") != "1") {echo "-->";}?>
										</tr>	
									</table>
								</td>
							</tr>																											
						</table>
					</td>
				</div>	
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
