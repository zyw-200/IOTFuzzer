<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_arpspoofing";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_arpspoofing";
set("/runtime/web/help_page",$MY_NAME);
/* --------------------------------------------------------------------------- */
if($ACTION_POST=="adv_arpspoofing")
{
	require("/www/model/__admin_check.php");
	require("/www/__action_adv.php");
	$ACTION_POST = "";
	exit;
}


/* --------------------------------------------------------------------------- */
require("/www/model/__html_head.php");
require("/www/comm/__js_ip.php");

/* --------------------------------------------------------------------------- */
$list_row=0;
for("/arpspoofing/static/index")
{
	$list_row++;
}
$cfg_arp_state=query("/arpspoofing/enable");
if($cfg_arp_state==""){$cfg_arp_state=0;}
$cfg_mode = 0;
$cfg_mode_g = query("/wlan/inf:1/ap_mode");
$cfg_mode_a = query("/wlan/inf:2/ap_mode");
if($cfg_mode_g == 1 || $cfg_mode_a == 1)
{$cfg_mode = 1;}
?>

<script>
var arp_list=[['index','ip_addr','mac_addr']
<?
for("/arpspoofing/static/index")
{
	echo ",\n ['".$@."','".query("ip")."','".query("mac")."']";
}
?>
];

function init()
{
	var f=get_obj("frm");
	change_arp_state();
	if("<?=$cfg_mode?>" == 1)
	{
		fields_disabled(f, true);
		f.arp_state.disabled = true;
	}
	AdjustHeight();
}

function change_arp_state()
{
	var f=get_obj("frm");
	if(f.arp_state.value==0)
	{
		fields_disabled(f, true);
		f.arp_state.disabled = false;
	}
	else
		{
			fields_disabled(f, false);
			if("<?=$list_row?>" >= 8)
			{
				f.add.disabled=f.clear.disabled=true;
			}
		}
}

/*function juage_mac_str(mac)
{
	var tmp_mac=get_mac(mac);
	var cmp_mac="";
	var cmp_mac1="";
	var i, sub_mac, sub_dec_mac;
	for(i=1;i<=6;i++)
	{
		sub_mac=eval("tmp_mac["+i+"]");
		sub_dec_mac=hexstr2int(sub_mac);
		if(sub_dec_mac>255 ||sub_dec_mac<0)	return false;
		else if(sub_dec_mac<=15)
		{
			cmp_mac +="0"+sub_dec_mac.toString(16);
			cmp_mac1+="0"+sub_dec_mac.toString(16);
		}
		else
		{
			cmp_mac +=sub_dec_mac.toString(16);
			cmp_mac1+=sub_dec_mac.toString(16);
		}
		if(i!=6)
		{
			cmp_mac +=":";
		}
	}
	if(cmp_mac!=mac.toLowerCase())	return false;
	return true;
}
*/
function do_edit(id)
{
	var f=get_obj("frm");
	var fu_mac;
	f.which_edit.value=id;
	f.ip_addr.value=arp_list[id][1];
	f.mac_addr1.value=arp_list[id][2].substring(0,2);
	f.mac_addr2.value=arp_list[id][2].substring(3,5);
	f.mac_addr3.value=arp_list[id][2].substring(6,8);
	f.mac_addr4.value=arp_list[id][2].substring(9,11);
	f.mac_addr5.value=arp_list[id][2].substring(12,14);
	f.mac_addr6.value=arp_list[id][2].substring(15);
	if(f.arp_state.value==1)
	{f.add.disabled=f.clear.disabled=false;}
}

function do_del(id)
{
	var f=get_obj("frm");
	if(f.arp_state.value == 1 && confirm("<?=$a_confirm_delete?>") ==  true)
	{
		f.which_delete.value=id;
		f.action.value="delete";
		f.submit();
	}
}

function do_delete_all()
{
	var f=get_obj("frm");
	if(confirm("<?=$a_rule_del_all_confirm?>")==true)
	{
		f.action.value="delete_all";
		f.submit();
	}
}

function check_values()
{
	var f=get_obj("frm");
	var mac_addr, ARP_MAC = "", ARP_MAC_LIST = "";
	var mac_check,mac_st="";
	if(f.ip_addr.value == "")
	{
	    alert("<?=$a_empty_ip_addr?>")
	    f.ip_addr.focus();
	    return false;
	}	
	if(!is_valid_ip(f.ip_addr.value,0))
	{
		alert("<?=$a_invalid_ip?>");
		f.ip_addr.select();
		return false;
	}
	for(var s=1;s<arp_list.length;s++)
	{
		if(f.ip_addr.value==arp_list[s][1] && f.which_edit.value!=s)
		{
			alert("<?=$a_can_not_same_ip ?>");
			f.ip_addr.select();
			return false;
		}
	}
	
	for(i=1;i<=6;i++)
	{
		mac_st+=eval("f.mac_addr"+i+".value");
	}
	if(mac_st == "")
	{
		alert("<?=$a_empty_mac_addr?>");
		f.mac_addr1.focus();
		return false;
	}
	else
		{
			var num_mac1=parseInt("0x"+f.mac_addr1.value);
			var str_mac1=num_mac1.toString(2);
			if(str_mac1.charAt(str_mac1.length-1) == "1")
			{
				alert("<?=$a_invalid_mac?>");
				f.mac_addr1.select();
				return false;
			}
			for(i=1;i<=6;i++)
			{
				mac_addr=eval("f.mac_addr"+i+".value");
				mac_check=eval("f.mac_addr"+i+".value");
				if(is_blank(mac_addr) || !is_valid_mac(mac_addr))
				{
					alert("<?=$a_invalid_mac?>");
					eval("f.mac_addr"+i+".select()");
					return false;			
				}
				if (mac_addr.length == 1) eval("f.mac_addr"+i+".value= '0'+mac_addr");
			}
			mac_addr = f.mac_addr1.value+":"+f.mac_addr2.value+":"+f.mac_addr3.value+":"+f.mac_addr4.value+":"+f.mac_addr5.value+":"+f.mac_addr6.value;
			mac_check =f.mac_addr1.value+f.mac_addr2.value+f.mac_addr3.value+f.mac_addr4.value+f.mac_addr5.value+f.mac_addr6.value;
			if(mac_addr == "00:00:00:00:00:00")
			{
				alert("<?=$a_invalid_mac?>");
				f.mac_addr1.select();
				return false;
			}
			for(i=1;i<arp_list.length;i++)
			{
				if((mac_check.length != 12)&&(mac_check.length != 0))
				{
					alert("<?=$a_invalid_mac?>");
					field_focus(f.mac_addr1, "**");
					return false;
				}			
				ARP_MAC = mac_addr.toUpperCase();
				ARP_MAC_LIST = arp_list[i][2].toUpperCase();		
				if(ARP_MAC == ARP_MAC_LIST && f.which_edit.value!=i)
				{
					alert("<?=$a_can_not_same_mac?>");
					field_focus(f.mac_addr1, "**");
					return false;
				}										
			}				
		f.harp_mac.value=mac_addr.toUpperCase();
		}
	return true;
}					

function do_add()
{
	var f=get_obj("frm");
	if(check_values()==true)
	{
		if(f.which_edit.value=="")
		{
			f.action.value="add";
		}
		else
			{
				f.action.value="edit";
			}
		AdjustHeight();
		f.submit();
		return true;
	}
}

function do_clear()
{
	var f=get_obj("frm");
	f.ip_addr.value=f.mac_addr1.value=f.mac_addr2.value=f.mac_addr3.value=f.mac_addr4.value=f.mac_addr5.value=f.mac_addr6.value="";
}
function check()
{
	return true;
}

function submit()
{
	var f=get_obj("frm");
	fields_disabled(f,false);
	f.submit();
	return true;
}
</script>

<body <?=$G_BODY_ATTR?> onload="init();">

<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return false;">
<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="which_delete" value="">
<input type="hidden" name="which_edit" value="">
<input type="hidden" name="harp_mac" value="">
<input type="hidden" name="action" value="">
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
      	<tr height="25px">
      		<td width="30%"><?=$m_arp_spoofing?></td>
      		<td>
      			<select id="arp_state" name="arp_state" onChange="change_arp_state()">
      				<option value="0" <? if($cfg_arp_state==0){echo "selected";}?>><?=$m_disable?></option>
      				<option value="1" <? if($cfg_arp_state==1){echo "selected";}?>><?=$m_enable?></option>
      			</select>
      		</td>
      	</tr>
      	<tr>
      		<td colspan="2">
      			<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
      				<tr>
      					<td class="table_tool_td" valign="middle" colspan="2"><b><?=$m_add_title ?></b></td>
      				</tr>
      				<tr>
      					<td width="30%"><?=$m_gateway_ip ?></td>
      					<td><input type="text" maxlength="15" size="20" id="ip_addr" name="ip_addr" value=""></td>
      				</tr>
      				<tr>
      					<td width="30%"><?=$m_gateway_mac ?></td>
      					<td><script>print_mac("mac_addr");</script></td>
      				</tr>
      				<tr>
      					<td width="30%">&nbsp;</td>
      					<td>
      						<input type="button" id="add" name="add" size="10" value="<?=$m_add?>" onClick="do_add()">
      					  &nbsp;
      						<input type="button" id="clear" name="clear" size="10" value="<?=$m_clear?>" onClick="do_clear()">
      					</td>
      				</tr>
      			</table>
      		</td>
      	</tr>
      	
      	<tr>
      		<td colspan="2">
      			<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
      				<tr>
      					<td class="table_tool_td" valign="middle" colspan="2"><b><?=$m_list_title ?></b></td>
      				</tr>
      				<tr>
      					<td><?=$m_total_entries ?>: <?=$list_row?></td>
      					<td width="20%"><input type="button" id="del_all" name="del_all" value="<?=$m_del_all ?>" onClick="do_delete_all()"></td>
      				</tr>
      				<tr>
      					<td colspan="2">
      						<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">
      							<tr class="list_head" align="left">
      								<td width="38%"><?=$m_gateway_ip ?></td>
      								<td width="38%"><?=$m_gateway_mac ?></td>
      								<td width="10%" align="center"><?=$m_edit ?></td>
      								<td width="10%" align="center"><?=$m_delete ?></td>
      							</tr>
      						</table>
      							<div>
      							<table id="acl_tab" width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">
<?
$key=0;
for("/arpspoofing/static/index")
{
	$key++;
	$list_ip_addr=query("ip");
	$list_mac_addr=query("mac");
	if($key%2==1)
	{
		echo "<tr style='background:#CCCCCC;'>\n";
	}
	else
	{
		echo "<tr style='background:#B3B3B3;'>\n";
	}
	echo "<td width=38%>".$list_ip_addr."</td>\n";
	echo "<td width=38%>".$list_mac_addr."</td>\n";
	echo "<td width=10%><a href='javascript:do_edit(\"".$@."\")'><img src='/pic/edit.jpg' border=0></a></td>\n";
	echo "<td width=10%><a href='javascript:do_del(\"".$@."\")'><img src='/pic/delete.jpg' border=0></a></td>\n";
	echo "</tr>\n";
}
?>      							
      							</table>
      						</div>
      					</td>
      				</tr>
      			</table>
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
