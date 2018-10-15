<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "bsc_ipv6";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "bsc_ipv6";
set("/runtime/web/help_page",$MY_NAME);
/* --------------------------------------------------------------------------- */
if($ACTION_POST != "")
{
	require("/www/model/__admin_check.php");
	require("/www/__action_bsc.php");
	$ACTION_POST = "";
	exit;
}

/* --------------------------------------------------------------------------- */
require("/www/model/__html_head.php");
set("/runtime/web/next_page",$first_frame);
require("/www/comm/__js_ip.php");
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
echo "<!--debug\n";

$cfg_lan_type = query("/inet/entry:1/ipv6/mode");
anchor("/inet/entry:1/ipv6");
$cfg_enable = query("valid");
$cfg_ipaddr = query("ipaddr");
$cfg_prefix = query("prefix");
$cfg_gw     = query("gateway");
$cfg_dns     = query("dns");
$cfg_mode	= query("/wlan/inf:1/ap_mode");
$cfg_mode_a   = query("/wlan/inf:2/ap_mode");
anchor("/runtime/inet/entry:1/ipv6");
$runtime_ipaddr = query("ipaddr");
$runtime_prefix = query("prefix");
$runtime_gw     = query("gateway");
$runtime_dns     = query("dns");

echo "-->";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>
function on_click_ipv6_enable()
{
	var f = get_obj("frm");
	if (f.ipv6_enable.checked)
	{
		if("<?=$cfg_enable?>"!="1" && ("<?=$cfg_mode?>" == 1 || "<?=$cfg_mode_a?>" == 1))
		{
			alert("<?=$a_enable_ipv6?>");
		}
		f.ipv6_lantype.disabled=false;	
	}
	else
	{
		f.ipv6_lantype.disabled=true;	
	}	
	on_change_ipv6_lan_type();
}


function on_change_ipv6_lan_type()
{
	var f = get_obj("frm");
	if(f.ipv6_lantype.selectedIndex=="1")
	{
		f.ipaddr6.value  = "<?=$runtime_ipaddr?>";
		f.prefix.value  = "<?=$runtime_prefix?>";
//		f.dns6.value = "<?=$runtime_dns?>";
		f.gateway6.value = "<?=$runtime_gw?>";
		f.ipaddr6.disabled  = true;
		f.prefix.disabled  = true;
//		f.dns6.disabled = true;
		f.gateway6.disabled = true;
	}
	else
	{
		f.ipaddr6.value  = "<?=$cfg_ipaddr?>";
		f.prefix.value  = "<?=$cfg_prefix?>";
//		f.dns6.value = "<?=$cfg_dns?>";
		f.gateway6.value = "<?=$cfg_gw?>";
		if (f.ipv6_enable.checked)
		{
			f.ipaddr6.disabled  = false;
			f.prefix.disabled  = false;
//			f.dns6.disabled = false;
			f.gateway6.disabled = false;
		}
		else
		{
			f.ipaddr6.disabled  = true;
			f.prefix.disabled  = true;
//			f.dns6.disabled = true;
			f.gateway6.disabled = true;
		}
	}
}

/* page init functoin */
function init()
{
	var f = get_obj("frm");
	// 1: static, 2: dhcp
	if("<?=$cfg_enable?>"==1)
	{
		f.ipv6_enable.checked=true;
	}
	else
	{
		f.ipv6_enable.checked=false;
	}
	select_index(f.ipv6_lantype, "<?=$cfg_lan_type?>");

	on_click_ipv6_enable();
	AdjustHeight();
}

/* parameter checking */
function check()
{
	var f = get_obj("frm");
	if(f.ipv6_enable.checked)
	{
		switch(f.ipv6_lantype.value)
		{
		case "static":
			if (!is_valid_ipv6(f.ipaddr6.value, 0))
            {
                alert("<?=$a_invalid_ip?>");
                field_focus(f.ipaddr6, "**");
                return false;
            }
			if (!is_digit(f.prefix.value))
			{
				alert("<?=$a_invalid_netmask?>");
				field_focus(f.prefix, "**");
				return false;
			}
			if(f.prefix.value < 0 || f.prefix.value > 128)
			{
                alert("<?=$a_invalid_netmask?>");
                field_focus(f.prefix, "**");
                return false;
            }
/*			if(f.dns6.value != "")
			{
				if (!is_valid_ipv6(f.dns6.value, 0))
				{
				alert("<?=$a_invalid_dns?>");
				field_focus(f.dns6, "**");
				return false;
				}
				f.dns6.value = f.dns6.value.toLowerCase();
			}
*/
			if(f.gateway6.value != "")
			{
				if (!is_valid_ipv6(f.gateway6.value, 0) || f.gateway6.value.toLowerCase() == f.ipaddr6.value.toLowerCase())
				{
				alert("<?=$a_invalid_gateway?>");
				field_focus(f.gateway6, "**");
				return false;
				}
				f.gateway6.value = f.gateway6.value.toLowerCase();
			}
			f.ipaddr6.value = f.ipaddr6.value.toLowerCase();
			break;

		case "auto": // dhcp
			break;

		default:
			return false;
		}
		f.f_ipv6_enable.value=1;
	}
	else
	{
		f.f_ipv6_enable.value=0;
	}
	fields_disabled(f, false);
	return true;
}

function submit()
{
	if(check()) get_obj("frm").submit();
}

<?=$G_TAG_SCRIPT_END?>

<body <?=$G_BODY_ATTR?> onload="init();">

<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return false;">
<input type="hidden" name="f_ipv6_enable" value="">
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
					<td colspan="2">
						<?=$G_TAG_SCRIPT_START?>genCheckBox("ipv6_enable", "on_click_ipv6_enable()");<?=$G_TAG_SCRIPT_END?>
						<?=$m_enable_ipv6?>
					</td>
				</tr>
				<tr>
					<td id="td_left" width="150">
						<?=$m_lan_type?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("ipv6_lantype", ["static","auto"], ["<?=$m_static_ip?>","<?=$m_auto?>"], "on_change_ipv6_lan_type()");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_ipaddr?>
					</td>
					<td id="td_right">
						<input type="text" class="text" id="ipaddr6" name="ipaddr6" class="flatL" size="40" maxlength="39" value="" />
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_subnet?>
					</td>
					<td id="td_right">
						<input type="text" class="text" id="prefix" name="prefix" class="flatL" size="5" maxlength="3" value="" />
					</td>
				</tr>
				<!--tr>
					<td id="td_left">
						<?=$m_dns?>
					</td>
					<td id="td_right">
						<input type="text" class="text" id="dns6" name="dns6" class="flatL" size="40" maxlength="39" value="" />
					</td>
				</tr-->
				<tr>
					<td id="td_left">
						<?=$m_gateway?>
					</td>
					<td id="td_right">
						<input type="text" class="text" id="gateway6" name="gateway6" class="flatL" size="40" maxlength="39" value="" />
					</td>
				</tr>
			</table>
<?=$G_APPLY_BUTTON?>
<!-- ________________________________  Main Content End _______________________________ -->
		</td>
	</tr>
</table>

</form>
</body>
</html>
