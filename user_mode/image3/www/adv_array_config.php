<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_array_config";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_array_config";
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
$cfg_array_scan = query("/wlan/inf:1/aparray_enable");
$cfg_config = query("/sys/aparray/syncconfig/enable");
if($cfg_config != 1){$cfg_config = 0;}
$cap_v1 = query("/runtime/web/display/cap_v1");
anchor("/aparray/sync");
$cfg_ssid_sync_status=query("ssid");
$cfg_wlan_status=query("wlanstate");
$cfg_wlmode_status=query("wlmode");
$cfg_captival_profile_status=query("captivalstate");
$cfg_ssidhidden_sync_status=query("ssidhidden");
$cfg_autochannel_sync_status=query("autochannel");
$cfg_channelwidth_sync_status=query("channelwidth");
$cfg_security_sync_status=query("security");
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
$cfg_mssid_captival=query("multi/captival");
//Qos and  Traffic control
$cfg_qos_sync_status=query("qos");
//VLAN
$cfg_vlan_sync_state=query("vlan");
//schedule
$cfg_schedule_sync_status=query("schedule");
//time and day
$cfg_time_sync_status=query("time");
//captival portal
$cfg_captival_portal_status=query("captivalportal");
//arp Spoofing Prevention
$cfg_arp_spoofing_status=query("arpspoofing");
//fair air tiem
$cfg_fair_air_time_status=query("fairairtime");
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
$cfg_autorf_sync_status=query("autorf");
$cfg_loadbalance_sync_status=query("loadbalance");
//login setting
$cfg_login_sync_status=query("login");
//ACL
$cfg_acl_sync_status=query("acl");
$cfg_ipv6 = query("/inet/entry:1/ipv6/valid");

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
	if(f.bsc_captival_profile != null)
		f.bsc_captival_profile.checked=false;
	f.adv_wlan_enable.checked=false;
	f.adv_wlan_mode.checked=false;
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
	if(f.mcaptival_profile != null)
		f.mcaptival_profile.checked=false;
	f.vlan.checked=false;
	f.schedule_settings.checked=false;
	f.qos_setting.checked=false;
	if("<?=$cfg_ipv6?>" != "1")
	{f.dhcp_svr_setting.checked=false;}
	f.autorf.checked=false;
	f.loadbalance.checked=false;
	f.log_setting.checked=false;
	f.time_date.checked=false;
	f.captival_portal.checked=false;
	f.arp_spoofing.checked=false;
	f.fair_air_time.checked=false;
	f.sys_name_setting.checked=false;
	f.login_setting.checked=false;
	f.console_setting.checked=false;
	f.snmp_setting.checked=false;
	if("<?=$runtime_ping?>" == "1")
	{f.ping_control_setting.checked=false;}
}
function init()
{
	var f=get_obj("frm");
	f.enable_config.value = "<?=$cfg_config?>";
	on_change_config();
	if("<?=$cfg_array_scan?>" != 1)
	{
		fields_disabled(f,true);
	}

	f.bsc_network_name.checked=<? if ($cfg_ssid_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.bsc_ssid_visibility.checked=<? if ($cfg_ssidhidden_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.bsc_auto_chann.checked=<? if ($cfg_autochannel_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.bsc_channel_width.checked=<? if ($cfg_channelwidth_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.bsc_security.checked=<? if ($cfg_security_sync_status=="1") {echo "true";} else {echo "false";}?>;
	if(f.bsc_captival_profile != null)
		f.bsc_captival_profile.checked=<? if ($cfg_captival_profile_status=="1") {echo "true";} else {echo "false";}?>;
	f.adv_wlan_enable.checked=<? if ($cfg_wlan_status=="1") {echo "true";} else {echo "false";}?>;
	f.adv_wlan_mode.checked=<? if ($cfg_wlmode_status=="1") {echo "true";} else {echo "false";}?>;
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
	if(f.mcaptival_profile != null)
		f.mcaptival_profile.checked=<? if ($cfg_mssid_captival=="1") {echo "true";} else {echo "false";}?>;
	f.vlan.checked=<? if ($cfg_vlan_sync_state=="1") {echo "true";} else {echo "false";}?>;
	f.schedule_settings.checked=<? if ($cfg_schedule_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.qos_setting.checked=<? if ($cfg_qos_sync_status=="1") {echo "true";} else {echo "false";}?>;
	if("<?=$cfg_ipv6?>" != "1")
	{f.dhcp_svr_setting.checked=<? if ($cfg_dhcp_sync_status=="1") {echo "true";} else {echo "false";}?>;}
	f.autorf.checked=<? if ($cfg_autorf_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.loadbalance.checked=<? if ($cfg_loadbalance_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.log_setting.checked=<? if ($cfg_log_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.time_date.checked=<? if ($cfg_time_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.captival_portal.checked=<? if ($cfg_captival_portal_status=="1") {echo "true";} else {echo "false";}?>;
	f.arp_spoofing.checked=<? if ($cfg_arp_spoofing_status=="1") {echo "true";} else {echo "false";}?>;
	f.fair_air_time.checked=<? if ($cfg_fair_air_time_status=="1") {echo "true";} else {echo "false";}?>;
	f.sys_name_setting.checked=<? if ($cfg_system_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.login_setting.checked=<? if ($cfg_login_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.console_setting.checked=<? if ($cfg_consoleprotocol_sync_status=="1") {echo "true";} else {echo "false";}?>;
	f.snmp_setting.checked=<? if ($cfg_snmp_sync_status=="1") {echo "true";} else {echo "false";}?>;
	if("<?=$runtime_ping?>" == "1")
	{f.ping_control_setting.checked=<? if ($cfg_pingctl_sync_status=="1") {echo "true";} else {echo "false";}?>;}
	AdjustHeight();

}

function on_change_config()
{
	var f = get_obj("frm");
	if(f.enable_config.value == 1)
	{
		fields_disabled(f, false);
	}
	else
	{
		fields_disabled(f, true);
		f.enable_config.disabled = f.basic_setting.disabled = f.adv_setting.disabled = f.mssid_vlan.disabled = f.adv_func.disabled = f.admin_setting.disabled = false;
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
					<td width="30%"><?=$m_enable_config?></td>
					<td><?=$G_TAG_SCRIPT_START?>genSelect("enable_config", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"],"on_change_config()");<?=$G_TAG_SCRIPT_END?></td>
				</tr>
				<tr>	
					<td colspan="2" align="left">
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
<?if($cap_v1 != 1){echo "<!--";}?>
											<td id="td_left">
												<?=$m_captival_profile?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("bsc_captival_profile","");<?=$G_TAG_SCRIPT_END?>
											</td>
<?if($cap_v1 != 1){echo "-->";}?>
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
                                                                                                <?=$m_wl_enable?>
                                                                                        </td>
                                                                                        <td id="td_right">
                                                                                                <?=$G_TAG_SCRIPT_START?>genCheckBox("adv_wlan_enable","");<?=$G_TAG_SCRIPT_END?>
                                                                                        </td>
                                                                                        <td width="35%" id="td_left">
                                                                                                <?=$m_wlmode?>
                                                                                        </td>
                                                                                        <td id="td_right">
                                                                                                <?=$G_TAG_SCRIPT_START?>genCheckBox("adv_wlan_mode","");<?=$G_TAG_SCRIPT_END?>
                                                                                        </td>
                                                                                </tr>
										<tr>
											<td id="td_left">
												<?=$m_data_rate?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("adv_data_rate","");<?=$G_TAG_SCRIPT_END?>
											</td>
											<td id="td_left">
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
<?if($cap_v1 != 1){echo "<!--";}?>
											<td id="td_left">
												<?=$m_captival_profile?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("mcaptival_profile","");<?=$G_TAG_SCRIPT_END?>
											</td>
<?if($cap_v1 != 1){echo "-->";}?>
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
										<tr>
											<td id="td_left">
												<?=$m_arp_spoofing?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("arp_spoofing","");<?=$G_TAG_SCRIPT_END?>
											</td>
											<td id="td_left">
												<?=$m_fair_air_time?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("fair_air_time","");<?=$G_TAG_SCRIPT_END?>
											</td>
										</tr>
										<tr>
											<td id="td_left">
												<?=$m_captival_portal?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("captival_portal","");<?=$G_TAG_SCRIPT_END?>
											</td>
											<td id="td_left">
												<?=$m_autorf?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("autorf","");<?=$G_TAG_SCRIPT_END?>
											</td>
										</tr>
										<tr>
											<td id="td_left">
												<?=$m_loadbalance?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("loadbalance","");<?=$G_TAG_SCRIPT_END?>
											</td>
<? if($cfg_ipv6 == "1") {echo "<!--";}?>
											<td id="td_left">
												<?=$m_dhcp_svr_setting?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("dhcp_svr_setting","");<?=$G_TAG_SCRIPT_END?>
											</td>
<? if($cfg_ipv6 == "1") {echo "-->";}?>
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
<? if(query("/runtime/web/display/ping_control") != "1") {echo "<!--";}?>
										<tr>
											<td id="td_left">
												<?=$m_ping_control_setting?>
											</td>
											<td id="td_right">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("ping_control_setting","");<?=$G_TAG_SCRIPT_END?>
											</td>
										</tr>
<? if(query("/runtime/web/display/ping_control") != "1") {echo "-->";}?>
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
