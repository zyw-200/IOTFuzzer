<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "st_ap";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "st_ap";
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
	$apc_band_m=1;
}
else
{
	if(query("/wlan/ch_mode") == 0)
	{
		$apc_band_m=1;
	}
	else
	{
		$apc_band_m=2;
	}
}
// get the variable value from rgdb.

$ACTION_DECTECT=query("/runtime/wlan/inf:".$apc_band_m."/st_ap");
echo "<!--";
echo "ACTION_DECTECT =".$ACTION_DECTECT;
echo "-->";
/* --------------------------------------------------------------------------- */
?>


<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>

var chann=[['channel','','','','','']
<?
$apc_chann_num=0;	
if($switch == 1)
{
	if($cfg_band == 0){$chan_band = 1;}
	else{$chan_band = 2;}
}
else
{
	$chan_band = $apc_band_m;
}
for("/runtime/stats/wlan/inf:".$chan_band."/channeltable/channel")
{	
	$AP_CHANNEL_ADDR="/runtime/stats/wlan/inf:".$chan_band."/channeltable/channel:".$@."";
	echo ",\n['".query($AP_CHANNEL_ADDR."/channel")."',' ',' ',' ',' ',' ']";
	$apc_chann_num++;	
}
?>];
var apc_display=1;
var apc_chanst=['<?=$m_best?>','<?=$m_nomal?>','<?=$m_bad?>']
var apc_rssi=[['ARssi']
<?
$apc_rssi_num=1;
	
for("/wlan/inf:".$apc_band_m."/rogue_ap/client/arssix")
{
		echo ",\n['".query("/wlan/inf:".$apc_band_m."/rogue_ap/client/arssix:".$apc_rssi_num."")."']";
	if($apc_chann_num > =  $apc_rssi_num)
		{
			$apc_rssi_num++;
		}
		
}
?>];

var apc_mrssi=[['Mrssi','AP NUM','Pri','CHANNEL']
<?
$apc_mrssi_num=0;
$AP_ADDR="/runtime/wlan/inf:".$apc_band_m."/apscanlistinfo/apscan:";
$AP_ADDR1="/runtime/wlan/inf:".$apc_band_m."/rogue_ap_scan/client:";
$AP_ADDR2="/runtime/wlan/inf:".$apc_band_m."/rogue_ap/client:";
for("/runtime/wlan/inf:".$apc_band_m."/apscanlistinfo/apscan")
{
	$AP_INDEX=query("/runtime/wlan/inf:".$apc_band_m."/apscanlistinfo/apscan/ap_index:".$@);
	echo ",\n['".query("/wlan/inf:".$apc_band_m."/rogue_ap/client/mrssi:".$apc_mrssi_num."")."','".query("/wlan/inf:".$apc_band_m."/rogue_ap/client/singleap_num:".$apc_mrssi_num."")."','".query("/wlan/inf:".$apc_band_m."/rogue_ap/client/arssipri:".$apc_mrssi_num."")."','".query($AP_ADDR.$AP_INDEX."/chan")."']";
		$apc_mrssi_num++;		
}
?>];

var apc_pri_2=[
<?
	echo "'".query("/wlan/inf:".$apc_band_m."/rogue_ap/client/arssipri:0")."','".query("/wlan/inf:".$apc_band_m."/rogue_ap/client/arssipri:1")."','".query("/wlan/inf:".$apc_band_m."/rogue_ap/client/arssipri:2")."']";
?>

if("<?=$cfg_band?>" == 0)
	var apc_band=1;
else
	var apc_band=2;
var apc_num_sort="<?=$apc_mrssi_num?>";
function display_chann()
{

		var m=1;
		for(var j=1;j<="<?=$apc_chann_num?>";j++)
					{
						for (var i=1;i<=apc_num_sort;i++)
						{
				
						if(apc_mrssi[i][3] == chann[j][0])
						{
							chann[j][1]='1';
							chann[j][2]=apc_mrssi[m][0];
							chann[j][3]=apc_rssi[j];
							chann[j][4]=apc_mrssi[m][1];
							chann[j][5]=apc_mrssi[m][2];
							if(i < apc_num_sort)
							{
								if(apc_mrssi[i][3]!=apc_mrssi[i+1][3])
									m++;
							}
							else
							{
								m++;
							}
						}
						else
						{
							chann[j][3]=apc_rssi[j];
						
						}
				
				}
		}
		
		
}

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

/* page init functoin */
function init()
{
	var f = get_obj("frm");
	
	f.band.value = "<?=$cfg_band?>";
	if("<?=$check_band?>" == "")
		f.band.disabled = true;
	on_change_scan_table_height();
	AdjustHeight();
	
}

/* parameter checking */
function check()
{
	var f=get_obj("frm");


	f.submit();
	return true;
}

function submit()
{
	if(check()) get_obj("frm").submit();
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

function page_table_summary(index)
{
	
	var chan = chann[index][0];
	var mrssi = chann[index][2];
  var arssi = chann[index][3];
  var singnum =chann[index][4];
	var str="";   
	var checkboxstr="";
  var id = "client_idx" + index;
	var centerchan ;
	var context=" ";
	if(apc_band ==2 )
	{
		if(chann[index][1] != "1" )
		{context=apc_chanst[0];}
		else
		{
		 	context = apc_chanst[chann[index][5]]; 		 
			if(chann[index][5] > 2 || chann[index][5] == "")
				context = " ";
		}
	}
	else if (apc_band ==1)
	{
		if(index<3)
			{
				centerchan=1;
			}
			else if(index<=6)
			{
				centerchan=6;
			}
			else if(index >= 7)
			{
				centerchan=11;
			}
			for(i=0;i<3;i++)
				{
					if(centerchan == apc_pri_2[i])
						{
							context=apc_chanst[i];
						}
						
				}
				if(apc_pri_2[0] == "" && apc_pri_2[1] == "" && apc_pri_2[2] == "")
				{
					context="";
				}
	}
	
	if(chan != "-2")   
	{
  			if(chann[index][1] != "1")
  			{
  				singnum = 0;
  				mrssi = " ";
  			}
  			if(index == 2)
  			{
  				arssi=chann[index-1][3];
  			}
  			else if(index == 7)
  			{
  				arssi=chann[index+1][3];
  			}
  			else if(index>7 )
  			{
  				arssi=chann[8][3];
  			}
			if("<?=$apc_rssi_num?>" == 1)
				arssi = " ";
	  		 if(apc_band == 1)
	  		 {
						if((index< 3)||(index>=7))
						{
							str+="<tr align='center' style='background:#CCCCCC;'>";
						}
						else if((index>=3)&&(index<7))
						{
							str+="<tr align='center' style='background:#B3B3B3;'>";
						}
					}
					else if(apc_band == 2)
					{
							if(index%2==1)
							{
								str+="<tr align='center' style='background:#CCCCCC;'>";
							}
							else
							{
								str+="<tr align='center' style='background:#B3B3B3;'>";
							}
					}
					str+="<td width='10%' style='font-size: 11px;'>"+chan+"</td>";
					str+="<td width='15%' style='font-size: 11px;'>"+singnum+"</td>";
					str+="<td width='25%' style='font-size: 11px;'>"+mrssi+"</td>";
					if(apc_band == 1)
					str+="<td width='25%' style='font-size: 11px;'>"+arssi+"</td>";
					else
					str+="<td width='25%' style='font-size: 11px;'>"+"   "+"</td>";
					str+="<td width='25%' style='font-size: 11px;'>"+context+"</td>";
					str+= "</tr>\n";		
					document.write(str);    				
			apc_display++;
		
	}   
}
function on_change_band(s)
{
	var f = get_obj("frm");
	self.location.href = "st_ap.php?band_reload=" + s.value;
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
									<?
											$count_list=0;	
											echo "<!--";
											echo "apc_num = ".$apc_num."\n";
																//aaaaaa
										while($count_list< $apc_num)
										{    
											$count_list++;
											echo $G_TAG_SCRIPT_START."page_table(".$count_list.");".$G_TAG_SCRIPT_END;  																		
											
										}
											echo "-->\n";	
										?>
										<tr height="25">
					<td width="20%"><?=$m_wireless_summary?></td>
									</tr>
								     <tr>
					<td colspan="2">
						<fieldset id="ap_list_fieldset" style="">
							<legend><?=$m_ap_list_title?></legend>
							<table  width="100%" border="0" align="center" <?=$G_TABLE_ATTR_CELL_ZERO?>>
								<tr>
									<td>
										<table width="100%" border="0" align="center" <?=$G_TABLE_ATTR_CELL_ZERO?>>
										<tr class="list_head" align="center">
												<td width="10%">
												<?=$m_channel?>
											</td>
											<td width="15%">
												<?=$m_ap_num?>
											</td>
											<td width="25%">
												<?=$m_mrssi?><?=$m_pp?>
											</td>
											<td width="25%">
												<?=$m_arssi?><?=$m_pp?>
											</td>
											<td width="25%">
												<?=$m_pri?>
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
										<div class="div_tab_scan">
										<table id="scan_tab" border="0"  width="100%" <?=$G_TABLE_ATTR_CELL_ZERO?>>
									<?
											$count_list=1;	
											echo "<!--";
											echo "apc_rssi_num = ".$apc_rssi_num."\n";
											echo "ACTION_DECTECT = ".$ACTION_DECTECT."\n";
											echo "-->\n";	
											echo $G_TAG_SCRIPT_START."display_chann();".$G_TAG_SCRIPT_END;  
											if($ACTION_DECTECT == "1")
											{
												while($count_list<= $apc_chann_num)
												{    
													
													echo $G_TAG_SCRIPT_START."page_table_summary(".$count_list.");".$G_TAG_SCRIPT_END;  																		
													$count_list++;
												}     
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
				<table id="scan_help" border="0"  width="100%" <?=$G_TABLE_ATTR_CELL_ZERO?>>
				<tr>
					<td align="center">
						<?=$a_st_ap_help?>
					</td>
			</tr>
			</table>
			</table>
<!-- ________________________________  Main Content End _______________________________ -->
		</td>
	</tr>
	
</table>
</form>
</body>
</html>

