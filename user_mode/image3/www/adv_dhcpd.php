<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_dhcpd";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_dhcpd";
set("/runtime/web/help_page",$MY_NAME);
/* --------------------------------------------------------------------------- */
if($ACTION_POST != "")
{
	require("/www/model/__admin_check.php");
	require("/www/__action_dhcp_server.php");
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
$cfg_lan_type = query("/wan/rg/inf:1/mode");
if($cfg_lan_type == 1)
{
	$cfg_lan_ip = query("/wan/rg/inf:1/static/ip");
}
else
{
	$cfg_lan_ip = query("/runtime/wan/inf:1/ip");
}
$cfg_srv_enable = query("/lan/dhcp/server/enable");
$cfg_ipaddr_s = query("/lan/dhcp/server/pool:1/startip");
$cfg_ipaddr_e = query("/lan/dhcp/server/pool:1/endip");
$cfg_ipmask = query("/lan/dhcp/server/pool:1/netmask");
$cfg_gateway = query("/lan/dhcp/server/pool:1/gateway");
$cfg_wins = query("/lan/dhcp/server/pool:1/primarywins");
$cfg_dns = query("/lan/dhcp/server/pool:1/dns1");
$cfg_domain = get("j","/lan/dhcp/server/pool:1/domain");
$cfg_leasetime = query("/lan/dhcp/server/pool:1/leasetime");
$cfg_whosetdomain = query("/lan/dhcp/server/pool:1/whosetdomain");
$lanipaddr	= query("/runtime/wan/inf:1/ip");
echo "-->\n";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>
function on_change_srv_enable(s)
{
	var f = get_obj("frm");
	
	if(s.value == 1)
	{
		fields_disabled(f, false);
		if("<?=$cfg_whosetdomain?>"=="2")
		{
			f.domain.disabled=true;
		}
	}
	else
	{
		fields_disabled(f, true);
		s.disabled = false;
	}
}
/* page init functoin */
function init()
{
	var f = get_obj("frm");
	var ip_s, ip_e;

	select_index(f.srv_enable, "<?=$cfg_srv_enable?>");
	f.ipaddr.value = "<?=$cfg_ipaddr_s?>";
	ip_s = get_ip("<?=$cfg_ipaddr_s?>");
	ip_e = get_ip("<?=$cfg_ipaddr_e?>");
	f.iprange.value = (parseInt(ip_e[4], [10]) - parseInt(ip_s[4], [10]))+1;
	f.ipmask.value = "<?=$cfg_ipmask?>";
	if("<?=$cfg_gateway?>" == "0.0.0.0")
    {
        f.gateway.value = "";
    }
    else
    {
        f.gateway.value = "<?=$cfg_gateway?>";
    }
    if("<?=$cfg_wins?>" == "0.0.0.0")
    {
        f.wins.value = "";
    }
    else
    {
        f.wins.value = "<?=$cfg_wins?>";
    }
    if("<?=$cfg_dns?>" == "0.0.0.0")
    {
        f.dns.value = "";
    }
    else
    {
        f.dns.value = "<?=$cfg_dns?>";
    }
	f.domain.value = "<?=$cfg_domain?>";
	f.leasetime.value = "<?=$cfg_leasetime?>";
	
	on_change_srv_enable(f.srv_enable);
	if("<?=$cfg_whosetdomain?>"=="2")
	{
		f.domain.disabled=true;
	}
	AdjustHeight();
}


/* parameter checking */
function check()
{
	var f = get_obj("frm");
	var ip, end_ip, wins_ip, net1, net2,lanip;
	
	if(f.srv_enable.value == 1)
	{		
		if (is_valid_ip3(f.ipaddr.value, 0)==false)
		{
			alert("<?=$a_invalid_ip?>");
			field_focus(f.ipaddr, "**");
			return false;
		}
	
		if( isNaN(f.iprange.value) || f.iprange.value < 1 || f.iprange.value > 255)
		{
			alert("<?=$a_invalid_ip_range?>");
			f.iprange.value="";
			f.iprange.focus();
			return false;
		}	
		
		ip = get_ip(f.ipaddr.value);
		lanip = get_ip("<?=$lanipaddr?>");
		end_ip = parseInt(ip[4], [10]) + parseInt((f.iprange.value-1), [10]);
		
		if( end_ip < 1 || end_ip > 254)
		{
			alert("<?=$a_invalid_ip_range?>");
			f.iprange.value="";
			f.iprange.focus();
			return false;
		}		
		f.f_endip.value = ip[1]+"."+ip[2]+"."+ip[3]+"."+end_ip;
		/*if(ip[1]==lanip[1] && ip[2]==lanip[2] && ip[3]==lanip[3] )
		{
			if(parseInt(lanip[4], [10])>=parseInt(ip[4], [10]) && parseInt(lanip[4], [10])<= end_ip)
			{
				alert("<?=$a_invalid_ip_range?>");
				f.iprange.value="";
				f.iprange.focus();
				return false;
			}
		}*/		
		if (is_valid_mask(f.ipmask.value)==false)
		{
			alert("<?=$a_invalid_netmask?>");
			field_focus(f.ipmask, "**");
			return false;
		}
		if(invalid_ip_mask(f.ipaddr.value, f.ipmask.value)==false)
		{
			alert("<?=$a_invalid_ip?>");
			field_focus(f.ipaddr, "**");
			return false;
		}

		if(f.gateway.value != "")
		{
			if (is_valid_ip3(f.gateway.value, 0)==false)
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
			
		}	
		
		wins_ip = get_ip(f.wins.value);
		
		if(f.wins.value !="")
		{
			if (wins_ip[1]=="0" && wins_ip[2]=="0" && wins_ip[3]=="0" && wins_ip[4]=="0")
			{
				
			}
			else if (is_valid_ip3(f.wins.value)==false)
			{
				alert("<?=$a_invalid_wins?>");
				field_focus(f.wins, "**");
				return false;
			}
			if(invalid_ip_mask(f.wins.value, f.ipmask.value)==false)
			{
				alert("<?=$a_invalid_wins?>");
				field_focus(f.wins, "**");
				return false;
			}
		}
		
		if(f.dns.value !="")
		{
			if (is_valid_ip3(f.dns.value)==false)
			{
				alert("<?=$a_invalid_dns?>");
				field_focus(f.dns, "**");
				return false;
			}
			if(invalid_ip_mask(f.dns.value, f.ipmask.value)==false)
			{
				alert("<?=$a_invalid_dns?>");
				field_focus(f.dns, "**");
				return false;
			}
		}	
		
		if (!is_blank(f.domain.value) && strchk_hostname(f.domain.value)==false)
		{
			alert("<?=$a_invalid_domain_name?>");
			field_focus(f.domain, "**");
			return false;
		}	
		
		if(!is_digit(f.leasetime.value))
		{
			alert("<?=$a_invalid_lease_time?>");
			field_focus(f.leasetime, "**");
			return false;
		}

				
		if(is_blank(f.leasetime.value)||f.leasetime.value==0)
		{
			alert("<?=$a_invalid_lease_time?>");
			field_focus(f.leasetime, "**");
			return false;
		}	
		
		if( isNaN(f.leasetime.value) || f.leasetime.value < 60 || f.leasetime.value > 31536000)
		{
			alert("<?=$a_invalid_lease_time?>");
			field_focus(f.leasetime, "**");
			return false;
		}	

		net1 = get_network_id(f.ipaddr.value, f.ipmask.value);
		net2 = get_network_id("<?=$cfg_ipaddr_s?>", f.ipmask.value);
		if(net1[0]!=net2[0])	f.on_lan_change.value="1";
		else					f.on_lan_change.value="";									
	}
	fields_disabled(f, false);
	return true;
}

function submit()
{
	var f = get_obj("frm");
	
	if(check()) 
	{
		fields_disabled(f, false);
		f.submit();
	}	
}

</script>

<body <?=$G_BODY_ATTR?> onLoad="init();">

<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onSubmit="return false;">

<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="f_endip"		value="">
<input type="hidden" name="on_lan_change"		value="">

<table id="table_frame" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
	<tr>
		<td valign="top" align="center">
			<table id="table_header" <?=$G_TABLE_ATTR_CELL_ZERO?>>
			<tr>
				<td id="td_header" valign="middle"><?=$m_context_title?></td>
			</tr>
			</table>
<!-- ________________________________ Main Content Start ______________________________ -->
			<table width="98%" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
				<tr>
					<td colspan="2" height="10">
				
					</td>
				</tr>
				<tr>
					<td bgcolor="#cccccc" colspan="2" height="15">
						<b><?=$m_dhcp_srv?></b>
					</td>
				</tr>
				<tr>
					<td width="45%" id="td_left" height="30">
						<?=$m_srv_enable?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("srv_enable", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"], "on_change_srv_enable(this)");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
				<tr>
					<td bgcolor="#cccccc" colspan="2" height="15">
						<b><?=$m_dhcp_pool?></b>
					</td>
				</tr>	
				<tr>
					<td id="td_left">
						<?=$m_ipaddr?>
					</td>
					<td id="td_right">
						<input type="text" class="text" id="ipaddr" name="ipaddr" size="15" maxlength="15" value="" />
					</td>
				</tr>	
				<tr>
					<td id="td_left">
						<?=$m_iprange?>
					</td>
					<td id="td_right">
						<input type="text" class="text" id="iprange" name="iprange" size="15" maxlength="3" value="" />
					</td>
				</tr>	
				<tr>
					<td id="td_left">
						<?=$m_ipmask?>
					</td>
					<td id="td_right">
						<input type="text" class="text" id="ipmask" name="ipmask" size="15" maxlength="15" value="" />
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_gateway?>
					</td>
					<td id="td_right">
						<input type="text" class="text" id="gateway" name="gateway" size="15" maxlength="15" value="" />
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_wins?>
					</td>
					<td id="td_right">
						<input type="text" class="text" id="wins" name="wins" size="15" maxlength="15" value="" />
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_dns?>
					</td>
					<td id="td_right">
						<input type="text" class="text" id="dns" name="dns" size="15" maxlength="15" value="" />
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_m_domain_name?>
					</td>
					<td id="td_right">
						<input type="text" class="text" id="domain" name="domain" size="15" maxlength="30" value="" />
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_m_lease_time?>
					</td>
					<td id="td_right">
						<input type="text" class="text" id="leasetime" name="leasetime" size="15" maxlength="8" value="" />
					</td>
				</tr>	
				<!--tr>
					<td d="td_left">
						<?=$m_status_enable?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("status_enable", [0,1], ["<?=$m_off?>","<?=$m_on?>"], "");<?=$G_TAG_SCRIPT_END?>
					</td>
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
				
