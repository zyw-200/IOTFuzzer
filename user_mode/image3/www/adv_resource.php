<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_resource";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_resource";
set("/runtime/web/help_page",$MY_NAME);
/* --------------------------------------------------------------------------- */
if($ACTION_POST != "")
{
	require("/www/model/__admin_check.php");
	require("/www/__action_adv.php");
	$ACTION_POST = "";
	//exit;
}

/* --------------------------------------------------------------------------- */
require("/www/model/__html_head.php");
set("/runtime/web/next_page",$first_frame);
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
echo "<!--debug\n";
$check_band = query("/wlan/inf:2/ap_mode");
if($band_reload == 0 || $band_reload == 1) // change band
{
    echo "reload <br>\n";
    $cfg_band = $band_reload;
}
else
{
    $cfg_band = query("/wlan/ch_mode");
}
echo $cfg_band;
if($cfg_band == 0) // 11g
{
    echo "anchor 11g";
    anchor("/wlan/inf:1");
}
else
{
    echo "anchor 11a";
    anchor("/wlan/inf:2");
}

$switch = query("/runtime/web/display/switchable");
if($switch == 1)
{
	anchor("/wlan/inf:1");
}

$cfg_ap_mode = query("ap_mode");
$cfg_limit_state = query("assoc_limit/enable");
$cfg_limit_num = query("assoc_limit/number");
$cfg_utilization = query("wlan_bytes_lim");
$cfg_aging_out =query("agbyrssiordrstatus");
$cfg_aging_rssi =query("agingbyrssithreshhold");
$cfg_aging_dr =query("agingbydrthreshhold");
$cfg_aclrssi_enable =query("aclbyrssi");
$cfg_aclrssi_thre =query("aclbyrssithreshhold");
$cfg_11n_enable =query("aclallbywlmode");
$cfg_5g_enable =query("/sys/wlan/preferred5g");
$cfg_5g_age =query("/sys/wlan/pre5g_age");
$cfg_5g_diff =query("/sys/wlan/pre5g_diff");
$cfg_5g_refuse =query("/sys/wlan/pre5g_refuse");
echo "-->";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>

function on_change_band(s)
{
    var f = get_obj("frm");
    self.location.href = "adv_resource.php?band_reload=" + s.value;
}

function on_change_user_limit(s)
{
	//alert(s.value);
	var f = get_obj("frm");
	f.limit_num.disabled = false;
	if(f.preferred_11n != null)
	{
		f.preferred_11n.disabled = false;
	}
	if(f.utilization != null)
	{
		f.utilization.disabled =false;
	}
	if(s.value == 0)
	{
		f.limit_num.disabled = true;
		if(f.preferred_11n != null)
		{
			f.preferred_11n.disabled = true;
		}
		if(f.utilization != null)
		{
			f.utilization.disabled =true;
		}
	}
}


/* page init functoin */
function init()
{
	var f = get_obj("frm");

	select_index(f.band, "<?=$cfg_band?>");
	select_index(f.limit_state, "<?=$cfg_limit_state?>");
	if("<?=$cfg_ap_mode?>" !="0") //not access point
	{
		select_index(f.limit_state, "0");
		f.limit_state.disabled = true;		
	}
	
	on_change_user_limit(f.limit_state);
	f.limit_num.value = "<?=$cfg_limit_num?>";
	if(f.utilization != null)
    {
		select_index(f.utilization, "<?=$cfg_utilization?>");
    }	
	if(f.preferred_5g != null)
	{
		select_index(f.preferred_5g,"<?=$cfg_5g_enable?>");
		f.preferred_age_5g.value="<?=$cfg_5g_age?>";
		f.preferred_diff_5g.value="<?=$cfg_5g_diff?>";
		f.preferred_refuse_5g.value="<?=$cfg_5g_refuse?>";
		if("<?=$cfg_band?>" == 0)
		{
			f.preferred_5g.disabled = f.preferred_age_5g.disabled = f.preferred_diff_5g.disabled = 
			f.preferred_refuse_5g.disabled = true;
		}
		else
		{
			f.preferred_5g.disabled = false;
			on_change_preferred_5g();
		}
	}
	if(f.aging_out != null)
	{
		if("<?=$cfg_band?>"== 1 && "<?=$cfg_ap_mode?>" == 1 )
		{
			select_index(f.aging_out,"0");
			f.aging_out.disabled = true;
			select_index(f.acl_rssi,"0");
			f.acl_rssi.disabled = true;
		}
		else
		{
			select_index(f.aging_out,"<?=$cfg_aging_out?>");
			select_index(f.acl_rssi,"<?=$cfg_aclrssi_enable?>");
		}
		select_index(f.rssi_threshod,"<?=$cfg_aging_rssi?>");
		select_index(f.Date_threshod,"<?=$cfg_aging_dr?>");
		select_index(f.acl_rssi_thre,"<?=$cfg_aclrssi_thre?>");
		select_index(f.preferred_11n,"<?=$cfg_11n_enable?>");
		on_change_aging_out();
		on_change_acl_rssi();
	}
	if("<?=$cfg_ap_mode?>" == 1)
	{
		fields_disabled(f, true);
		f.band.disabled = false;
	}
	if("<?=$check_band?>" == "")
		f.band.disabled = true;
	AdjustHeight();
}

function on_change_aging_out()
{
	var f=get_obj("frm");
	if(f.aging_out.value == 0)
	{
		f.rssi_threshod.disabled = true;
		f.Date_threshod.disabled = true;
	}
	else if(f.aging_out.value == 1)
	{
		f.rssi_threshod.disabled = false;
		f.Date_threshod.disabled = true;
	}
	else if(f.aging_out.value==2)
	{
		f.Date_threshod.disabled = false;
		f.rssi_threshod.disabled = true;
	}
}
function on_change_preferred_5g()
{
	var f=get_obj("frm");
	if(f.preferred_5g.value==0)
	{
		f.preferred_age_5g.disabled=true;
		f.preferred_diff_5g.disabled=true;
		f.preferred_refuse_5g.disabled=true;
	}
	else
	{
		f.preferred_age_5g.disabled=false;
		f.preferred_diff_5g.disabled=false;
		f.preferred_refuse_5g.disabled=false;
	}
}

function on_change_utilization(s)
{
	//alert(s.value);
}

function on_change_acl_rssi()
{
	var f=get_obj("frm");
	if(f.acl_rssi.value == 0)
	{
		f.acl_rssi_thre.disabled=true;
	}
	else
	{
		f.acl_rssi_thre.disabled=false;
	}
}
/* parameter checking */
function check()
{
	var f=get_obj("frm");
	
	if("<?=$cfg_ap_mode?>" =="0") //access point
	{		
		if(f.limit_state.value == 1)
		{		
			if(!is_in_range(f.limit_num.value,0,64))
			{
				alert("<?=$a_invalid_limit_num?>");
				if(f.limit_num.value=="") f.limit_num.value=64;
				field_select(f.limit_num);
				return false;
			}	
		}
	}
	if(f.preferred_5g != null)
	{
		if(f.preferred_5g.value == 1)
		{
			if(!is_digit(f.preferred_age_5g.value))
			{
				alert("<?=$a_invalid_preferred_5g_age?>");
				f.preferred_age_5g.select();
				return false;
			}
			if(!is_in_range(f.preferred_age_5g.value,0,600))
			{
				alert("<?=$a_preferred_5g_age_range?>");
				f.preferred_age_5g.select();
				return false;
			}
			if(!is_digit(f.preferred_diff_5g.value))
			{
				alert("<?=$a_invalid_preferred_5g_diff?>");
				f.preferred_diff_5g.select();
				return false;
			}
			if(!is_in_range(f.preferred_diff_5g.value,0,32))
			{
				alert("<?=$a_preferred_5g_diff_range?>");
				f.preferred_diff_5g.select();
				return false;
			}
			if(!is_digit(f.preferred_refuse_5g.value))
			{
				alert("<?=$a_invalid_preferred_5g_refuse?>");
				f.preferred_refuse_5g.select();
				return false;
			}
			if(!is_in_range(f.preferred_refuse_5g.value,0,10))
			{
				alert("<?=$a_preferred_5g_refuse_range?>");
				f.preferred_refuse_5g.select();
				return false;
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
			<table id="table_set_main"  border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
				<tr>
					<td width="35%" id="td_left">
                        <?=$m_band?>
                    </td>
                    <td id="td_right">
                        <?=$G_TAG_SCRIPT_START?>genSelect("band", [0,1], ["<?=$m_band_2.4G?>","<?=$m_band_5G?>"], "on_change_band(this)");<?=$G_TAG_SCRIPT_END?>
                    </td>	
				</tr>
<?if($check_band == "") {echo "<!--";}?>
				<tr>
					<td id="td_left"><?=$m_5g_preferred?></td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("preferred_5g", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"],"on_change_preferred_5g()");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
				<tr>
					<td id="td_left">&nbsp;&nbsp;&nbsp;&nbsp;<?=$m_5g_preferred_age?></td>
					<td id="td_right">
						<input name="preferred_age_5g" id="preferred_age_5g" class="text" type="text" size="10" maxlength="3" value="">(s)</td>
				</tr>
					<tr>
					<td id="td_left">&nbsp;&nbsp;&nbsp;&nbsp;<?=$m_5g_preferred_diff?></td>
					<td id="td_right">
						<input name="preferred_diff_5g" id="preferred_diff_5g" class="text" type="text" size="10" maxlength="2" value="">
					</td>
				</tr>
				<tr>
					<td id="td_left">&nbsp;&nbsp;&nbsp;&nbsp;<?=$m_5g_preferred_refuse?></td>
					<td id="td_right">
						<input name="preferred_refuse_5g" id="preferred_refuse_5g" class="text" type="text" size="10" maxlength="2" value="">
					</td>
				</tr>
<?if($check_band == "") {echo "-->";}?>
				<tr>
					<td id="td_left">
						<?=$m_limit_state?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("limit_state", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"], "on_change_user_limit(this)");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>	
				<tr>
					<td id="td_left">
						&nbsp;&nbsp;&nbsp;&nbsp;
						<?=$m_limit_num?>
					</td>
					<td id="td_right">
						<input name="limit_num" id="limit_num" class="text" type="text" size="10" maxlength="2" value="">
					</td>
				</tr>	
<? if(query("/runtime/web/display/agingout")!="1") {echo "<!--";}?>
				<tr>
					<td>&nbsp;&nbsp;&nbsp;&nbsp;<?=$m_11n_preferred?></td>
					<td>
						<?=$G_TAG_SCRIPT_START?>genSelect("preferred_11n", [0,1], ["<?=$m_disable?>","<?=$m_enable?>" ]," ");<?=$G_TAG_SCRIPT_END?>
					</td>
					</tr>
<? if(query("/runtime/web/display/agingout")!="1") {echo "-->";}?>
<? if(query("/runtime/web/display/utilization") != "1")	{echo "<!--";} ?>																		
				<tr>
					<td id="td_left">
						&nbsp;&nbsp;&nbsp;&nbsp;
						<?=$m_utilization?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("utilization", [1,2,3,4,5,6], ["<?=$m_100?>%","<?=$m_80?>%","<?=$m_60?>%","<?=$m_40?>%","<?=$m_20?>%","<?=$m_0?>%"], "on_change_utilization(this)");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>					
<? if(query("/runtime/web/display/utilization") != "1") {echo "-->";} ?>				
<? if(query("/runtime/web/display/agingout")!="1") {echo "<!--";}?>
				<tr>
					<td id="td_left"><?=$m_aging?></td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("aging_out", [0,1,2], ["<?=$m_disable?>","<?=$m_rssi?>","<?=$m_date_rate?>"], "on_change_aging_out()");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
				<tr>
					<td id="td_left">&nbsp;&nbsp;&nbsp;&nbsp;<?=$m_aging_rssi?></td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("rssi_threshod", [10,20,30,40,50,60,70,80,90,100], ["<?=$m_10?>%","<?=$m_20?>%","<?=$m_30?>%","<?=$m_40?>%","<?=$m_50?>%","<?=$m_60?>%","<?=$m_70?>%","<?=$m_80?>%","<?=$m_90?>%","<?=$m_100?>%"], " ");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
				<tr>
					<td id="td_left">&nbsp;&nbsp;&nbsp;&nbsp;<?=$m_aging_date_rate?></td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("Date_threshod", [6,9,12,18,24,36,48,54], ["<?=$m_6?>","<?=$m_9?>","<?=$m_12?>","<?=$m_18?>","<?=$m_24?>","<?=$m_36?>","<?=$m_48?>","<?=$m_54?>"]," ");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
				<tr>
					<td id="td_left"><?=$m_acl_rssi?></td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("acl_rssi", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"],"on_change_acl_rssi()");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
				<tr>
					<td id="td_left">&nbsp;&nbsp;&nbsp;&nbsp;<?=$m_acl_rssi_thre?></td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("acl_rssi_thre", [10,20,30,40,50,60,70,80,90,100], ["<?=$m_10?>%","<?=$m_20?>%","<?=$m_30?>%","<?=$m_40?>%","<?=$m_50?>%","<?=$m_60?>%","<?=$m_70?>%","<?=$m_80?>%","<?=$m_90?>%","<?=$m_100?>%"], " ");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
<? if(query("/runtime/web/display/agingout")!="1") {echo "-->";}?>
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
				
