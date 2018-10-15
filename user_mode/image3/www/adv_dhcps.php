<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_dhcps";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_dhcps";
set("/runtime/web/help_page",$MY_NAME);
/* --------------------------------------------------------------------------- */
if($ACTION_POST != "")
{
	require("/www/model/__admin_check.php");
	require("/www/__action_dhcp_server.php");
	$ACTION_POST = "";
	exit;
}
/*-------------------------------------------*/
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
$MAX_RULES=query("/lan/dhcp/server/pool:1/staticdhcp/max_client");
if ($MAX_RULES==""){$MAX_RULES=25;}

	$cfg_ipmask = query("/lan/dhcp/server/pool:1/netmask");
	$cfg_gateway = query("/lan/dhcp/server/pool:1/gateway");
	$cfg_wins = query("/lan/dhcp/server/pool:1/primarywins");
	$cfg_dns = query("/lan/dhcp/server/pool:1/dns1");
	$cfg_domain = get(h,"/lan/dhcp/server/pool:1/domain");
	$cfg_srv_enable = query("/lan/dhcp/server/enable");
	$cfg_whosetdomain = query("/lan/dhcp/server/pool:1/whosetdomain");
if(query("/wan/rg/inf:1/mode") == 1)
{
	$lanipaddr = query("/wan/rg/inf:1/static/ip");
}
else
{
	$lanipaddr = query("/runtime/wan/inf:1/ip");
}
	
echo "-->\n";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>
var mac_list=[['index','mac','ip','netmask','gateway','primarywins','dns1','domain']<?
for("/lan/dhcp/server/pool:1/staticdhcp/entry")
{
	echo ",\n['".$@."',";
	echo "'".query("/lan/dhcp/server/pool:1/staticdhcp/entry:".$@."/mac")."',";
	echo "'".query("/lan/dhcp/server/pool:1/staticdhcp/entry:".$@."/ip")."',";
	echo "'".query("/lan/dhcp/server/pool:1/staticdhcp/entry:".$@."/netmask")."',";
	echo "'".query("/lan/dhcp/server/pool:1/staticdhcp/entry:".$@."/gateway")."',";
	echo "'".query("/lan/dhcp/server/pool:1/staticdhcp/entry:".$@."/primarywins")."',";
	echo "'".query("/lan/dhcp/server/pool:1/staticdhcp/entry:".$@."/dns1")."',";
	echo "'".query("/lan/dhcp/server/pool:1/staticdhcp/entry:".$@."/domain")."']";
}
?>];
function print_rule_del(id)
{
	var str="";

	str+="<a href='javascript:rule_del_confirm(\""+id+"\")'><img src='/pic/delete.jpg' border=0></a>";
	document.write(str);
}


function print_rule_edit(id)
{
	var str="";

	str+="<a href='javascript:rule_edit_confirm(\""+id+"\")'><img src='/pic/edit.jpg' border=0></a>";

	document.write(str);
}

function rule_del_confirm(id)
{
	var f = get_obj("frm");
	if(confirm("<?=$a_entry_del_confirm?>")==false) return;
	f.f_entry_del.value = id;
	fields_disabled(f, false);
	get_obj("frm").submit();
}

function rule_edit_confirm(id)
{
	var f=get_obj("frm")
	f.entry_edit.value = id;
	init();
}
function on_change_srv_enable(s)
{
	var f = get_obj("frm");
	
	if(s.value == 1)
	{
		fields_disabled(f, false);
		if("<?=$cfg_whosetdomain?>"=="1")
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
	var mac_addr;
	
	select_index(f.srv_enable, "<?=$cfg_srv_enable?>");
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
	if(f.entry_edit.value !="")
	{
		f.ipaddr.value = get_obj("h_ip"+f.entry_edit.value).value;	
		f.host.value = get_obj("h_host"+f.entry_edit.value).value;
		mac_addr = get_mac(get_obj("h_mac"+f.entry_edit.value).value);
		for(var i=1; i<7; i++)
		{
			get_obj("mac"+i).value = mac_addr[i];
		}
		f.ipmask.value = mac_list[f.entry_edit.value][3];
		f.gateway.value = mac_list[f.entry_edit.value][4];
		f.wins.value = mac_list[f.entry_edit.value][5];
		f.dns.value = mac_list[f.entry_edit.value][6];
		f.domain.value = mac_list[f.entry_edit.value][7];
	}
	on_change_srv_enable(f.srv_enable);
	if("<?=$cfg_whosetdomain?>"=="1")
	{
		f.domain.disabled=true;
	}
	AdjustHeight();
}


/* parameter checking */
function check()
{
	var f = get_obj("frm");
	var wins_ip, net1, net2 ,mac_check, mac_add, DHCPS_MAC = "", DHCPS_MAC_LIST = "";;
	var max_rule = <?=$MAX_RULES?>+1;
	var lanip = get_ip("<?=$lanipaddr?>");
	if(f.srv_enable.value == 1)
	{	
		if (is_blank(f.host.value) || strchk_hostname(f.host.value)==false)
		{
			alert("<?=$a_invalid_host?>");
			field_focus(f.host, "**");
			return false;
		}	
		if (is_valid_ip3(f.ipaddr.value, 0)==false)
		{
			alert("<?=$a_invalid_ip?>");
			field_focus(f.ipaddr, "**");
			return false;
		}	
		if(f.ipaddr.value==lanip[0])
		{
			alert("<?=$a_invalid_ip?>");
			field_focus(f.ipaddr, "**");
			return false;
		}
		var num_mac1=parseInt("0x"+f.mac1.value);
        var str_mac1=num_mac1.toString(2);
        if(str_mac1.charAt(str_mac1.length-1) == "1")
        {
            alert("<?=$a_invalid_mac?>");
            f.mac1.select();
            return false;
        }

		for(var i=1; i<=6; i++)
		{
			mac_add=eval("f.mac"+i+".value");
			mac_check=eval("f.mac"+i+".value");

			if(is_blank(mac_add) || !is_valid_mac(mac_add))
			{
				alert("<?=$a_invalid_mac?>");
				eval("f.mac"+i+".focus()");
				return false;			
			}
			if (mac_add.length == 1) eval("f.mac"+i+".value= '0'+mac_add");
		}
	
		mac_add = f.mac1.value+":"+f.mac2.value+":"+f.mac3.value+":"+f.mac4.value+":"+f.mac5.value+":"+f.mac6.value;
		mac_check =f.mac1.value+f.mac2.value+f.mac3.value+f.mac4.value+f.mac5.value+f.mac6.value;
		if(mac_add == "00:00:00:00:00:00")
		{
			alert("<?=$a_invalid_mac?>");
			f.mac1.select();
			return false;
		}
		for(var j=1; j<mac_list.length; j++)
		{
			if(f.ipaddr.value==mac_list[j][2]&& (j !=f.entry_edit.value))
			{
				alert("<?=$a_same_ip?>");
				field_focus(f.ipaddr, "**");
				return false;
			}
			if((mac_check.length != 12)&&(mac_check.length != 0))
			{
				alert("<?=$a_invalid_mac?>");
				field_focus(f.mac1, "**");
				return false;
			}
			if(f.entry_edit.value=="")
			{
			if((mac_list.length == max_rule)&&(!is_blank(mac_check)))
			{
				alert("<?=$a_max_mac_num?>");
				return false;
			}	
			}	
			
			DHCPS_MAC = mac_add.toUpperCase();
			DHCPS_MAC_LIST = mac_list[j][1].toUpperCase();
						
			if((DHCPS_MAC == DHCPS_MAC_LIST)&&(j !=f.entry_edit.value))
			{
				alert("<?=$a_same_mac?>");
				field_focus(f.mac1, "**");
				return false;
			}		
			
			if(is_blank(mac_check))
			{
				acl_mac = 0;				
			}							
		}	
				
		f.f_mac.value = mac_add;
		
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

<body <?=$G_BODY_ATTR?> onload="init();">

<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return false;">

<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="f_mac"		value="">
<input type="hidden" name="f_entry_del"		value="">
<input type="hidden" name="entry_edit"		value="">

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
						<?=$m_computer_name?>
					</td>
					<td id="td_right">
						<input class="text" type="text" class="text" id="host" name="host" size="15" maxlength="20" value="" />
					</td>
				</tr>	
				<tr>
					<td id="td_left">
						<?=$m_ipaddr?>
					</td>
					<td id="td_right">
						<input class="text" type="text" class="text" id="ipaddr" name="ipaddr" size="15" maxlength="15" value="" />
					</td>
				</tr>	
				<tr>
					<td id="td_left"><?=$m_macaddr?></td>
					<td id="td_right"><script>print_mac("mac");</script></td>
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
				<tr>
					<td colspan="2">							
						<div class="div_tab" >
							<table id="dhcps_tab" width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">						
							<tr class="fixed_head" align="left">
								<td width="25%">
									<?=$m_computer_name?>
								</td>
								<td width="35%">
									<?=$m_mac?>
								</td>
								<td width="20%">
									<?=$m_ip?>
								</td>
								<td width="9%">
									<?=$m_edit?>
								</td>															
								<td>
									<?=$m_del?>
								</td>																																																										
							</tr>	
<?
$tmp = "";
for("/lan/dhcp/server/pool:1/staticdhcp/entry")
{	
	echo "<input type=\"hidden\" id=\"h_mac".$@."\" name=\"h_mac".$@."\" value=\"".query("mac")."\">\n";
	echo "<input type=\"hidden\" id=\"h_ip".$@."\" name=\"h_ip".$@."\" value=\"".query("ip")."\">\n";
	echo "<input type=\"hidden\" id=\"h_host".$@."\" name=\"h_host".$@."\" value=\"".query("hostname")."\">\n";
	$tmp = $@%2;
	if(query("mac") !="")
	{	
		if($tmp == 1)
		{
			echo "<tr align=\"left\" style=\"background:#cccccc;\">\n";
			$tmp = 0;
		}
		else
		{
			echo "<tr align=\"left\" style=\"background:#b3b3b3;\">\n";
			$tmp = 1;
		}
		echo "<td width=\"25%\">".$G_TAG_SCRIPT_START."genTableSSID(\"".get(j,"hostname")."\",\"0\");".$G_TAG_SCRIPT_END."</td>\n";
		echo "<td width=\"35%\">".query("mac")."</td>\n";
		echo "<td width=\"20%\">".query("ip")."</td>\n";
		echo "<td width=\"9%\"><script>print_rule_edit(".$@.");</script></td>\n";
		echo "<td>".$G_TAG_SCRIPT_START."print_rule_del(".$@.");".$G_TAG_SCRIPT_END."</script></td>\n";
		echo "</tr>\n";	
	}			
}
?>
							</table>	
						</div>										
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
				
