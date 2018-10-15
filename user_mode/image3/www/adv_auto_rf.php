<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_auto_rf";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_auto_rf";
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
$cfg_band = query("/wlan/ch_mode");
if($cfg_band == 0) // 11g
{
	echo "anchor 11g";
	anchor("/wlan/inf:1");
}
else
{
	echo "anchor 11a";
	anchor("/wlan/inf:1");
}
$cfg_ap_array_enable = query("aparray_enable");
$cfg_role = query("arrayrole_original");
anchor("/sys/autorf");
$cfg_auto_rf = query("enable");
if($cfg_auto_rf == ""){$cfg_auto_rf = 0;}
$cfg_init_auto = query("init/auto");
if($cfg_init_auto == ""){$cfg_init_auto = 0;}
$cfg_init_period = query("init/period");
if($cfg_init_period == ""){$cfg_init_period = 24;}
$cfg_miss = query("server/thresold/miss");
if($cfg_miss == ""){$cfg_miss = 20;}
$cfg_rssi = query("server/thresold/rssi");
if($cfg_rssi == ""){$cfg_rssi = 40;}
$cfg_freq = query("/sys/autorf/client/time/loop");
echo "-->";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>
/* page init functoin */
function init()
{
	var f=get_obj("frm");
	f.auto_rf.value = "<?=$cfg_auto_rf?>";
	f.init_auto.value = "<?=$cfg_init_auto?>";
	f.rssi.value = "<?=$cfg_rssi?>";
	on_change_auto_rf();
	if("<?=$cfg_ap_array_enable?>" != 1)
	{
		fields_disabled(f,true);
	}
	AdjustHeight();
}

function on_change_auto_rf()
{
	var f=get_obj("frm");
	if(f.auto_rf.value != 1 || ("<?=$cfg_ap_array_enable?>" == 1 && "<?=$cfg_role?>" == 3))
	{
		fields_disabled(f,true);
		f.auto_rf.disabled = false;
	}
	else
	{
		fields_disabled(f,false);
		on_change_init_auto();	
	}
}

function do_init()
{
	var f=get_obj("frm");
	f.f_action.value = 1;
	f.submit();
}

function on_change_init_auto()
{
	var f=get_obj("frm");
	if(f.init_auto.value == 1)
	{
		f.init_period.disabled = false;
	}
	else
	{
		f.init_period.disabled = true;
	}
}

function check()
{
	var f=get_obj("frm");
	if(f.auto_rf.value == 1)
	{
		if(f.init_auto.value == 1)
		{
			if(f.init_period.value == "")
			{
				alert("<?=$a_empty_period?>");
				f.init_period.focus();
				return false;
			}
			if(!is_digit(f.init_period.value))
			{
				alert("<?=$a_invalid_period?>");
				f.init_period.select();
				return false;
			}
			if(!is_in_range(f.init_period.value, 1, 24))
			{
				alert("<?=$a_invalid_period?>");
				f.init_period.select();
				return false;
			}
		}
/*		if(f.miss.value == "")
		{
			alert("<?=$a_empty_miss?>");
			f.miss.focus();
			return false;
		}
		if(!is_digit(f.miss.value))
		{
			alert("<?=$a_invalid_miss?>");
			f.miss.select();
			return false;
		}
		if(!is_in_range(f.miss.value, 0, 20))
		{
			alert("<?=$a_invalid_miss?>");
			f.miss.select();
			return false;
		}*/
		if(f.freq.value == "")
		{
			alert("<?=$a_empty_freq?>");
			f.freq.focus();
			return false;
		}
		if(!is_digit(f.freq.value))
		{
			alert("<?=$a_invalid_freq?>");
			f.freq.select();
			return false;
		}
		if(!is_in_range(f.freq.value, 5, 300))
		{
			alert("<?=$a_invalid_freq?>");
			f.freq.select();
			return false;
		}
	}
	return true;
}

function submit()
{
	var f=get_obj("frm");
	if(check() == true)
	{
		fields_disabled(f,false);
		f.submit();
		return true;
	}
}
</script>

<body <?=$G_BODY_ATTR?> onload="init();">

<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return false;">
<input type="hidden" name="f_action" value="">
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
  			<table id="table_set_main" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
		<tr>
			<td width="30%" id="td_left"><?=$m_enable_auto_rf?></td> 
			<td><?=$G_TAG_SCRIPT_START?>genSelect("auto_rf", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"], "on_change_auto_rf();");<?=$G_TAG_SCRIPT_END?>
			</td>
		</tr>
		<tr>
			<td width="30%" id="td_left"><?=$m_init_auto_rf?></td>
			<td><input type="button" id="init" name="init" value="<?=$m_optimize?>" onclick="do_init();"></td>
		</tr>
		<tr>
			<td><?=$m_init_auto?></td>
			<td><?=$G_TAG_SCRIPT_START?>genSelect("init_auto", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"], "on_change_init_auto();");<?=$G_TAG_SCRIPT_END?></td>
		</tr>
		<tr>
			<td>&nbsp;&nbsp;&nbsp;<?=$m_init_period?></td>
			<td><input type="text" id="init_period" name="init_period" size="7" maxlength="2" value="<?=$cfg_init_period?>"><?=$m_hour?></td>
		</tr>
		<tr>
			<td><?=$m_rssi?></td>
			<td><?=$G_TAG_SCRIPT_START?>genSelect("rssi", [10,20,30,40,50,60,70,80,90,100], ["<?=$m_10?>%","<?=$m_20?>%","<?=$m_30?>%","<?=$m_40?>%","<?=$m_50?>%","<?=$m_60?>%","<?=$m_70?>%","<?=$m_80?>%","<?=$m_90?>%","<?=$m_100?>%"], " ");<?=$G_TAG_SCRIPT_END?></td>
		</tr>
		<tr>
			<td><?=$m_freq?></td>
			<td><input type="text" id="freq" name="freq" size="7" maxlength="5" value="<?=$cfg_freq?>"><?=$m_second?></td>
		</tr>
		<!--tr>
			<td><?=$m_miss?></td>
			<td><input type="text" id="miss" name="miss" size="7" maxlength="2" value="<?=$cfg_miss?>"></td>
		</tr-->
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
