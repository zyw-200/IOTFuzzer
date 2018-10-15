<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "bsc_capwap";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "bsc_capwap";
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
$cfg_capwap = query("/sys/wtp/capwap/enable");
$cfg_stp = query("/sys/wtp/capwap/stp");
$cfg_hw_ver = query("/sys/wtp/capwap/hwversion");
$cfg_company = query("/sys/wtp/capwap/company");
$cfg_sys_model = query("/sys/wtp/capwap/sysmodel");
$cfg_prefix = query("/sys/wtp/capwap/prefix");
$cfg_discover_mode = query("/sys/wtp/capwap/mode");
$cfg_domain_name = query("/sys/wtp/capwap/domainname");
$cfg_dns_ipv46 = query("/sys/wtp/capwap/dns_ipv46");
if($cfg_dns_ipv46 == ""){$cfg_dns_ipv46 = 0;}
$cfg_dns_addr_ipv4 = query("/sys/wtp/capwap/dnsaddr_v4");
$cfg_dns_addr_ipv6 = query("/sys/wtp/capwap/dnsaddr_v6");
$cfg_static_ipv46 = query("/sys/wtp/capwap/static_ipv46");
if($cfg_static_ipv46 == ""){$cfg_static_ipv46 = 0;}
$cfg_static_addr_ipv4 = query("/sys/wtp/capwap/staticaddr_v4");
$cfg_static_addr_ipv6 = query("/sys/wtp/capwap/staticaddr_v6");
echo "-->";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>

function init()
{
	var f=get_obj("frm");
	get_obj("dns_setting").style.display="none";
	get_obj("static_setting").style.display="none";
	change_discover_mode();
}

function change_discover_mode()
{
	var f=get_obj("frm");
	if(f.discover_mode.value == 4)
	{
		get_obj("dns_setting").style.display="";
		get_obj("static_setting").style.display="none";
		if("<?=$cfg_dns_ipv46?>" == 0)
		{
			f.dns_ipv46[0].checked = true;
		}
		else
		{
			f.dns_ipv46[1].checked = true;
		}	
		on_change_dns_ipv46();
	}
	else if(f.discover_mode.value == 5)
	{
		get_obj("static_setting").style.display="";
		get_obj("dns_setting").style.display="none";
		if("<?=$cfg_static_ipv46?>" == 0)
		{
			f.static_ipv46[0].checked = true;
		}
		else
		{
			f.static_ipv46[1].checked = true;
		}
		on_change_static_ipv46();
	}
	else
	{
		get_obj("static_setting").style.display="none";
		get_obj("dns_setting").style.display="none";
	}
}

function on_change_dns_ipv46()
{
	var f=get_obj("frm");
	if(f.dns_ipv46[0].checked)
	{
		f.dns_addr.value = "<?=$cfg_dns_addr_ipv4?>";
	}
	else
	{
		f.dns_addr.value = "<?=$cfg_dns_addr_ipv6?>";
	}
}

function on_change_static_ipv46()
{
	var f=get_obj("frm");
	if(f.static_ipv46[0].checked)
	{
		f.static_addr.value = "<?=$cfg_static_addr_ipv4?>";
	}
	else
	{
		f.static_addr.value = "<?=$cfg_static_addr_ipv6?>";
	}
}

function strchk_prefix(str)
{
	if (__is_str_in_allow_chars(str, 1, "._-")) return true;
	return false;
}

function do_refresh()
{
	return true;
}

function check()
{
	var f=get_obj("frm");
	if(f.capwap.checked)
	{
		f.capwap.value = 1;
	}
	else
	{
		f.capwap.value = 0;
	}
	if(strchk_hostname(f.hw_ver.value)==false)
	{
		alert("<?=$a_invalid_hw_ver?>");
		f.hw_ver.select();
		return false;
	}
	if(strchk_hostname(f.company.value)==false)
	{
		alert("<?=$a_invalid_company?>");
		f.company.select();
		return false;
	}
	if(strchk_hostname(f.sys_model.value)==false)
	{
		alert("<?=$a_invalid_sys_model?>");
		f.sys_model.select();
		return false;
	}
	if(strchk_prefix(f.prefix.value)==false)
	{
		alert("<?=$a_invalid_prefix?>");
		f.prefix.select();
		return false;
	}
	for(var k=1; k <= f.prefix.value.length; k++)
	{
		if(f.prefix.value.charAt(k) == '.' && f.prefix.value.charAt(k+1) == '.')
		{
			alert("<?=$a_invalid_prefix?>");
			f.prefix.select();
			return false;
		}
	}
	if(f.discover_mode.value == 4)
	{
			if(is_blank(f.domain_name.value))
			{
				alert("<?=$a_blank_name?>");
				f.domain_name.focus();
				return false;
			}
			if(first_blank(f.domain_name.value))
			{
				alert("<?=$a_first_blank_name?>");
				f.domain_name.select();
				return false;
			}
			if(strchk_unicode(f.domain_name.value))
			{
				alert("<?=$a_invalid_name?>");
				f.domain_name.select();
				return false;
			}
			if(f.dns_addr.value != "")
			{
				if(f.dns_ipv46[0].checked)
				{
					if(!is_valid_ip3(f.dns_addr.value, 0))	
					{
						alert("<?=$a_invalid_ip?>");
						field_focus(f.dns_addr, "**");
						return false;
					}
				}
				else
				{
					if(!is_valid_ipv6(f.dns_addr.value, 0))
					{
						alert("<?=$a_invalid_ip?>");
						field_focus(f.dns_addr, "**");
						return false;
					}
					f.dns_addr.value = f.dns_addr.value.toLowerCase();
				}
			}
	}
	else if(f.discover_mode.value == 5)
	{
		if(f.static_ipv46[0].checked)
		{
			if(!is_valid_ip3(f.static_addr.value, 0))
			{
				alert("<?=$a_invalid_ip?>");
				field_focus(f.static_addr, "**");
				return false;
			}
		}
		else
		{
			if(!is_valid_ipv6(f.static_addr.value, 0))
			{
				alert("<?=$a_invalid_ip?>");
				field_focus(f.static_addr, "**");
				return false;
			}
			f.static_addr.value = f.static_addr.value.toLowerCase();
		}
	}
	fields_disabled(f,false);
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
						<?=$m_enable_capwap?>
					</td>
					<td>
						<input type="checkbox" name="capwap" id="capwap" value="" <? if($cfg_capwap == 1) {echo "checked";}?>>
					</td>
				</tr>
				<tr>
					<td id="td_left" width="150">
						<?=$m_stp?>
					</td>
					<td>
						<select name="stp" id="stp">
							<option value="0" <? if($cfg_stp==0) {echo "selected";}?>><?=$m_disable?></option>
							<option value="1" <? if($cfg_stp==1) {echo "selected";}?>><?=$m_enable?></option>
					</td>
				</tr>
				<tr>
					<td id="td_left" width="150"><?=$m_hw_version?></td>
					<td><input type="text" name="hw_ver" id="hw_ver"  value="<?=$cfg_hw_ver?>"></td>
				</tr>
				<tr>
					<td id="td_left" width="150"><?=$m_company?></td>
					<td><input type="text" name="company" id="company" value="<?=$cfg_company?>"></td>
				</tr>
				<tr>
					<td id="td_left" width="150"><?=$m_sys_model?></td>
					<td><input type="text" name="sys_model" id="sys_model" value="<?=$cfg_sys_model?>"></td>
				</tr>
				<tr>
					<td id="td_left" width="150"><?=$m_prefix?></td>
					<td><input type="text" name="prefix" id="prefix" value="<?=$cfg_prefix?>"></td>
				</tr>
				<tr>
					<td>
						<?=$m_discover_mode?>
					</td>
					<td>
						<select name="discover_mode" id="discover_mode" onChange="change_discover_mode()">
							<option value="0" <? if($cfg_discover_mode == 0) {echo "selected";}?>><?=$m_auto?></option>
							<?
							//<option value="1" <? if($cfg_discover_mode == 1) {echo "selected";}?>><?=$m_dhcp_60?></option>
							//<option value="2" <? if($cfg_discover_mode == 2) {echo "selected";}?>><?=$m_dhcp_43?></option>
							//<!--option value="3" < ? if($cfg_discover_mode == 3) {echo "selected";}?>>< ?=$m_broadcast?></option-->
							//<option value="4" <? if($cfg_discover_mode == 4) {echo "selected";}?>><?=$m_dns?></option>
							?>
							<option value="5" <? if($cfg_discover_mode == 5) {echo "selected";}?>><?=$m_static?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<fieldset id="dns_setting" style="display:none">
						<legend><?=$m_dns_setting?></legend>
						<table width="100%">
							<tr>
								<td id="td_left" width="28%">
									<?=$m_domain_name?>
								</td>
								<td>
									<input type="text" name="domain_name" id="domain_name" value="<?=$cfg_domain_name?>">
								</td>
							</tr>
							<tr>
								<td id="td_left" width="28%"><?=$m_dns_addr?></td>
								<td>
									<input type="radio" id="dns_ipv46" name="dns_ipv46" value="0" onClick="on_change_dns_ipv46()"><?=$m_ipv4?>
									<input type="radio" id="dns_ipv46" name="dns_ipv46" value="1" onClick="on_change_dns_ipv46()"><?=$m_ipv6?>
								</td>
							</tr>
							<tr>
								<td id="td_left" width="28%"></td>
								<td>
									<input type="text" name="dns_addr" id="dns_addr" value="">
								</td>
							</tr>
						</table>
						</fieldset>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<fieldset id="static_setting" style="display:none">
						<legend><?=$m_static_setting?></legend>
						<table width="100%">
							<tr>
								<td id="td_left" width="28%">
									<?=$m_ip_addr?>
								</td>
								<td>
									<input type="radio" id="static_ipv46" name="static_ipv46" value="0" onClick="on_change_static_ipv46()"><?=$m_ipv4?>
									<input type="radio" id="static_ipv46" name="static_ipv46" value="1" onClick="on_change_static_ipv46()"><?=$m_ipv6?>
								</td>
							</tr>
							<tr>
								<td id="td_left" width="28%"></td>
								<td>
									<input type="text" name="static_addr" id="static_addr" value="">
								</td>
							</tr>
						</table>
						</fieldset>
					</td>
				</tr>
				<tr>
					<td><input type="button" name="refresh" id="refresh" value="<?=$m_refresh?>" onClick="do_refresh()"></td>
				</tr>
			</table>
<?=$G_APPLY_BUTTON?>
<!-- ________________________________  Main Content End _______________________________ -->
        </td>
    </tr>
</table>
