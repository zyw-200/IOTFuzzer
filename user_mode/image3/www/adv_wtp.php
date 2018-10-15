<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_wtp";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_wtp";
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
$cfg_wtp_enable	= query("/sys/wtp/enable");
$cfg_wtp_name	= get("j","/sys/systemName");
$cfg_wtp_location	= get("j","/sys/systemLocation");
$cfg_ac_ip	= query("/runtime/wtp/acipaddr");
if($cfg_ac_ip=="")
{
	$cfg_ac_ip="N/A";	
}
$cfg_ac_name	= get("j","/runtime/wtp/acname");
if($cfg_ac_name=="")
{
	$cfg_ac_name="N/A";	
}
$ac_ip_list = 0;
for("/sys/wtp/acstatic/ip")
{
	$ac_ip_list++;
}
echo "-->";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>

function on_click_wtp_enable(s)
{
	var f = get_obj("frm");
	
	f.ac_ipaddr.disabled = true;
	if(s.checked)
	{
		f.ac_ipaddr.disabled = false;
	}
}

function print_rule_del(id)
{
	var str="";

	str+="&nbsp;&nbsp;<a href='javascript:wtp_del_confirm(\""+id+"\")'><img src='/pic/delete.jpg' border=0></a>";

	document.write(str);
}

function wtp_del_confirm(id)
{
	var f = get_obj("frm");
	if(confirm("<?=$a_wtp_del_confirm?>")==false) return;
	f.f_wtp_del.value = id;
	get_obj("frm").submit();
}

/* page init functoin */
function init()
{
	var f=get_obj("frm");
	
	f.wtp_enable.checked = <? if ($cfg_wtp_enable=="1") {echo "true";} else {echo "false";}?>;
	f.wtp_name.value = "<?=$cfg_wtp_name?>";
	f.wtp_name.disabled = true;
	f.wtp_location.value = "<?=$cfg_wtp_location?>";
	f.wtp_location.disabled = true;
	
	on_click_wtp_enable(f.wtp_enable);
}

/* parameter checking */
function check()
{
	
	return true;
}

function submit()
{
	var f=get_obj("frm");	
	if(f.wtp_enable.checked)
	{
		f.f_wtp_enable.value = 1;
	}	
	else
	{
		f.f_wtp_enable.value = 0;
	}
	if(check()) {f.submit();}	
}

function submit_do_add()
{
	var f=get_obj("frm");
	if(f.wtp_enable.checked)
	{
		f.f_wtp_enable.value = 1;
	}	
	else
	{
		f.f_wtp_enable.value = 0;
	}
	
	if(f.f_wtp_enable.value == 1 && f.ac_ipaddr.value != "")
	{	
		if(do_add())  get_obj("frm").submit();
	}
}


var ip_list=[['index','ip']<?
for("/sys/wtp/acstatic/ip")
{
	echo ",\n['".$@."',";
	echo "'".query("/sys/wtp/acstatic/ip:".$@.)."']";
}
?>];

function do_add()
{
	var f=get_obj("frm");	
	
	f.f_add.value="1";	

	if("<?=$ac_ip_list?>" >= 8)
	{
		alert("<?=$a_max_ip_table?>");	
		return false;
	}	

	for(var i=1; i<ip_list.length; i++)
	{
		if(f.ac_ipaddr.value == ip_list[i][1])
		{
			alert("<?=$a_same_wtp_ip?>");
			return false;	
		}
	}		
	
	if (!is_valid_ip3(f.ac_ipaddr.value, 0))
	{
		alert("<?=$a_invalid_ip?>");
		field_focus(f.ac_ipaddr, "**");
		return false;
	}		
	return true;
}	
</script>

<body <?=$G_BODY_ATTR?> onload="init();">
<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">
<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="f_wtp_del"	value="">
<input type="hidden" name="f_add"	value="">
<input type="hidden" name="f_wtp_enable"	value="">
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
					<td colspan="2">
						<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr>
								<td class="table_tool_td" valign="middle" colspan="2"><b><?=$m_wtp_title?></b></td>
							</tr>	
							<tr>
								<td width="30%" id="td_left">
									<?=$m_wtp_enable?>
								</td>
								<td id="td_right">	
									<?=$G_TAG_SCRIPT_START?>genCheckBox("wtp_enable", "on_click_wtp_enable(this)");<?=$G_TAG_SCRIPT_END?>
								</td>
							</tr>
							<tr>
								<td id="td_left">
									<?=$m_wtp_name?>
								</td>
								<td id="td_right">&nbsp;
									<input class="text" maxlength="32" id="wtp_name" name="wtp_name" size="32" value="">
								</td>
							</tr>	
							<tr>
								<td id="td_left">
									<?=$m_wtp_location?>
								</td>
								<td id="td_right">&nbsp;
									<input class="text" maxlength="32" id="wtp_location" name="wtp_location" size="32" value="">
								</td>
							</tr>
						</table>
					</td>
				</tr>		
				<tr>
					<td colspan="2">
						<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr>
								<td class="table_tool_td" valign="middle" colspan="2"><b><?=$m_connect_title?></b></td>
							</tr>	
							<tr>
								<td width="30%"  id="td_left">
									<?=$m_ac_ip?>
								</td>
								<td id="td_right">&nbsp;
								
									<?=$cfg_ac_ip?>
								</td>
							</tr>									
							<tr>
								<td id="td_left">
									<?=$m_ac_name?>
								</td>
								<td id="td_right">&nbsp;
									
									<?=$cfg_ac_name?>
								</td>								
							</tr>	
						</table>
					</td>
				</tr>	
				<tr>
					<td colspan="2">
						<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr>
								<td class="table_tool_td" valign="middle" colspan="2"><b><?=$m_ac_ip_list_title?></b></td>
							</tr>	
							<tr>
								<td id="td_left">
									<?=$m_ac_ipaddr?>
								</td>
								<td id="td_right">&nbsp;
									<input name="ac_ipaddr" id="ac_ipaddr" class="text" type="text" size="32" maxlength="15" value="">
									&nbsp;&nbsp;
									<a href="javascript:submit_do_add()">
									<img src="<?=$pic_path?>add1.gif" align="middle" border="0" OnMouseOver="this.src='<?=$pic_path?>add2.gif'"  OnMouseOut="this.src='<?=$pic_path?>add1.gif'">
									</a>								</td>
							</tr>														
							<tr>
								<td colspan="2">
									<table width="100%" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
										<tr class="list_head" align="left">
											<td width="10">
												&nbsp;
											</td>
											<td width="70">
												<?=$m_id?>
											</td>
											<td width="200">
												<?=$m_ip?>
											</td>
											<td>
												<?=$m_del?>
											</td>																																																										
										</tr>	
									</table>	
									<div class="div_tab">
										<table id="acl_tab" width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>						
<?
$tmp = "";
for("/sys/wtp/acstatic/ip")
{
	$tmp = $@%2;
	
	if($tmp == 1)
	{
		echo "<tr align=\"left\" style=\"background:#cccccc;\">\n";
	}
	else
	{
		echo "<tr align=\"left\" style=\"background:#b3b3b3;\">\n";
	}
	echo "<td width=\"10\">&nbsp;</td>\n";	
	echo "<td width=\"70\">".$@."</td>\n";	
	echo "<td width=\"200\">".query("/sys/wtp/acstatic/ip:".$@.)."</td>\n";	
	echo "<td>".$G_TAG_SCRIPT_START."print_rule_del(".$@.");".$G_TAG_SCRIPT_END."</td></tr></td>\n";			
	echo "</tr>\n";	
}
?>
										</table>	
									</div>										
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
