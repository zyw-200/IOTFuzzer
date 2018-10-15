<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_radiusclient";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_radiusclient";
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
$cfg_radiusclient_enable=query("radiusclient_enable"); 
$cfg_radius_server_ip=query("radius_ip");
$cfg_radius_server_pwd=queryEnc("radius_password");
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
	select_index(f.radiusclient, "<?=$cfg_radiusclient_enable?>");
	f.radius_srv.value="<?=$cfg_radius_server_ip?>";
	f.radius_sec.value="<?=$cfg_radius_server_pwd?>";
	on_change_radiusclient();
}

function on_change_radiusclient()
{
	var f=get_obj("frm");
	if(f.radiusclient.value!=1)
	{
		f.radius_srv.disabled=true;
		f.radius_sec.disabled=true;
	}
	else
	{
		f.radius_srv.disabled=false;
		f.radius_sec.disabled=false;
	}
}
/* parameter checking */
function check()
{
	var f=get_obj("frm");
	if(f.radiusclient.value!=0)
	{
		if(!is_valid_ip3(f.radius_srv.value,0))
		{
			alert("<?=$a_invalid_radius_srv?>");
			f.radius_srv.select();
			return false;
		}

		if(is_blank(f.radius_sec.value))
		{
			alert("<?=$a_empty_radius_sec?>");
			f.radius_sec.focus();
			return false;
		}
		if(strchk_unicode(f.radius_sec.value))
		{
			alert("<?=$a_invalid_radius_sec?>");
			f.radius_sec.select();
			return false;
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
			<table id="table_set_main"  border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
				<tr>
					<td width="35%" id="td_left">
						<?=$m_radiusclient?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("radiusclient", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"], "on_change_radiusclient()");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_radius_server?>
					</td>
					<td id="td_right">
						<input type="text" class="text" id="radius_srv" name="radius_srv" value="" size="16" />
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_radius_secret?>
					</td>
					<td id="td_right">
						<input type="password" class="text" id="radius_sec" name="radius_sec" value="" size="40" />
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
