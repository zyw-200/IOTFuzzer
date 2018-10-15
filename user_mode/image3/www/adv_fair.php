<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_fair";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = "adv_fair";
$NEXT_PAGE    = "adv_fair";
set("/runtime/web/help_page",$MY_NAME);
/* --------------------------------------------------------------------------- */
if($ACTION_POST!="")
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
$check_band = query("/wlan/inf:2/ap_mode");
if($band_reload == 0 || $band_reload == 1) // change band
{ 	
	$cfg_band = $band_reload;
}
else
{
$cfg_band = query("/wlan/ch_mode");
}
$switch = query("/runtime/web/display/switchable");
$cfg_fair_state=query("/tc_monitor/state");
if($cfg_fair_state==""){$cfg_fair_state=0;}
$cfg_trafficmgr_state = query("/trafficctrl/trafficmgr/enable");
$cfg_qos_state = query("/trafficctrl/qos/enable");
$cfg_e2w=query("/tc_monitor/downlink");
$cfg_w2e=query("/tc_monitor/uplink");
if($cfg_e2w=="" || $cfg_w2e==""){$judge_enable=0;}else{$judge_enable=1;}
$list_row=0;
for("/trafficctrl/trafficmgr/rule/index")
{
	$list_row++;
}
$max_rate = query("/sys/data_rate/amax");
if($max_rate == "")
{$max_rate = query("/sys/data_rate/gmax");}
?>
<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>

var t_list_a=[['index','band','type','nameindex','downtype','uptype','downlink','uplink','name']
<?
$t_list_a_num=1;
for("/tc_monitor/mssid")
{

	echo ",\n ['".$@."','".query("band")."','".query("state")."','".query("nameindex")."','".query("downratetype")."','".query("upratetype")."','".query("downrate")."','".query("uprate")."','".query("name")."']";
	$t_list_a_num++;
}
?>
];

var t_list_flow_sum=[['flowdown','flowup']
<?
 echo",\n['".$cfg_e2w."000','".$cfg_w2e."000']";
?>];

var t_list_flow=[['index','downtype','uptype','downlink','uplink']
<?
$t_list_a_flow=1;
for("/tc_monitor/mssid")
{

	if( query("downratetype")==1000 && query("upratetype")==1000 )
	{
		echo ",\n ['".$@."','".query("downratetype")."','".query("upratetype")."','".query("downrate")."000','".query("uprate")."000']";
	}
	else if( query("downratetype")!=1000 && query("upratetype")==1000 )
	{
		echo ",\n ['".$@."','".query("downratetype")."','".query("upratetype")."','".query("downrate")."','".query("uprate")."000']";
	}
	else if( query("downratetype")==1000 && query("upratetype")!=1000 )
	{
		echo ",\n ['".$@."','".query("downratetype")."','".query("upratetype")."','".query("downrate")."000','".query("uprate")."']";
	}
		else
	{
		echo ",\n ['".$@."','".query("downratetype")."','".query("upratetype")."','".query("downrate")."','".query("uprate")."']";
	}
	$t_list_a_flow++;
}
?>
];

function init()
{
	var f=get_obj("frm");
	if("<?=$switch?>" == 1)
		f.band.value = "<?=$cfg_band?>";
	else
		f.band.value=0;
	f.ssid.value=0;
 	f.speed_e.value =1;
	f.speed_w.value =1;
	change_fair_state();
	AdjustHeight();
}

function change_fair_state()
{
	var f=get_obj("frm");
	if(f.fair_state.value == 1)
	{
		if("<?=$cfg_trafficmgr_state?>" == 1 || "<?=$cfg_qos_state?>" == 1)
		{
			alert("<?=$a_disable_trafficctrl?>");
		}
		f.e2w.disabled = f.w2e.disabled = f.averagetype.disabled = f.band.disabled = 
		f.ssid.disabled = f.e2wrule_a.disabled = f.speed_e.disabled = f.w2erule_a.disabled = 
		f.speed_w.disabled = f.t_add_a.disabled = f.t_clr_a.disabled = false;
	}
	else
	{
		f.e2w.disabled = f.w2e.disabled = f.averagetype.disabled = f.band.disabled = 
		f.ssid.disabled = f.e2wrule_a.disabled = f.speed_e.disabled = f.w2erule_a.disabled = 
		f.speed_w.disabled = f.t_add_a.disabled = f.t_clr_a.disabled = true;
	}
	if("<?=$check_band?>" == "")
		f.band.disabled = true;
}

function check_list_values_a()
{
	var f = get_obj("frm");
	var downcheck =parseInt("<?=$cfg_e2w?>" +"000",[10]);
	var upcheck =parseInt("<?=$cfg_w2e?>" +"000",[10]);
	if(f.e2wrule_a.value=="" || f.w2erule_a.value=="")
	{
		alert("<?=$a_empty_value_for_two_speed ?>");
		return false;
	}
	if(f.e2wrule_a.value!="")
	{
		if(!is_digit(f.e2wrule_a.value))
		{
			alert("<?=$a_invalid_value_for_speed?>");
			f.e2wrule_a.select();
			return false;
		}
		if(f.speed_e.value==1000)
		{
			var	downspeed = f.e2wrule_a.value + "000";
		}
		else
		{
			var downspeed = f.e2wrule_a.value;
		}
		if(parseInt(downspeed, [10]) < 1 || parseInt(downspeed, [10]) > parseInt(downcheck, [10]))
		{
			alert("<?=$a_GreaterThanPrimary_value_for_speed?>");
			f.e2wrule_a.select();
			return false;
		}
	}						
	if(f.w2erule_a.value!="")
	{
		if(!is_digit(f.w2erule_a.value))
		{
			alert("<?=$a_invalid_value_for_speed?>");
			f.w2erule_a.select();
			return false;
		}
		if(f.speed_w.value==1000)
		{
			var upspeed = f.w2erule_a.value + "000";
		}
		else
		{
			var upspeed = f.w2erule_a.value;
		}
		if(parseInt(upspeed, [10]) < 1 || parseInt(upspeed, [10]) > parseInt(upcheck, [10]))
		{
			alert("<?=$a_GreaterThanPrimary_value_for_speed?>");
			f.w2erule_a.select();
			return false;
		}
	}
	return true;
}
function do_t_add_a()
{
	var f = get_obj("frm");
	if(check_list_values_a()==true)
	{
		for(var i=0;i<f.e2wrule_a.value.length;i++)
		{
			if(f.e2wrule_a.value.charAt(i)!=0)
			{
				f.e2wrule_a.value=f.e2wrule_a.value.substring(i);
				break;
			}
		}
		for(var i=0;i<f.w2erule_a.value.length;i++)
		{
			if(f.w2erule_a.value.charAt(i)!=0)
			{
				f.w2erule_a.value=f.w2erule_a.value.substring(i);
				break;
			}
		}
		f.action.value="add";
		fields_disabled(f, false);	
		f.submit();
		return true;
	}	
	AdjustHeight();
}

function change_ssid()
{
	var f=get_obj("frm");
	if(f.band.value == 0)
		var id = 1;
	else
		var id = 9;
	var index = parseInt(f.ssid.value, [10]) + id;
	f.e2wrule_a.value = t_list_a[index][6];
	f.w2erule_a.value = t_list_a[index][7];
	f.speed_e.value = t_list_a[index][4];
	f.speed_w.value = t_list_a[index][5];
	if(t_list_a[index][2] == 0 || t_list_a[index][2] == "")
	{
		f.e2wrule_a.value=f.w2erule_a.value="";
		f.speed_e.value = f.speed_w.value = 1;
	}
}
function do_t_clr_a()
{
	var f=get_obj("frm");
	f.averagetype.value=1;
	f.band.value=f.ssid.value=0;
	f.speed_e.value=f.speed_w.value=1;
	f.e2wrule_a.value=f.w2erule_a.value="";
}

function do_edit_a(id)
{
	var f=get_obj("frm");
	f.band.value=t_list_a[id][1];
	f.averagetype.value=t_list_a[id][2];
	f.ssid.value=t_list_a[id][3];
	f.speed_e.value=t_list_a[id][4];
	f.speed_w.value=t_list_a[id][5];
	f.e2wrule_a.value=t_list_a[id][6];
	f.w2erule_a.value=t_list_a[id][7];
}

function do_del_a(id)
{
	var f=get_obj("frm");	
	var ath_id;
	if(f.fair_state.value == 1 && confirm("<?=$a_rule_del_confirm?>")==true)
	{
		f.which_delete.value=id;
		f.action.value="delete";
		f.submit();
	  	return true;
	}	
}
function check()
{
	var f=get_obj("frm");
	if(!is_digit(f.e2w.value))
	{
		alert("<?=$a_invalid_value_for_bandwidth?>");
		f.e2w.select();
		return false;
	}
	if(f.e2w.value < 0 || parseInt(f.e2w.value, [10]) > parseInt("<?=$max_rate?>", [10]))
	{
		alert("<?=$a_invalid_range_for_bandwidth_st?>" + "<?=$max_rate?>" + "<?=$a_invalid_range_for_bandwidth_end?>");
		f.e2w.select();
		return false;
	}
	if(!is_digit(f.w2e.value))
	{
		alert("<?=$a_invalid_value_for_bandwidth?>");
		f.w2e.select();
		return false;
	}
	if(f.w2e.value < 0 || parseInt(f.w2e.value,[10]) > parseInt("<?=$max_rate?>", [10]))
	{
		alert("<?=$a_invalid_range_for_bandwidth_st?>" + "<?=$max_rate?>" + "<?=$a_invalid_range_for_bandwidth_end?>");
		f.w2e.select();
		return false;
	}
	var max_speed_e2w = 0;
	var downband = f.e2w.value + "000";
	for(var i=1;i<"<?=$t_list_a_flow?>";i++)
	{
		if(t_list_flow[i][3] == "")
		{
			t_list_flow[i][3] = 0;
		}
		if(t_list_a[i][2] != 0)
		{
			if(parseInt(max_speed_e2w, [10]) < parseInt(t_list_flow[i][3], [10]))
			{
				max_speed_e2w = t_list_flow[i][3];
			}
		}
	}
	if(parseInt(downband, [10]) < parseInt(max_speed_e2w, [10]))
	{
		alert("<?=$a_primaryless_value_for_bandwidth?>");
		f.e2w.select();
		return false;
	}
	var max_speed_w2e = 0;
	var upband = f.w2e.value + "000";
	for(var i=1;i<"<?=$t_list_a_flow?>";i++)
	{
		if(t_list_flow[i][4] == "")
		{
			t_list_flow[i][4] = 0;
		}
		if(t_list_a[i][2] != 0)
		{
			if(parseInt(max_speed_w2e, [10]) < parseInt(t_list_flow[i][4], [10]))
			{
				max_speed_w2e = t_list_flow[i][4];
			}
		}
	}
	if(parseInt(upband, [10]) < parseInt(max_speed_w2e, [10]))
	{
		alert("<?=$a_primaryless_value_for_bandwidth?>");
		f.w2e.select();
		return false;
	}
	return true;
}

function submit()
{
	var f=get_obj("frm");
	if(check()==true)
	{
		f.action.value="apply";
		f.submit();
		return true;
	}
}
function toBreakWord(index)
{ 
	var str1,listname,str2="",str="";
	listname=t_list[index][1];
	if(navigator.userAgent.indexOf('Firefox') >= 0)
	{
		if(listname.length > 8)
		{
			str1=listname.substring(0,7);
        	str2=listname.substring(8);
        	str="<td width=17%>"+str1+"<br>"+str2+"</td>\n";
        }
		else
		{str="<td width=17%>"+listname+"</td>\n";}
	}
	else
	{
		str+="<td width=17% style='word-wrap:break-word; word-break:break-all;'>"+t_list[index][1]+"</td>\n";
	}
	document.write(str); 
}
</script>

<body <?=$G_BODY_ATTR?> onload="init();">

<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">
<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="which_delete" value="">
<input type="hidden" name="action" value="">
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
      		<td width="25%" id="td_left"><?=$m_enable_fair?></td>
      		<td>
      			<select id="fair_state" name="fair_state" onChange="change_fair_state()">
      				<option value="0" <? if($cfg_fair_state==0) {echo "selected";}?>><?=$m_disable?></option>
      				<option value="1" <? if($cfg_fair_state==1) {echo "selected";}?>><?=$m_enable?></option>
      			</select>
      		</td>
      	</tr>
		<tr>
			<td width="25%" id="td_left"><?=$m_DownlinkInterface?></td>
			<td>
				<input type="text" id="e2w" name="e2w" maxlength="4" size="6" value="<?=$cfg_e2w?>"><?=$m_speed_m?>
			</td>
		</tr>
		<tr>
			<td width="25%" id="td_left"><?=$m_UplinkInterface?></td>
			<td>
				<input type="text" id="w2e" name="w2e" maxlength="4" size="6" value="<?=$cfg_w2e?>"><?=$m_speed_m?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
					<tr>
						<td class="table_tool_td" valign="middle" colspan="2"><b><?=$m_context_add_title?></b></td>
						<td class="table_tool_td" valign="middle" colspan="2"></td>
					</tr>
					<tr>
						<td width="25%"  id="td_left"><?=$m_average?></td>
						<td>
							<?=$G_TAG_SCRIPT_START?>genSelect("averagetype", [1,4,3,2], ["<?=$m_averagetype_s?>","<?=$m_averagetype_fstation?>","<?=$m_averagetype_fw?>","<?=$m_averagetype_fssid?>"], "");<?=$G_TAG_SCRIPT_END?>
						</td>								
      				</tr>
      				<tr>
      					<td width="25%"> <?=$m_band?></td>
      					<td>
      							<?=$G_TAG_SCRIPT_START?>genSelect("band", [0,1], ["<?=$m_band_2_4G?>","<?=$m_band_5G?>"], "change_ssid()");<?=$G_TAG_SCRIPT_END?>
      					</td>
      				</tr>			
      				<tr>
      					<td width="25%"><?=$m_ssidindex?></td>
    				<? if(query("/runtime/web/display/mssid_index4") !="0")	{echo "<!--";} ?>										
						<td>
							<?=$G_TAG_SCRIPT_START?>genSelect("ssid", [0,1,2,3,4,5,6,7], ["<?=$m_pri_ssid?>","<?=$m_ms_ssid1?>","<?=$m_ms_ssid2?>","<?=$m_ms_ssid3?>","<?=$m_ms_ssid4?>","<?=$m_ms_ssid5?>","<?=$m_ms_ssid6?>","<?=$m_ms_ssid7?>"], "change_ssid()");<?=$G_TAG_SCRIPT_END?>
						</td>
					<? if(query("/runtime/web/display/mssid_index4") !="0")	{echo "-->";} ?>										
					<? if(query("/runtime/web/display/mssid_index4") =="0")	{echo "<!--";} ?>										
						<td>
							<?=$G_TAG_SCRIPT_START?>genSelect("ssid", [0,1,2,3], ["<?=$m_pri_ssid?>","<?=$m_ms_ssid1?>","<?=$m_ms_ssid2?>","<?=$m_ms_ssid3?>"], "change_ssid()");<?=$G_TAG_SCRIPT_END?>
						</td>
					<? if(query("/runtime/web/display/mssid_index4") =="0")	{echo "-->";} ?>		
      				</tr>      				
    				 	
      				<tr>
      					<td width="25%"><?=$m_Downlink_Speed?></td>
      					<td><input type="text" id="e2wrule_a" name="e2wrule_a" maxlength="6" size="8" value=""><?=$G_TAG_SCRIPT_START?>genSelect("speed_e", [1,1000], ["<?=$m_speed_k?>","<?=$m_speed_m?>"], "");<?=$G_TAG_SCRIPT_END?></td>
      				</tr>
      				<tr>
      					<td width="25%" ><?=$m_Uplink_Speed?></td>
      					<td><input type="text" id="w2erule_a" name="w2erule_a" maxlength="6" size="8" value=""><?=$G_TAG_SCRIPT_START?>genSelect("speed_w", [1,1000], ["<?=$m_speed_k?>","<?=$m_speed_m?>"], "");<?=$G_TAG_SCRIPT_END?></td>
      				</tr>
   		 			<tr>
      					<td width="25%"></td>
      					<td>
      						<input type="button" id="t_add_a" name="t_add_a" value="<?=$m_b_add?>" onClick="do_t_add_a()">
      						<input type="button" id="t_clr_a" name="t_clr_a" value="<?=$m_b_cancel?>" onClick="do_t_clr_a()">
      					</td>
      				</tr>  
      			</table>
   				<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
      				<tr>
      					<td class="table_tool_td" valign="middle" colspan="2"><b><?=$m_context_qos_title?></b></td>
      				</tr>
      				
      				<tr>
      					<td colspan="2">      			
      					<div class="div_tab_tr"> 
      					 	<table id="acla_tab" width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;table-layout:fixed;">

                            <tr class="fixed_head" align="left">
      								<td width="10%"><?=$m_band?></td>
      								<td width="20%"><?=$m_type?></td>
      								<td width="10%"><?=$m_ssidindex?></td>
      								<td width="20%"><?=$m_Downlink_Speed ?></td>
      								<td width="20%"><?=$m_Uplink_Speed ?></td>
      								<td width="5%"><?=$m_edit ?></td>
      								<td width="5%"><?=$m_del ?></td>
      						</tr>
				<?
				$key=0;
				$ssid_index = 1;
				$select_id=0;
				while($ssid_index < 17)
				{
					anchor("/tc_monitor/mssid:".$ssid_index);
					if(query("state")==1)
					{
								$list_type=$m_averagetype_s;
					}
					else if(query("state")==2)
					{
							$list_type=$m_averagetype_fssid;
					}
					else if(query("state")==3)
					{
						$list_type=$m_averagetype_fw;
					}
					else if(query("state")==4)
					{
							$list_type=$m_averagetype_fstation;
					}
					if($switch == 1)
					{
						if($cfg_band == 0){$list_band="2.4G";}
						else{$list_band="5G";}
						$select_id=1;
					}
					else
					{
						if(query("band")==0)
						{
							$list_band="2.4G";
							$select_id=1;
						}
						else if (query("band")==1)
						{
							$list_band="5G";
							$select_id=9;
						}
					}
					$select_id+=query("nameindex");
					if(query("upratetype")==1)
					{
						$list_uptype="Kbits/sec";
					}
					else if (query("upratetype")==1000)
					{
						$list_uptype="Mbits/sec";
					}
					if(query("downratetype")==1)
					{
						$list_downtype="Kbits/sec";
					}
					else if (query("downratetype")==1000)
					{
						$list_downtype="Mbits/sec";
					}
					$list_nameindex=query("nameindex");
					if($list_nameindex == 0)
					{
						$list_ssid = $m_pri_ssid;
					}
					else
					{
						$list_ssid = $m_ssid.$list_nameindex;
					}
					$list_e2w=query("downrate");
					$list_w2e=query("uprate");
					if(query("state") != "0" )
					{
							$key++;
							if($key%2==1)
							{
								echo "<tr style='background:#CCCCCC;'>\n";
							}
							else
							{
								echo "<tr style='background:#B3B3B3;'>\n";
							}
							echo "<td width=10%>".$list_band."</td>\n";
								echo "<td width=20%>".$G_TAG_SCRIPT_START."genTableName(\"".$list_type."\",18);".$G_TAG_SCRIPT_END."</td>\n";
						  echo "<td width=10%>".$list_ssid."</td>\n";		
						  echo "<td width=20%>".$list_e2w.$list_downtype."</td>\n";
						  echo "<td width=20%>".$list_w2e.$list_uptype."</td>\n";
						  echo "<td width=5%><a href='javascript:do_edit_a(\"".$select_id."\")'><img src='/pic/edit.jpg' border=0></a></td>";
						  echo "<td width=5%><a href='javascript:do_del_a(\"".$select_id."\")'><img src='/pic/delete.jpg' border=0></a></td>";
							echo "</tr>\n";
					}
					$ssid_index++;
					if($ssid_index > 8 && $check_band == ""){$ssid_index+=100;}
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
