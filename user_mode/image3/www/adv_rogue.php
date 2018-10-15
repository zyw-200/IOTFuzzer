<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_rogue";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_rogue";
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
echo "<!DOCTYPE html PUBLIC \"-\/\/W3C\/\/DTD XHTML 1.0 Strict\/\/EN\" \"http:\/\/www.w3.org\/TR\/xhtml1\/DTD\/xhtml1-strict.dtd\">";
require("/www/model/__html_head.php");
require("/www/comm/__js_ip.php");
set("/runtime/web/next_page",$first_frame);
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
if($switch == 1)
{
	anchor("/wlan/inf:1");
	$cfg_index = 1;
}
else
{
	if($cfg_band == 0) // 11g
	{	
		anchor("/wlan/inf:1");
	}
	else
	{
		$cfg_band = 1;
		anchor("/wlan/inf:2");	
	}
	$cfg_index = $cfg_band+1;
}
$cfg_mode_g = query("/wlan/inf:1/ap_mode");
$cfg_mode_a = query("/wlan/inf:2/ap_mode");
// get the variable value from rgdb.
$tmp_valid = 0;
$tmp_neighborhood = 0;
$tmp_rogue = 0;
for("rogue_ap/client")
{
	if(query("type")== 1)
	{
		$tmp_valid ++;
	}
	else if(query("type")== 2)
	{
		$tmp_neighborhood ++;
	}
	else if(query("type")== 3)
	{
		$tmp_rogue ++;
	}

}

if($type_reload != 1 && $type_reload != 2 && $type_reload != 3 && $type_reload != 0)
{
	$type_reload = 5;
}
/* --------------------------------------------------------------------------- */
?>


<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>
var apc_display=0;
var apc_type=['<?=$m_new?>','<?=$m_valid?>','<?=$m_neighborhood?>','<?=$m_rogue?>'];
var apc_Band=['<?=$m_b?>','<?=$m_g?>','<?=$m_n?>','<?=$m_a?>','<?=$m_ac?>'];
var apc_status=['','<?=$m_up?>','<?=$m_down?>'];
var apc_list=[['Type','Band','CH','SSID',,'BSSID','Time','Time_str','Status']
<?
$apc_num=0;
$apc_band_m=query("/wlan/ch_mode");
for("/runtime/wlan/inf:".$cfg_index."/rogue_ap/client")
{
	if(query("chan:") != "-2")
	{
				set("/runtime/wlan/inf:".$cfg_index."/rogue_ap/client:".$@."/index",  0); 
				echo ",\n['".query("type")."','".query("band")."','".query("channel")."','".get("j","ssid")."','".query("mac")."','".query("time")."','".query("time_str")."','".query("status")."']";
				$apc_num++;
	}
}
}
?>];
var apc_band="<?=$apc_band_m?>";
function on_change_scan_table_height()
{
	var x = get_obj("scan_tab").offsetHeight;
	
	if(get_obj("adjust_td") != null)
	{
		if(x <= 120)
		{
			get_obj("adjust_td").width="10%";
			if(is_IE())
			{
				get_obj("scan_tab").width="100%";
			}
		}
		else
		{
			get_obj("adjust_td").width="7%";
			if(is_IE())
			{
				get_obj("scan_tab").width="97%";
			}
		}
	}
}	

function on_change_rogue_ap_type(s)
{
	var f = get_obj("frm");
	if("<?=$check_band?>" == "")
		self.location.href = "<?=$MY_NAME?>.php?type_reload=" + s.value;
	else
		self.location.href = "adv_rogue.php?type_reload=" + s.value + "&band_reload=" + f.band.value;
}

function shortTime(t,t_str)
{
	var str=new String("");
	var t=parseInt(t, [10]);
	var sec=0,min=0,hr=0,day=0;

	if(t > 1199116800)
	{
		str=t_str;
	}
	else
	{
		sec=t % 60;  //sec
		min=parseInt(t/60, [10]) % 60; //min
		hr=parseInt(t/(60*60), [10]) % 24; //hr
		day=parseInt(t/(60*60*24), [10]); //day
	
		if(day>=0 || hr>=0 || min>=0 || sec >=0)
			str=(day >0? day+" <?=$m_days?>, ":"0 <?=$m_days?>, ")+(hr >0? hr+":":"00:")+(min >0? min+":":"00:")+(sec >0? sec :"00");
	}	
	return (str);
}
/* page init functoin */
function init()
{
	var f = get_obj("frm");
	
	f.band.value = "<?=$cfg_band?>";
	if("<?=$check_band?>" == "")
		f.band.disabled = true;
	f.rogue_type.value = "<?=$type_reload?>";
	
	f.set_new[0].disabled = false;
	f.set_new[1].disabled = false;	
		
	
	if(f.rogue_type.value == 1 || f.rogue_type.value == 2 || f.rogue_type.value == 3)
	{
		f.set_new[0].disabled = true;
		f.set_new[1].disabled = true;	
	}

	f.set_new[0].checked = true;

	on_change_scan_table_height();
	if((f.band.value == 0 && "<?=$cfg_mode_g?>" == 1) || (f.band.value == 1 && "<?=$cfg_mode_a?>" == 1))
	{
		fields_disabled(f, true);
		f.band.disabled = false;
	}
	AdjustHeight();

}

/* parameter checking */
function check()
{
	var f=get_obj("frm");

	if(f.set_new[0].checked)
	{
		f.f_type.value = 1;
	}
	else
	{
		f.f_type.value = 3;
	}

	if(check_db_number(f.f_type.value) != false)
	{
		fields_disabled(f, false);
		f.submit();
	}
	return true;
}

function submit()
{
	var f =get_obj("frm");
	if(check()) 
	{
		fields_disabled(f, false);
		f.submit();
	}
}

function do_detect()
{
	var f	=get_obj("frm");
	f.f_detect.value = "1";
	var str = "";
	for(var x in f)
	{
		str += x + " : " + " --- ";
	}
	//alert(str);
	fields_disabled(f, false);
	f.submit();
}

function do_change_type(type)
{
	var f	=get_obj("frm");
	f.f_type.value = type;

	if(check_db_number(f.f_type.value) != false)
	{
		fields_disabled(f, false);
		f.submit();
	}
}

function genSSID(ssid)
{
				var str = "";
        for(var i=0; i < ssid.length; i++)
        {
                if(i!=0 && (i%11)==0)// change line
                {
                        str+="<br \>";
                }
                if(ssid.charAt(i)==" ")
                {
                str+="&nbsp;";
                }
                else if(ssid.charAt(i)=="<")
                {
                str+="&lt;";
                }
                else
                {
                str+=ssid.charAt(i);
        				}
				}
       	return(str);

}
function gentime(time,time_str)
{
	var str = "";
  str+=time;
  str+="Days,";
  str+=time_str;  
       	return(str);
}
function genCheckBox1(id)
{
        var str = "";
        str+="<input name=\"" + id + "\" id=\"" + id + "\" type=\"checkbox\"  value=\"1\">";
        return(str);
}
function page_table(index)
{
	var chan = apc_list[index][2];
	if(chan != "-2")   
	{
		var str="";   
		var checkboxstr="";
		var type = apc_type[apc_list[index][0]];
  	var band = apc_Band[apc_list[index][1]];
		var ssid = apc_list[index][3];
  	var mac = apc_list[index][4];
  	var time = apc_list[index][5];
  	var time_str = apc_list[index][6];
  	var status=apc_status[apc_list[index][7]];
  	var id = "client_idx" + index;
   	checkboxstr = genCheckBox1(id); 
		str+="<input type='hidden' id='h_type"+index+"' name='h_type"+index+"' value='"+apc_list[index][0]+"'>";  
		if((<?=$type_reload?>==apc_list[index][0])||(<?=$type_reload?>==5))
		{
			if(((apc_band==0)&&(chan<36))||((apc_band==1)&&(chan>=36)))
				{
					if(index%2==1)
					{
						str+="<tr align='center' style='background:#CCCCCC;'>";
					}
					else
					{
						str+="<tr align='center' style='background:#B3B3B3;'>";
					}
					str+="<td width='5%'>"+checkboxstr+"</td>";			
					str+="<td width='10%' style='font-size: 11px;'>"+type+"</td>";
					str+="<td width='10%' style='font-size: 11px;'>"+band+"</td>";
					str+="<td width='5%' style='font-size: 11px;'>"+chan+"</td>";
					str+="<td width='20%' style='font-size: 11px;'>"+genSSID(ssid)+"</td>";
					str+="<td width='20%' style='font-size: 11px;'>"+mac+"</td>";
					str+="<td width='20%' style='font-size: 11px;'>"+shortTime(parseInt(time),time_str)+"</td>";
					str+="<td id='adjust_td' style='font_size: 11px'>"+	status+"</td>";
					str+= "</tr>\n";		
					document.write(str);    
					
				}
					
		}
		apc_display++;
		
	}   
}

function table_apply()
{
	
}

</script>

<body <?=$G_BODY_ATTR?> onload="init();">

<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">

<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="f_detect"		value="">
<input type="hidden" name="f_type"		value="">
<input type="hidden" name="f_new_to_valid"		value="">
<input type="hidden" name="f_new_to_rogue"		value="">

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
				<tr height="25">
					<td width="20%"><?=$m_wireless_band?></td>
					<td>
						<?=$G_TAG_SCRIPT_START?>genSelect("band", [0,1], ["<?=$m_band_2_4G?>","<?=$m_band_5G?>"], "on_change_band(this)");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="button" value="&nbsp;&nbsp;<?=$m_b_detect?>&nbsp;&nbsp;" name="detect" onclick="do_detect()">
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<fieldset id="ap_list_fieldset" style="">
							<legend><?=$m_ap_list_title?></legend>
							<table  width="100%" border="0" align="center" <?=$G_TABLE_ATTR_CELL_ZERO?>>
								<tr>
									<td height="25">
										<?=$G_TAG_SCRIPT_START?>genSelect("rogue_type", [5,1,2,3,0], ["<?=$m_all?>","<?=$m_valid?>","<?=$m_neighborhood?>","<?=$m_rogue?>","<?=$m_new?>"], "on_change_rogue_ap_type(this)");<?=$G_TAG_SCRIPT_END?>
									</td>
								</tr>
								<tr>
									<td>
										<table width="100%" border="0" align="center" <?=$G_TABLE_ATTR_CELL_ZERO?>>
										<tr class="list_head" align="center">
											<td width="5%">
												<?=$G_TAG_SCRIPT_START?>genCheckBox("all_click", "on_click_all(this)");<?=$G_TAG_SCRIPT_END?>
											</td>
											<td width="10%">
												<?=$m_type?>
											</td>
											<td width="10%">
												<?=$m_band?>
											</td>
											<td width="5%">
												<?=$m_channel?>
											</td>
											<td width="20%">
												<?=$m_ssid?>
											</td>
											<td width="20%">
												<?=$m_mac?>
											</td>
											<td width="20%">
												<?=$m_last_seem?>
											</td>
											<td width="10%">
												<?=$m_status?>
											</td>
											<td>
												&nbsp;
											</td>												
										</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<div class="div_tab">
										<table id="scan_tab" border="0"  width="100%" <?=$G_TABLE_ATTR_CELL_ZERO?>>
									<?
											$count_list=0;										//aaaaaa
										while($count_list< $apc_num)
										{    
											$count_list++;
											echo $G_TAG_SCRIPT_START."page_table(".$count_list.");".$G_TAG_SCRIPT_END;  																		
											
										}
?>

										</table>
										</div>
									</td>
								</tr>
							</table>
						</fieldset>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="button" value="&nbsp;<?=$m_b_valid?>&nbsp;" name="valid" onclick="do_change_type('1')">
						<input type="button" value="&nbsp;<?=$m_b_neighborhood?>&nbsp;" name="neighborhood" onclick="do_change_type('2')">
						<input type="button" value="&nbsp;<?=$m_b_rogue?>&nbsp;" name="rogue" onclick="do_change_type('3')">
						<input type="button" value="&nbsp;<?=$m_b_new?>&nbsp;" name="new" onclick="do_change_type('0')">
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="radio" id="set_new" name="set_new" value="0" onClick="on_check_set_new()">
						<?=$m_all_valid?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<input type="radio" id="set_new" name="set_new" value="1" onClick="on_check_set_new()">
						<?=$m_all_rogue?>
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
<?=$G_TAG_SCRIPT_START?>
function on_change_band(s)
{
	var f = get_obj("frm");
	self.location.href = "adv_rogue.php?band_reload=" + s.value;
}

function on_click_all(s)
{
	var tmp=<?=$count_list?>;
	if(s.checked)
	{
		for(var i=1; i<=apc_display ; i++)
		{                   
			if(get_obj("client_idx"+i) != null)
			{
				get_obj("client_idx"+i).checked = true;
			}
		}
	}
	else
	{
		for(var i=1; i<=apc_display ; i++)
		{
			if(get_obj("client_idx"+i) != null)
			{
				get_obj("client_idx"+i).checked = false;
			}
		}
	}
}

function check_db_number(type_num)
{
	var how_many_click = 0;
	var can_add_num = 0 , want_to_add_num = 0;

	for(var i=1; i<=apc_display; i++)
	{
		if(get_obj("client_idx"+i) != null)
		{
			if(get_obj("client_idx"+i).checked)
				how_many_click ++;
		}
	}
	if(how_many_click < 1)
	{
		alert("<?=$a_no_click?>");
		return false;
	}

	if(type_num == 1)
	{
		can_add_num = 64 - parseInt("<?=$tmp_valid?>",[10]);
		want_to_add_num = parseInt("<?=$tmp_valid?>",[10])+parseInt(how_many_click);
	}
	else if(type_num == 2)
	{
		can_add_num = 64 - parseInt("<?=$tmp_neighborhood?>",[10]);
		want_to_add_num = parseInt("<?=$tmp_neighborhood?>",[10])+parseInt(how_many_click);
	}
	else if(type_num == 3)
	{
		can_add_num = 64 - parseInt("<?=$tmp_rogue?>",[10]);
		want_to_add_num = parseInt("<?=$tmp_rogue?>",[10])+parseInt(how_many_click);
	}

	if(want_to_add_num > 64 )
	{
		alert("<?=$a_max_list?><?=$a_can_add_num?>"+can_add_num+"<?=$a_entry?>");
		return false;
	}
	return true;
}

function on_check_set_new()
{
	var f = get_obj("frm");

	for(var i=1; i<=apc_display ; i++)
	{
		if(get_obj("h_type"+i).value == 0)
		{
			get_obj("client_idx"+i).checked = true;
		}
	}
}
<?=$G_TAG_SCRIPT_END?>
</html>

