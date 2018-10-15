<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_qos_user";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_qos_user";
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

/* --------------------------------------------------------------------------- */

$cfg_qos_state=query("/trafficctrl/qos/enable");
$cfg_fair_state = query("/tc_monitor/state");
$cfg_e2w=query("/trafficctrl/updownlinkset/bandwidth/downlink");
$cfg_w2e=query("/trafficctrl/updownlinkset/bandwidth/uplink");
if($cfg_e2w=="" || $cfg_w2e==""){$judge_enable=0;}else{$judge_enable=1;}
anchor("/trafficctrl/qos/protocol");
$cfg_aui_pri=query("aui/priority");
if($cfg_aui_pri==""){$cfg_aui_pri=0;}
$cfg_aui_lim=query("aui/limit");
if($cfg_aui_lim==""){$cfg_aui_lim=100;}
$cfg_web_pri=query("web/priority");
if($cfg_web_pri==""){$cfg_web_pri=2;}
$cfg_web_lim=query("web/limit");
if($cfg_web_lim==""){$cfg_web_lim=100;}
$cfg_mail_pri=query("mail/priority");
if($cfg_mail_pri==""){$cfg_mail_pri=1;}
$cfg_mail_lim=query("mail/limit");
if($cfg_mail_lim==""){$cfg_mail_lim=100;}
$cfg_ftp_pri=query("ftp/priority");
if($cfg_ftp_pri==""){$cfg_ftp_pri=3;}
$cfg_ftp_lim=query("ftp/limit");
if($cfg_ftp_lim==""){$cfg_ftp_lim=100;}

$cfg_user1_pri=query("user1/priority");
if($cfg_user1_pri==""){$cfg_user1_pri=0;}
$cfg_user1_lim=query("user1/limit");
if($cfg_user1_lim==""){$cfg_user1_lim=100;}
$cfg_user1_st_port=query("user1/startport");
if($cfg_user1_st_port==""){$cfg_user1_st_port=0;}
$cfg_user1_e_port=query("user1/endport");
if($cfg_user1_e_port==""){$cfg_user1_e_port=0;}
$cfg_user2_pri=query("user2/priority");
if($cfg_user2_pri==""){$cfg_user2_pri=1;}
$cfg_user2_lim=query("user2/limit");
if($cfg_user2_lim==""){$cfg_user2_lim=100;}
$cfg_user2_st_port=query("user2/startport");
if($cfg_user2_st_port==""){$cfg_user2_st_port=0;}
$cfg_user2_e_port=query("user2/endport");
if($cfg_user2_e_port==""){$cfg_user2_e_port=0;}
$cfg_user3_pri=query("user3/priority");
if($cfg_user3_pri==""){$cfg_user3_pri=2;}
$cfg_user3_lim=query("user3/limit");
if($cfg_user3_lim==""){$cfg_user3_lim=100;}
$cfg_user3_st_port=query("user3/startport");
if($cfg_user3_st_port==""){$cfg_user3_st_port=0;}
$cfg_user3_e_port=query("user3/endport");
if($cfg_user3_e_port==""){$cfg_user3_e_port=0;}
$cfg_user4_pri=query("user4/priority");
if($cfg_user4_pri==""){$cfg_user4_pri=3;}
$cfg_user4_lim=query("user4/limit");
if($cfg_user4_lim==""){$cfg_user4_lim=100;}
$cfg_user4_st_port=query("user4/startport");
if($cfg_user4_st_port==""){$cfg_user4_st_port=0;}
$cfg_user4_e_port=query("user4/endport");
if($cfg_user4_e_port==""){$cfg_user4_e_port=0;}

$cfg_other_pri=query("other/priority");
if($cfg_other_pri==""){$cfg_other_pri=3;}
$cfg_other_lim=query("other/limit");
if($cfg_other_lim==""){$cfg_other_lim=100;}
?>

<script>
function init()
{
	var f=get_obj("frm");
  change_qos_state();
	AdjustHeight();
}

function change_qos_state()
{
	var f=get_obj("frm");
	if(f.qos_state.checked)
	{
		if(<?=$judge_enable?>==0)
		{
			alert("<?=$m_can_not_enable ?>");
			f.qos_state.checked=false;
			return false;
		}
		if("<?=$cfg_fair_state?>" == 1)
		{
			alert("<?=$a_disable_fair?>");
		}
		fields_disabled(f, false);
	}
	else
		{
			fields_disabled(f, true);
			f.qos_state.disabled=false;
		}
	f.e2w.disabled=f.w2e.disabled=true;
	f.aui_port.disabled=f.web_port.disabled=f.mail_port.disabled=f.ftp_port.disabled=true;
}

function check_values()
{
	var f=get_obj("frm");

	if(f.qos_state.checked)
	{		
		if(check_limit(f.aui_lim)==false)
		{return false;}
		if(check_limit(f.web_lim)==false)
		{return false;}
		if(check_limit(f.mail_lim)==false)
		{return false;}
		if(check_limit(f.ftp_lim)==false)
		{return false;}
		if(check_limit(f.user1_lim)==false)
		{return false;}
		if(check_limit(f.user2_lim)==false)
		{return false;}
		if(check_limit(f.user3_lim)==false)
		{return false;}
		if(check_limit(f.user4_lim)==false)
		{return false;}
		if(check_limit(f.other_lim)==false)
		{return false;}
	
		if(check_valid_port(f.user1_st_port,f.user1_e_port)==false)
		{return false;}
		if(check_valid_port(f.user2_st_port,f.user2_e_port)==false)
		{return false;}
		if(check_valid_port(f.user3_st_port,f.user3_e_port)==false)
		{return false;}
		if(check_valid_port(f.user4_st_port,f.user4_e_port)==false)
		{return false;}
	
		if(compare_ports()==false)
		{return false;}
	}
  return true;
}

function check_valid_port(item1,item2)
{
	var f=get_obj("frm");
	if(item1.value=="")
	{
		alert("<?=$a_empty_value_for_port ?>");
		item1.focus();
		return false;
	}
	else 
		{
			if(!is_digit(item1.value))
		  {
			  alert("<?=$a_invalid_value_for_port ?>");
			  item1.select();
				return false;
		  }
		  if(item1.value <0 || item1.value >65534 || isNaN(item1.value))
		  {
		  	alert("<?=$a_invalid_value_for_port ?>");
			  item1.select();
				return false;
		  } 
		}
		
	if(item2.value=="")
	{
		alert("<?=$a_empty_value_for_port ?>");
		item2.focus();
		return false;
	}
	else 
		{
			if(!is_digit(item2.value))
		  {
			  alert("<?=$a_invalid_value_for_port ?>");
			  item2.select();
				return false;
		  }
		  if(item2.value <0 || item2.value >65534 || isNaN(item2.value))
		  {
		  	alert("<?=$a_invalid_value_for_port ?>");
			  item2.select();
				return false;
		  }
		}
		
	if(item2.value!=0 && item1.value==0)
	{
		alert("<?=$a_st_port_can_not_be_zero ?>");
		item1.select();
		return false;
	}
		
	if(parseInt(item1.value, [10]) > parseInt(item2.value, [10]))
	{
		alert("<?=$a_unrule_value_for_port ?>");
		item1.select();
		return false;
	}
	return true;
}


function check_limit(item)
{
	var f=get_obj("frm");
	if(item.value=="")
	{
		alert("<?=$a_empty_value_for_limit ?>");
		item.focus();
		return false;
	}
	else 
		{
			if(!is_digit(item.value))
		  {
			  alert("<?=$a_invalid_value_for_limit ?>");
			  item.select();
				return false;
		  }
		  if(item.value <1 || item.value >100 || isNaN(item.value))
		  {
		  	alert("<?=$a_invalid_value_for_limit ?>");
			  item.select();
				return false;
		  }
		  return true;
		}
}

function compare_ports()
{
	var f=get_obj("frm");
	if(compare_default_and_defined_ports(f.user1_st_port,f.user1_e_port)==false){return false;}
	if(compare_default_and_defined_ports(f.user2_st_port,f.user2_e_port)==false){return false;}
	if(compare_default_and_defined_ports(f.user3_st_port,f.user3_e_port)==false){return false;}
	if(compare_default_and_defined_ports(f.user4_st_port,f.user4_e_port)==false){return false;}
			
	if(!(parseInt(f.user2_st_port.value, [10]) > parseInt(f.user1_e_port.value, [10]) || parseInt(f.user2_e_port.value, [10]) < parseInt(f.user1_st_port.value, [10])) && f.user1_e_port.value!=0 && f.user2_e_port.value!=0 )
	{
		alert("<?=$a_can_not_be_the_same_port ?>");
		f.user2_st_port.select();
		return false;
	}
	if(!(parseInt(f.user3_st_port.value, [10]) > parseInt(f.user1_e_port.value, [10]) || parseInt(f.user3_e_port.value, [10]) < parseInt(f.user1_st_port.value, [10])) && f.user1_e_port.value!=0 && f.user3_e_port.value!=0)
	{
		alert("<?=$a_can_not_be_the_same_port ?>");
		f.user3_st_port.select();
		return false;
	}
	if(!(parseInt(f.user3_st_port.value, [10]) > parseInt(f.user2_e_port.value, [10]) || parseInt(f.user3_e_port.value, [10]) < parseInt(f.user2_st_port.value, [10])) && f.user2_e_port.value!=0 && f.user3_e_port.value!=0)
	{
		alert("<?=$a_can_not_be_the_same_port ?>");
		f.user3_st_port.select();
		return false;
	}
	if(!(parseInt(f.user4_st_port.value, [10]) > parseInt(f.user1_e_port.value, [10]) || parseInt(f.user4_e_port.value, [10]) < parseInt(f.user1_st_port.value, [10])) && f.user4_e_port.value!=0 && f.user1_e_port.value!=0)
	{
		alert("<?=$a_can_not_be_the_same_port ?>");
		f.user4_st_port.select();
		return false;
	}
	if(!(parseInt(f.user4_st_port.value, [10]) > parseInt(f.user2_e_port.value, [10]) || parseInt(f.user4_e_port.value, [10]) < parseInt(f.user2_st_port.value, [10])) && f.user4_e_port.value!=0 && f.user2_e_port.value!=0)
	{
		alert("<?=$a_can_not_be_the_same_port ?>");
		f.user4_st_port.select();
		return false;
	}
	if(!(parseInt(f.user4_st_port.value, [10]) > parseInt(f.user3_e_port.value, [10]) || parseInt(f.user4_e_port.value, [10]) < parseInt(f.user3_st_port.value, [10])) && f.user4_e_port.value!=0 && f.user3_e_port.value!=0)
	{
		alert("<?=$a_can_not_be_the_same_port ?>");
		f.user4_st_port.select();
		return false;
	}
	return true;
}

function compare_default_and_defined_ports(st_item,e_item)
{
	var f=get_obj("frm");
	var port_list=[20,21,25,53,67,68,80,110,443,465,546,547,995,3128,8080];
	var comlength=port_list.length-1;
	for(var s=0;s<=comlength;s++)
	{
		if(port_list[s] >= parseInt(st_item.value, [10]) && port_list[s] <= parseInt(e_item.value, [10]))
		{
			alert("<?=$a_can_not_use_default_port ?>");
			st_item.select();
			return false;
		}
	}
	return true;
}

function first_zero(item)
{
	for(var i=0;i<item.value.length;i++)
		{
			if(item.value.charAt(i)!=0)
			{
				item.value=item.value.substring(i);
				break;
			}
			if(i==item.value.length-1 && item.value.charAt(i)==0)
			{
				item.value=0;
			}
		}
	return item.value;
}

function check()
{
	var f=get_obj("frm");
	if(check_values()==true)
	{
		f.aui_lim.value=first_zero(f.aui_lim);
		f.web_lim.value=first_zero(f.web_lim);
		f.mail_lim.value=first_zero(f.mail_lim);
		f.ftp_lim.value=first_zero(f.ftp_lim);
		f.user1_lim.value=first_zero(f.user1_lim);
		f.user2_lim.value=first_zero(f.user2_lim);
		f.user3_lim.value=first_zero(f.user3_lim);
		f.user4_lim.value=first_zero(f.user4_lim);
		f.other_lim.value=first_zero(f.other_lim);
		f.user1_st_port.value=first_zero(f.user1_st_port);
		f.user2_st_port.value=first_zero(f.user2_st_port);
		f.user3_st_port.value=first_zero(f.user3_st_port);
		f.user4_st_port.value=first_zero(f.user4_st_port);
		f.user1_e_port.value=first_zero(f.user1_e_port);
		f.user2_e_port.value=first_zero(f.user2_e_port);
		f.user3_e_port.value=first_zero(f.user3_e_port);
		f.user4_e_port.value=first_zero(f.user4_e_port);
	

		fields_disabled(f, false);
		return true;
	}
}

function submit()
{
	var f=get_obj("frm");
	if(check()==true)
	{
		f.submit();
		return true;
	}
}

</script>

<body <?=$G_BODY_ATTR?> onload="init();">

<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">
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
			<table id="table_set_main"  border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
				<tr>
					<td width=25% id="td_left"><?=$m_enable ?></td>
					<td>
						<input type="checkbox" id="qos_state" name="qos_state" value="1" onClick="change_qos_state()" <? if($cfg_qos_state==1){echo "checked";}?>>
					</td>
				</tr>

				<tr>
					<td colspan="2">
						<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr>
								<td class="table_tool_td" valign="middle" colspan="2"><b><?=$m_context_AdvanceQos_title ?></b></td>
							</tr>
							
							<tr>
								<td width="25%"><?=$m_DownlinkTraffic ?></td>
								<td><input type="text" id="e2w" name="e2w" maxlength="6" size="6" value="<?=$cfg_e2w ?>">Mbits/sec
								</td>
							</tr>
							
							<tr>
								<td width="25%"><?=$m_UplinkTraffic ?></td>
								<td><input type="text" id="w2e" name="w2e" maxlength="6" size="6" value="<?=$cfg_w2e ?>">Mbits/sec
								</td>
							</tr>
							
							<tr>
								<td width="25%"><?=$m_AUI_Priority ?></td>
								<td>
									<select id="aui_pri" name="aui_pri">
										<option value="0" <? if($cfg_aui_pri==0){echo "selected";}?>><?=$m_high ?></option>
										<option value="1" <? if($cfg_aui_pri==1){echo "selected";}?>><?=$m_second ?></option>
										<option value="2" <? if($cfg_aui_pri==2){echo "selected";}?>><?=$m_third ?></option>
										<option value="3" <? if($cfg_aui_pri==3){echo "selected";}?>><?=$m_low ?></option>
									</select>
								  <?=$m_PriLimit ?>
									<input type="text" id="aui_lim" name="aui_lim" maxlength="3" size="3" value="<?=$cfg_aui_lim ?>">%
									<?=$m_Port ?>
									<input type="text" id="aui_port" name="aui_port" size="15" value="53,67,68,546,547">
								</td>
							</tr>
							
							<tr>
								<td width="25%"><?=$m_web_Priority ?></td>
								<td>
									<select id="web_pri" name="web_pri">
										<option value="0" <? if($cfg_web_pri==0){echo "selected";}?>><?=$m_high ?></option>
										<option value="1" <? if($cfg_web_pri==1){echo "selected";}?>><?=$m_second ?></option>
										<option value="2" <? if($cfg_web_pri==2){echo "selected";}?>><?=$m_third ?></option>
										<option value="3" <? if($cfg_web_pri==3){echo "selected";}?>><?=$m_low ?></option>
									</select>
								  <?=$m_PriLimit ?>
									<input type="text" id="web_lim" name="web_lim" maxlength="3" size="3" value="<?=$cfg_web_lim ?>">%
									<?=$m_Port ?>
									<input type="text" id="web_port" name="web_port" size="15" value="80,443,3128,8080">
								</td>
							</tr>
							
							<tr>
                <td width="25%"><?=$m_mail_Priority ?></td>
								<td>
									<select id="mail_pri" name="mail_pri">
										<option value="0" <? if($cfg_mail_pri==0){echo "selected";}?>><?=$m_high ?></option>
										<option value="1" <? if($cfg_mail_pri==1){echo "selected";}?>><?=$m_second ?></option>
										<option value="2" <? if($cfg_mail_pri==2){echo "selected";}?>><?=$m_third ?></option>
										<option value="3" <? if($cfg_mail_pri==3){echo "selected";}?>><?=$m_low ?></option>
									</select>
								  <?=$m_PriLimit ?>
									<input type="text" id="mail_lim" name="mail_lim" maxlength="3" size="3" value="<?=$cfg_mail_lim ?>">%
									<?=$m_Port ?>
									<input type="text" id="mail_port" name="mail_port" size="15" value="25,110,465,995">
								</td>
							</tr>
							
							<tr>
								<td width="25%"><?=$m_ftp_Priority ?></td>
								<td>
									<select id="ftp_pri" name="ftp_pri">
										<option value="0" <? if($cfg_ftp_pri==0){echo "selected";}?>><?=$m_high ?></option>
										<option value="1" <? if($cfg_ftp_pri==1){echo "selected";}?>><?=$m_second ?></option>
										<option value="2" <? if($cfg_ftp_pri==2){echo "selected";}?>><?=$m_third ?></option>
										<option value="3" <? if($cfg_ftp_pri==3){echo "selected";}?>><?=$m_low ?></option>
									</select>
								  <?=$m_PriLimit ?>
									<input type="text" id="ftp_lim" name="ftp_lim" maxlength="3" size="3" value="<?=$cfg_ftp_lim ?>">%
									<?=$m_Port ?>
									<input type="text" id="ftp_port" name="ftp_port" size="15" value="20,21">
								</td>
							</tr>
							
							<tr>
								<td width="25%"><?=$m_user1_Priority ?></td>
								<td>
									<select id="user1_pri" name="user1_pri">
										<option value="0" <? if($cfg_user1_pri==0){echo "selected";}?>><?=$m_high ?></option>
										<option value="1" <? if($cfg_user1_pri==1){echo "selected";}?>><?=$m_second ?></option>
										<option value="2" <? if($cfg_user1_pri==2){echo "selected";}?>><?=$m_third ?></option>
										<option value="3" <? if($cfg_user1_pri==3){echo "selected";}?>><?=$m_low ?></option>
									</select>
								  <?=$m_PriLimit ?>
									<input type="text" id="user1_lim" name="user1_lim" maxlength="3" size="3" value="<?=$cfg_user1_lim ?>">%
									<?=$m_Port ?>
									<input type="text" id="user1_st_port" name="user1_st_port" maxlength="5" size="4" value="<?=$cfg_user1_st_port ?>">
									-
									<input type="text" id="user1_e_port" name="user1_e_port" maxlength="5" size="4" value="<?=$cfg_user1_e_port ?>">
								</td>
							</tr>
							
							<tr>
								<td width="25%"><?=$m_user2_Priority ?></td>
								<td>
									<select id="user2_pri" name="user2_pri">
										<option value="0" <? if($cfg_user2_pri==0){echo "selected";}?>><?=$m_high ?></option>
										<option value="1" <? if($cfg_user2_pri==1){echo "selected";}?>><?=$m_second ?></option>
										<option value="2" <? if($cfg_user2_pri==2){echo "selected";}?>><?=$m_third ?></option>
										<option value="3" <? if($cfg_user2_pri==3){echo "selected";}?>><?=$m_low ?></option>
									</select>
								  <?=$m_PriLimit ?>
									<input type="text" id="user2_lim" name="user2_lim" maxlength="3" size="3" value="<?=$cfg_user2_lim ?>">%
									<?=$m_Port ?>
									<input type="text" id="user2_st_port" name="user2_st_port" maxlength="5" size="4" value="<?=$cfg_user2_st_port ?>">
									-
									<input type="text" id="user2_e_port" name="user2_e_port" maxlength="5" size="4" value="<?=$cfg_user2_e_port ?>">
								</td>
							</tr>
							
							<tr>
								<td width="25%"><?=$m_user3_Priority ?></td>
								<td>
									<select id="user3_pri" name="user3_pri">
										<option value="0" <? if($cfg_user3_pri==0){echo "selected";}?>><?=$m_high ?></option>
										<option value="1" <? if($cfg_user3_pri==1){echo "selected";}?>><?=$m_second ?></option>
										<option value="2" <? if($cfg_user3_pri==2){echo "selected";}?>><?=$m_third ?></option>
										<option value="3" <? if($cfg_user3_pri==3){echo "selected";}?>><?=$m_low ?></option>
									</select>
								  <?=$m_PriLimit ?>
									<input type="text" id="user3_lim" name="user3_lim" maxlength="3" size="3" value="<?=$cfg_user3_lim ?>">%
									<?=$m_Port ?>
									<input type="text"  id="user3_st_port" name="user3_st_port"maxlength="5" size="4" value="<?=$cfg_user3_st_port ?>">
									-
									<input type="text"  id="user3_e_port" name="user3_e_port"maxlength="5" size="4" value="<?=$cfg_user3_e_port ?>">
								</td>
							</tr>
							
							<tr>
								<td width="25%"><?=$m_user4_Priority ?></td>
								<td>
									<select id="user4_pri" name="user4_pri">
										<option value="0" <? if($cfg_user4_pri==0){echo "selected";}?>><?=$m_high ?></option>
										<option value="1" <? if($cfg_user4_pri==1){echo "selected";}?>><?=$m_second ?></option>
										<option value="2" <? if($cfg_user4_pri==2){echo "selected";}?>><?=$m_third ?></option>
										<option value="3" <? if($cfg_user4_pri==3){echo "selected";}?>><?=$m_low ?></option>
									</select>
								  <?=$m_PriLimit ?>
									<input type="text" id="user4_lim" name="user4_lim" maxlength="3" size="3" value="<?=$cfg_user4_lim ?>">%
									<?=$m_Port ?> 
									<input type="text"  id="user4_st_port" name="user4_st_port"maxlength="5" size="4" value="<?=$cfg_user4_st_port ?>">
									-
									<input type="text"  id="user4_e_port" name="user4_e_port"maxlength="5" size="4" value="<?=$cfg_user4_e_port ?>">
								</td>
							</tr>
							
							<tr>
                <td width="25%"><?=$m_other_Priority ?></td>
								<td>
									<select id="other_pri" name="other_pri">
										<option value="0" <? if($cfg_other_pri==0){echo "selected";}?>><?=$m_high ?></option>
										<option value="1" <? if($cfg_other_pri==1){echo "selected";}?>><?=$m_second ?></option>
										<option value="2" <? if($cfg_other_pri==2){echo "selected";}?>><?=$m_third ?></option>
										<option value="3" <? if($cfg_other_pri==3){echo "selected";}?>><?=$m_low ?></option>
									</select>
								  <?=$m_PriLimit ?>
									<input type="text" id="other_lim" name="other_lim" maxlength="3" size="3" value="<?=$cfg_other_lim ?>">%
								</td>
							</tr>
							
						</table>
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
