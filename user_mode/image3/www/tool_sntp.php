<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "tool_sntp";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "tool_sntp";
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
$cfg_date	= query("/runtime/time/date");
$cfg_time	= query("/runtime/time/time");
$cfg_tzone	= query("/time/timezone");
$cfg_sync	= query("/time/syncwith");
$cfg_interval	= query("/time/ntpserver/interval");
$cfg_ntp_server	= get(h,"/time/ntpserver/ip");
$cfg_dlink_ntp_server= query("/runtime/dlinksever"); //add cici 2012-02-23
$cfg_ds=query("/time/daylightSaving");
anchor("/time/DaylightSaving");
$cfg_offset=query("offset");
$cfg_s_mon=query("startdate/month");
$cfg_s_week=query("startdate/week");
$cfg_s_day_of_week=query("startdate/dayofweek");
$cfg_s_time=query("startdate/time");
$cfg_e_mon=query("enddate/month");
$cfg_e_week=query("enddate/week");
$cfg_e_day_of_week=query("enddate/dayofweek");
$cfg_e_time=query("enddate/time");
$cfg_lan_type = query("/wan/rg/inf:1/mode");

/* --------------------------------------------------------------------------- */
?>


<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>

var tz_list=[['index','name','dst']
<?
for("/tmp/tz/zone")
{
	echo ",\n['".$@."','".get("j","name")."','".query("dst")."']";
}
?>
];

var days="";
function on_change_time_zone()
{
	var f=get_obj("frm");
	f.daylight.disabled = true;
	if(f.time_type.checked)
	{
		f.daylight.disabled = false;
	}
	if(tz_list[f.tzone.value][2] == "")
	{
		f.daylight.checked = false;
		f.daylight.disabled = true;
	}

	on_click_daylight_saving();

}
function on_click_daylight_saving()
{
	var f=get_obj("frm");

//	f.daylight_saving_offset.disabled = true;
/*	f.dst_s_mon.disabled = true;
	f.dst_s_week.disabled = true;
	f.dst_s_day.disabled = true;
	f.dst_s_time.disabled = true;
	f.dst_e_mon.disabled = true;
	f.dst_e_week.disabled = true;
	f.dst_e_day.disabled = true;
	f.dst_e_time.disabled = true;
*/
	if(f.time_type.checked)
	{
	if(f.daylight.checked)
	{
		//f.daylight_saving_offset.disabled = false;
/*		f.dst_s_mon.disabled = false;
		f.dst_s_week.disabled = false;
		f.dst_s_day.disabled = false;
		f.dst_s_time.disabled = false;
		f.dst_e_mon.disabled = false;
		f.dst_e_week.disabled = false;
		f.dst_e_day.disabled = false;
		f.dst_e_time.disabled = false;*/
	}
	}
}

function on_click_ntp()
{
	var f = get_obj("frm");

	/* ntp part */
	/*f.interval.disabled = */f.ntp.disabled =
//	f.ntp_btn.disabled = f.page_ntp_servers.disabled = f.time_type.checked ? false : true;
	f.ntp.disabled = f.time_type.checked ? false : true;
	/* manual part */
	f.year.disabled = f.mon.disabled = days.disabled =
	f.hour.disabled = f.min.disabled = f.sec.disabled =
	f.set.disabled = f.time_type.checked ? true : false;
	if(!f.time_type.checked)
	{
		f.tzone.disabled=true;
	}
	else
	{
		f.tzone.disabled=false;
	}
	on_change_time_zone();
}

function set_time()
{
	var date = new Date();

	get_obj("year").value=date.getFullYear();
	get_obj("mon").selectedIndex = date.getMonth();
	days.selectedIndex = date.getDate() - 1;
	get_obj("hour").selectedIndex = date.getHours();
	get_obj("min").selectedIndex = date.getMinutes();
	get_obj("sec").selectedIndex = date.getSeconds();
	on_change_month();
}

/*function copy_ntp_server()
{
	if (get_obj("page_ntp_servers").selectedIndex > 0)
	{
		if(get_obj("page_ntp_servers").value != "")
			get_obj("ntp").value = get_obj("page_ntp_servers").value;
		else
			get_obj("ntp").value = "207.232.83.70";
	}
}*/

/* page init functoin */
function init()
{
	var f = get_obj("frm");
	// init here ...
//	select_index(f.daylight_saving_offset, "<?=$cfg_offset?>");
/*	select_index(f.dst_s_mon, "<?=$cfg_s_mon?>");
	select_index(f.dst_s_week, "<?=$cfg_s_week?>");
	select_index(f.dst_s_day, "<?=$cfg_s_day_of_week?>");
	select_index(f.dst_s_time, "<?=$cfg_s_time?>");
	select_index(f.dst_e_mon, "<?=$cfg_e_mon?>");
	select_index(f.dst_e_week, "<?=$cfg_e_week?>");
	select_index(f.dst_e_day, "<?=$cfg_e_day_of_week?>");
	select_index(f.dst_e_time, "<?=$cfg_e_time?>");*/
	set_time();
	on_change_month();
	var date = new Date();
	days.selectedIndex = date.getDate() - 1;
	on_click_ntp();
//	select_index(f.daylight_saving_offset, 5);
	AdjustHeight();
	
}

/* parameter checking */
function check()
{
	var f=get_obj("frm");
	// do check here ....
/*	if (f.daylight)
	{
		if((f.dst_s_mon.selectedIndex == f.dst_e_mon.selectedIndex) 
			&&(f.dst_s_week.selectedIndex == f.dst_e_week.selectedIndex)
			&&(f.dst_s_day.selectedIndex == f.dst_e_day.selectedIndex)
			&&(f.dst_s_time.selectedIndex == f.dst_e_time.selectedIndex)
			&&(f.daylight.checked == true)
		)
		{
			alert("<?=$a_invalid_day_light_saving_dates?>");
			return false;
		}		
	}
*/
	if (f.time_type.checked)
	{
		f.sync.value = "2";
		if (is_blank(f.ntp.value) || strchk_hostname(f.ntp.value)==false)
		{
			alert("<?=$a_invalid_ntp_server?>");
			field_focus(f.ntp, "**");
			return false;
		}
/*		if (is_valid_ip3(f.ntp.value, 0)==false)
		{
			alert("<?=$a_invalid_ip?>");
			field_focus(f.ntp, "**");
			return false;
		}
*/		
	}
	else
	{
		f.sync.value = "0";
	}
/*
	if(get_obj("am_pm").value == "2")
	{
		f.hour24.value = get_obj("hour").selectedIndex + 12;
	}
	else
	{
		f.hour24.value = get_obj("hour").selectedIndex;
	}
	
*/
	if(!is_digit(f.year.value))
	{
		alert("<?=$a_invalid_year?>");
		f.year.select();
		return false;
	}
	if((f.year.value<2010)||(f.year.value>2037))	
	{
		alert("<?=$a_invalid_year?>");
		f.year.select();
		return false;
	}
	f.f_days.value = days.value;
	fields_disabled(f, false);
	return true;
}
function on_change_year()
{
	var f=get_obj("frm");
	on_change_month();
}

function on_change_month()
{
	var f=get_obj("frm");
	get_obj("day28").style.display = "none";
	get_obj("day29").style.display = "none";
	get_obj("day30").style.display = "none";
	get_obj("day31").style.display = "none";
	if(f.mon.value == 2)
	{
		if((f.year.value %4==0)&&(f.year.value %100!=0)||(f.year.value %400==0))     
   		{
			get_obj("day29").style.display = "";
			days = get_obj("day29");
		}
		else
		{
			get_obj("day28").style.display = "";
			days = get_obj("day28");			
		}
	}
	else if(f.mon.value == 1 || f.mon.value == 3 || f.mon.value == 5 || f.mon.value == 7 || f.mon.value == 8 || f.mon.value == 10 || f.mon.value == 12)
	{
		get_obj("day31").style.display = "";
		days = get_obj("day31");
	}
	else
	{
		get_obj("day30").style.display = "";	
		days = get_obj("day30");
	}
}

function submit()
{
	if(check()) get_obj("frm").submit();
}

</script>

<body <?=$G_BODY_ATTR?> onload="init();">

<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return false;">
<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="hour24" value="">
<input type="hidden" name="f_days" value="">
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
								<td class="table_tool_td" valign="middle" colspan="2"><b><?=$m_time_config_title?></b></td>
							</tr>	
							<tr>
								<td width="30%" id="td_left"><?=$m_time?></td>
								<td id="td_right">
									<?=$cfg_date?>&nbsp;<?=$cfg_time?>
								</td>
							</tr>
								
						</table>
					</td>
				</tr>	
				<tr>
					<td colspan="2">
						<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr>
								<td class="table_tool_td" valign="middle" colspan="2"><b><?=$m_auto_time_config_title?></b></td>
							</tr>					
							<tr>
								<td width="30%" id="td_left"><?=$m_enable_ntp?></td>
								<td id="td_right">
								<input name="time_type" type="checkbox" id="time_type" value="1" onclick="on_click_ntp();"<?if ($cfg_sync=="2") {echo " checked";}?>>
								<input type="hidden" name="sync" id="sync">
							</td>
							<tr>
								<td id="td_left">
									<?=$m_ntp_server?>
								</td>
								<td id="td_right">
									<input name="ntp" id="ntp" class="text" value="<?=$cfg_ntp_server?>">
								</td>
							</tr>	
							<tr>
                                <td id="td_left"><?=$m_time_zone?></td>
                                <td id="td_right">
                                    <select size="1" name="tzone" id="tzone" onChange="on_change_time_zone()">
<?
                                    for ("/tmp/tz/zone")
                                    {
                                        echo "<option value=".$@;
                                        if ($cfg_tzone==$@) {echo " selected";}
                                        echo ">".get(h,"name")."</option>\n";
                                    }
?>                                  </select>
                                </td>
                            </tr>
							<tr>
								<td id="td_left"><?=$m_enable_daylight_saving?></td>
								<td id="td_right">
									<input type="checkbox" name="daylight" id="daylight" value="1" <?if ($cfg_ds=="1"){echo " checked";}?> onclick="on_click_daylight_saving()");>
								</td>
							</tr>
							<!--tr>
								<td id="td_left">
									<?=$m_daylight_saving_offset?>
								</td>
								<td id="td_right">
									<?=$G_TAG_SCRIPT_START?>genSelect("daylight_saving_offset", [0,1,2,3,4,5,6,7], ["-2:00","-1:30","-1:00","-0:30","+0:30","+1:00","+1:30","+2:00"], "");<?=$G_TAG_SCRIPT_END?>
								</td>
							</tr>			
							<tr>
								<td colspan="2">
									<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
										<tr>
											<td width="30%" id="td_left">
												<?=$m_daylight_saving_date?>
											</td>
											<td>
												<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
													<tr>
														<td></td>
														<td><?=$m_month?></td>
														<td><?=$m_week?></td>
														<td><?=$m_day_of_week?></td>
														<td><?=$m_time?></td>
													</tr>
													<tr>
														<td><?=$m_dst_start?></td>	
														<td>
															<?=$G_TAG_SCRIPT_START?>genSelect("dst_s_mon", [1,2,3,4,5,6,7,8,9,10,11,12], ["<?=$m_jan?>","<?=$m_feb?>","<?=$m_mar?>","<?=$m_apr?>","<?=$m_may?>","<?=$m_jun?>","<?=$m_jul?>","<?=$m_aug?>","<?=$m_sep?>","<?=$m_oct?>","<?=$m_nov?>","<?=$m_dec?>"], "");<?=$G_TAG_SCRIPT_END?>
														</td>	
														<td>
															<?=$G_TAG_SCRIPT_START?>genSelect("dst_s_week", [1,2,3,4,5], ["<?=$m_1st?>","<?=$m_2nd?>","<?=$m_3rd?>","<?=$m_4th?>","<?=$m_5th?>"], "");<?=$G_TAG_SCRIPT_END?>
														</td>	
														<td>
															<?=$G_TAG_SCRIPT_START?>genSelect("dst_s_day", [0,1,2,3,4,5,6], ["<?=$m_sun?>","<?=$m_mon?>","<?=$m_tue?>","<?=$m_wed?>","<?=$m_thu?>","<?=$m_fri?>","<?=$m_sat?>"], "");<?=$G_TAG_SCRIPT_END?>
														</td>
														<td>
															<?=$G_TAG_SCRIPT_START?>genSelect("dst_s_time", [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23], ["12<?=$m_am?>","1<?=$m_am?>","2<?=$m_am?>","3<?=$m_am?>","4<?=$m_am?>","5<?=$m_am?>","6<?=$m_am?>","7<?=$m_am?>","8<?=$m_am?>","9<?=$m_am?>","10<?=$m_am?>","11<?=$m_am?>","12<?=$m_pm?>","1<?=$m_pm?>","2<?=$m_pm?>","3<?=$m_pm?>","4<?=$m_pm?>","5<?=$m_pm?>","6<?=$m_pm?>","7<?=$m_pm?>","8<?=$m_pm?>","9<?=$m_pm?>","10<?=$m_pm?>","11<?=$m_pm?>"], "");<?=$G_TAG_SCRIPT_END?>
														</td>																																										
													</tr>	
													<tr>
														<td><?=$m_dst_end?></td>	
														<td>
															<?=$G_TAG_SCRIPT_START?>genSelect("dst_e_mon", [1,2,3,4,5,6,7,8,9,10,11,12], ["<?=$m_jan?>","<?=$m_feb?>","<?=$m_mar?>","<?=$m_apr?>","<?=$m_may?>","<?=$m_jun?>","<?=$m_jul?>","<?=$m_aug?>","<?=$m_sep?>","<?=$m_oct?>","<?=$m_nov?>","<?=$m_dec?>"], "");<?=$G_TAG_SCRIPT_END?>
														</td>	
														<td>
															<?=$G_TAG_SCRIPT_START?>genSelect("dst_e_week", [1,2,3,4,5], ["<?=$m_1st?>","<?=$m_2nd?>","<?=$m_3rd?>","<?=$m_4th?>","<?=$m_5th?>"], "");<?=$G_TAG_SCRIPT_END?>
														</td>	
														<td>
															<?=$G_TAG_SCRIPT_START?>genSelect("dst_e_day", [0,1,2,3,4,5,6], ["<?=$m_sun?>","<?=$m_mon?>","<?=$m_tue?>","<?=$m_wed?>","<?=$m_thu?>","<?=$m_fri?>","<?=$m_sat?>"], "");<?=$G_TAG_SCRIPT_END?>
														</td>
														<td>
															<?=$G_TAG_SCRIPT_START?>genSelect("dst_e_time", [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23], ["12<?=$m_am?>","1<?=$m_am?>","2<?=$m_am?>","3<?=$m_am?>","4<?=$m_am?>","5<?=$m_am?>","6<?=$m_am?>","7<?=$m_am?>","8<?=$m_am?>","9<?=$m_am?>","10<?=$m_am?>","11<?=$m_am?>","12<?=$m_pm?>","1<?=$m_pm?>","2<?=$m_pm?>","3<?=$m_pm?>","4<?=$m_pm?>","5<?=$m_pm?>","6<?=$m_pm?>","7<?=$m_pm?>","8<?=$m_pm?>","9<?=$m_pm?>","10<?=$m_pm?>","11<?=$m_pm?>"], "");<?=$G_TAG_SCRIPT_END?>
														</td>																																										
													</tr>																														
												</table>
											</td>						
										</tr>
									</table>					
								</td>
							</tr-->
						</table>
					</td>
				</tr>	
				<tr>
					<td colspan="2">
						<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
							<tr>
								<td class="table_tool_td" valign="middle" colspan="2"><b><?=$m_set_date_time_title?></b></td>
							</tr>															
							<tr>
								<td colspan="2">
									<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
										<tr>
											<td width="30%" id="td_left">
												<?=$m_current_time?>
											</td>
											<td>
												<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
													<tr>
														<td><?=$m_year?></td>
														<td>
															<input id="year" name="year" type="text" value="" size=4 maxlength=4>
														</td>
														<td><?=$m_month?></td>
														<td>
															<?=$G_TAG_SCRIPT_START?>genSelect("mon", [1,2,3,4,5,6,7,8,9,10,11,12], ["<?=$m_jan?>","<?=$m_feb?>","<?=$m_mar?>","<?=$m_apr?>","<?=$m_may?>","<?=$m_jun?>","<?=$m_jul?>","<?=$m_aug?>","<?=$m_sep?>","<?=$m_oct?>","<?=$m_nov?>","<?=$m_dec?>"], "on_change_month();");<?=$G_TAG_SCRIPT_END?>
														</td>
														<td><?=$m_day?></td>
														<td>
															<select size=1 id="day28" name="day28">
<?
															$i=0;
															while ($i<28)
															{
																$i++;
																echo "<option value=".$i.">".$i."</option>\n";
															}
?>
															</select>	

<select size=1 id="day29" name="day29">
<?
$i=0;
while ($i<29)
{
	$i++;
	echo "<option value=".$i.">".$i."</option>\n";
}
?>
</select>

<select size=1 id="day30" name="day30">
<?
$i=0;
while ($i<30)
{
    $i++;
    echo "<option value=".$i.">".$i."</option>\n";
}
?>
</select>

<select size=1 id="day31" name="day31">
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
														<td><?=$m_hour?></td>
														<td>
															<select size=1 id="hour" name="hour">
<?
															$i=0;
															while ($i<24)
															{
																echo "<option value=".$i.">".$i."</option>\n";
																$i++;
															}
?>
															</select>	
														</td>	
														<td><?=$m_minute?></td>
														<td>
															<select size=1 id="min" name="min">
<?
															$i=0;
															$value=0;
															while ($i<60)
															{
																
																echo "<option value=".$i.">".$value."</option>\n";
																$value++;
																$i++;
															}
?>
															</select>	
														</td>	
														<td><?=$m_second?></td>
														<td>
															<select size=1 id="sec" name="sec">
<?
															$i=0;
															$value=0;
															while ($i<60)
															{
																
																echo "<option value=".$i.">".$value."</option>\n";
																$value++;
																$i++;
															}
?>
															</select>	
														</td>	
														<!--td>
															<?=$G_TAG_SCRIPT_START?>genSelect("am_pm", [1,2], ["<?=$m_am?>","<?=$m_pm?>"], "");<?=$G_TAG_SCRIPT_END?>
														</td-->																																												
													</tr>
												</table>
											</td>
										<tr>			
									</table>					
								</td>
							</tr>	
							<tr>
								<td width="30%" id="td_left"></td>
								<td id="td_right">
									<input name="set" id="set" type="button" class="button_submit" onClick="set_time()" value="<?=$m_copy_pc_time?>">
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
