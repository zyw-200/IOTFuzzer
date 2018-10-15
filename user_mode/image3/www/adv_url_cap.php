<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_url_cap";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_url_cap";
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
$cfg_url_enable = query("/captival/url_state");
$cfg_url_addr = query("/captival/http_url");
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
	on_change_url();
	var tmp_url = "<?=$cfg_url_addr?>";
	if(tmp_url.substring(0,5) == "https")
	{
		f.url_addr.value = tmp_url.substring(8);
		f.site_begin.value = 2;
	}
	else
	{
		f.url_addr.value = tmp_url.substring(7);
		f.site_begin.value = 1;
	}
	AdjustHeight();
}


function on_change_url()
{
	var f=get_obj("frm");
	if(f.url_enable.checked)
	{
		f.url_addr.disabled = f.site_begin.disabled = false;
		f.url_enable.value = 1;
	}
	else
	{
		f.url_addr.disabled = f.site_begin.disabled = true;
		f.url_enable.value = 0;
	}
}


function check()
{
	var f=get_obj("frm");
	if(f.url_enable.checked)
	{
		if(f.url_addr.value == "")
		{
			alert("<?=$a_empty_url_addr?>");
			f.url_addr.focus();
			return false;
		}
		if(strchk_url(f.url_addr.value)==false)
		{
			alert("<?=$a_invalid_url_addr?>");
			f.url_addr.select();
			return false;
		}
		for(var k=0; k < f.url_addr.value.length; k++)
		{
			if(f.url_addr.value.charAt(k) == '.' && f.url_addr.value.charAt(k+1) == '.')
			{
				alert("<?=$a_invalid_url_addr?>");
				f.url_addr.select();
				return false;
			}
		}
		if(f.url_addr.value.substring(0,4) == "http")
		{
			alert("<?=$a_invalid_http?>");
			f.url_addr.select();
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
			<td width="30%" id="td_left"><?=$m_enable_url?></td>
			<td>
				<input type="checkbox" name="url_enable" value="<?=$cfg_url_enable?>" onclick="on_change_url()" <?if($cfg_url_enable == 1){echo "checked";}?>><?=$G_TAG_SCRIPT_END?>
			</td>
		</tr>
		<tr>
			<td width="30%" id="td_left"><?=$m_url_addr?></td> 
			<td>
				<select id="site_begin" name="site_begin">
					<option value="1"><?=$m_http?></option>
					<option value="2"><?=$m_https?></option>
				</select>
				<input type="text" name="url_addr" id="url_addr" maxlength="120" size="30" value="">
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
