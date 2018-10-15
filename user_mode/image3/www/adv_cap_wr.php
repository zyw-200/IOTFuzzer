<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_cap_wr";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_cap_wr";
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
$cfg_url_enable = query("/captival/url_state");
$cfg_url_addr = query("/captival/http_url");
$cfg_radius_ip = query("/captival/radius/auth_server_ip");
$cfg_radius_port = query("/captival/radius/auth_server_port");
$cfg_radius_sec = queryEnc("/captival/radius/auth_server_secret");
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
	var str="";
	str+="adv_cap_wr.xgi?random="+generate_random_str();
<?
$a=1;
$b=query("/runtime/b");
if($b==""){$b=0;}
echo "str+=exe_str(\"submit ST_CAPTIVAL_REFRESH;submit SLEEP 1\");\n";
if($a > $b)
{
	echo "self.location.href=str;\n";
	$b++;
}
else
{
	$b--;
}
set("/runtime/b",$b);
?>
	on_change_url();
	AdjustHeight();
}


function on_change_url()
{
	var f=get_obj("frm");
	if(f.url_enable.checked)
	{
		f.url_addr.disabled = false;
		f.url_enable.value = 1;
	}
	else
	{
		f.url_addr.disabled = true;
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
	if(!is_valid_ip3(f.radius_ip.value,0))
	{
		alert("<?=$a_invalid_ip?>");
		f.radius_ip.select();
		return false;
	}
	if(is_blank(f.radius_port.value))
	{
		alert("<?=$a_empty_port?>");
		f.radius_port.focus();
		return false;
	}
	if(!is_valid_port_str(f.radius_port.value))
	{
		alert("<?=$a_invalid_port?>");
		f.radius_port.select();
		return false;
	}
	if(is_blank(f.radius_sec.value))
	{
		alert("<?=$a_empty_sec?>");
		f.radius_sec.focus();
		return false;
	}
	if(strchk_unicode(f.radius_sec.value))
	{
		alert("<?=$a_invalid_sec?>");
		f.radius_sec.select();
		return false;
	}
	if(f.radius_sec.value.length<1 || f.radius_sec.value.length>64)
	{
		alert("<?=$a_invalid_secret_len?>");
		f.radius_sec.select();
		return false;
	}
	return true;
}

function do_refresh()
{
	self.location.href="<?=$MY_MSG_FILE?>";
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
			<td bgcolor="#cccccc" colspan="2" height="18"><b><?=$m_web_site_title?></b></td>
		</tr>
		<tr>
			<td width="30%" id="td_left"><?=$m_enable_url?></td>
			<td>
				<input type="checkbox" name="url_enable" value="<?=$cfg_url_enable?>" onclick="on_change_url()" <?if($cfg_url_enable == 1){echo "checked";}?>><?=$G_TAG_SCRIPT_END?>
			</td>
		</tr>
		<tr>
			<td width="30%" id="td_left"><?=$m_url_addr?></td> 
			<td><input type="text" name="url_addr" id="url_addr" maxlength="120" size="30" value="<?=$cfg_url_addr?>"></td>
		</tr>
		<tr>
			<td bgcolor="#cccccc" colspan="2" height="18"><b><?=$m_radius_server_title?></b></td>
		</tr>
		<tr height="25px">
			<td><?=$m_radius_server?></td>
			<td>
				&nbsp;<input type="text" class="text" id="radius_ip" name="radius_ip" value="<?=$cfg_radius_ip?>" size="16" maxlength="15" />
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$m_radius_port?>&nbsp;
				<input type="text" class="text" id="radius_port" name="radius_port" value="<?=$cfg_radius_port?>" maxlength="5" size="7" />
			</td>
		</tr>
		<tr height="25px">
			<td><?=$m_radius_secret?></td>
			<td>&nbsp;<input type="password" class="text" id="radius_sec" name="radius_sec" value="<?=$cfg_radius_sec?>" size="40" /></td>
		</tr>
		<tr>    
			<td bgcolor="#cccccc" colspan="2" height="18"><b><?=$m_users_title?></b></td>
		</tr>
		<tr>
			<td colspan="2" height="16" align="right">
				<input type="button" name="refresh" value="<?=$m_refresh?>" onClick="do_refresh();">
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">
					<tr class="list_head" align="center">
						<td width="20%" align="center"><?=$m_index?></td>
						<td width="40%" align="center"><?=$m_mac?></td>
						<td width="40%" align="center"><?=$m_ssid?></td>
					</tr>
				</table>
				<div class="div_tab">
				<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">
<?
$key = 0;
for("/runtime/captival/pass_table/index")
{
	$index = $key + 1;
	$mac = query("mac");
	$ssid = query("ssid");
	if($key%2==1)
	{
		echo "<tr style='background:#CCCCCC;'>\n";
	}
	else
	{
		echo "<tr style='background:#B3B3B3;'>\n";
	}
	echo "<td width='20%' align='center'>".$index."</td>\n";
	echo "<td width='40%' align='center'>".$mac."</td>\n";
	echo "<td width='40%' align='center'>".$ssid."</td>\n";
	echo "</tr>\n";
	$key++;
}
?>
				</table>
				</div>
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
