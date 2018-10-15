<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_dhcpd";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_mdhcpd";
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
$cfg_dhcp_enable=query("/lan/dhcp/server/enable");
$cfg_multi_dhcp_enable=query("/lan/dhcp/server/multiinstances");
$cfg_mode = query("ap_mode");
$cfg_mssid_enable=query("multi/state");
$cfg_display_multi_dhcp=query("/runtime/web/display/multi_dhcp");
echo "-->\n";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>
var Mdhcp_list=[['index','multi_srv_enable','ipaddr_s','ipaddr_e','ipmask','gateway','wins','dns','domain','leasetime']
<?
for("/lan/dhcp/server/pool")
{
	$cfg_multi_srv_enable = query("enable");
	$cfg_ipaddr_s = query("startip");
	$cfg_ipaddr_e = query("endip");
	$cfg_ipmask = query("netmask");
	$cfg_gateway = query("gateway");
	$cfg_wins = query("primarywins");
	$cfg_dns = query("dns1");
	$cfg_domain = get("j","domain");
	$cfg_leasetime = query("leasetime");
	echo ",\n['".$@."','".$cfg_multi_srv_enable."','".$cfg_ipaddr_s."','".$cfg_ipaddr_e."','".$cfg_ipmask."','".$cfg_gateway."','".$cfg_wins."','".$cfg_dns."','".$cfg_domain."','".$cfg_leasetime."']";
}
?>];

function on_change_multi_enable(s)
{
	var f = get_obj("frm");
	var ip_s, ip_e;
	if (s.value!=1)
	{
		f.index.value=1;
		get_obj("dhcp_index").style.display = "none";
		f.multi_srv_enable.value=1;
		fields_disabled(f,false);
		f.multi_srv_enable.disabled=true;
		f.ipaddr.value = Mdhcp_list[1][2];
		ip_s = get_ip(Mdhcp_list[1][2]);
		ip_e = get_ip(Mdhcp_list[1][3]);
		f.iprange.value = (parseInt(ip_e[4], [10]) - parseInt(ip_s[4], [10]))+1;
		f.ipmask.value = Mdhcp_list[1][4];
		f.gateway.value = Mdhcp_list[1][5];
		f.wins.value = Mdhcp_list[1][6];
		f.dns.value = Mdhcp_list[1][7];
		f.domain.value = Mdhcp_list[1][8];
		f.leasetime.value = Mdhcp_list[1][9];
		
	}
	else
	{
		get_obj("dhcp_index").style.display = "";
		f.multi_srv_enable.disabled=false;
	}
}
function on_change_index(s)
{
		var f = get_obj("frm");
		var ip_s, ip_e;
		select_index(f.multi_srv_enable,Mdhcp_list[s.value][1]);
		f.ipaddr.value = Mdhcp_list[s.value][2];
		ip_s = get_ip(Mdhcp_list[s.value][2]);
		ip_e = get_ip(Mdhcp_list[s.value][3]);
		f.iprange.value = (parseInt(ip_e[4], [10]) - parseInt(ip_s[4], [10]))+1;
		f.ipmask.value = Mdhcp_list[s.value][4];
		f.gateway.value = Mdhcp_list[s.value][5];
		f.wins.value = Mdhcp_list[s.value][6];
		f.dns.value = Mdhcp_list[s.value][7];
		f.domain.value = Mdhcp_list[s.value][8];
		f.leasetime.value = Mdhcp_list[s.value][9];
		fields_disabled(f,true);
		f.multi_dhcp_enable.disabled=false;
		f.index.disabled=false;
		f.srv_enable.disabled=false;
		f.multi_srv_enable.disabled=false;

}
function on_change_multi_srv_enable(s)
{
	var f = get_obj("frm");
	if(s.value == 1)
	{
		fields_disabled(f, false);
		f.multi_dhcp_enable.disabled=false;
		f.index.disabled=false;
		f.srv_enable.disabled=false;
	}
	else
	{
		fields_disabled(f, true);
		s.disabled = false;
		f.multi_dhcp_enable.disabled=false;
		f.index.disabled=false;
		f.srv_enable.disabled=false;
	}
}

function on_change_srv_enable(s)
{
	var f = get_obj("frm");
	if("<?=$cfg_mssid_enable?>"=="1" && "<?=$cfg_display_multi_dhcp?>"=="1")
	{
		if(s.value == 1)
		{			
			
		
			f.multi_dhcp_enable.disabled=false;
			f.index.disabled=false;
			s.disabled=false;
			f.multi_srv_enable.value=1;
			if(f.multi_srv_enable.value==1)
			{
				fields_disabled(f,false);
			}
			if(f.multi_dhcp_enable.value==1)
			{
				f.multi_srv_enable.disabled=false;
			}
			else
			{
				f.multi_srv_enable.disabled=true;
			}
			
		}
		else
		{
			fields_disabled(f, true);
			s.disabled = false;
		}	
	}
	else
	{
		if(s.value == 1)
		{			
			fields_disabled(f,false);
			s.disabled=false;
		}
		else
		{
			fields_disabled(f, true);
			s.disabled = false;
		}	
	}
}
/* page init functoin */
function init()
{
	var f = get_obj("frm");
	var ip_s, ip_e;
	
	select_index(f.srv_enable,"<?=$cfg_dhcp_enable?>");
	if(f.multi_dhcp_enable != null)
	{
		f.multi_dhcp_enable.value = "<?=$cfg_multi_dhcp_enable?>";
		on_change_multi_enable(f.multi_dhcp_enable);
	}
	if(f.multi_srv_enable != null)
	{
		select_index(f.multi_srv_enable,Mdhcp_list[1][1]);
	}
	f.ipaddr.value = Mdhcp_list[1][2];
	ip_s = get_ip(Mdhcp_list[1][2]);
	ip_e = get_ip(Mdhcp_list[1][3]);
	f.iprange.value = (parseInt(ip_e[4], [10]) - parseInt(ip_s[4], [10]))+1;
	f.ipmask.value = Mdhcp_list[1][4];
	f.gateway.value = Mdhcp_list[1][5];
	f.wins.value = Mdhcp_list[1][6];
	f.dns.value = Mdhcp_list[1][7];
	f.domain.value = Mdhcp_list[1][8];
	f.leasetime.value = Mdhcp_list[1][9];	
	on_change_srv_enable(f.srv_enable);
	if ("<?=$cfg_mode?>"==1)
	{
			f.srv_enable.disabled=true;
	}	
	
}


/* parameter checking */
function check()
{
	var f = get_obj("frm");
	var ip, end_ip, wins_ip, net1, net2;;
	
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
		end_ip = parseInt(ip[4], [10]) + parseInt((f.iprange.value-1), [10]);
		
		if( end_ip < 1 || end_ip > 254)
		{
			alert("<?=$a_invalid_ip_range?>");
			f.iprange.value="";
			f.iprange.focus();
			return false;
		}		
		f.f_endip.value = ip[1]+"."+ip[2]+"."+ip[3]+"."+end_ip;
			
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
		}	
		
		wins_ip = get_ip(f.wins.value);
		
		if(f.wins.value !="")
		{
			if (wins_ip[1]=="0" && wins_ip[2]=="0" && wins_ip[3]=="0" && wins_ip[4]=="0")
			{
				
			}
			else if (is_valid_ip2(f.wins.value)==false)
			{
				alert("<?=$a_invalid_wins?>");
				field_focus(f.wins, "**");
				return false;
			}
		}
		
		if(f.dns.value !="")
		{
			if (is_valid_ip2(f.dns.value)==false)
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
<? if(query("/wlan/inf:1/multi/state") !="1" || query("/runtime/web/display/multi_dhcp") !="1")	{echo "<!--";} ?>					
				
				<tr>
					<td bgcolor="#cccccc" colspan="2" height="15">
						<b><?=$m_multi_dhcp?></b>
					</td>
				</tr>
				<tr>
					<td width="45%" id="td_left" height="30">
						<?=$m_multi_dhcp_enable?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("multi_dhcp_enable", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"], "on_change_multi_enable(this)");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
				<tr>
					<td colspan="2">
					<div id="dhcp_index" style="display:none;">
					<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
						<tr>
							<td  width="45%" id="td_left">
								<?=$m_index?>
							</td>
							<td id="td_right">
								<?=$G_TAG_SCRIPT_START?>genSelect("index", [1,2,3,4,5,6,7,8], ["<?=$m_pri_ssid?>","<?=$m_ms_ssid1?>","<?=$m_ms_ssid2?>","<?=$m_ms_ssid3?>","<?=$m_ms_ssid4?>","<?=$m_ms_ssid5?>","<?=$m_ms_ssid6?>","<?=$m_ms_ssid7?>"], "on_change_index(this)");<?=$G_TAG_SCRIPT_END?>
							</td>
						</tr>
					</table>
					</div>
					</td>
				</tr>
				<tr>
					<td bgcolor="#cccccc" colspan="2" height="15">
						<b><?=$m_multi_dhcp_srv?></b>
					</td>
				</tr>
				<tr>
					<td width="45%" id="td_left" height="30">
						<?=$m_multi_srv_enable?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("multi_srv_enable", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"], "on_change_multi_srv_enable(this)");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
<? if(query("/wlan/inf:1/multi/state") !="1" || query("/runtime/web/display/multi_dhcp") !="1")	{echo "-->";} ?>	

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
				
