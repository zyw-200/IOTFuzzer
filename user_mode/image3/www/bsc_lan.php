<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "bsc_lan";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "bsc_lan";
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
if($lan_type_reload == 1 || $lan_type_reload == 2) // change lan type
{
	$cfg_lan_type = $lan_type_reload;
}
else // first init
{
	$cfg_lan_type = query("/wan/rg/inf:1/mode");
}

if($cfg_lan_type == 1)
{
	echo "anchor static\n";
	anchor("/wan/rg/inf:1/static");
}
else
{
	echo "anchor dhcp\n";
	anchor("/runtime/wan/inf:1");
}
$cfg_ipaddr = query("ip");
$cfg_ipmask = query("netmask");
$cfg_gw     = query("gateway");
if($cfg_lan_type == 1)
{
	$cfg_dns = query("/dnsrelay/server/primarydns");
}
else
{
	$cfg_dns = query("/runtime/wan/inf:1/primarydns");
}
$runtime_dns = query("/runtime/web/display/dns");

echo "lan_type_reload = ". $lan_type_reload ."\n";
echo "cfg_lan_type = ". $cfg_lan_type ."\n";
echo "cfg_ipaddr = ". $cfg_ipaddr ."\n";
echo "cfg_ipmask = ". $cfg_ipmask ."\n";
echo "cfg_gw = ". $cfg_gw ."\n";
echo "cfg_dns = ". $cfg_dns ."\n";
echo "-->";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>

function check_disable_items()
{
	var f = get_obj("frm");

	if(f.lantype.selectedIndex == 0) // static
	{
		f.ipaddr.disabled  = false;
		f.ipmask.disabled  = false;
		f.gateway.disabled = false;
		if("<?=$runtime_dns?>" == 1)
		{
			f.dns.disabled  = false;
		}
	}
	else // dhcp
	{
		f.ipaddr.disabled  = true;
		f.ipmask.disabled  = true;
		f.gateway.disabled = true;
		if("<?=$runtime_dns?>" == 1)
		{
			f.dns.disabled  = true;
		}
	}
}

function on_change_lan_type(s)
{
	self.location.href = "<?=$MY_NAME?>.php?lan_type_reload=" + s.value;
}

/* page init functoin */
function init()
{
	var f = get_obj("frm");
	// 1: static, 2: dhcp
	select_index(f.lantype, "<?=$cfg_lan_type?>");

	f.ipaddr.value  = "<?=$cfg_ipaddr?>";
	f.ipmask.value  = "<?=$cfg_ipmask?>";
	if("<?=$cfg_gw?>" == "0.0.0.0")
	{
		f.gateway.value = "";
	}
	else
	{
		f.gateway.value = "<?=$cfg_gw?>";
	}
	if("<?=$runtime_dns?>" == 1)
	{
		f.dns.value  = "<?=$cfg_dns?>";
	}
	check_disable_items();
	AdjustHeight();
}

/* parameter checking */
function check()
{
	var f = get_obj("frm");

	switch(f.lantype.value)
	{
		case "1":
			if(!is_valid_ip3(f.ipaddr.value, 0))
			{
				alert("<?=$a_invalid_ip?>");
				field_focus(f.ipaddr, "**");
				return false;
			}
			if (!is_valid_mask(f.ipmask.value))
			{
				alert("<?=$a_invalid_netmask?>");
				field_focus(f.ipmask, "**");
				return false;
			}
			if(invalid_ip_mask(f.ipaddr.value, f.ipmask.value)==false)
            {
                alert("<?=$a_ip_mask_not_match?>");
                field_focus(f.ipaddr, "**");
                return false;
            }
			if(!is_blank(f.gateway.value))
			{
				if (!is_valid_ip3(f.gateway.value, 0))
				{
					alert("<?=$a_invalid_gateway?>");
					field_focus(f.gateway, "**");
					return false;
				}
				if(invalid_ip_mask(f.gateway.value, f.ipmask.value)==false)
				{
					alert("<?=$a_invalid_gateway?>");
					field_focus(f.gateway, "**");
					return false;
				}
				if(f.gateway.value == f.ipaddr.value)
				{
					alert("<?=$a_ip_gateway?>");
					field_focus(f.gateway, "**");
					return false;
				}
			}
			if("<?=$runtime_dns?>" == 1)
			{
				if(!is_blank(f.dns.value))
				{
					if (!is_valid_ip(f.dns.value, 0))
					{
						alert("<?=$a_invalid_dns?>");
						field_focus(f.dns, "**");
						return false;
					}
					if(f.dns.value == f.ipaddr.value)
					{
						alert("<?=$a_ip_dns?>");
						field_focus(f.dns, "**");
						return false;
					}
				}
			}
			break;

		case "2": // dhcp
			break;

		default:
			return false;
	}
	return true;
}

function submit()
{
	if(check()) get_obj("frm").submit();
}

<?=$G_TAG_SCRIPT_END?>

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
					<td id="td_left" width="150">
						<?=$m_lan_type?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("lantype", [1,2], ["<?=$m_static_ip?>","<?=$m_dhcp?>"], "on_change_lan_type(this)");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_ipaddr?>
					</td>
					<td id="td_right">
						<input type="text" class="text" id="ipaddr" name="ipaddr" class="flatL" size="15" maxlength="15" value="" />
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_subnet?>
					</td>
					<td id="td_right">
						<input type="text" class="text" id="ipmask" name="ipmask" class="flatL" size="15" maxlength="15" value="" />
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_gateway?>
					</td>
					<td id="td_right">
						<input type="text" class="text" id="gateway" name="gateway" class="flatL" size="15" maxlength="15" value="" />
					</td>
				</tr>
<? if(query("/runtime/web/display/dns") != "1") {echo "<!--";}?>
				<tr>
					<td id="td_left">
						<?=$m_dns?>
					</td>
					<td id="td_right">
						<input type="text" class="text" id="dns" name="dns" class="flatL" size="15" maxlength="15" value="" />
					</td>
				</tr>
<? if(query("/runtime/web/display/dns") != "1") {echo "-->";}?>
			</table>
<?=$G_APPLY_BUTTON?>
<!-- ________________________________  Main Content End _______________________________ -->
		</td>
	</tr>
</table>

</form>
</body>
</html>
