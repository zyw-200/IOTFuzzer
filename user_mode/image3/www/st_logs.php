<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "st_logs";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "st_logs";
set("/runtime/web/help_page",$MY_NAME);
/* --------------------------------------------------------------------------- */
if($ACTION_POST != "")
{
	require("/www/model/__admin_check.php");
	require("/www/__action.php");
	$ACTION_POST = "";
	exit;
}

/* --------------------------------------------------------------------------- */
require("/www/model/__html_head.php");
require("/www/comm/__js_ip.php");
set("/runtime/web/next_page",$first_frame);
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
anchor("/security/log");
$log_system_activity	= query("apsystemInfo");
$log_wireless_activity	= query("WirelessInfo");
$log_notice	= query("apnoticeInfo");
$smtp_info = query("smtpInfo"); //smtp server enable

anchor("/sys/log");
$cfg_log_schedule = query("timemail");
$log_server	= query("logserver"); //log server ip
$eu_server = query("eulogserver");
$smtp_mailserver = query("mailserver");
$smtp_from_email = query("fromemail");
$smtp_to_email = query("toemail");
$smtp_name = query("username");
$smtp_password = queryEnc("pass1");
$smtp_port = query("mailport");
$cfg_smtp_index=query("smtpindex");  
anchor("/sys/log/smtp/index:1");
$smtp1_authtype = query("authtype");
$smtp1_ssl = query("ssl");
$smtp1_mailserver = query("mailserver");
$smtp1_from_email = query("fromemail");
$smtp1_to_email = query("toemail");
$smtp1_name = query("username");
$smtp1_password = queryEnc("pass1");
$smtp1_port = query("mailport");
anchor("/sys/log/smtp/index:2");/*gmail*/
$smtp2_authtype = query("authtype");
$smtp2_ssl = query("ssl");
$smtp2_mailserver = query("mailserver");
if($smtp2_mailserver=="")
{
	$smtp2_mailserver="smtp.gmail.com";
}
$smtp2_from_email = query("fromemail");
$smtp2_to_email = query("toemail");
$smtp2_name = query("username");
$smtp2_password = queryEnc("pass1");
$smtp2_port = query("mailport");
if($smtp2_port=="")
{
	$smtp2_port="25";
}
anchor("/sys/log/smtp/index:3");/*hotmail*/
$smtp3_authtype = query("authtype");
$smtp3_ssl = query("ssl");
$smtp3_mailserver = query("mailserver");
if($smtp3_mailserver=="")
{
	$smtp3_mailserver="smtp.hotmail.com";
}
$smtp3_from_email = query("fromemail");
$smtp3_to_email = query("toemail");
$smtp3_name = query("username");
$smtp3_password = queryEnc("pass1");
$smtp3_port = query("mailport");
if($smtp3_port=="")
{
	$smtp3_port="25";
}
$flag_autorekey = query("/runtime/web/display/autorekey");
if(query("/wan/rg/inf:1/mode") == 1)
{
	$cfg_mask = query("/wan/rg/inf:1/static/netmask");
}
else
{
	$cfg_mask = query("/runtime/wan/inf:1/netmask");
}
/* --------------------------------------------------------------------------- */
?>


<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>
function on_change_email_type()
{
	var f=get_obj("frm");	
	if(f.email_type.value==2)
	{
		if(f.auth_enable != null)
			f.auth_enable.checked = <? if ($smtp2_authtype=="1") {echo "true";} else {echo "false";}?>;
		if(f.ssl_enable != null)
			f.ssl_enable.checked = <? if ($smtp2_ssl=="1") {echo "true";} else {echo "false";}?>;
		if(f.smtp_ip != null)
			f.smtp_ip.value = "<?=$smtp2_mailserver?>";
		if(f.smtp_from_email != null)
			f.smtp_from_email.value = "<?=$smtp2_from_email?>";
		if(f.smtp_to_email != null)
			f.smtp_to_email.value = "<?=$smtp2_to_email?>";	
		if(f.smtp_name != null)
			f.smtp_name.value = "<?=$smtp2_name?>";
		if(f.smtp_password != null)
		{
			f.smtp_password.value = "<?=$smtp2_password?>";	
		f.confirm_password.value = "<?=$smtp2_password?>";		
		}
		if(f.smtp_port != null)
			f.smtp_port.value = "<?=$smtp2_port?>";	
		f.auth_enable.disabled = true;
		f.ssl_enable.disabled = true;	
	}
	else if (f.email_type.value==3)
	{
		if(f.auth_enable != null)
			f.auth_enable.checked = <? if ($smtp3_authtype=="1") {echo "true";} else {echo "false";}?>;
		if(f.ssl_enable != null)
			f.ssl_enable.checked = <? if ($smtp3_ssl=="1") {echo "true";} else {echo "false";}?>;
		if(f.smtp_ip != null)
			f.smtp_ip.value = "<?=$smtp3_mailserver?>";
		if(f.smtp_from_email != null)
			f.smtp_from_email.value = "<?=$smtp3_from_email?>";
		if(f.smtp_to_email != null)
			f.smtp_to_email.value = "<?=$smtp3_to_email?>";	
		if(f.smtp_name != null)
			f.smtp_name.value = "<?=$smtp3_name?>";
		if(f.smtp_password != null)
		{
			f.smtp_password.value = "<?=$smtp3_password?>";	
		f.confirm_password.value = "<?=$smtp3_password?>";		
		}
		if(f.smtp_port != null)
			f.smtp_port.value = "<?=$smtp3_port?>";	
		f.auth_enable.disabled = true;
		f.ssl_enable.disabled = true;		
	}
	else
	{
		if(f.auth_enable != null)
			f.auth_enable.checked = <? if ($smtp1_authtype=="1") {echo "true";} else {echo "false";}?>;
		if(f.ssl_enable != null)
			f.ssl_enable.checked = <? if ($smtp1_ssl=="1") {echo "true";} else {echo "false";}?>;
		if(f.smtp_ip != null)
			f.smtp_ip.value = "<?=$smtp1_mailserver?>";
		if(f.smtp_from_email != null)
			f.smtp_from_email.value = "<?=$smtp1_from_email?>";
		if(f.smtp_to_email != null)
			f.smtp_to_email.value = "<?=$smtp1_to_email?>";	
		if(f.smtp_name != null)
			f.smtp_name.value = "<?=$smtp1_name?>";
		if(f.smtp_password != null)
		{
			f.smtp_password.value = "<?=$smtp1_password?>";	
		f.confirm_password.value = "<?=$smtp1_password?>";		
		}
		if(f.smtp_port != null)
			f.smtp_port.value = "<?=$smtp1_port?>";	
		if(f.smtp_enable.checked==true)
		{
			f.auth_enable.disabled = false;
			f.ssl_enable.disabled = false;	
		}
	}
}
function on_check_smtp()
{
	var f=get_obj("frm");	
	
	if(f.smtp_enable.checked)
	{
		if(f.email_type != null)		
		f.email_type.disabled = false;
		if(f.auth_enable != null)
		f.auth_enable.disabled = false;
		if(f.ssl_enable != null)
		f.ssl_enable.disabled = false;
		f.smtp_ip.disabled = false;
		f.smtp_from_email.disabled = false;
		f.smtp_to_email.disabled = false;
		f.smtp_name.disabled = false;
		f.smtp_password.disabled = false;
		f.confirm_password.disabled = false;
		f.smtp_port.disabled = false;
	}
	else
	{
		if(f.email_type != null)
		f.email_type.disabled = true;
		if(f.auth_enable != null)
		f.auth_enable.disabled = true;
		if(f.ssl_enable != null)
		f.ssl_enable.disabled = true;
		f.smtp_ip.disabled = true;
		f.smtp_from_email.disabled = true;
		f.smtp_to_email.disabled = true;
		f.smtp_name.disabled = true;
		f.smtp_password.disabled = true;
		f.confirm_password.disabled = true;	
		f.smtp_port.disabled = true;	
	}		
	if("<?=$flag_autorekey?>"==1)		
	{
	on_change_email_type();	
}
}

/* page init functoin */
function init()
{
	var f = get_obj("frm");
	f.log_ip.value = "<?=$log_server?>";
	f.eu_ip.value = "<?=$eu_server?>";
	f.system_activity.checked = <? if ($log_system_activity=="1") {echo "true";} else {echo "false";}?>;
	f.wireless_activity.checked = <? if ($log_wireless_activity=="1") {echo "true";} else {echo "false";}?>;
	f.notice.checked = <? if ($log_notice=="1") {echo "true";} else {echo "false";}?>;
	if(f.smtp_enable != null)
		f.smtp_enable.checked = <? if ($smtp_info=="1") {echo "true";} else {echo "false";}?>;	
	if(f.email_type != null)
	select_index(f.email_type, "<?=$cfg_smtp_index?>");
        if(f.smtp_ip != null)
		f.smtp_ip.value = "<?=$smtp_mailserver?>";
	if(f.smtp_from_email != null)
		f.smtp_from_email.value = "<?=$smtp_from_email?>";
	if(f.smtp_to_email != null)
		f.smtp_to_email.value = "<?=$smtp_to_email?>";	
	if(f.smtp_name != null)
		f.smtp_name.value = "<?=$smtp_name?>";
	if(f.smtp_password != null)
	{
		f.smtp_password.value = "<?=$smtp_password?>";	
		f.confirm_password.value = "<?=$smtp_password?>";	
	}
	if(f.smtp_port != null)
			f.smtp_port.value = "<?=$smtp_port?>";	
	if(f.log_schedule != null)
		select_index(f.log_schedule, "<?=$cfg_log_schedule?>");
	if(f.smtp_enable != null)
		on_check_smtp();
	
	AdjustHeight();
}
function email_model(str)
{
	var flag_code=0;
	var i;

	for (i=0; i<str.length; i++)
	{
		if (str.charCodeAt(0)!=64)
		{
			if(str.charCodeAt(i)==64) 
			{
				flag_code=1;
			}
			continue;
		}
		return false;
	}
	if(flag_code==1)
	{
		return true;
	}
	else
	{
		return false;
	}
}
/* parameter checking */
function check()
{
	var f=get_obj("frm");
	
/*	if(!is_valid_ip3(f.log_ip.value, 1))
	{
		alert("<?=$a_invalid_log_ip?>");
		field_focus(f.log_ip, "**");
		return false;
	}
	if(!is_valid_ip3(f.eu_ip.value, 1))
	{
		alert("<?=$a_invalid_log_ip?>");
		field_focus(f.eu_ip, "**");
		return false;
	}*/

	if(f.smtp_enable != null)
	{
		if(f.smtp_enable.checked)
		{
			if(f.smtp_from_email != null)
			{
				if (!strchk_email(f.smtp_from_email.value))
				{
					alert("<?=$a_invalid_email?>");
                    f.smtp_from_email.select();
					return false;
				}	
				if (!email_model(f.smtp_from_email.value))
				{
					alert("<?=$a_invalid_email?>");
					field_focus(f.smtp_from_email, "**");
					return false;
				}	
				var tmp_from_email=f.smtp_from_email.value.split("@");
                if(tmp_from_email[0].length > 64)
                {
                    alert("<?=$a_long_email_name?>");
                    field_focus(f.smtp_from_email, "**");
                    return false;
                }
                if(tmp_from_email[1] == "")
                {
                    alert("<?=$a_invalid_email?>");
                    field_focus(f.smtp_from_email, "**");
                    return false;
                }
			}	
			if(f.smtp_to_email != null)
			{
				if (!strchk_email(f.smtp_to_email.value))
				{
					alert("<?=$a_invalid_email?>");
					f.smtp_to_email.select();
					return false;
				}	
				if (!email_model(f.smtp_to_email.value))
				{
					alert("<?=$a_invalid_email?>");
					field_focus(f.smtp_to_email, "**");
					return false;
				}	
				var tmp_to_email=f.smtp_to_email.value.split("@");
                if(tmp_to_email[0].length > 64)
                {
                    alert("<?=$a_long_email_name?>");
                    field_focus(f.smtp_to_email, "**");
                    return false;
                }
                if(tmp_to_email[1] == "")
                {
                    alert("<?=$a_invalid_email?>");
                    field_focus(f.smtp_to_email, "**");
                    return false;
                }
			}	
/*			if(f.smtp_ip != null)
			{
				if (!is_valid_ip3(f.smtp_ip.value, 0))
				{
					alert("<?=$a_invalid_smtp_ip?>");
					field_focus(f.smtp_ip, "**");
					return false;
				}	
			}	*/
			
			if(f.smtp_port != null)
			{
				if (!is_valid_port_str(f.smtp_port.value))
				{
					alert("<?=$a_invalid_smtp_port?>");
					field_focus(f.smtp_port, "**");
					f.smtp_port.value = 25;
					return false;
				}	
			}				
			
			if(f.smtp_name != null)
			{
				if(is_blank(f.smtp_name.value))
				{
					alert("<?=$a_empty_user_name?>");
					f.smtp_name.select();
					return false;
				}
		
				if(first_blank(f.smtp_name.value))
				{
					alert("<?=$a_first_blank_user_name?>");
					f.smtp_name.select();
					return false;
				}	
				
//				if(strchk_unicode(f.smtp_name.value))
				if(strchk_hostname(f.smtp_name.value)==false)
				{
					alert("<?=$a_invalid_user_name?>");
					f.smtp_name.select();
					return false;
				}	
			}
					
			if(f.smtp_password != null)
			{		
				if(strchk_unicode(f.smtp_password.value)==true)
				{
					alert("<?=$a_invalid_new_password?>");
					f.smtp_password.select();
					return false;
				}
				if(is_blank_in_first_end(f.smtp_password.value))
				{
					alert("<?=$a_first_end_blank?>");
					f.smtp_password.select();
					return false;
				}
				if(f.smtp_password.value != f.confirm_password.value)
				{
					alert("<?=$a_password_not_matched?>");
					f.smtp_password.select();	
					return false;
				}	
			}		
		}	
	}
	fields_disabled(f, false);
	return true;
}

function submit()
{
	if(check()) get_obj("frm").submit();
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
			<table id="table_set_main"  border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
				<tr>
					<td colspan="2">
						<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr>
								<td class="table_tool_td" valign="middle" colspan="2"><b><?=$m_log_setting_title?></b></td>
							</tr>	
							<tr>
								<td>
								<fieldset>
								<legend><?=$m_normal_log?></legend>
						<table>
							<tr>
								<td width="35%" id="td_left"><?=$m_log_ip?></td>
								<td id="td_right"><input type ="text" class="text" maxlength="256" name="log_ip" size="16" value=""></td>
							</tr>
							<tr>
								<td id="td_left"><?=$m_log_type?></td>
								<td id="td_right">
									<?=$G_TAG_SCRIPT_START?>genCheckBox("system_activity","");<?=$G_TAG_SCRIPT_END?>	
									<?=$m_system_activity?>
								</td>
							</tr>
							<tr>
								<td id="td_left"></td>
								<td id="td_right">
									<?=$G_TAG_SCRIPT_START?>genCheckBox("wireless_activity","");<?=$G_TAG_SCRIPT_END?>	
									<?=$m_wireless_activity?>
								</td>
							</tr>
							<tr>
								<td id="td_left"></td>
								<td id="td_right">
									<?=$G_TAG_SCRIPT_START?>genCheckBox("notice","");<?=$G_TAG_SCRIPT_END?>	
									<?=$m_notice?>
								</td>
							</tr>
						</table>					
								</fieldset>
								</td>
							</tr>
							<tr>
								<td>
								<fieldset>
								<legend><?=$m_eu_log?></legend>										
						<table>
							<tr>
								<td width="35%" id="td_left"><?=$m_log_ip?></td>
								<td id="td_right"><input type ="text" class="text" maxlength="256" name="eu_ip" size="16" value=""></td>
							</tr>
						</table>
								</fieldset>
								</td>
							</tr>															
						</table>
					</td>
				</tr>	
<? if(query("/runtime/web/display/log/email") != "1")	{echo "<!--";} ?>					
				<tr>
					<td colspan="2">
						<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr>
								<td class="table_tool_td" valign="middle" colspan="2"><b><?=$m_smtp_setting_title?></b></td>
							</tr>	
							<tr>
								<td width="35%" id="td_left"><?=$m_smtp?></td>
								<td id="td_right">
									<?=$G_TAG_SCRIPT_START?>genCheckBox("smtp_enable","on_check_smtp()");<?=$G_TAG_SCRIPT_END?>	
									<?=$m_enable?>
								</td>
							</tr>						
<? if($flag_autorekey !="1" && query("/runtime/web/display/log/email") == "1")	{echo "<!--";} ?>
							<tr>
								<td id="td_left"><?=$m_email_type?></td>
								<td id="td_right"><?=$G_TAG_SCRIPT_START?>genSelect("email_type", [1,2,3], ["<?=$m_internal?>","<?=$m_gmail?>","<?=$m_homail?>"], "on_change_email_type()");<?=$G_TAG_SCRIPT_END?></td>
							</tr>					
							<tr>
								<td id="td_left"><?=$m_authentication?></td>
								<td id="td_right"><?=$G_TAG_SCRIPT_START?>genCheckBox("auth_enable","");<?=$G_TAG_SCRIPT_END?>	
									<?=$m_enable?></td>
							</tr>		
							<tr>
								<td id="td_left"><?=$m_ssltls?></td>
								<td id="td_right"><?=$G_TAG_SCRIPT_START?>genCheckBox("ssl_enable","");<?=$G_TAG_SCRIPT_END?>	
									<?=$m_enable?></td>
							</tr>			
<? if($flag_autorekey !="1" && query("/runtime/web/display/log/email") == "1")	{echo "-->";} ?>
							<tr>
								<td id="td_left"><?=$m_smtp_from_email?></td>
								<td id="td_right"><input type ="text" class="text" name="smtp_from_email" size="30" maxlength="254" value=""></td>
							</tr>								
							<tr>
								<td id="td_left"><?=$m_smtp_to_email?></td>
								<td id="td_right"><input type ="text" class="text" name="smtp_to_email" size="30" maxlength="254" value=""></td>
							</tr>	
							<tr>
								<td id="td_left"><?=$m_smtp_ip?></td>
								<td id="td_right"><input type ="text" class="text" maxlength="60" name="smtp_ip" size="16" maxlength="254" value=""></td>
							</tr>	
							<tr>
								<td id="td_left"><?=$m_smtp_port?></td>
								<td id="td_right"><input type ="text" class="text" name="smtp_port" size="16" value="" maxlength="5"></td>
							</tr>											
							<tr>
								<td id="td_left"><?=$m_smtp_name?></td>
								<td id="td_right"><input type ="text" class="text" maxlength="64" name="smtp_name" size="16"  value=""></td>
							</tr>	
							<tr>
								<td id="td_left"><?=$m_smtp_password?></td>
								<td id="td_right"><input type ="password" class="text" maxlength="64" name="smtp_password" size="16" value=""></td>
							</tr>	
							<tr>
								<td id="td_left"><?=$m_smtp_confirm_password?></td>
								<td id="td_right"><input type ="password" class="text" maxlength="64" name="confirm_password" size="16" value=""></td>
							</tr>															
						</table>
					</td>
				</tr>									
				<tr>
					<td colspan="2">
						<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr>
								<td class="table_tool_td" valign="middle" colspan="2"><b><?=$m_email_log_schedule_title?></b></td>
							</tr>	
							<tr>
								<td width="35%"  id="td_left">
									<?=$m_log_schedule?>
								</td>
								<td id="td_right">
									<?=$G_TAG_SCRIPT_START?>genSelect("log_schedule", [0,30,60,90,120,150,180,210,240,270,300,330,360,390,420,450,480,510,540,570,600,630,660,690,720,
									750,780,810,840,870,900,930,960,990,1020,1050,1080,1110,1140,1170,1200,1230,1260,1290,1320,1350,1380,1410,1440], ["0","0.5","1","1.5","2","2.5",
									"3","3.5","4","4.5","5","5.5","6","6.5","7","7.5","8","8.5","9","9.5","10","10.5","11","11.5","12","12.5","13","13.5","14","14.5","15","15.5","16"
									,"16.5","17","17.5","18","18.5","19","19.5","20","20.5","21","21.5","22","22.5","23","23.5","24"], "");<?=$G_TAG_SCRIPT_END?>
									&nbsp;<?=$m_log_schedule_msg?>
								</td>
							</tr>																								
						</table>
					</td>
				</tr>	
<? if(query("/runtime/web/display/log/email") != "1")	{echo "-->";} ?>																					
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
						
				
