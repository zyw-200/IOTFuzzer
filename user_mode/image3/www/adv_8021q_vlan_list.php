<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_8021q_vlan_list";
$MY_MSG_FILE  = "adv_8021q.php";
$MY_ACTION    = "adv_8021q";
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
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
echo "<!--debug\n";
$check_lan = query("/sys/2lan");
echo "-->";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>
/* page init functoin */
		var num_ssid=9;
		var num_wds=17;
		var num_ssid_a=25;
		var num_wds_a=33;
function init()
{
	var tmp;
	tmp = get_obj("mainTable").offsetHeight;	
	tmp = parseInt(tmp, [10])+50;	
	if(tmp == 50) tmp = window.screen.availHeight;
	parent.ifrMain_vlan.height = tmp;
}

function print_tag(index, flag)
{
	var f=get_obj("frm")	
	var str="", tag_untag = 1;
	var wdsnum = 0;
	if(flag == "untag")
	{
		tag_untag = 2;
	}

	if(get_obj("rule_egress_sys"+index).value == tag_untag)
	{
		str+="<?=$m_admin?>, ";
	}
	if("<?=$check_lan?>" == "")
	{
		if(get_obj("rule_egress_eth"+index).value == tag_untag)
		{
			str+="<?=$m_eth?>, ";
		}
	}
	else
	{
		if(get_obj("rule_egress_eth"+index).value == tag_untag)
		{
			str+="<?=$m_lan1?>, ";
		}
	
		if(get_obj("rule_egress_eth2"+index).value == tag_untag)
		{
			str+="<?=$m_lan2?>, ";
		}
	}


	for(var i=1; i<num_wds; i++)
	{
		if(i==1)
		{
			if(get_obj("rule_egress_ath"+index+"_"+i).value == tag_untag)
			{
				str+="<?=$m_p1?>(2.4G), ";
			}			
		}
		if(i>1 && i<num_ssid)
		{
			if(get_obj("rule_egress_ath"+index+"_"+i).value == tag_untag)
			{
				str+="<?=$m_m?>"+(i-1)+"(2.4G), ";
			}				
		}
		if(i>=9)
		{
			if(get_obj("rule_egress_ath"+index+"_"+i).value == tag_untag)
			{
				str+="<?=$m_w?>"+(i-8)+"(2.4G), ";
			}				
		}		
	}	
	
	
	for(var i=17; i<num_wds_a; i++)
	{
		if(i==17)
		{
			if(get_obj("rule_egress_ath"+index+"_"+i).value == tag_untag)
			{
				str+="<?=$m_p1?>(5G), ";
			}			
		}
		if(i>17 && i<num_ssid_a)
		{
			if(get_obj("rule_egress_ath"+index+"_"+i).value == tag_untag)
			{
				str+="<?=$m_m?>"+(i-17)+"(5G), ";
			}				
		}
		if(i>=25)
		{
			
				wdsnum=i;
			
			if(get_obj("rule_egress_ath"+index+"_"+wdsnum).value == tag_untag)
			{
				str+="<?=$m_w?>"+(i-24)+"(5G), ";
			}				
		}		
	}	
	
	str = get_tag_untag(str);
	document.write(str);	
}

function get_tag_untag(str)
{
	var tag_array=new Array();
	var return_str ="";
	
	for(var i=0; i<(str.length-2) ;i++)
	{
		tag_array[i]="";
		tag_array[i] = str.charAt(i);
		return_str+=tag_array[i];
	}

	return return_str;
}

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

var vlan_list=[['index','name']
<?
for("/sys/group_vlan/index")
{
	echo ",\n['".$@."','".get("j","group_name")."']";
}
?>];


function rule_del_confirm(id)
{
	var f = get_obj("frm");
	var f_final	=get_obj("final_form");
	var name = vlan_list[id][1];
	
	var f_vlan = parent.document.getElementById("f_vlan");
	var f_pvid_auto = parent.document.getElementById("f_pvid_auto");
	
	if(confirm("<?=$a_rule_del_confirm?>"+name+"?")==false) return;
	f_final.f_rule_del.value = id;	
	
	if(parent.get_obj("enable_vlan").checked)
	{
		f_final.f_vlan.value= 1;
	}
	else
	{
		f_final.f_vlan.value = 0;
	}
	
	if(parent.get_obj("enable_pvid_auto").checked)
	{
		f_final.f_pvid_auto.value= 1;
	}
	else
	{
		f_final.f_pvid_auto.value= 0;
	}			
	get_obj("final_form").submit();
}

function rule_edit_confirm(id)
{
	var f_final	=get_obj("final_form");
	if(parent.get_obj("enable_vlan").checked)
	{	
		parent.location.href="adv_8021q.php?evid="+id+"&vlan_enable=1";
	}
	else
	{	
		parent.location.href="adv_8021q.php?evid="+id+"&vlan_enable=0";
	}
	
}
</script>
<body <?=$G_BODY_ATTR?> onload="init();">	
<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return false;">
	<table id="mainTable" class="TabPane_body"  width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
		<tr> 
	    	<td valign="top">
				<table width="100%" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:5px 0px 0px 0px; padding-left:3px;">
					<tr class="list_head" align="left">
						<td width="50">
							<?=$m_vid?>
						</td>
						<td width="120">
							<?=$m_name?>
						</td>
						<td width="140">
							<?=$m_uvp?>
						</td>	
						<td width="140">
							<?=$m_tvp?>
						</td>																						
						<td>
							<?=$m_edit?>&nbsp;&nbsp;<?=$m_del?>
						</td>																																																										
					</tr>	
				</table>  
				<div class="div_client_tab" >
					<table id="vlan_rule_tab" width="100%" border="0" align="left" <?=$G_TABLE_ATTR_CELL_ZERO?>style="padding-left:3px;">					
	<?
	$tmp = "";
	$index = 1;
	for("/sys/group_vlan/index")
	{	
		echo "<input type=\"hidden\" id=\"rule_vid".$index."\" name=\"rule_vid".$index."\" value=\"".query("group_vid")."\">\n";	
		echo "<input type=\"hidden\" id=\"rule_egress_eth".$index."\" name=\"rule_egress_eth".$index."\" value=\"".query("eth:1/egress")."\">\n";	
		if($check_lan != "")
		{
			echo "<input type=\"hidden\" id=\"rule_egress_eth2".$index."\" name=\"rule_egress_eth2".$index."\" value=\"".query("eth:2/egress")."\">\n";
		}
		echo "<input type=\"hidden\" id=\"rule_egress_sys".$index."\" name=\"rule_egress_sys".$index."\" value=\"".query("sys:1/egress")."\">\n";	
		$while_index = 1;
		while($while_index < 33)
		{
			echo "<input type=\"hidden\" id=\"rule_egress_ath".$index."_".$while_index."\" name=\"rule_egress_ath".$index."_".$while_index."\" value=\"".query("ath:".$while_index."/egress")."\">\n";
			$while_index++;	
		}	
		$tmp = $index%2;
		
		if($tmp == 1)
		{
			echo "<tr align=\"left\" style=\"background:#b3b3b3;\">\n";
		}
		else
		{
			echo "<tr align=\"left\" style=\"background:#cccccc;\">\n";
		}	
	
		echo "<td width=\"50\">".query("group_vid")."</td>\n";
		echo "<td width=\"120\">".$G_TAG_SCRIPT_START."genTableSSID(\"".get("j","group_name")."\",0);".$G_TAG_SCRIPT_END."</td>\n";
		echo "<td width=\"140\">".$G_TAG_SCRIPT_START."print_tag(".$index.",\"untag\");".$G_TAG_SCRIPT_END."</td>\n";
		echo "<td width=\"140\">".$G_TAG_SCRIPT_START."print_tag(".$index.",\"tag\");".$G_TAG_SCRIPT_END."</td>\n";	
		
		echo "<td width=\"30\">".$G_TAG_SCRIPT_START."print_rule_edit(".$index.");".$G_TAG_SCRIPT_END."</td>\n";
		echo "<td>".$G_TAG_SCRIPT_START."print_rule_del(".$index.");".$G_TAG_SCRIPT_END."</td>\n";	
		echo "</tr>\n";	
		$index++;
	}
	?>									  								  
					</table>
				</div>										   							
		    </td>
		</tr>
	</table>
</form>
<form name="final_form" id="final_form" method="post" action="<?=$MY_NAME?>.php"  onsubmit="return check();">
		<input type="hidden" name="ACTION_POST"			value="<?=$MY_ACTION?>">
		<input type="hidden" name="f_rule_edit"		value="">
		<input type="hidden" name="f_rule_del"		value="">
		<input type="hidden" name="f_vlan"		value="">
		<input type="hidden" name="f_pvid_auto"		value="">
</form>
</body>
</html>		
