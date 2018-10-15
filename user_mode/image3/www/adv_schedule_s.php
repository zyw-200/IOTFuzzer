<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_schedule_s";
$MY_MSG_FILE  = "adv_schedule.php";
$MY_ACTION    = "adv_schedule";
$NEXT_PAGE    = "adv_schedule_s";
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
$schedule_list =0;
for("/schedule/rule/index")
{
	$schedule_list++;
}
anchor("/schedule");
if($schedule_reload != 1)
{
	$cfg_schedule_enable = query("enable");
}
else
{
	$cfg_schedule_enable = 1;
}	
$cfg_ssid=get("j", "/wlan/inf:1/ssid");
$cfg_apmode=query("/wlan/inf:1/ap_mode");
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>
function get_time(str_time)
{
	var mytime=new Array();

	mytime[0] = mytime[1] = mytime[2] = "";
	if (str_time != "")
	{
		var tmp=str_time.split(":");
		for (var i=1;i <= tmp.length;i++) mytime[i]=tmp[i-1];
		mytime[0]=str_time;
	}
	else
	{
		for (var i=0; i <= 4;i++) mytime[i]="";
	}
	return mytime;
}

function print_rule_del(id)
{
	var str="";

	str+="<a href='javascript:rule_del_confirm(\""+id+"\")'><img src='/pic/delete.jpg' border=0></a>";

	document.write(str);
}


function print_rule_edit(id)
{
	var str="";

	str+="<a href='javascript:rule_edit_confirm(\""+id+"\")'><img src='/pic/edit.jpg' border=0></a>";

	document.write(str);
}

function rule_del_confirm(id)
{
	var f = get_obj("frm");
	if(confirm("<?=$a_entry_del_confirm?>")==false) return;
	f.f_entry_del.value = id;
	fields_disabled(f, false);
	get_obj("frm").submit();
}

function rule_edit_confirm(id)
{
	var f=get_obj("frm");
	f.entry_edit.value = id;
	init();
}

function on_check_all_week(s)
{
	var f=get_obj("frm");

	f.sun.disabled = f.mon.disabled = f.tue.disabled = f.wed.disabled = f.thu.disabled = f.fri.disabled = f.sat.disabled = true;
	f.sun.checked = f.mon.checked = f.tue.checked = f.wed.checked = f.thu.checked = f.fri.checked = f.sat.checked = true;

	if(f.day_select[1].checked)
	{
		f.sun.disabled = f.mon.disabled = f.tue.disabled = f.wed.disabled = f.thu.disabled = f.fri.disabled = f.sat.disabled = false;
		f.sun.checked = f.mon.checked = f.tue.checked = f.wed.checked = f.thu.checked = f.fri.checked = f.sat.checked = false;
	}	
}

function on_check_all_day(s)
{
	var f=get_obj("frm");
	f.s_hr.disabled = f.s_min.disabled = f.e_hr.disabled = f.e_min.disabled =  false;
	f.overnight.disabled = false;
	if(s.checked)
	{
		f.s_hr.disabled = f.s_min.disabled = f.e_hr.disabled = f.e_min.disabled = true;
		f.overnight.disabled = true;
	}	
}

function on_change_schedule_status(s)
{
	var f=get_obj("frm");
	
	if(s.value == 1)
	{	
		fields_disabled(f, false);	
		on_check_all_week(f.day_select);
		on_check_all_day(f.all_day);
		/*if("<?=$schedule_list?>" == 4 )	
		{
			if(f.entry_edit.value !="")
			{
				f.add.disabled = false;	
			}
			else
			{
				f.add.disabled = true;	
			}
		}*/
	}
	else
	{	
		fields_disabled(f, true);
		s.disabled=false;
	}
	f.ssid.disabled=true;		
	AdjustHeight();
}

/* page init functoin */

var s_list=[['index','enable','name','s_time','e_time','wireless'
			,'sun','mon','tue','wed','thu','fri','sat','allday','ssid_num','overnight']<?

for("/schedule/rule/index")
{
	if(query("name") != "")
	{
		echo ",\n['".$@."','".query("enable")."','".get("j","name")."','"
		.query("starttime")."','".query("endtime")."','".query("wirelesson")."','"
		.query("sun")."','".query("mon")."','".query("tue")."','"
		.query("wed")."','".query("thu")."','".query("fri")."','".query("sat")."','".query("allday")."','".query("ssidnum")."','".query("overnight")."']";
	}
}
?>];
var mssid_list_g=[['index','ssid','state']
<?
for("/wlan/inf:1/multi/index")
{
	echo ",\n['".$@."','".get("j","ssid")."','".query("state")."']";
}
?>];

function on_change_index()
{
	//alert(s.value);	
	var f=get_obj("frm")
	if(f.index.value == 0) //2.4G band primary
	{
		f.ssid.value="<?=$cfg_ssid?>";
	}
	else if(f.index.value>0 && f.index.value<8 ) //2.4G band multi-ssid
	{
		f.ssid.value= mssid_list_g[f.index.value][1];	
	}
}

function init()
{
	var f=get_obj("frm");
	var i, start_time, end_time;
	select_index(f.schedule_status, "<?=$cfg_schedule_enable?>");
	if("<?=$cfg_apmode?>" == 1)
	{
		f.schedule_status.value = 0;
		f.schedule_status.disabled = true;
	}
	f.day_select[1].checked = true;
	on_change_schedule_status(f.schedule_status);

	for(var i=1;i<= s_list.length;i++)
	{
		if(get_obj("schedule_index"+i) != null)
		{
			if(s_list[i][1]!=1)
			{
				get_obj("schedule_index"+i).checked = false;
			}
			else
			{
				get_obj("schedule_index"+i).checked = true;
			}
		}
	}
	if(f.entry_edit.value !="")
	{
		f.index.value= s_list[f.entry_edit.value][14];
		f.name.value = s_list[parseInt(f.entry_edit.value, [10])][2];
		if( s_list[f.entry_edit.value][6]== 1 &&
			s_list[f.entry_edit.value][7]== 1 &&
			s_list[f.entry_edit.value][8]== 1 &&
			s_list[f.entry_edit.value][9]== 1 &&
			s_list[f.entry_edit.value][10]== 1 &&
			s_list[f.entry_edit.value][11]== 1 &&
			s_list[f.entry_edit.value][12]== 1)
		{
			f.day_select[0].checked = true;	
			on_check_all_week(f.day_select);
		}	
		else
		{	
			f.day_select[1].checked = true;
			on_check_all_week(f.day_select);
			f.sun.checked=(s_list[f.entry_edit.value][6]=="1")?true:false;
			f.mon.checked=(s_list[f.entry_edit.value][7]=="1")?true:false;
			f.tue.checked=(s_list[f.entry_edit.value][8]=="1")?true:false;
			f.wed.checked=(s_list[f.entry_edit.value][9]=="1")?true:false;
			f.thu.checked=(s_list[f.entry_edit.value][10]=="1")?true:false;
			f.fri.checked=(s_list[f.entry_edit.value][11]=="1")?true:false;
			f.sat.checked=(s_list[f.entry_edit.value][12]=="1")?true:false;	
		}			
		if(s_list[f.entry_edit.value][13] == 1)
		{
			f.all_day.checked = true;
			on_check_all_day(f.all_day);
			f.s_hr.value = f.s_min.value = f.e_hr.value = f.e_min.value = "";

		}
		else
		{
			f.all_day.checked = false;
			on_check_all_day(f.all_day);
			start_time = get_time(s_list[f.entry_edit.value][3]);
			end_time = get_time(s_list[f.entry_edit.value][4]);
			f.s_hr.value = start_time[1];
			f.s_min.value = start_time[2];
			f.e_hr.value = end_time[1];
			f.e_min.value = end_time[2];			
			if(s_list[f.entry_edit.value][15] == 1)
			{f.overnight.checked = true;}
			else
			{f.overnight.checked = false;}
		}
		//f.add.disabled = true;
	}
	on_change_index();
        
	if("<?=$cfg_apmode?>"=="1")
	{
		f.schedule_status.disabled=true;	
	}
}

/* parameter checking */

function check()
{
	// do check here ....
	var f = get_obj("frm");
	
	fields_disabled(f, false);
	
	f.f_day.value = (f.day_select[1].checked?1:0);
	
	
	for(var i=1;i<="<?=$schedule_list?>";i++)
	{
		if(get_obj("schedule_index"+i) != null)
		{
			if(get_obj("schedule_index"+i).checked)
			{
				get_obj("s_index"+i).value = 1;
			}
			else
			{
				get_obj("s_index"+i).value = 0;
			}
		}
	}
	return true;
}

function submit()
{
	var f=get_obj("frm");	
	fields_disabled(f, false);
	if(check())
	{
		f.submit();
	}
}

function check_value()
{
	var f=get_obj("frm");

	if(is_blank(f.name.value))
	{
		alert("<?=$a_empty_name?>");
		f.name.focus();
		return false;
	}		
	
	if(first_blank(f.name.value))
	{
		alert("<?=$a_first_blank_name?>");
		f.name.select();
		return false;
	}
	
	if(strchk_unicode(f.name.value))
	{
		alert("<?=$a_invalid_name?>");
		f.name.select();
		return false;
	}		

	if(f.entry_edit.value!="")
	{
		for(var i=1;i<s_list.length;i++)
		{
			if(f.name.value == s_list[i][2] && i != f.entry_edit.value)
			{
				alert("<?=$a_entry_same_name?>");
				f.name.select();
				return false;
			}
		}
	}
	else
	{
		for(var i=1;i<s_list.length;i++)
		{
			if(f.name.value == s_list[i][2])
			{
				alert("<?=$a_entry_same_name?>");
				f.name.select();
				return false;
			}
		}
	}
	
	for(var i=1;i<s_list.length;i++)
	{
		if(s_list[i][14]!= f.index.value)
		{
			continue;
		}
		else
		{
			if(f.entry_edit.value!="")
			{
				if(s_list[f.entry_edit.value][14]==f.index.value)
				{
					continue;
				}
				else
				{
					alert("<?=$a_entry_same_ssid?>");
					f.index.focus();
					return false;
				}
			}
			else
			{
				alert("<?=$a_entry_same_ssid?>");
				f.index.focus();
				return false;
			}
		}
	}

	
	if(f.sun.checked == false && f.mon.checked == false && f.tue.checked == false && f.wed.checked == false && f.thu.checked == false && f.fri.checked == false && f.sat.checked == false)
	{
		alert("<?=$a_invalid_day?>");
		return false;			
	}
		
	if(f.all_day.checked == false)
	{
		if(!is_in_range(f.s_hr.value,0,23))
		{
			alert("<?=$a_invalid_hr?>");
			f.s_hr.select();
			return false;
		}	
			
		if(!is_in_range(f.s_min.value,0,59))
		{
			alert("<?=$a_invalid_min?>");
			f.s_min.select();
			return false;
		}	
				
		if(!is_in_range(f.e_hr.value,0,23))
		{
			alert("<?=$a_invalid_hr?>");
			f.e_hr.select();
			return false;
		}	
		if(!is_in_range(f.e_min.value,0,59))
		{
			alert("<?=$a_invalid_min?>");
			f.e_min.select();
			return false;
		}				
			
		if(f.overnight.checked == false)
		{
			if(parseInt(f.s_hr.value, [10]) > parseInt(f.e_hr.value, [10]))
			{
				alert("<?=$a_endtime_bigger?>");
				f.e_hr.select();
				return false;
			}
			else if(parseInt(f.s_hr.value, [10]) == parseInt(f.e_hr.value, [10]))
			{
				if(parseInt(f.s_min.value, [10]) >= parseInt(f.e_min.value, [10]))
				{
					alert("<?=$a_endtime_bigger?>");
					f.e_min.select();
					return false;
				}
			}
			f.overnight.value = 0;
		}
		else
		{
			if(parseInt(f.s_hr.value, [10]) < parseInt(f.e_hr.value, [10])) 
			{
				alert("<?=$a_starttime_bigger?>");
				f.e_hr.select();
				return false;
			}
			else if(parseInt(f.s_hr.value, [10]) == parseInt(f.e_hr.value, [10]))
			{
				if(parseInt(f.s_min.value, [10]) <= parseInt(f.e_min.value, [10]))
				{
					alert("<?=$a_starttime_bigger?>");
					f.e_min.select();
					return false;
				}
			}
			f.overnight.value = 1;
		}
		if(f.s_hr.value.length == 1)
		{
			f.s_hr.value = "0"+f.s_hr.value;
		}		
				
		if(f.s_min.value.length == 1)
		{
			f.s_min.value = "0"+f.s_min.value;
		}	
	
		if(f.e_hr.value.length == 1)
		{
			f.e_hr.value = "0"+f.e_hr.value;
		}	
		
		if(f.e_min.value.length == 1)
		{
			f.e_min.value = "0"+f.e_min.value;
		}
		
		f.f_start_time.value = f.s_hr.value + ":" + f.s_min.value;
		f.f_end_time.value = f.e_hr.value + ":" + f.e_min.value;
	}

}

function do_add()
{
	var f = get_obj("frm");
	if(check_value() != false)
	{
		f.f_add_value.value="1";
		fields_disabled(f, false);
		get_obj("frm").submit();		
	}			
	
}
function do_clear()
{
	self.location.href="<?=$MY_NAME?>.php?schedule_reload=1";		
}
</script>

<body <?=$G_BODY_ATTR?> onload="init();">

<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="">

<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="f_entry_del"	value="">
<input type="hidden" name="entry_edit"	value="">
<input type="hidden" name="f_add_value"	value="">
<input type="hidden" name="f_day"		value="">
<input type="hidden" name="f_start_time" value="">
<input type="hidden" name="f_end_time"	value="">
<input type="hidden" name="f_sce_idx"	value="">
<input type="hidden" name="f_ssid_select"		value="">
<input type="hidden" name="f_overnight"		value="">
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
					<td width="25%" id="td_left">
						<?=$m_schedule_status?>
					</td>
					<td id="td_right">						
						<?=$G_TAG_SCRIPT_START?>genSelect("schedule_status", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"], "on_change_schedule_status(this)");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr>
								<td class="table_tool_td" valign="middle" colspan="2"><b><?=$m_schedule_title?></b></td>
							</tr>
							<tr>
								<td width="25%" id="td_left">
									<?=$m_name?>
								</td>
								<td id="td_right">
									<input name="name" id="name" class="text" type="text" size="20" maxlength="32" value="">
								</td>
							</tr>	
							<tr>
								<td width="30%">
									<?=$m_index?>
								</td>
								<td>
									<?=$G_TAG_SCRIPT_START?>genSelect("index", [0,1,2,3,4,5,6,7], ["<?=$m_pri_ssid?>","<?=$m_ms_ssid1?>","<?=$m_ms_ssid2?>","<?=$m_ms_ssid3?>","<?=$m_ms_ssid4?>","<?=$m_ms_ssid5?>","<?=$m_ms_ssid6?>","<?=$m_ms_ssid7?>"], "on_change_index()");<?=$G_TAG_SCRIPT_END?>
								</td>
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
								<td id="td_left"><?=$m_day?></td>
								<td id="td_right">
									<input type="radio" id="day_select" name="day_select" value="0" onClick="on_check_all_week(this)"><?=$m_all_week?>
									<input type="radio" id="day_select" name="day_select" value="1" onClick="on_check_all_week(this)"><?=$m_select_day?>
								</td>
							</tr>	
							<tr>
								<td id="td_left"></td>
								<td id="td_right">
									<?=$G_TAG_SCRIPT_START?>genCheckBox("sun","");<?=$G_TAG_SCRIPT_END?><?=$m_sun?>&nbsp;
									<?=$G_TAG_SCRIPT_START?>genCheckBox("mon","");<?=$G_TAG_SCRIPT_END?><?=$m_mon?>&nbsp;
									<?=$G_TAG_SCRIPT_START?>genCheckBox("tue","");<?=$G_TAG_SCRIPT_END?><?=$m_tue?>&nbsp;
									<?=$G_TAG_SCRIPT_START?>genCheckBox("wed","");<?=$G_TAG_SCRIPT_END?><?=$m_wed?>&nbsp;
									<?=$G_TAG_SCRIPT_START?>genCheckBox("thu","");<?=$G_TAG_SCRIPT_END?><?=$m_thu?>&nbsp;
									<?=$G_TAG_SCRIPT_START?>genCheckBox("fri","");<?=$G_TAG_SCRIPT_END?><?=$m_fri?>&nbsp;
									<?=$G_TAG_SCRIPT_START?>genCheckBox("sat","");<?=$G_TAG_SCRIPT_END?><?=$m_sat?>&nbsp;
								</td>
							</tr>	
							<tr>
								<td id="td_left"><?=$m_all_day?></td>
								<td id="td_right">	
									<?=$G_TAG_SCRIPT_START?>genCheckBox("all_day","on_check_all_day(this)");<?=$G_TAG_SCRIPT_END?>
								</td>
							</tr>	
							<tr>
								<td id="td_left">
									<?=$m_start_time?>
								</td>
								<td id="td_right">
									<input name="s_hr" id="s_hr" class="text" type="text" size="3" maxlength="2" value="">&nbsp;:
									<input name="s_min" id="s_min" class="text" type="text" size="3" maxlength="2" value="">
									&nbsp;<?=$m_set_time_msg?>
								</td>
							</tr>	
							<tr>
								<td id="td_left">
									<?=$m_end_time?>
								</td>
								<td id="td_right">
									<input name="e_hr" id="e_hr" class="text" type="text" size="3" maxlength="2" value="">&nbsp;:
									<input name="e_min" id="e_min" class="text" type="text" size="3" maxlength="2" value="">
									&nbsp;<?=$m_set_time_msg?>
									<input type="checkbox" id="overnight" name="overnight"><?=$m_overnight?>
								</td>
							</tr>	
<? if(query("/runtime/web/display/schedule_wireless") =="0")	{echo "<!--";} ?>							
							<tr>
								<td width="25%" id="td_left">
									<?=$m_wireless?>
								</td>
								<td id="td_right">
									<?=$G_TAG_SCRIPT_START?>genSelect("wireless", [0,1], ["<?=$m_off?>","<?=$m_on?>"], "");<?=$G_TAG_SCRIPT_END?>
								</td>
							</tr>	
<? if(query("/runtime/web/display/schedule_wireless") =="0")	{echo "-->";} ?>														
							<tr>
								<td id="td_left">
								</td>
								<td id="td_right">
									<input type="button" id="add" name="add" value=" <?=$m_b_add?> " onclick="do_add()">	
									<input type="button" id="clear" name="clear" value=" <?=$m_b_clear?> " onclick="do_clear()">	
								</td>
							</tr>										
						</table>
					</td>
				</tr>	
				<tr>
					<td colspan="2">
						<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr>
								<td class="table_tool_td" valign="middle" colspan="2"><b><?=$m_schedule_list?></b></td>
							</tr>	
							<tr>
								<td colspan="2">										
									<div class="div_tab">
										<table id="acl_tab" width="100%" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">						
										<tr class="fixed_head" align="left">
											<td width="20">
												&nbsp;
											</td>										
											<td width="90">
												<?=$m_name?>
											</td>
											<td width="40">
												<?=$m_ssid_index?>
											</td>
											<td width="90">
												<?=$m_ssid?>
											</td>
											<td width="90">
												<?=$m_day?>
											</td>
											<td width="90">
												<?=$m_time?>
											</td>											
											<td width="30">
												<?=$m_wireless?>
											</td>	
											<td width="30">
												<?=$m_edit?>
											</td>															
											<td>
												<?=$m_del?>
											</td>																																																																					
										</tr>	
<?
$tmp = 1;
for("/schedule/rule/index")
{
	echo "<input type=\"hidden\" id=\"s_index".$@."\" name=\"s_index".$@."\" value=\"".query("enable")."\">\n";
	echo "<input type=\"hidden\" id=\"h_name".$@."\" name=\"h_name".$@."\" value=\"".get("h","name")."\">\n";
	echo "<input type=\"hidden\" id=\"h_sun".$@."\" name=\"h_sun".$@."\" value=\"".query("sun")."\">\n";
	echo "<input type=\"hidden\" id=\"h_mon".$@."\" name=\"h_mon".$@."\" value=\"".query("mon")."\">\n";
	echo "<input type=\"hidden\" id=\"h_tue".$@."\" name=\"h_tue".$@."\" value=\"".query("tue")."\">\n";
	echo "<input type=\"hidden\" id=\"h_wed".$@."\" name=\"h_wed".$@."\" value=\"".query("wed")."\">\n";
	echo "<input type=\"hidden\" id=\"h_thu".$@."\" name=\"h_thu".$@."\" value=\"".query("thu")."\">\n";
	echo "<input type=\"hidden\" id=\"h_fri".$@."\" name=\"h_fri".$@."\" value=\"".query("fri")."\">\n";
	echo "<input type=\"hidden\" id=\"h_sat".$@."\" name=\"h_sat".$@."\" value=\"".query("sat")."\">\n";
	echo "<input type=\"hidden\" id=\"h_index".$@."\" name=\"h_index".$@."\" value=\"".query("ssidnum")."\">\n";
$tmp_24g_mode=query("/wlan/inf:1/ap_mode");
    $tmp_5g_mode=query("/wlan/inf:2/ap_mode");
    $tmp_ssid=query("ssidnum");
    if($tmp_ssid < 8 && $tmp_24g_mode!="1"){$show_24g_list=1;}else{$show_24g_list=0;}
    if($tmp_ssid > 7 && $tmp_5g_mode!="1"){$show_5g_list=1;}else{$show_5g_list=0;}
    if($show_24g_list==1 || $show_5g_list==1)
    {
	
	$day = "";
	$how_many_days = 0;
	if(query("sun") == 1){$day = $day.$m_sun.$nbsp; $how_many_days++;}
	if(query("mon") == 1){$day = $day.$m_mon.$nbsp; $how_many_days++;}
	if(query("tue") == 1){$day = $day.$m_tue.$nbsp; $how_many_days++;}
	if(query("wed") == 1){if($how_many_days ==3){$day = $day.$br;} $day = $day.$m_wed.$nbsp; $how_many_days++;}
	if(query("thu") == 1){if($how_many_days ==3){$day = $day.$br;} $day = $day.$m_thu.$nbsp; $how_many_days++;}
	if(query("fri") == 1){if($how_many_days ==3){$day = $day.$br;} $day = $day.$m_fri.$nbsp; $how_many_days++;}
	if(query("sat") == 1){if($how_many_days ==3){$day = $day.$br;} $day = $day.$m_sat; $how_many_days++;}	
	if(query("overnight") == 1){$overnight = $m_add;}else{$overnight = "";}

	if(query("name") !="")
	{
		if($tmp == 1)
		{
			echo "<tr align=\"left\" style=\"background:#cccccc;\">\n";
			$tmp = 0;
		}
		else
		{
			echo "<tr align=\"left\" style=\"background:#b3b3b3;\">\n";
			$tmp = 1;
		}
		$index_ssid="";
		if($tmp_ssid==0)
		{
			$index_ssid=$m_pri_ssid;
			echo "<td width=\"20\"><input type=\"checkbox\" id=\"schedule_index".$@."\" name=\"schedule_index".$@."\" value=\"\"></td>\n";	
			echo "<td width=\"80\">".$G_TAG_SCRIPT_START."genTableSSID(\"h_name\",".$@.");".$G_TAG_SCRIPT_END."</td>\n";
			echo "<td width=\"40\">".$index_ssid."</td>\n";	
			echo "<td width=\"75\">".$G_TAG_SCRIPT_START."genTableSSID(\"".$cfg_ssid."\",\"0\");".$G_TAG_SCRIPT_END."</td>\n";		
		}
		else if($tmp_ssid>0 && $tmp_ssid<8)
		{
			$index_ssid=$m_ssid.$tmp_ssid;
			echo "<td width=\"20\"><input type=\"checkbox\" id=\"schedule_index".$@."\" name=\"schedule_index".$@."\" value=\"\"></td>\n";	
			echo "<td width=\"80\">".$G_TAG_SCRIPT_START."genTableSSID(\"h_name\",".$@.");".$G_TAG_SCRIPT_END."</td>\n";
			echo "<td width=\"40\">".$index_ssid."</td>\n";	
			echo "<td width=\"75\">".$G_TAG_SCRIPT_START."genTableSSID(mssid_list_g[".$tmp_ssid."][1],\"0\");".$G_TAG_SCRIPT_END."</td>\n";		
		}
		
		if(query("sun") == 1 && query("mon") == 1 && query("tue") == 1 && query("wed") == 1 && query("thu") == 1 && query("fri") == 1 && query("sat") == 1)
		{
			echo "<td width=\"90\">".$m_all_week."</td>\n";
		}
		else
		{
			echo "<td width=\"90\">".$day."</td>\n";
		}
		if(query("allday") == 1)
		{
			echo "<td width=\"90\">".$m_all_day."</td>\n";			
		}
		else
		{	
			echo "<td width=\"90\">".query("starttime")."-".query("endtime").$overnight."</td>\n";
		}
		if(query("wirelesson") == 1)
		{
			echo "<td width=\"30\">".$m_on."</td>\n";
		}
		else
		{
			echo "<td width=\"30\">".$m_off."</td>\n";
		}
		
		echo "<td width=\"30\"><script>print_rule_edit(".$@.");</script></td>\n";
		echo "<td><script>print_rule_del(".$@.");</script></td>\n";
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
<td>
	<?=$m_remark_of_add?>
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
