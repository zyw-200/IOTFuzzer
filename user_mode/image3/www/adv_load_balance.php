<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_load_balance";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_load_balance";
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
	//anchor("/wlan/inf:2");
	anchor("/wlan/inf:1");
}
$cfg_ap_array_enable = query("/wlan/inf:1/aparray_enable");
$cfg_load_balance = query("/sys/loadbalance/enable");
if($cfg_load_balance == ""){$cfg_load_balance = 0;}
$cfg_thre = query("/sys/loadbalance/threshold");
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
	f.load_balance.value = "<?=$cfg_load_balance?>";
	on_change_load_balance();
	if("<?=$cfg_ap_array_enable?>" != 1)
	{
		fields_disabled(f,true);
	}
	AdjustHeight();
}

function on_change_load_balance()
{
	var f=get_obj("frm");
	if(f.load_balance.value == 1)
	{
		f.thre.disabled = false;
	}
	else
	{
		f.thre.disabled = true;
	}
}

function check()
{
	var f=get_obj("frm");
	if(f.thre.value == "")
	{
		alert("<?=$a_empty_thre?>");
		f.thre.focus();
		return false;
	}
	if(!is_digit(f.thre.value))
	{
		alert("<?=$a_invalid_thre?>");
		f.thre.select();
		return false;
	}
	if(!is_in_range(f.thre.value, 1, 128))
	{
		alert("<?=$a_invalid_thre?>");
		f.thre.select();
		return false;
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
			<td width="30%" id="td_left"><?=$m_enable_load_balance?></td> 
			<td><?=$G_TAG_SCRIPT_START?>genSelect("load_balance", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"], "on_change_load_balance();");<?=$G_TAG_SCRIPT_END?></td>
		</tr>
		<tr>
			<td width="30%" id="td_left"><?=$m_thre?></td>
			<td><input type="text" id="thre" name="thre" maxlength="3" size="10" value="<?=$cfg_thre?>"></td>
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
