<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_trafficmanage";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_trafficmanage";
set("/runtime/web/help_page",$MY_NAME);
/* --------------------------------------------------------------------------- */
if($ACTION_POST!="")
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

anchor("/trafficctrl/trafficmgr");
$cfg_tramgr_state=query("enable");
if($cfg_tramgr_state==""){$cfg_tramgr_state=0;}
$cfg_de_for=query("unlistclientstraffic");
if($cfg_de_for==""){$cfg_de_for=1;}
$cfg_e2w=query("/trafficctrl/updownlinkset/bandwidth/downlink");
if($cfg_e2w==""){$temp_e2w=0;}else{$temp_e2w=$cfg_e2w;}
$cfg_w2e=query("/trafficctrl/updownlinkset/bandwidth/uplink");
if($cfg_w2e==""){$temp_w2e=0;}else{$temp_w2e=$cfg_w2e;}
if($cfg_e2w=="" || $cfg_w2e==""){$judge_enable=0;}else{$judge_enable=1;}
$cfg_fair_state = query("/tc_monitor/state");
$list_row=0;
for("/trafficctrl/trafficmgr/rule/index")
{
	$list_row++;
}

$cfg_ipv6 = query("/inet/entry:1/ipv6/valid");
?>

<script>
var t_list=[['index','name','clientip','clientmac','downlink','uplink']
<?
for("/trafficctrl/trafficmgr/rule/index")
{
	echo ",\n ['".$@."','".get("j","name")."','".query("clientip")."','".query("clientmac")."','".query("downlink")."','".query("uplink")."']";
}
?>
];

var ipmac_list=[['index','clientip','clientmac']
<?
for("/trafficctrl/trafficmgr/rule/index")
{
	echo ",\n ['".$@."','".query("clientip")."','".query("clientmac")."']";
}
?>
];

var n_list=['name'
<?
for("/trafficctrl/trafficmgr/rule/index")
{
	echo ",'".get("j","name")."'";
}
?>
];

function init()
{
	var f=get_obj("frm");
	change_tramgr_state();
}

function change_tramgr_state()
{
	var f=get_obj("frm");
	if(f.tramgr_state.value==0)
	{
		f.de_for[0].disabled=f.de_for[1].disabled=f.e2w.disabled=f.w2e.disabled=true;
	//	get_obj("add_rule").style.display="none";
	//	get_obj("rule_list").style.display="none";
		f.tra_name.disabled = f.cli_ip.disabled = f.cli_mac.disabled = f.e2wrule.disabled = 
		f.w2erule.disabled = f.t_add.disabled = f.t_clr.disabled = true;
	}
	if(f.tramgr_state.value==1)
	{
		if("<?=$judge_enable?>"==0)
		{
			alert("<?=$m_can_not_enable ?>");
			f.tramgr_state.selectedIndex=0;
			return false;
		}
		else
			{
				if("<?=$cfg_fair_state?>" == 1)
				{
					alert("<?=$a_disable_fair?>");
				}
				f.de_for[0].disabled=f.de_for[1].disabled=false;
				f.e2w.disabled=f.w2e.disabled=true;
				if(f.de_for[0].checked)
				{
					f.denyorforward.value=0;
				}
				else if(f.de_for[1].checked)
					{
						f.denyorforward.value=1;
					}
	//			get_obj("add_rule").style.display="";
	//			get_obj("rule_list").style.display="";
				f.tra_name.disabled = f.cli_ip.disabled = f.cli_mac.disabled = f.e2wrule.disabled =
		        f.w2erule.disabled = f.t_add.disabled = f.t_clr.disabled = false;
				do_t_clr();
			}
		}
	AdjustHeight();
}

function juage_mac_str(mac)
{
	var tmp_mac=get_mac(mac);
	var cmp_mac="";
	var cmp_mac1="";
	var i, sub_mac, sub_dec_mac;
	var num_mac1=parseInt("0x"+tmp_mac[1]);
	var str_mac1=num_mac1.toString(2);
	if(str_mac1.charAt(str_mac1.length-1) == "1"){return false;}
	if(mac == "00:00:00:00:00:00"){return false;}
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

function check_list_values()
{
	var f=get_obj("frm");
	if(f.tramgr_state.value==1)
	{
		if(<?=$list_row ?>==64 && f.which_edit.value=="")
		{
			alert("<?=$a_Rule_maxnum ?>");
			return false;
		}
		if(is_blank(f.tra_name.value))
		{
			alert("<?=$a_blank_name?>");
			f.tra_name.focus();
			return false;
		}
		else
		{
			if(first_blank(f.tra_name.value))
			{
				alert("<?=$a_first_blank_name?>");
				f.tra_name.select();
				return false;
			}
			if(strchk_unicode(f.tra_name.value))
			{
	    		alert("<?=$a_invalid_name?>");
				f.tra_name.select();
				return false;
			}
			if(f.which_edit.value=="")
			{
				for(var s=1;s<n_list.length;s++)
				{
					if(f.tra_name.value==n_list[s])
					{
						alert("<?=$a_can_not_same_name?>");
						f.tra_name.select();
						return false;
					}
				}
			}
			else
				{
					for(var s=1;s<n_list.length;s++)
					{
						if(f.tra_name.value==n_list[s] && f.which_edit.value!=s)
						{
							alert("<?=$a_can_not_same_name?>");
							f.tra_name.select();
							return false;
						}
					}
				}
		}

		if(f.cli_ip.value=="" && f.cli_mac.value=="")
		{
			alert("<?=$a_empty_ip_or_mac_address ?>");
			f.cli_ip.focus();
			return false;
		}
		if(!is_valid_ip(f.cli_ip.value,1))
		{
			alert("<?=$a_invalid_ip?>");
			f.cli_ip.select();
			return false;
		}
		if(!is_blank(f.cli_mac.value))
		{
			if(!juage_mac_str(f.cli_mac.value))
			{
				alert("<?=$a_invalid_mac?>");
				f.cli_mac.select();
				return false;
			}
		}

		if(f.which_edit.value=="")
		{
		   for(i=1;i< ipmac_list.length;i++)
			{				
			  	if(f.cli_ip.value != "" && f.cli_ip.value==ipmac_list[i][1])
				{
					alert("<?=$a_same_tmrule_ip?>");
					f.cli_ip.select();
					return false;
				}
			    if(f.cli_mac.value !="" && f.cli_mac.value.toUpperCase()==ipmac_list[i][2])
			 	{
					alert("<?=$a_same_tmrule_mac?>");
				 	f.cli_mac.select();
				   	return false;
				}											
	  		}
		}
		else
			{
				for(i=1;i< ipmac_list.length;i++)
	 	 		{				
		 		 	if(f.cli_ip.value != "" && f.cli_ip.value==ipmac_list[i][1] && f.which_edit.value!=i)
			  		{
				  		alert("<?=$a_same_tmrule_ip?>");
				  		f.cli_ip.select();
				  		return false;
			  		}
			  		if(f.cli_mac.value !="" && f.cli_mac.value.toUpperCase()==ipmac_list[i][2] && f.which_edit.value!=i)
			  		{
				  		alert("<?=$a_same_tmrule_mac?>");
				  		f.cli_mac.select();
						return false;
					}											
	  	 		}
			}	
	
		if(f.e2wrule.value=="" && f.w2erule.value=="")
		{
			alert("<?=$a_empty_value_for_two_speed ?>");
			f.e2wrule.select();
			return false;
		}
		
		if(f.e2wrule.value!="")
		{
			if(!is_digit(f.e2wrule.value))
			{
				alert("<?=$a_invalid_value_for_speed?>");
				f.e2wrule.select();
				return false;
			}
			if(f.e2wrule.value < 1 || f.e2wrule.value > <?=$temp_e2w?> || isNaN(f.e2wrule.value))
			{
				alert("<?=$a_GreaterThanPrimary_value_for_speed?>");
				f.e2wrule.select();
				return false;
			}
		}
		
		if(f.w2erule.value!="")
		{
			if(!is_digit(f.w2erule.value))
			{
				alert("<?=$a_invalid_value_for_speed?>");
				f.w2erule.select();
				return false;
			}
			if(f.w2erule.value < 1 || f.w2erule.value > <?=$temp_w2e?> || isNaN(f.w2erule.value))
			{
				alert("<?=$a_GreaterThanPrimary_value_for_speed?>");
				f.w2erule.select();
				return false;
			}
		}
		
		f.cli_mac.value = f.cli_mac.value.toUpperCase();
		return true;
	}
}


function do_t_add()
{
	var f=get_obj("frm");
	if(check_list_values()==true)
	{
		for(var i=0;i<f.e2wrule.value.length;i++)
		{
			if(f.e2wrule.value.charAt(i)!=0)
			{
				f.e2wrule.value=f.e2wrule.value.substring(i);
				break;
			}
		}
		for(var i=0;i<f.w2erule.value.length;i++)
		{
			if(f.w2erule.value.charAt(i)!=0)
			{
				f.w2erule.value=f.w2erule.value.substring(i);
				break;
			}
		}

		if(f.which_edit.value=="")
		{
			f.action.value="add";
		}
		else
		{
			f.action.value="edit";
		}
		f.submit();
		return true;
	}	
	AdjustHeight();
}

function do_t_clr()
{
	var f=get_obj("frm");
	f.tra_name.value="";
	f.cli_ip.value=f.cli_mac.value="";
	f.e2wrule.value=f.w2erule.value="";
}

function do_edit(id)
{
	var f=get_obj("frm");
	f.which_edit.value=id;
	f.tra_name.value=t_list[id][1];
	f.cli_ip.value=t_list[id][2];
	f.cli_mac.value=t_list[id][3];
	f.e2wrule.value=t_list[id][4];
	f.w2erule.value=t_list[id][5];
}

function do_del(id)
{
	var f=get_obj("frm");	
	if(f.tramgr_state.value == 1 && confirm("<?=$a_rule_del_confirm?>"+id+"?")==true)
	{
		f.which_delete.value=id;
		//f.ACTION_POST.value="delete";
		f.action.value="delete";
		f.submit();
  	return true;
	}	
}

function check()
{
	var f=get_obj("frm");
	f.denyorforward.value= (f.de_for[0].checked==true?0:1);
	return true;	
}

function submit()
{
	var f=get_obj("frm");
	if(check()==true)
	{
		//f.ACTION_POST.value="apply";
		f.action.value="apply";
		f.submit();
		return true;
	}
}
function toBreakWord(index)
{ 
	var str1,listname,str2="",str="";
	listname=t_list[index][1];
	if(navigator.userAgent.indexOf('Firefox') >= 0)
	{
		if(listname.length > 8)
		{
			str1=listname.substring(0,7);
        	str2=listname.substring(8);
        	str="<td width=17%>"+str1+"<br>"+str2+"</td>\n";
        }
		else
		{str="<td width=17%>"+listname+"</td>\n";}
	}
	else
	{
		str+="<td width=17% style='word-wrap:break-word; word-break:break-all;'>"+t_list[index][1]+"</td>\n";
	}
	document.write(str); 
}

</script>

<body <?=$G_BODY_ATTR?> onload="init();">

<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return false;">
<input type="hidden" name="ACTION_POST" value="<?=$MY_NAME?>">
<input type="hidden" name="which_edit" value="">
<input type="hidden" name="which_delete" value="">
<input type="hidden" name="denyorforward" value="">
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
      	<tr>
      		<td width="25%" id="td_left"><?=$m_Trafficmanage?></td>
      		<td>
      			<select id="tramgr_state" name="tramgr_state" onChange="change_tramgr_state()">
      				<option value="0" <? if($cfg_tramgr_state==0) {echo "selected";}?>><?=$m_TrafficmanageDisa?></option>
      				<option value="1" <? if($cfg_tramgr_state==1) {echo "selected";}?>><?=$m_TrafficmanageEnable?></option>
      			</select>
      		</td>
      	</tr>
      	
      	<tr>
      		<td width="25%" id="td_left"><?=$m_UnlistedClientsTraffic?></td>
      		<td>
      			<input type="radio" id="de_for" name="de_for" value="0" <? if($cfg_de_for==0){echo "checked";}?>><?=$m_deny?>&nbsp;
      			<input type="radio" id="de_for" name="de_for" value="1" <? if($cfg_de_for==1){echo "checked";}?>><?=$m_forward?>
      		</td>
      	</tr>
      	
      	<tr>
      		<td width="25%" id="td_left"><?=$m_DownlinkInterface?></td>
      		<td>
      			<input type="text" id="e2w" name="e2w" maxlength="6" size="6" value="<?=$cfg_e2w?>">Mbits/sec
      		</td>
      	</tr>
      	
      	<tr>
      		<td width="25%" id="td_left"><?=$m_UplinkInterface?></td>
      		<td>
      			<input type="text" id="w2e" name="w2e" maxlength="6" size="6" value="<?=$cfg_w2e?>">Mbits/sec
      		</td>
      	</tr>
      	
        
      	<tr>
      		<td colspan="2">
      			<div id="add_rule">
      			<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
      				<tr>
      					<td class="table_tool_td" valign="middle" colspan="2"><b><?=$m_context_add_title?></b></td>
      				</tr>
      				
      				<tr>
      					<td width="25%"><?=$m_name?></td>
      					<td><input type="text" id="tra_name" name="tra_name" maxlength="15" size="17" value=""></td>
      				</tr>
      				
      				<tr>
      					<td width="25%"><?=$m_ClientIP?></td>
      					<td><input type="text" id="cli_ip" name="cli_ip" maxlength="15" size="17" value=""></td>
      				</tr>
      				
      				<tr>
      					<td width="25%"><?=$m_ClientMac?></td>
      					<td><input type="text" id="cli_mac" name="cli_mac" maxlength="17" size="17" value=""></td>
      				</tr>
      				
      				<tr>
      					<td width="25%"><?=$m_Downlink_Speed?></td>
      					<td><input type="text" id="e2wrule" name="e2wrule" maxlength="3" size="6" value="">Mbits/sec</td>
      				</tr>
      				
      				<tr>
      					<td width="25%"><?=$m_Uplink_Speed?></td>
      					<td><input type="text" id="w2erule" name="w2erule" maxlength="3" size="6" value="">Mbits/sec</td>
      				</tr>
      				
      				<tr>
      					<td width="25%"></td>
      					<td>
      						<input type="button" id="t_add" name="t_add" value="<?=$m_b_add?>" onClick="do_t_add()">
      						<input type="button" id="t_clr" name="t_clr" value="<?=$m_b_cancel?>" onClick="do_t_clr()">
      					</td>
      				</tr>
      			</table>
      			</div>
      		</td>
      	</tr>
      
      
        
      	<tr>
      		<td colspan="2">
      			<div id="rule_list">
      			<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
      				<tr>
      					<td class="table_tool_td" valign="middle" colspan="2"><b><?=$m_context_qos_title?></b></td>
      				</tr>
      				
      				<tr>
      					<td colspan="2">
      						<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">
      							<tr class="list_head" align="left">
      								<td width="18%"><?=$m_name?></td>
      								<td width="20%"><?=$m_SourceAddressIp ?></td>
      								<td width="19%"><?=$m_SourceAddressMac ?></td>
      								<td width="18%"><?=$m_Downlink_Speed ?></td>
      								<td width="15%"><?=$m_Uplink_Speed ?></td>
      								<td width="5%"><?=$m_edit ?></td>
      								<td width="5%"><?=$m_del ?></td>
      							</tr>
      						</table>
      						
      				<!--		<div class="div_tab">  -->
      						<div>
	  		        	<table id="acl_tab" width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;table-layout:fixed;">

<?
$key=0;
for("/trafficctrl/trafficmgr/rule/index")
{
	$key++;
	$list_name=query("name");
	$list_clientip=query("clientip");
	$list_clientmac=query("clientmac");
	$list_e2w=query("downlink");
	$list_w2e=query("uplink");
	if($key%2==1)
	{
		echo "<tr style='background:#CCCCCC;'>\n";
	}
	else
	{
		echo "<tr style='background:#B3B3B3;'>\n";
	}
	echo $G_TAG_SCRIPT_START."toBreakWord(".$@.");".$G_TAG_SCRIPT_END;
  echo "<td width=20%>".$list_clientip."</td>\n";
  echo "<td width=22%>".$list_clientmac."</td>\n";
  echo "<td width=16%>".$list_e2w."Mbits/sec</td>\n";
  echo "<td width=15%>".$list_w2e."Mbits/sec</td>\n";
  echo "<td width=5%><a href='javascript:do_edit(\"".$@."\")'><img src='/pic/edit.jpg' border=0></a></td>";
  echo "<td width=5%><a href='javascript:do_del(\"".$@."\")'><img src='/pic/delete.jpg' border=0></a></td>";
	echo "</tr>\n";
}
?>
      					 </table>
      					 </div>
      					</td>
      				</tr>
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
