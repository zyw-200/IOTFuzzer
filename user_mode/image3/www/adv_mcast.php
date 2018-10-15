<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_mcast";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_mcast";
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
$cfg_mode = query("ap_mode");
$cfg_band		= query("/wlan/ch_mode");
$cfg_mcast_a = query("mcastrate_a");
$cfg_mcast_g = query("mcastrate_g");
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
	select_index(f.band, "<?=$cfg_band?>");
	f.band.disabled = true;
    if("<?=$cfg_mcast_a?>"=="0" &&"<?=$cfg_mcast_g?>"=="0")
    {
    	f.mcast_enabled.value=0;
    	f.mcast_a.disabled=true;	
    	f.mcast_g.disabled=true;
    }
    else
    {
    	f.mcast_enabled.value=1;
    	select_index(f.mcast_a, "<?=$cfg_mcast_a?>");
    	select_index(f.mcast_g, "<?=$cfg_mcast_g?>");
    }
}

function on_change_mcast_enabled()
{
	var f=get_obj("frm");
	if(f.mcast_enabled.value==0)
	{
		f.mcast_a.disabled=true;	
    	f.mcast_g.disabled=true;
	}
	else
	{
		f.mcast_a.disabled=false;	
    	f.mcast_g.disabled=false;	
	}
}
/* parameter checking */
function check()
{
	var f=get_obj("frm");
	fields_disabled(f, false);
	if(f.mcast_enabled.value==0)
	{
		f.f_mcast_a.value=0;
		f.f_mcast_g.value=0;
	}
	else
	{
		f.f_mcast_a.value=f.mcast_a.value;
		f.f_mcast_g.value=f.mcast_g.value;
	}
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
<input type="hidden" name="f_mcast_a" value="">
<input type="hidden" name="f_mcast_g" value="">
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
					<td width="35%" id="td_left">
						<?=$m_band?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("band", [0,1], ["<?=$m_band_2.4G?>","<?=$m_band_5G?>"], "");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_multicast_rate?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("mcast_enabled", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"], "on_change_mcast_enabled()");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_mcast_a?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("mcast_a", [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20], ["6","9","5.12","18","24","36","48","54","6.5","13","19.5","26","39","52","58.5","65","78","104","117","130"], "");<?=$G_TAG_SCRIPT_END?><?=$m_mbps?>
					</td>
				</tr>
				<tr>
					<td width="35%" id="td_left">
						<?=$m_mcast_g?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("mcast_g", [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24], ["1","2","5.5","11","6","9","12","18","24","36","48","54","6.5","13","19.5","26","39","52","58.5","65","78","104","117","130"], "");<?=$G_TAG_SCRIPT_END?><?=$m_mbps?>
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
