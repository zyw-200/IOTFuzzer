<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_8021q";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_8021q";
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
$check_lan = query("/sys/2lan");
$cfg_ap_mode	=query("/wlan/inf:1/ap_mode");
$cfg_ap_mode_a  =query("/wlan/inf:2/ap_mode");
$cfg_auth = query("/wlan/inf:1/authentication");
$security_eap = "";
if($cfg_auth=="2" || $cfg_auth=="4" || $cfg_auth=="6") 
{$security_eap="1";} //eap
$cfg_auth_a = query("/wlan/inf:2/authentication");
$security_eap_a = "";
if($cfg_auth_a=="2" || $cfg_auth_a=="4" || $cfg_auth_a=="6")
{$security_eap_a="1";} //eap

$cfg_vlan = query("/sys/vlan_state");
$cfg_nap_enable = query("/sys/vlan_mode");
if($security_eap == 1 && $cfg_nap_enable == 1){$cfg_dyna = 1;}
if($security_eap_a == 1 && $cfg_nap_enable == 1){$cfg_dyna_a = 1;}
$cfg_limit_admin_status = query("/sys/adminlimit/status");
$cfg_admin_pvid = query("/sys/vlan_id");
$cfg_eth_pvid = query("/lan/ethernet/vlan_id");
$cfg_eth_pvid2 = query("/lan/ethernet:2/vlan_id");
$cfg_pri_pvid = query("/wlan/inf:1/vlan_id");
$cfg_pri_pvid_a = query("/wlan/inf:2/vlan_id");
$cfg_pvid_auto= query("/sys/auto_set_pvid");
anchor("/wlan/inf:1/multi");
$cfg_ms_1_pvid = query("index:1/vlan_id");
$cfg_ms_2_pvid = query("index:2/vlan_id");
$cfg_ms_3_pvid = query("index:3/vlan_id");
$cfg_ms_4_pvid = query("index:4/vlan_id");
$cfg_ms_5_pvid = query("index:5/vlan_id");
$cfg_ms_6_pvid = query("index:6/vlan_id");
$cfg_ms_7_pvid = query("index:7/vlan_id");
anchor("/wlan/inf:2/multi");
$cfg_ms_1_pvid_a = query("index:1/vlan_id");
$cfg_ms_2_pvid_a = query("index:2/vlan_id");
$cfg_ms_3_pvid_a = query("index:3/vlan_id");
$cfg_ms_4_pvid_a = query("index:4/vlan_id");
$cfg_ms_5_pvid_a = query("index:5/vlan_id");
$cfg_ms_6_pvid_a = query("index:6/vlan_id");
$cfg_ms_7_pvid_a = query("index:7/vlan_id");
anchor("/wlan/inf:1/wds");
$cfg_wds_1_pvid = query("index:1/vlan_id");
$cfg_wds_2_pvid = query("index:2/vlan_id");
$cfg_wds_3_pvid = query("index:3/vlan_id");
$cfg_wds_4_pvid = query("index:4/vlan_id");
$cfg_wds_5_pvid = query("index:5/vlan_id");
$cfg_wds_6_pvid = query("index:6/vlan_id");
$cfg_wds_7_pvid = query("index:7/vlan_id");
$cfg_wds_8_pvid = query("index:8/vlan_id");
anchor("/wlan/inf:2/wds");                 
$cfg_wds_1_pvid_a = query("index:1/vlan_id");
$cfg_wds_2_pvid_a = query("index:2/vlan_id");
$cfg_wds_3_pvid_a = query("index:3/vlan_id");
$cfg_wds_4_pvid_a = query("index:4/vlan_id");
$cfg_wds_5_pvid_a = query("index:5/vlan_id");
$cfg_wds_6_pvid_a = query("index:6/vlan_id");
$cfg_wds_7_pvid_a = query("index:7/vlan_id");
$cfg_wds_8_pvid_a = query("index:8/vlan_id");
$rule_list =0;
for("/sys/group_vlan/index")
{
	$rule_list++;
}
anchor("/sys/group_vlan/index:".$evid);
$e_gvid=query("group_vid");
$e_gname=get("j","group_name");
echo "-->";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>
		var num_ssid=8;
		var num_wds=9;
		var max_num=17;
var check_apmode_msg=0;
function showCursor(targetObj)
{
	if(navigator.appName =="Microsoft Internet Explorer")
		targetObj.style.cursor = "hand";
	else
    	targetObj.style.cursor = "pointer";

}

function unit_setting(id,width,val,func)
{
	var str = "";
	if(id.length != width.length)
	{
		document.write("Length Error!!!");
		return;
	}	
	str+="<td width=\""+width[0]+"\">"+id[0]+"</td>";	
	str+="<td width=\""+width[1]+"\">";
	str+="<input type=\"button\" id=\""+val+"_"+id[1]+"\" name=\""+id[1]+"\" value=\" <?=$m_b_sec_all?> \" onclick=\""+func+"\">";
	str+="</td>";
	for(var i=2; i<id.length; i++)
	{
		str+="<td width=\""+width[i]+"\">";
		if(id[i]=="")
		{
			str+="&nbsp;";
		}
		else
		{
			str+="<input type=\"radio\" id=\""+val+"_"+id[i]+"\" name=\""+id[i]+"\" value=\""+val+"\">";
			
		}
		str+="</td>";		
	}	
	document.write(str);
}

function unit_pvid_setting(id,width)
{
	var str = "";
	if(id.length != width.length)
	{
		document.write("Length Error!!!");
		return;
	}	
	
	str+="<td width=\""+width[0]+"\" align=\"left\"><?=$m_pvid?></td>";	
	for(var i=1; i<id.length; i++)
	{
		str+="<td width=\""+width[i]+"\">";	
		if(id[i]=="")
		{
			str+="&nbsp;";
		}
		else
		{
			str+="<input name=\""+id[i]+"\" id=\""+id[i]+"\" class=\"text\" type=\"text\" size=\"2\" maxlength=\"4\" value=\"\">";	
		}
		str+="</td>";		
	}	
	document.write(str);
}
function unit_set_title(obj,width)
{
	var str = "";
	if(obj.length != width.length)
	{
		document.write("Length Error!!!");
		return;
	}		
	for(var i=0; i<obj.length; i++)
	{
		str+="<td width=\""+width[i]+"\">"+obj[i]+"</td>";
	}
	document.write(str);
}

function unit_pvid_title(obj,width)
{
	var str = "";
	if(obj.length != width.length)
	{
		document.write("Length Error!!!");
		return;
	}		
	str+="<td width=\""+width[0]+"\" align=\"left\">"+obj[0]+"</td>";
	
	for(var i=1; i<obj.length; i++)
	{
		str+="<td width=\""+width[i]+"\">"+obj[i]+"</td>";
	}
	document.write(str);
}

function on_change_vlan()
{
	var f = get_obj("frm");
	var f_final	=get_obj("final_form");
	
	if(f.vlan[0].checked || ("<?=$cfg_nap_enable?>" == 1 && "<?=$security_eap?>" == 1) || ("<?=$cfg_nap_enable?>" == 1 && "<?=$security_eap_a?>" == 1))
	{
		fields_disabled(f, true);
        f.vlan[0].disabled=false;
        f.vlan[1].disabled=false;
	}
	else
	{
		if("<?=$cfg_limit_admin_status?>"==1 || "<?=$cfg_limit_admin_status?>"==3)
		{
			alert("<?=$a_enable_vlan?>");	
		}	
		if("<?=$cfg_ap_mode?>" =="1")
		{
			alert("<?=$a_set_to_ap?>");
		}
		if("<?=$cfg_ap_mode_a?>" =="1")
        {
            alert("<?=$a_set_to_ap_a?>");
        }
        fields_disabled(f, false);  
        on_change_pvid_auto();
	}

	if("<?=$vlan_enable?>"!="")
	{
		f.vid.disabled = true;
	}
	
	f.sec_ssid_untag_all[1].disabled = true;
	f.sec_ssid_untag_all[4].disabled = true;
	f.sec_p1[1].disabled = true;
	f.sec_p2[1].disabled = true;
	
	for(var i=1;i<num_ssid;i++)
	{
		get_obj("1_sec_m"+i).disabled=true;
	}	
	
	for(var i=17;i<24;i++)
	{
		get_obj("1_sec_m"+i).disabled=true;
	}	
	
}	

function on_change_pvid_auto()
{
	var f=get_obj("frm");
	if(f.pvid_auto[0].checked)
	{
		f.admin_pvid.disabled=false;
		f.eth_pvid.disabled=false;
		if("<?=$check_lan?>" != "")
			f.eth_pvid2.disabled=false;
		f.pri_pvid.disabled=false;
		f.pri_pvid_a.disabled=false;
		for(var i=1;i<num_ssid;i++)
		{
			get_obj("ms_"+i+"_pvid").disabled=false;
			get_obj("ms_"+i+"_pvid_a").disabled=false;
		}
		for(var j=1;j<num_wds;j++)
		{
			get_obj("wds_"+j+"_pvid").disabled=false;
			get_obj("wds_"+j+"_pvid_a").disabled=false;
		}		
	}
	else
	{
		f.admin_pvid.disabled=true;
		f.eth_pvid.disabled=true;
		if("<?=$check_lan?>" != "")
			f.eth_pvid2.disabled=true;
		f.pri_pvid.disabled=true;
		f.pri_pvid_a.disabled=true;
		for(var m=1;m<num_ssid;m++)
		{
			get_obj("ms_"+m+"_pvid").disabled=true;
			get_obj("ms_"+m+"_pvid_a").disabled=true;
		}
		for(var n=1;n<num_wds;n++)
		{
			get_obj("wds_"+n+"_pvid").disabled=true;
			get_obj("wds_"+n+"_pvid_a").disabled=true;
		}			
	}
}

function do_sec_all(id , name)
{
	var f=get_obj("frm");
	
	if(name == "port")
	{
		f.sec_admin[id].checked = true;
		f.sec_eth[id].checked = true;
		if("<?=$check_lan?>" != "")	
			f.sec_eth2[id].checked = true;
	}
	else if(name == "ms_port")
	{
		f.sec_p1[id].checked = true;
		for(var i=1; i<num_ssid; i++)
		{
			get_obj(id+"_sec_m"+i).checked = true;
		}
	}
	else if(name == "wds_port")
	{
		for(var j=1; j<num_wds; j++)
		{
			get_obj(id+"_sec_w"+j).checked = true;
		}		
	}
}
//ssssssssssssssss
function do_sec_all_a(id , name)
{
	var f=get_obj("frm");
	
	if(name == "port")
	{
		f.sec_admin[id].checked = true;
		f.sec_eth[id].checked = true;	
		if("<?=$check_lan?>" != "")
			f.sec_eth2[id].checked = true;
	}
	else if(name == "ms_port")
	{
		f.sec_p2[id].checked = true;
		for(var i=17; i<24; i++)
		{
			get_obj(id+"_sec_m"+i).checked = true;
		}
	}
	else if(name == "wds_port")
	{
		for(var j=24; j<32; j++)
		{
			get_obj(id+"_sec_w"+j).checked = true;
		}		
	}
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
	
	if(confirm("<?=$a_rule_del_confirm?>"+name+"?")==false) return;
	f_final.f_rule_del.value = id;	
	
	if(f.vlan[1].checked)
	{
		f_final.f_vlan.value = 1;
	}
	else
	{
		f_final.f_vlan.value = 0;
	}
	
	if(f.pvid_auto[1].checked)
	{
		f_final.f_pvid_auto.value = 1;
	}
	else
	{
		f_final.f_pvid_auto.value = 0;
	}			
	get_obj("final_form").submit();
}

function rule_edit_confirm(id)
{
	var f_final	=get_obj("final_form");
	f_final.f_rule_edit.value = id;
	init();
}

function print_tag(index, flag)
{
	var f=get_obj("frm")	
	var str="", tag_untag = 1;
	
	if(flag == "untag")
	{
		tag_untag = 2;
	}

	if(get_obj("rule_egress_sys"+index).value == tag_untag)
	{
		str+="<?=$m_admin?>, ";
	}
	
	if("<?=$check_lan?>" != "")
	{
		if(get_obj("rule_egress_eth2"+index).value == tag_untag)
		{
			str+="<?=$m_lan1?>, ";
    		}
		if(get_obj("rule_egress_eth"+index).value == tag_untag)
		{
			str+="<?=$m_lan2?>, ";
		}
	}
	else
	{
		if(get_obj("rule_egress_eth"+index).value == tag_untag)
		{
			str+="<?=$m_lan1?>, ";
		}
	}
	for(var i=1; i<max_num; i++)
	{
		if(i==1)
		{
			if(get_obj("rule_egress_ath"+index+"_"+i).value == tag_untag)
			{
				str+="<?=$m_p1?>, ";
			}			
		}
		if(i>1 && i<num_wds)
		{
			if(get_obj("rule_egress_ath"+index+"_"+i).value == tag_untag)
			{
				str+="<?=$m_m?>"+(i-1)+", ";
			}				
		}
		if(i>=num_wds)
		{
			if(get_obj("rule_egress_ath"+index+"_"+i).value == tag_untag)
			{
				str+="<?=$m_w?>"+(i-num_ssid)+", ";
			}				
		}		
	}	

	str = get_tag_untag(str);
	document.write(str);	
}


function print_port_list_debug(port_name,flag,num)
{
	var f=get_obj("frm")	
	var str="";	
	if(flag == "tag" && num != 0)
	{		
		for (var i=0; i<=num-1; i++)
		{			
			str+= get_obj(port_name+"_port_tag"+(i)).value+", ";
		}	
	}
	else if(flag == "untag" && num != 0)
	{
		for (var j=0; j<=num-1; j++)
		{		
			 str+= get_obj(port_name+"_port_untag"+(j)).value+", ";			
		}	
	}	
	str = get_tag_untag(str);	
	document.write(str);		
}

function print_port_list(port_name,flag,num)
{
	var f=get_obj("frm")	
	var str=""; 
	if(flag == "tag" && num != 0)
	{
		for (var i=1; i<=num; i++)
		{
			str+= get_obj(port_name+"_port_tag"+i).value+", ";
		}	
	}
	else if(flag == "untag" && num != 0)
	{
		for (var j=1; j<=num; j++)
		{
			str+= get_obj(port_name+"_port_untag"+j).value+", ";
			
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

/* page init functoin */
function init()
{
	var f=get_obj("frm");
	var f_final	=get_obj("final_form");
	if("<?=$vlan_enable?>"!="")
	{
			f.vlan[1].checked = true;
	}
	else
	{
	if("<?=$cfg_vlan?>" == 0)
		f.vlan[0].checked = true;
	else
		f.vlan[1].checked = true;	
	}
	
	do_sec_all("0","port");
	do_sec_all("0","ms_port");
	do_sec_all("0","wds_port");
	do_sec_all_a("0","ms_port");
	do_sec_all_a("0","wds_port");

	if("<?=$evid?>"!= "")
	{
		f_final.f_rule_edit.value="<?=$evid?>";
		f.vlan[1].focus();
		
		f.vid.value="<?=$e_gvid?>";
		f.ssid.value="<?=$e_gname?>";		
		if(get_obj("rule_egress_sys"+"<?=$evid?>").value == 2)
		{
			f.sec_admin[0].checked =  true;
		}	
		else if(get_obj("rule_egress_sys"+"<?=$evid?>").value == 1)
		{
			f.sec_admin[1].checked =  true;
		}	
		else
		{
			f.sec_admin[2].checked =  true;
		}
	
		if(get_obj("rule_egress_eth"+"<?=$evid?>").value == 2)
		{
			f.sec_eth[0].checked =  true;
		}	
		else if(get_obj("rule_egress_eth"+"<?=$evid?>").value == 1)
		{
			f.sec_eth[1].checked =  true;
		}	
		else
		{
			f.sec_eth[2].checked =  true;
		}
		if("<?=$check_lan?>" != "")
		{	
			if(get_obj("rule_egress_eth2"+"<?=$evid?>").value == 2)
		        {
		            f.sec_eth2[0].checked =  true;
		        }
		        else if(get_obj("rule_egress_eth2"+"<?=$evid?>").value == 1)
		        {
		            f.sec_eth2[1].checked =  true;
		        }
		        else
		        {
		            f.sec_eth2[2].checked =  true;
		        }
		}
		
		var temp=0;	
		for(var i=1; i< 17; i++)
		{
			if(get_obj("rule_egress_ath"+"<?=$evid?>"+"_"+i).value == 2)
			{
				if(i==1) //primary
				{
					f.sec_p1[0].checked =  true;	
				}
				
				if(i==17) //primary
				{
					f.sec_p2[0].checked =  true;	
				}
				
				if(i>1 && i<9)
				{
					get_obj("0_sec_m"+(i-1)).checked =  true;
				}
				if(i>=9)
				{
					get_obj("0_sec_w"+(i-8)).checked =  true;
				}				
				
			}	
			else if(get_obj("rule_egress_ath"+"<?=$evid?>"+"_"+i).value == 1)
			{
				if(i==1) //primary
				{
					f.sec_p1[1].checked =  true;	
				}
				
				if(i>1 && i<9)
				{
					get_obj("1_sec_m"+(i-1)).checked =  true;
				}
				if(i>=9)
				{
					get_obj("1_sec_w"+(i-8)).checked =  true;
				}				
				
			}	
			else
			{
				if(i==1) //primary
				{
					f.sec_p1[2].checked =  true;	
				}
				
				if(i>1 && i<9)
				{
					get_obj("2_sec_m"+(i-1)).checked =  true;
				}
				if(i>=9)
				{
					get_obj("2_sec_w"+(i-8)).checked =  true;
				}				
				
			}			
		}
		
			for(var i=17; i< 33; i++)
		{
			
			if(get_obj("rule_egress_ath"+"<?=$evid?>"+"_"+i).value == 2)
			{
				
				if(i==17) //primary
				{
					f.sec_p2[0].checked =  true;	
				}
				
				if(i>17 && i<25)
				{
					get_obj("0_sec_m"+(i-1)).checked =  true;
				}
				if(i>=25)
				{
					get_obj("0_sec_w"+(i-1)).checked =  true;
				}				
				
			}	
			else if(get_obj("rule_egress_ath"+"<?=$evid?>"+"_"+i).value == 1)
			{
				if(i==17) //primary
				{
					f.sec_p2[1].checked =  true;	
				}
				
				if(i>17 && i<25)
				{
					get_obj("1_sec_m"+(i-1)).checked =  true;
				}
				if(i>=25)
				{
					get_obj("1_sec_w"+(i-1)).checked =  true;
				}				
				
			}	
			else
			{
				if(i==17) //primary
				{
					f.sec_p2[2].checked =  true;	
				}
				
				if(i>17 && i<25)
				{
					get_obj("2_sec_m"+(i-1)).checked =  true;
				}
				if(i>=25)
				{
					get_obj("2_sec_w"+(i-1)).checked =  true;
				}				
				
			}			
			
		}
	}
		
	if("<?=$cfg_nap_enable?>" == 1 && "<?=$security_eap?>" == 1) 
	{
		get_obj("vlan_mode").innerHTML = "<?=$m_dynamic?>";		
	}
	if("<?=$cfg_nap_enable?>" == 1 && "<?=$security_eap_a?>" == 1)
    {
        get_obj("vlan_mode_a").innerHTML = "<?=$m_dynamic?>";
    }
	
	if("<?=$cfg_pvid_auto?>" == 0)
		f.pvid_auto[0].checked = true;
	else
		f.pvid_auto[1].checked = true;	
			
	on_change_vlan();

	f.admin_pvid.value = "<?=$cfg_admin_pvid?>";
	f.eth_pvid.value = "<?=$cfg_eth_pvid?>";
	if("<?=$check_lan?>" != "")
		f.eth_pvid2.value = "<?=$cfg_eth_pvid2?>";
	f.pri_pvid.value = "<?=$cfg_pri_pvid?>";

	f.ms_1_pvid.value = "<?=$cfg_ms_1_pvid?>";	
	f.ms_2_pvid.value = "<?=$cfg_ms_2_pvid?>";	
	f.ms_3_pvid.value = "<?=$cfg_ms_3_pvid?>";	
	f.ms_4_pvid.value = "<?=$cfg_ms_4_pvid?>";	
	f.ms_5_pvid.value = "<?=$cfg_ms_5_pvid?>";	
	f.ms_6_pvid.value = "<?=$cfg_ms_6_pvid?>";	
	f.ms_7_pvid.value = "<?=$cfg_ms_7_pvid?>";	
	/*add for another band*/	
	 f.pri_pvid_a.value = "<?=$cfg_pri_pvid_a?>";
	f.ms_1_pvid_a.value = "<?=$cfg_ms_1_pvid_a?>";	
	f.ms_2_pvid_a.value = "<?=$cfg_ms_2_pvid_a?>";	
	f.ms_3_pvid_a.value = "<?=$cfg_ms_3_pvid_a?>";	
	f.ms_4_pvid_a.value = "<?=$cfg_ms_4_pvid_a?>";	
	f.ms_5_pvid_a.value = "<?=$cfg_ms_5_pvid_a?>";	
	f.ms_6_pvid_a.value = "<?=$cfg_ms_6_pvid_a?>";	
	f.ms_7_pvid_a.value = "<?=$cfg_ms_7_pvid_a?>";	
	/*end for another band*/
	f.wds_1_pvid.value = "<?=$cfg_wds_1_pvid?>";
	f.wds_2_pvid.value = "<?=$cfg_wds_2_pvid?>";
	f.wds_3_pvid.value = "<?=$cfg_wds_3_pvid?>";
	f.wds_4_pvid.value = "<?=$cfg_wds_4_pvid?>";
	f.wds_5_pvid.value = "<?=$cfg_wds_5_pvid?>";
	f.wds_6_pvid.value = "<?=$cfg_wds_6_pvid?>";
	f.wds_7_pvid.value = "<?=$cfg_wds_7_pvid?>";
	f.wds_8_pvid.value = "<?=$cfg_wds_8_pvid?>";	
	f.wds_1_pvid_a.value = "<?=$cfg_wds_1_pvid_a?>";	
	f.wds_2_pvid_a.value = "<?=$cfg_wds_2_pvid_a?>";
	f.wds_3_pvid_a.value = "<?=$cfg_wds_3_pvid_a?>";
	f.wds_4_pvid_a.value = "<?=$cfg_wds_4_pvid_a?>";
	f.wds_5_pvid_a.value = "<?=$cfg_wds_5_pvid_a?>";
	f.wds_6_pvid_a.value = "<?=$cfg_wds_6_pvid_a?>";
	f.wds_7_pvid_a.value = "<?=$cfg_wds_7_pvid_a?>";
	f.wds_8_pvid_a.value = "<?=$cfg_wds_8_pvid_a?>";	
/*********************  Page Height  *******************/

		var page_height = 630,page_height_1 = 50,page_height_0 = 0;
		
		sec_plan(1);	
		page_height_1 = get_obj("table_frame").offsetHeight;
		sec_plan(0);
		page_height_0 = get_obj("table_frame").offsetHeight;
	
		if((page_height_1 > page_height_0) && (page_height_1 > page_height))
		{
			parent.ifrMain.height = page_height_1+20;	
		}
		else if ((page_height_0 > page_height_1) && (page_height_0 > page_height))
		{
			parent.ifrMain.height = page_height_0;	
		}
		else
		{
			parent.ifrMain.height = page_height;	
		}
		if("<?=$evid?>"!="")
		{	
			sec_plan(2);
	}
	AdjustHeight();
}


var vid_list=[['index','vid']<?
for("/sys/group_vlan/index")
{
	echo ",\n['".$@."',";
	echo "'".query("/sys/group_vlan/index:".$@."/group_vid")."']";
}
?>];
/* parameter checking */
function check(s)
{
	// do check here ....
	var f = get_obj("frm");
	var f_final	=get_obj("final_form");
	
	if(f.vlan[1].checked)
	{
		if(("<?=$cfg_dyna?>" == 1 && "<?=$cfg_dyna_a?>" != 1) || ("<?=$cfg_dyna?>" != 1 && "<?=$cfg_dyna_a?>" == 1))
        {
            alert("<?=$a_invalid_disable?>");
			f.vlan[0].checked= true;
            return false;
        }
		f_final.f_vlan.value = 1;
	}
	else
	{
		f_final.f_vlan.value = 0;
	}

	if(f.pvid_auto[1].checked)
	{
		f_final.f_pvid_auto.value = 1;
	}
	else
	{
		f_final.f_pvid_auto.value = 0;
	}	
	
	if(f.vlan[1].checked)
	{	
		if(s == "rule_add")
		{
			if("<?=$evid?>"== "")
			{
				for(var v_l=1; v_l<vid_list.length; v_l++)
				{
					if(f.vid.value == vid_list[v_l][1])
					{
						alert("<?=$a_same_rule_vid?>");
						return false;	
					}
				}	
					
				if(f_final.f_rule_edit!="" &&"<?=$rule_list?>"==15)
				{
					alert("<?=$a_max_rule_list?>");
					return false;			
				}			
				
				if(!is_in_range(f.vid.value,1,4094))
				{
					alert("<?=$a_invalid_vid?>");
					if(f.vid.value=="") f.vid.value=1;
					field_select(f.vid);
					return false;
				}	
			}
			
			f_final.f_vid.value = f.vid.value;
			
			if(is_blank(f.ssid.value))
			{
				alert("<?=$a_empty_ssid?>");
				f.ssid.focus();
				return false;
			}		
				
			if(first_blank(f.ssid.value))
			{
				alert("<?=$a_first_blank_ssid?>");
				f.ssid.select();
				return false;
			}
				
			if(strchk_unicode(f.ssid.value))
			{
				alert("<?=$a_invalid_ssid?>");
				f.ssid.select();
				return false;
			}
			
			f_final.f_ssid.value = f.ssid.value;

			if(f.sec_admin[2].checked) //not member
			{
				f_final.f_sys_egress.value = 0;
			}
			else if(f.sec_admin[1].checked) //tag
			{
				f_final.f_sys_egress.value = 1;
			}
			else //untag
			{
				f_final.f_sys_egress.value = 2;
			}
			
			if(f.sec_eth[2].checked) //not member
			{
				f_final.f_eth_egress.value = 0;
			}
			else if(f.sec_eth[1].checked) //tag
			{
				f_final.f_eth_egress.value = 1;
			}
			else //untag
			{
				f_final.f_eth_egress.value = 2;
			}
			if("<?=$check_lan?>" != "")
				{
				if(f.sec_eth2[2].checked) //not member
				{
					f_final.f_eth_egress2.value = 0;
				}
				else if(f.sec_eth2[1].checked) //tag
				{
					f_final.f_eth_egress2.value = 1;
				}
				else
				{
					f_final.f_eth_egress2.value = 2;
				}
			}
			if(f.sec_p1[2].checked) //not member
			{
				f_final.f_ath1_egress.value = 0;
			}
			else if(f.sec_p1[1].checked) //tag
			{
				f_final.f_ath1_egress.value = 1;
			}
			else //untag
			{
				f_final.f_ath1_egress.value = 2;
			}	
			
//add for 11a's mssid port

if(f.sec_p2[2].checked) //not member
			{
				f_final.f_ath17_egress.value = 0;
			}
			else if(f.sec_p2[1].checked) //tag
			{
				f_final.f_ath17_egress.value = 1;
			}
			else //untag
			{
				f_final.f_ath17_egress.value = 2;
			}		
							
			
			for(var m=1; m<num_ssid; m++)	
			{
				if(get_obj("2_sec_m"+m).checked) //not member
				{
					var str = "";
					str += "f_ath";
					str += (m+1);
					str += "_egress";
					get_obj(str).value = 0;
				}
				else if(get_obj("1_sec_m"+m).checked) //tag
				{
					get_obj("f_ath"+(m+1)+"_egress").value = 1;
				}
				else//untag
				{
					get_obj("f_ath"+(m+1)+"_egress").value = 2;
				}					
			}		
		//add for 11a's mssid port	
			for(var m=17; m<24; m++)	
			{
				if(get_obj("2_sec_m"+m).checked) //not member
				{
					var str = "";
					str += "f_ath";
					str += (m+1);
					str += "_egress";
					get_obj(str).value = 0;
				}
				else if(get_obj("1_sec_m"+m).checked) //tag
				{
					get_obj("f_ath"+(m+1)+"_egress").value = 1;
				}
				else//untag
				{
					get_obj("f_ath"+(m+1)+"_egress").value = 2;
				}					
			}		
			
			for(var n=1; n<num_wds; n++)	
			{
				if(get_obj("2_sec_w"+n).checked) //not member
				{
					get_obj("f_ath"+(n+8)+"_egress").value = 0;
				}
				else if(get_obj("1_sec_w"+n).checked) //tag
				{
					get_obj("f_ath"+(n+8)+"_egress").value = 1;
				}
				else //untag
				{
					get_obj("f_ath"+(n+8)+"_egress").value = 2;
				}			
			}		
			
			for(var n=24; n<32; n++)	
			{
				if(get_obj("2_sec_w"+n).checked) //not member
				{
					get_obj("f_ath"+(n+1)+"_egress").value = 0;
				}
				else if(get_obj("1_sec_w"+n).checked) //tag
				{
					get_obj("f_ath"+(n+1)+"_egress").value = 1;
				}
				else //untag
				{
					get_obj("f_ath"+(n+1)+"_egress").value = 2;
				}			
			}		
			
			f_final.f_apply.value = "rule_add";
		}	

		if(s == "rule")
		{	
			f_final.f_apply.value = "rule";
		}
		
		if(s == "pvid")
		{
			if(f.pvid_auto[0].checked)
			{
				if(!is_in_range(f.admin_pvid.value,1,4094))
				{
					alert("<?=$a_invalid_pvid?>");
					if(f.admin_pvid.value=="") f.admin_pvid.value=1;
					field_select(f.admin_pvid);
					return false;
				}			
				
				f_final.f_admin_pvid.value = f.admin_pvid.value;
				
				if(!is_in_range(f.eth_pvid.value,1,4094))
				{
					alert("<?=$a_invalid_pvid?>");
					if(f.eth_pvid.value=="") f.eth_pvid.value=1;
					field_select(f.eth_pvid);
					return false;
				}	
				
				f_final.f_eth_pvid.value = f.eth_pvid.value;
				if("<?=$check_lan?>" != "")
				{
					if(!is_in_range(f.eth_pvid2.value,1,4094))
					{
						alert("<?=$a_invalid_pvid?>");
						if(f.eth_pvid2.value=="") f.eth_pvid2.value=1;
						field_select(f.eth_pvid2);
						return false;
					}
					f_final.f_eth_pvid2.value = f.eth_pvid2.value;
				}
				if(!is_in_range(f.pri_pvid.value,1,4094))
				{
					alert("<?=$a_invalid_pvid?>");
					if(f.pri_pvid.value=="") f.pri_pvid.value=1;
					field_select(f.pri_pvid);
					return false;
				}					
			
				if(!is_in_range(f.pri_pvid_a.value,1,4094))
				{
					alert("<?=$a_invalid_vid?>");
					if(f.pri_pvid_a.value=="") f.pri_pvid_a.value=1;
					field_select(f.pri_pvid_a);
					return false;
				}					
			
				f_final.f_pri_pvid.value = f.pri_pvid.value;
				f_final.f_pri_pvid_a.value = f.pri_pvid_a.value;    
				
				for(var i=1; i<num_ssid; i++)				
				{
					if(!is_in_range(get_obj("ms_"+i+"_pvid").value,1,4094))
					{
						alert("<?=$a_invalid_pvid?>");
						if(get_obj("ms_"+i+"_pvid").value=="") get_obj("ms_"+i+"_pvid").value=1;
						field_select(get_obj("ms_"+i+"_pvid"));
						return false;
					}	
					get_obj("f_ms_"+i+"_pvid").value = get_obj("ms_"+i+"_pvid").value;
				}
				
				for(var i=1; i<num_ssid; i++)				
				{
					if(!is_in_range(get_obj("ms_"+i+"_pvid_a").value,1,4094))
					{
						alert("<?=$a_invalid_pvid?>");
						if(get_obj("ms_"+i+"_pvid_a").value=="") get_obj("ms_"+i+"_pvid_a").value=1;
						field_select(get_obj("ms_"+i+"_pvid_a"));
						return false;
					}	
					get_obj("f_ms_"+i+"_pvid_a").value = get_obj("ms_"+i+"_pvid_a").value;
				}
				for(var j=1; j<num_wds; j++)				
				{
					if(!is_in_range(get_obj("wds_"+j+"_pvid").value,1,4094))
					{
						alert("<?=$a_invalid_pvid?>");
						if(get_obj("wds_"+j+"_pvid").value=="") get_obj("wds_"+j+"_pvid").value=1;
						field_select(get_obj("wds_"+j+"_pvid"));
						return false;
					}	
					get_obj("f_wds_"+j+"_pvid").value = get_obj("wds_"+j+"_pvid").value;					
				}	
				for(var j=1; j<num_wds; j++)				
				{
					if(!is_in_range(get_obj("wds_"+j+"_pvid_a").value,1,4094))
					{					
						alert("<?=$a_invalid_pvid?>");
						if(get_obj("wds_"+j+"_pvid_a").value=="") get_obj("wds_"+j+"_pvid_a").value=1;
						field_select(get_obj("wds_"+j+"_pvid_a"));
						return false;
					}	
					get_obj("f_wds_"+j+"_pvid_a").value = get_obj("wds_"+j+"_pvid_a").value;					
				}	
			}			
			f_final.f_apply.value = "pvid";
		}	
	}	
	return true;
}

function submit(s)
{
	var f_final	=get_obj("final_form");
	
	if(check(s)) 
	{	 
		f_final.submit();
	}	
}

function sec_plan(n)
{
	var f = get_obj("frm");
	var sec_Table = new Array();
	var t_Bodies = new Array();

	for(var i = 0; i < 4 ; i++)
	{
		sec_Table[i] = get_obj("sec_"+(parseInt(i, [10])+1));
		sec_Table[i].className="sec_n";
	}

	sec_Table[n].className="sec_s";

	for(var j = 0; j < 4 ; j++)
	{
		t_Bodies[j] = get_obj("tbody_"+(parseInt(j, [10])+1));
		t_Bodies[j].style.display="none";

	}  
	t_Bodies[n].style.display="";	
	AdjustHeight();
}

var vlan_port_name_list=['S-1(2.4G)','S-2(2.4G)','S-3(2.4G)','S-4(2.4G)','S-5(2.4G)','S-6(2.4G)','S-7(2.4G)',
				'W-1(2.4G)','W-2(2.4G)','W-3(2.4G)','W-4(2.4G)','W-5(2.4G)','W-6(2.4G)','W-7(2.4G)','W-8(2.4G)',
				'S-1(5G)','S-2(5G)','S-3(5G)','S-4(5G)','S-5(5G)','S-6(5G)','S-7(5G)',
				'W-1(5G)','W-2(5G)','W-3(5G)','W-4(5G)','W-5(5G)','W-6(5G)','W-7(5G)','W-8(5G)'];

var vlan_port_list=[['index','name','vid','sys','eth0','eth1','ath1','ath17',
				'ath2','ath3','ath4','ath5','ath6','ath7','ath8',
				'ath9','ath10','ath11','ath12','ath13','ath14','ath15','ath16',
				'ath18','ath19','ath20','ath21','ath22','ath23','ath24',
				'ath25','ath26','ath27','ath28','ath29','ath30','ath31','ath32']				
<?
$list_count=0;
for("/sys/group_vlan/index")
{
	echo ",\n['".$@."','".get("j","group_name")."','".query("group_vid")."','".query("sys:1/egress").
	"','".query("eth:1/egress")."','".query("eth:2/egress")."','".query("ath:1/egress")."','".query("ath:17/egress").
	"','".query("ath:2/egress")."','".query("ath:3/egress")."','".query("ath:4/egress")."','".query("ath:5/egress").
	"','".query("ath:6/egress")."','".query("ath:7/egress")."','".query("ath:8/egress")."','".query("ath:9/egress").
	"','".query("ath:10/egress")."','".query("ath:11/egress")."','".query("ath:12/egress")."','".query("ath:13/egress").
	"','".query("ath:14/egress")."','".query("ath:15/egress")."','".query("ath:16/egress")."','".query("ath:18/egress").
	"','".query("ath:19/egress")."','".query("ath:20/egress")."','".query("ath:21/egress")."','".query("ath:22/egress").
	"','".query("ath:23/egress")."','".query("ath:24/egress")."','".query("ath:25/egress")."','".query("ath:26/egress").
	"','".query("ath:27/egress")."','".query("ath:28/egress")."','".query("ath:29/egress")."','".query("ath:30/egress").
	"','".query("ath:31/egress")."','".query("ath:32/egress")."']";

	$list_count++;
}
?>];
var vlan_list_count = "<?=$list_count?>";
var sys_vlan_id = "<?=$cfg_admin_pvid?>";
var eth1_vlan_id = "<?=$cfg_eth_pvid?>";
var eth2_vlan_id = "<?=$cfg_eth_pvid2?>";
var pri_vlan_id = "<?=$cfg_pri_pvid?>";
var pri2_vlan_id = "<?=$cfg_pri_pvid_a?>";
var multi_wds_vlan_id = ["<?=$cfg_ms_1_pvid?>","<?=$cfg_ms_2_pvid?>","<?=$cfg_ms_3_pvid?>","<?=$cfg_ms_4_pvid?>",
						 "<?=$cfg_ms_5_pvid?>","<?=$cfg_ms_6_pvid?>","<?=$cfg_ms_7_pvid?>",
						 "<?=$cfg_wds_1_pvid?>","<?=$cfg_wds_2_pvid?>","<?=$cfg_wds_3_pvid?>","<?=$cfg_wds_4_pvid?>",
						 "<?=$cfg_wds_5_pvid?>","<?=$cfg_wds_6_pvid?>","<?=$cfg_wds_7_pvid?>","<?=$cfg_wds_8_pvid?>",
						 "<?=$cfg_ms_1_pvid_a?>","<?=$cfg_ms_2_pvid_a?>","<?=$cfg_ms_3_pvid_a?>","<?=$cfg_ms_4_pvid_a?>",
						 "<?=$cfg_ms_5_pvid_a?>","<?=$cfg_ms_6_pvid_a?>","<?=$cfg_ms_7_pvid_a?>",
						 "<?=$cfg_wds_1_pvid_a?>","<?=$cfg_wds_2_pvid_a?>","<?=$cfg_wds_3_pvid_a?>","<?=$cfg_wds_4_pvid_a?>",
						 "<?=$cfg_wds_5_pvid_a?>","<?=$cfg_wds_6_pvid_a?>","<?=$cfg_wds_7_pvid_a?>","<?=$cfg_wds_8_pvid_a?>"];

function vlan_port_table()
{
/*-----------------------------------------sys start------------------------------------------------------------------*/
	var str="";
	var admin_port_tag_count = 0;
	var admin_port_untag_count = 0;
	for(var i=1; i<=vlan_list_count; i++)
	{	
		if(1  ==  vlan_port_list[i][3])//("sys:1/egress")
		{				
			str+="<input type='hidden' id='admin_port_tag"+admin_port_tag_count+"' name='admin_port_tag"+admin_port_tag_count+"' value='"+vlan_port_list[i][2]+"'>\n";  //(group_vid)
			admin_port_tag_count++;
		}
	}
	document.write(str);
	str="";
	for(var i=1; i<=vlan_list_count; i++)
	{	
		if(2  ==  vlan_port_list[i][3])//("sys:1/egress")
		{				
			str+="<input type='hidden' id='admin_port_untag"+admin_port_untag_count+"' name='admin_port_untag"+admin_port_untag_count+"' value='"+vlan_port_list[i][2]+"'>\n";  //(group_vid)
			admin_port_untag_count++;
		}
	}	
	document.write(str);	
	str="<tr align='left' style='background:#b3b3b3;'>\n";
	str+="<td width='100'>";
	document.write(str);	
	genTableSSID("<?=$m_admin?>",0);
	str="</td>\n";
	str+="<td width='140'>";
	document.write(str);
	print_port_list_debug("admin","tag",admin_port_tag_count);
	str="</td>\n";
	str+="<td width='140'>";
	document.write(str);	
	print_port_list_debug("admin","untag",admin_port_untag_count);
	str="</td>\n";
	str+="<td width='60'>&nbsp;&nbsp;"+sys_vlan_id+"</td>\n</tr>\n";
	document.write(str);
	str="";		
/*-----------------------------------------sys end------------------------------------------------------------------*/
/*-----------------------------------------eth start------------------------------------------------------------------*/
	var eth_port_tag_count = 0;
	var eth_port_untag_count = 0;
	for(var i=1; i<=vlan_list_count; i++)
	{	
		if(1  ==  vlan_port_list[i][4])//("eth:1/egress")
		{				
			str+="<input type='hidden' id='eth_port_tag"+eth_port_tag_count+"' name='eth_port_tag"+eth_port_tag_count+"' value='"+vlan_port_list[i][2]+"'>\n";  //(group_vid)
			eth_port_tag_count++;
		}
	}
	document.write(str);	
	str="";
	for(var i=1; i<=vlan_list_count; i++)
	{	
		if(2  ==  vlan_port_list[i][4])//("eth:1/egress")
		{				
			str+="<input type='hidden' id='eth_port_untag"+eth_port_untag_count+"' name='eth_port_untag"+eth_port_untag_count+"' value='"+vlan_port_list[i][2]+"'>\n";  //(group_vid)
			eth_port_untag_count++;
		}
	}	
	//alert(str);
	document.write(str);	
	str="<tr align='left' style='background:#cccccc;'>\n";			
	str+="<td width='100'>";
	document.write(str);
	if("<?=$check_lan?>" != "")
		genTableSSID("<?=$m_lan1?>",0);	
	else
		genTableSSID("<?=$m_eth?>",0);	
	str="</td>\n";
	str+="<td width='140'>";
	document.write(str);
	print_port_list_debug("eth","tag",eth_port_tag_count);
	str="</td>\n";
	str+="<td width='140'>";
	document.write(str);	
	print_port_list_debug("eth","untag",eth_port_untag_count);
	str="</td>\n";
	str+="<td width='60'>&nbsp;&nbsp;"+eth1_vlan_id+"</td>\n</tr>\n";
	document.write(str);
	str="";	
/*-----------------------------------------eth end------------------------------------------------------------------*/		
/*-----------------------------------------eth2 start------------------------------------------------------------------*/
	if("<?=$check_lan?>" != "")
	{
		var eth2_port_tag_count = 0;
		var eth2_port_untag_count = 0;
		for(var i=1; i<=vlan_list_count; i++)
		{	
			if(1  ==  vlan_port_list[i][5])//("eth:2/egress")
			{				
				str+="<input type='hidden' id='eth2_port_tag"+eth2_port_tag_count+"' name='eth2_port_tag"+eth2_port_tag_count+"' value='"+vlan_port_list[i][2]+"'>\n";  //(group_vid)
				eth2_port_tag_count++;
			}
		}
		document.write(str);	
		str="";
		for(var i=1; i<=vlan_list_count; i++)
		{	
			if(2  ==  vlan_port_list[i][5])//("eth:2/egress")
			{				
				str+="<input type='hidden' id='eth2_port_untag"+eth2_port_untag_count+"' name='eth2_port_untag"+eth2_port_untag_count+"' value='"+vlan_port_list[i][2]+"'>\n";  //(group_vid)
				eth2_port_untag_count++;
			}
		}	
		//alert(str);
		document.write(str);	
		str="<tr align='left' style='background:#b3b3b3;'>\n";			
		str+="<td width='100'>";
		document.write(str);
		genTableSSID("<?=$m_lan2?>",0);	
		str="</td>\n";
		str+="<td width='140'>";
		document.write(str);
		print_port_list_debug("eth2","tag",eth2_port_tag_count);
		str="</td>\n";
		str+="<td width='140'>";
		document.write(str);	
		print_port_list_debug("eth2","untag",eth2_port_untag_count);
		str="</td>\n";
		str+="<td width='60'>&nbsp;&nbsp;"+eth2_vlan_id+"</td>\n</tr>\n";
		document.write(str);
		str="";
	}	
/*-----------------------------------------eth2 end------------------------------------------------------------------*/	
/*-----------------------------------------pri 2G start------------------------------------------------------------------*/
	var pri_port_tag_count = 0;
	var pri_port_untag_count = 0;
	for(var i=1; i<=vlan_list_count; i++)
	{	
		if(1  ==  vlan_port_list[i][6])//("ath:1/egress")
		{				
			str+="<input type='hidden' id='pri_port_tag"+pri_port_tag_count+"' name='pri_port_tag"+pri_port_tag_count+"' value='"+vlan_port_list[i][2]+"'>\n";  //(group_vid)
			pri_port_tag_count++;
		}
	}
	document.write(str);	
	str="";
	for(var i=1; i<=vlan_list_count; i++)
	{	
		if(2  ==  vlan_port_list[i][6])//("ath:1/egress")
		{				
			str+="<input type='hidden' id='pri_port_untag"+pri_port_untag_count+"' name='pri_port_untag"+pri_port_untag_count+"' value='"+vlan_port_list[i][2]+"'>\n";  //(group_vid)
			pri_port_untag_count++;
		}
	}	
	//alert(str);
	document.write(str);	
	if("<?=$check_lan?>" != "")
		str="<tr align='left' style='background:#cccccc;'>\n";
	else
		str="<tr align='left' style='background:#b3b3b3;'>\n";
	str+="<td width='100'>"+"<?=$m_p1?>"+"(2.4G)</td>\n";
	str+="<td width='140'>";
	document.write(str);
	print_port_list_debug("pri","tag",pri_port_tag_count);
	str="</td>\n";
	str+="<td width='140'>";
	document.write(str);	
	print_port_list_debug("pri","untag",pri_port_untag_count);
	str="</td>\n";
	str+="<td width='60'>&nbsp;&nbsp;"+pri_vlan_id+"</td>\n</tr>\n";
	document.write(str);
	str="";	
/*-----------------------------------------pri 2G end------------------------------------------------------------------*/
/*-----------------------------------------pri 5G start------------------------------------------------------------------*/
	var pri2_port_tag_count = 0;
	var pri2_port_untag_count = 0;
	for(var i=1; i<=vlan_list_count; i++)
	{	
		if(1  ==  vlan_port_list[i][7])//("ath:17/egress")
		{				
			str+="<input type='hidden' id='pri2_port_tag"+pri2_port_tag_count+"' name='pri2_port_tag"+pri2_port_tag_count+"' value='"+vlan_port_list[i][2]+"'>\n";  //(group_vid)
			pri2_port_tag_count++;
		}
	}
	document.write(str);	
	str="";
	for(var i=1; i<=vlan_list_count; i++)
	{	
		if(2  ==  vlan_port_list[i][7])//("ath:17/egress")
		{				
			str+="<input type='hidden' id='pri2_port_untag"+pri2_port_untag_count+"' name='pri2_port_untag"+pri2_port_untag_count+"' value='"+vlan_port_list[i][2]+"'>\n";  //(group_vid)
			pri2_port_untag_count++;
		}
	}	
	//alert(str);
	document.write(str);	
	if("<?=$check_lan?>" != "")
		str="<tr align='left' style='background:#b3b3b3;'>\n";
	else
		str="<tr align='left' style='background:#cccccc;'>\n";
	str+="<td width='100'>"+"<?=$m_p1?>"+"(5G)</td>\n";
	str+="<td width='140'>";
	document.write(str);
	print_port_list_debug("pri2","tag",pri2_port_tag_count);
	str="</td>\n";
	str+="<td width='140'>";
	document.write(str);	
	print_port_list_debug("pri2","untag",pri2_port_untag_count);
	str="</td>\n";
	str+="<td width='60'>&nbsp;&nbsp;"+pri2_vlan_id+"</td>\n</tr>\n";
	document.write(str);
	str="";	
/*-----------------------------------------pri 5G end------------------------------------------------------------------*/
/*-----------------------------------------multi & wds start------------------------------------------------------------------*/
	var tmp=1;
	for(var j=8; j<=37; j++)		//list multi&wds, ath2-ath33, but not ath17
	{
		var ath_port_tag_count = 0;
		var ath_port_untag_count = 0;
		for(var i=1; i<=vlan_list_count; i++)
		{	
			if(1  ==  vlan_port_list[i][j])//("ath:/egress")
			{				
				str+="<input type='hidden' id='"+vlan_port_list[0][j]+"_port_tag"+ath_port_tag_count+"' name='"+vlan_port_list[0][j]+"_port_tag"+ath_port_tag_count+"' value='"+vlan_port_list[i][2]+"'>\n";  //(group_vid)
				ath_port_tag_count++;
			}
		}
		document.write(str);
		str="";
		for(var i=1; i<=vlan_list_count; i++)
		{	
			if(2  ==  vlan_port_list[i][j])//("ath:/egress")
			{				
				str+="<input type='hidden' id='"+vlan_port_list[0][j]+"_port_untag"+ath_port_untag_count+"' name='"+vlan_port_list[0][j]+"_port_untag"+ath_port_untag_count+"' value='"+vlan_port_list[i][2]+"'>\n";  //(group_vid)
				ath_port_untag_count++;
			}
		}
		document.write(str);
		if("<?=$check_lan?>" != "")
		{
			if(tmp==1)
			{
				str="<tr align='left' style='background:#cccccc;'>\n";
				tmp=0;
			}
			else
			{
				str="<tr align='left' style='background:#b3b3b3;'>\n";
				tmp=1;
			}
		}
		else
		{
			if(tmp==1)
			{
				str="<tr align='left' style='background:#b3b3b3;'>\n";
				tmp=0;
			}
			else
			{
				str="<tr align='left' style='background:#cccccc;'>\n";
				tmp=1;
			}		
		}
		str+="<td width='100'>";
		document.write(str);
		genTableSSID(vlan_port_name_list[j-8],0);	
		str="</td>\n";
		str+="<td width='140'>";
		document.write(str);
		print_port_list_debug(vlan_port_list[0][j],"tag",ath_port_tag_count);
		str="</td>\n";
		str+="<td width='140'>";
		document.write(str);	
		print_port_list_debug(vlan_port_list[0][j],"untag",ath_port_untag_count);
		str="</td>\n";
		str+="<td width='60'>&nbsp;&nbsp;"+multi_wds_vlan_id[j-8]+"</td>\n</tr>\n";
		document.write(str);
		str="";					
	}			
/*-----------------------------------------multi & wds end------------------------------------------------------------------*/
	if("<?=$evid?>"!="")
	{
		str="<input type='hidden' id='rule_vid"+"<?=$evid?>"+"' name='rule_vid"+"<?=$evid?>"+"' value='"+vlan_port_list["<?=$evid?>"][2]+"'>\n";  //(group_vid)
		str+="<input type='hidden' id='rule_egress_sys"+"<?=$evid?>"+"' name='rule_egress_sys"+"<?=$evid?>"+"' value='"+vlan_port_list["<?=$evid?>"][3]+"'>\n";  //(sys_egress)
		str+="<input type='hidden' id='rule_egress_eth"+"<?=$evid?>"+"' name='rule_egress_eth"+"<?=$evid?>"+"' value='"+vlan_port_list["<?=$evid?>"][4]+"'>\n";  //(eth1_egress)
		if("<?=$check_lan?>" != "")
			str+="<input type='hidden' id='rule_egress_eth2"+"<?=$evid?>"+"' name='rule_egress_eth2"+"<?=$evid?>"+"' value='"+vlan_port_list["<?=$evid?>"][5]+"'>\n";  //(eth2_egress)	
		for(var k=1; k<=32; k++)		//list all ath
		{
			if(k==1)
				str+="<input type='hidden' id='rule_egress_ath"+"<?=$evid?>"+"_"+k+"' name='rule_egress_eth"+"<?=$evid?>"+"_"+k+"' value='"+vlan_port_list["<?=$evid?>"][6]+"'>\n";  //(ath1_egress)
			else if(k==17)
				str+="<input type='hidden' id='rule_egress_ath"+"<?=$evid?>"+"_"+k+"' name='rule_egress_eth"+"<?=$evid?>"+"_"+k+"' value='"+vlan_port_list["<?=$evid?>"][7]+"'>\n";  //(ath17_egress)			
			else if(k>1&&k<17)
				str+="<input type='hidden' id='rule_egress_ath"+"<?=$evid?>"+"_"+k+"' name='rule_egress_eth"+"<?=$evid?>"+"_"+k+"' value='"+vlan_port_list["<?=$evid?>"][k+6]+"'>\n";  //(ath2--ath16_egress)				
			else if(k>17)
				str+="<input type='hidden' id='rule_egress_ath"+"<?=$evid?>"+"_"+k+"' name='rule_egress_eth"+"<?=$evid?>"+"_"+k+"' value='"+vlan_port_list["<?=$evid?>"][k+5]+"'>\n";  //(ath18--ath32_egress)							
		}	
		document.write(str);
		str="";					
		
	}
}

</script>

<body <?=$G_BODY_ATTR?> onload="init();">
<form name="final_form" id="final_form" method="post" action="<?=$MY_NAME?>.php"  onsubmit="return check();">
<input type="hidden" name="ACTION_POST"			value="<?=$MY_ACTION?>">
<input type="hidden" id="f_ath1_egress" name="f_ath1_egress"		value="">
<input type="hidden" id="f_ath2_egress" name="f_ath2_egress"		value="">
<input type="hidden" id="f_ath3_egress" name="f_ath3_egress"		value="">
<input type="hidden" id="f_ath4_egress" name="f_ath4_egress"		value="">
<input type="hidden" id="f_ath5_egress" name="f_ath5_egress"		value="">
<input type="hidden" id="f_ath6_egress" name="f_ath6_egress"		value="">
<input type="hidden" id="f_ath7_egress" name="f_ath7_egress"		value="">
<input type="hidden" id="f_ath8_egress" name="f_ath8_egress"		value="">
<input type="hidden" id="f_ath9_egress" name="f_ath9_egress"		value="">
<input type="hidden" id="f_ath10_egress" name="f_ath10_egress"		value="">
<input type="hidden" id="f_ath11_egress" name="f_ath11_egress"		value="">
<input type="hidden" id="f_ath12_egress" name="f_ath12_egress"		value="">
<input type="hidden" id="f_ath13_egress" name="f_ath13_egress"		value="">
<input type="hidden" id="f_ath14_egress" name="f_ath14_egress"		value="">
<input type="hidden" id="f_ath15_egress" name="f_ath15_egress"		value="">
<input type="hidden" id="f_ath16_egress" name="f_ath16_egress"		value="">
<input type="hidden" id="f_ath17_egress" name="f_ath17_egress"		value=""> 
<input type="hidden" id="f_ath18_egress" name="f_ath18_egress"		value="">
<input type="hidden" id="f_ath19_egress" name="f_ath19_egress"		value="">
<input type="hidden" id="f_ath20_egress" name="f_ath20_egress"		value="">
<input type="hidden" id="f_ath21_egress" name="f_ath21_egress"		value="">
<input type="hidden" id="f_ath22_egress" name="f_ath22_egress"		value="">
<input type="hidden" id="f_ath23_egress" name="f_ath23_egress"		value="">
<input type="hidden" id="f_ath24_egress" name="f_ath24_egress"		value="">
<input type="hidden" id="f_ath25_egress" name="f_ath25_egress"		value="">
<input type="hidden" id="f_ath26_egress" name="f_ath26_egress"		value="">
<input type="hidden" id="f_ath27_egress" name="f_ath27_egress"		value="">       
<input type="hidden" id="f_ath28_egress" name="f_ath28_egress"		value="">       
<input type="hidden" id="f_ath29_egress" name="f_ath29_egress"		value="">       
<input type="hidden" id="f_ath30_egress" name="f_ath30_egress"		value="">     
<input type="hidden" id="f_ath31_egress" name="f_ath31_egress"		value="">     
<input type="hidden" id="f_ath32_egress" name="f_ath32_egress"		value=""> 


<input type="hidden" id="f_eth_egress" name="f_eth_egress"		value="">
<input type="hidden" id="f_eth_egress2" name="f_eth_egress2"      value="">
<input type="hidden" id="f_sys_egress" name="f_sys_egress"		value="">
<input type="hidden" name="f_apply"		value="">
<input type="hidden" name="f_rule_edit"		value="">
<input type="hidden" name="f_rule_del"		value="">
<input type="hidden" name="f_vlan"		value="">
<input type="hidden" name="f_vid"		value="">
<input type="hidden" name="f_ssid"		value="">
<input type="hidden" name="f_pvid_auto"		value="">
<input type="hidden" name="f_admin_pvid"		value="">
<input type="hidden" name="f_eth_pvid"		value="">
<input type="hidden" name="f_eth_pvid2"      value="">
<input type="hidden" name="f_pri_pvid"		value="">
<input type="hidden" name="f_pri_pvid_a"		value="">               
<?
$idx = 1;
while($idx < 8)
{
	echo "<input type=\"hidden\" id=\"f_ms_".$idx."_pvid\"	 name=\"f_ms_".$idx."_pvid\"		value=\"\">\n";
	echo "<input type=\"hidden\" id=\"f_ms_".$idx."_pvid_a\"	 name=\"f_ms_".$idx."_pvid_a\"		value=\"\">\n";
	echo "<input type=\"hidden\" id=\"f_wds_".$idx."_pvid\"  name=\"f_wds_".$idx."_pvid\"		value=\"\">\n";
	echo "<input type=\"hidden\" id=\"f_wds_".$idx."_pvid_a\"  name=\"f_wds_".$idx."_pvid_a\"		value=\"\">\n";
	$idx++;	
}
?>
<input type="hidden" id="f_wds_8_pvid" name="f_wds_8_pvid"		value="">
<input type="hidden" id="f_wds_8_pvid_a" name="f_wds_8_pvid_a"		value="">
</form>
<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return false;">
<table id="table_frame" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
	<tr>
		<td valign="top">
			<table id="table_header" <?=$G_TABLE_ATTR_CELL_ZERO?>>
			<tr>
				<td id="td_header" valign="middle"><?=$m_context_title?></td>
			</tr>
			</table>
<!-- ________________________________ Main Content Start ______________________________ -->
			<table id="table_set_main"  border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>	
				<tr>
					<td id="td_left" width="60%">
						&nbsp;&nbsp;&nbsp;&nbsp;<?=$m_vlan_status?>&nbsp;&nbsp;:&nbsp;&nbsp;
						<?=$G_TAG_SCRIPT_START?>genRaidoEnableDisable("vlan", "on_change_vlan()");<?=$G_TAG_SCRIPT_END?>
					</td>
					<td id="td_left">
						<a href="javascript:submit('rule')">
							<img src="<?=$pic_path?>save1.gif" border="0" OnMouseOver="this.src='<?=$pic_path?>save2.gif'"  OnMouseOut="this.src='<?=$pic_path?>save1.gif'">
						</a>
					</td>
				</tr>
    			<tr>
    				<td id="td_left" colspan="2">
    					&nbsp;&nbsp;&nbsp;&nbsp;<?=$m_vlan_mode?>&nbsp;&nbsp;:&nbsp;&nbsp;
    					<span id="vlan_mode"><?=$m_static?></span>(2.4G),&nbsp;
						<span id="vlan_mode_a"><?=$m_static?></span>(5G)
    				</td>
				</tr>
				<tr>
					<td colspan="2">
						<table border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> width="100%" id="secTable" name="secTable">
    						<tr height="20" align="center"> 
     							<td id="sec_1" name="sec_1" class="sec_s" width="15%" onclick="sec_plan(0)" onMouseOver="showCursor(this);"><?=$m_vlan_list?></td>
     							<td id="sec_2" name="sec_2" class="sec_n" width="15%" onclick="sec_plan(1)" onMouseOver="showCursor(this);"><?=$m_port_list?></td>
     							<td id="sec_3" name="sec_3" class="sec_n" width="25%" onclick="sec_plan(2)" onMouseOver="showCursor(this);"><?=$m_vlan_edit?></td>
     							<td id="sec_4" name="sec_4" class="sec_n" width="20%" onclick="sec_plan(3)" onMouseOver="showCursor(this);"><?=$m_pvid_set?></td>
     							<td width="40%">&nbsp;</td>
    						</tr>	
   						</table>
   						<table id="mainTable" class="TabPane_body" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
   							<tbody id="tbody_1" style="">
    						<tr> 
     							<td valign="top">
									<iframe id="ifrMain_vlan" name="ifrMain_vlan"  src="adv_8021q_vlan_list.php" height="100%" frameborder="0" scrolling="no" width="555" onLoad=""></iframe>
     							</td>
    						</tr>
    						</tbody>
   							<tbody id="tbody_2" style="display:none;">
    						<tr> 
     							<td valign="top">
									<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:5px 0px 0px 0px; padding-left:3px;">
										<tr class="list_head" align="left">
											<td width="100">
												<?=$m_port_name?>
											</td>
											<td width="140">
												<?=$m_tag_vid?>
											</td>
											<td width="140">
												<?=$m_untag_vid?>
											</td>	
											<td width="60">
												<?=$m_pvid?>
											</td>																						
																																																									
										</tr>	
									</table>  								
									<div class="div_vlan_tab">
									<table id="vlan_port_tab" width="100%" border="0" align="left" <?=$G_TABLE_ATTR_CELL_ZERO?>>					
										<?
										echo $G_TAG_SCRIPT_START."vlan_port_table();".$G_TAG_SCRIPT_END;
										?>   									  
									</table>
									</div>										   							
     							</td>
    						</tr>
    						</tbody>   						
    						<tbody id="tbody_3" style="display:none;">
    						<tr> 
     							<td valign="top">
     								<div class="edit_vlan_table" style="height:650px;">
 									<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:5px 0px 0px 3px;">
  										<tr>
											<td>
												&nbsp;&nbsp;<?=$m_vlan_id?> &nbsp;&nbsp;
												<input name="vid" id="vid" class="text" type="text" size="5" maxlength="4" value="">
												&nbsp;&nbsp;&nbsp;&nbsp;
												<?=$m_vlan_name?> &nbsp;&nbsp;
												<input name="ssid" id="ssid" class="text" type="text" size="20" maxlength="32" value="">
												&nbsp;&nbsp;<!--input type="button" id="b_add" name="b_add" value=" <?=$m_b_add?> " onclick="check('rule_add')"-->										
											</td>												
										</tr>
										<tr>
											<td>
<?if($check_lan == ""){echo "<!--";}?>
												<table width="500" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:5px 0px 0px 3px;padding-left:5px;">
													<tr class="list_head" align="center">
														<?=$G_TAG_SCRIPT_START?>unit_set_title(["<?=$m_port?>","<?=$m_sec_all?>","<?=$m_admin?>","<?=$m_lan1?>","<?=$m_lan2?>","","","","",""],[80,60,30,30,30,30,30,30,30,30]);<?=$G_TAG_SCRIPT_END?>
													</tr>	
												</table> 
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr  align="center" style="background:#b3b3b3">
														<?=$G_TAG_SCRIPT_START?>unit_setting(["<?=$m_untag?>","sec_port_untag_all","sec_admin","sec_eth","sec_eth2","","","","",""],[80,60,30,30,30,30,30,30,30,30],"0","do_sec_all('0','port')");<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table>  
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr align="center" style="background:#ffffff">
														<?=$G_TAG_SCRIPT_START?>unit_setting(["<?=$m_tag?>","sec_port_untag_all","sec_admin","sec_eth","sec_eth2","","","","",""],[80,60,30,30,30,30,30,30,30,30],"1","do_sec_all('1','port')");<?=$G_TAG_SCRIPT_END?>																								 	
												 	</tr> 
												</table> 		
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr align="center" style="background:#b3b3b3">
														<?=$G_TAG_SCRIPT_START?>unit_setting(["<?=$m_not_member?>","sec_port_untag_all","sec_admin","sec_eth","sec_eth2","","","","",""],[80,60,30,30,30,30,30,30,30,30],"2","do_sec_all('2','port')");<?=$G_TAG_SCRIPT_END?>																									 	
												 	</tr> 
												</table> 
<?if($check_lan == ""){echo "-->";}?>
<?if($check_lan != ""){echo "<!--";}?>
												<table width="500" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:5px 0px 0px 3px;padding-left:5px;">
													<tr class="list_head" align="center">
														<?=$G_TAG_SCRIPT_START?>unit_set_title(["<?=$m_port?>","<?=$m_sec_all?>","<?=$m_admin?>","<?=$m_eth?>","","","","","",""],[80,60,30,30,30,30,30,30,30,30]);<?=$G_TAG_SCRIPT_END?>
													</tr>	
												</table> 
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr  align="center" style="background:#b3b3b3">
														<?=$G_TAG_SCRIPT_START?>unit_setting(["<?=$m_untag?>","sec_port_untag_all","sec_admin","sec_eth","","","","","",""],[80,60,30,30,30,30,30,30,30,30],"0","do_sec_all('0','port')");<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table>  
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr align="center" style="background:#ffffff">
														<?=$G_TAG_SCRIPT_START?>unit_setting(["<?=$m_tag?>","sec_port_untag_all","sec_admin","sec_eth","","","","","",""],[80,60,30,30,30,30,30,30,30,30],"1","do_sec_all('1','port')");<?=$G_TAG_SCRIPT_END?>																								 	
												 	</tr> 
												</table> 		
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr align="center" style="background:#b3b3b3">
														<?=$G_TAG_SCRIPT_START?>unit_setting(["<?=$m_not_member?>","sec_port_untag_all","sec_admin","sec_eth","","","","","",""],[80,60,30,30,30,30,30,30,30,30],"2","do_sec_all('2','port')");<?=$G_TAG_SCRIPT_END?>																									 	
												 	</tr> 
												</table>
<?if($check_lan != ""){echo "-->";}?>
											</td>
										</tr>		  							
										<tr>
											<td>
											<fieldset><legend>2.4GHz</legend>
												<table width="500" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:5px 0px 0px 3px;padding-left:5px;">
													<tr class="list_head" align="center">
														<?=$G_TAG_SCRIPT_START?>unit_set_title(["<?=$m_ms?><?=$m_port?>","<?=$m_sec_all?>","<?=$m_p1?>","<?=$m_m1?>","<?=$m_m2?>","<?=$m_m3?>","<?=$m_m4?>","<?=$m_m5?>","<?=$m_m6?>","<?=$m_m7?>"],[100,50,20,30,30,30,30,30,30,30]);<?=$G_TAG_SCRIPT_END?>
													</tr>	
												</table> 
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr align="center" style="background:#b3b3b3">
														<?=$G_TAG_SCRIPT_START?>unit_setting(["<?=$m_untag?>","sec_ssid_untag_all","sec_p1","sec_m1","sec_m2","sec_m3","sec_m4","sec_m5","sec_m6","sec_m7"],[80,60,30,30,30,30,30,30,30,30],"0","do_sec_all('0','ms_port')");<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table>  
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr align="center" style="background:#ffffff">
														<?=$G_TAG_SCRIPT_START?>unit_setting(["<?=$m_tag?>","sec_ssid_untag_all","sec_p1","sec_m1","sec_m2","sec_m3","sec_m4","sec_m5","sec_m6","sec_m7"],[80,60,30,30,30,30,30,30,30,30],"1","do_sec_all('1','ms_port')");<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table> 		
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr align="center" style="background:#b3b3b3">
														<?=$G_TAG_SCRIPT_START?>unit_setting(["<?=$m_not_member?>","sec_ssid_untag_all","sec_p1","sec_m1","sec_m2","sec_m3","sec_m4","sec_m5","sec_m6","sec_m7"],[80,60,30,30,30,30,30,30,30,30],"2","do_sec_all('2','ms_port')");<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table> 						
												
												<table width="500" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:5px 0px 0px 3px;padding-left:5px;">
													<tr class="list_head" align="center">	
													<?=$G_TAG_SCRIPT_START?>unit_set_title(["<?=$m_wds?><?=$m_port?>","<?=$m_sec_all?>","<?=$m_w1?>","<?=$m_w2?>","<?=$m_w3?>","<?=$m_w4?>","<?=$m_w5?>","<?=$m_w6?>","<?=$m_w7?>","<?=$m_w8?>"],[90,50,30,30,30,30,30,30,30,30]);<?=$G_TAG_SCRIPT_END?>
													</tr>	
												</table> 
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr align="center" style="background:#b3b3b3">
														<?=$G_TAG_SCRIPT_START?>unit_setting(["<?=$m_untag?>","sec_wds_untag_all","sec_w1","sec_w2","sec_w3","sec_w4","sec_w5","sec_w6","sec_w7","sec_w8"],[80,60,30,30,30,30,30,30,30,30],"0","do_sec_all('0','wds_port')");<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table>  
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr align="center" style="background:#ffffff">
														<?=$G_TAG_SCRIPT_START?>unit_setting(["<?=$m_tag?>","sec_wds_untag_all","sec_w1","sec_w2","sec_w3","sec_w4","sec_w5","sec_w6","sec_w7","sec_w8"],[80,60,30,30,30,30,30,30,30,30],"1","do_sec_all('1','wds_port')");<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table> 		
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr align="center" style="background:#b3b3b3">
														<?=$G_TAG_SCRIPT_START?>unit_setting(["<?=$m_not_member?>","sec_wds_untag_all","sec_w1","sec_w2","sec_w3","sec_w4","sec_w5","sec_w6","sec_w7","sec_w8"],[80,60,30,30,30,30,30,30,30,30],"2","do_sec_all('2','wds_port')");<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table> 									
												</fieldset>																																																			
											</td>
										</tr>											
										<tr><td height=10></td></tr>
										<tr>									
											<td>						
												<fieldset><legend>5GHz</legend>
												<table width="500" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:5px 0px 0px 3px;padding-left:5px;">
												<tr class="list_head" align="center">
														<?=$G_TAG_SCRIPT_START?>unit_set_title(["<?=$m_ms?><?=$m_port?>","<?=$m_sec_all?>","<?=$m_p1?>","<?=$m_m1?>","<?=$m_m2?>","<?=$m_m3?>","<?=$m_m4?>","<?=$m_m5?>","<?=$m_m6?>","<?=$m_m7?>"],[100,50,20,30,30,30,30,30,30,30]);<?=$G_TAG_SCRIPT_END?>
													</tr>	
												</table> 												
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr align="center" style="background:#b3b3b3">
														<?=$G_TAG_SCRIPT_START?>unit_setting(["<?=$m_untag?>","sec_ssid_untag_all","sec_p2","sec_m17","sec_m18","sec_m19","sec_m20","sec_m21","sec_m22","sec_m23"],[80,60,30,30,30,30,30,30,30,30],"0","do_sec_all_a('0','ms_port')");<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table>  
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr align="center" style="background:#ffffff">
														<?=$G_TAG_SCRIPT_START?>unit_setting(["<?=$m_tag?>","sec_ssid_untag_all","sec_p2","sec_m17","sec_m18","sec_m19","sec_m20","sec_m21","sec_m22","sec_m23"],[80,60,30,30,30,30,30,30,30,30],"1","do_sec_all_a('1','ms_port')");<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table> 		
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr align="center" style="background:#b3b3b3">
														<?=$G_TAG_SCRIPT_START?>unit_setting(["<?=$m_not_member?>","sec_ssid_untag_all","sec_p2","sec_m17","sec_m18","sec_m19","sec_m20","sec_m21","sec_m22","sec_m23"],[80,60,30,30,30,30,30,30,30,30],"2","do_sec_all_a('2','ms_port')");<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table>												
													<table width="500" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:5px 0px 0px 3px;padding-left:5px;">
													<tr class="list_head" align="center">	
													<?=$G_TAG_SCRIPT_START?>unit_set_title(["<?=$m_wds?><?=$m_port?>","<?=$m_sec_all?>","<?=$m_w1?>","<?=$m_w2?>","<?=$m_w3?>","<?=$m_w4?>","<?=$m_w5?>","<?=$m_w6?>","<?=$m_w7?>","<?=$m_w8?>"],[90,50,30,30,30,30,30,30,30,30]);<?=$G_TAG_SCRIPT_END?>
													</tr>	
												</table> 
												
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr align="center" style="background:#b3b3b3">
														<?=$G_TAG_SCRIPT_START?>unit_setting(["<?=$m_untag?>","sec_wds_untag_all","sec_w24","sec_w25","sec_w26","sec_w27","sec_w28","sec_w29","sec_w30","sec_w31"],[80,60,30,30,30,30,30,30,30,30],"0","do_sec_all_a('0','wds_port')");<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table>  
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr align="center" style="background:#ffffff">
														<?=$G_TAG_SCRIPT_START?>unit_setting(["<?=$m_tag?>","sec_wds_untag_all","sec_w24","sec_w25","sec_w26","sec_w27","sec_w28","sec_w29","sec_w30","sec_w31"],[80,60,30,30,30,30,30,30,30,30],"1","do_sec_all_a('1','wds_port')");<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table> 		
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr align="center" style="background:#b3b3b3">
														<?=$G_TAG_SCRIPT_START?>unit_setting(["<?=$m_not_member?>","sec_wds_untag_all","sec_w24","sec_w25","sec_w26","sec_w27","sec_w28","sec_w29","sec_w30","sec_w31"],[80,60,30,30,30,30,30,30,30,30],"2","do_sec_all_a('2','wds_port')");<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table> 					
												</fieldset>																																													
											</td>
										</tr>	
  										<tr>
											<td height="30" align="right">
												<table width="100%">
													<tr>
														<td align="right" height="50">
															<a href="javascript:submit('rule_add')">
																<img src="<?=$pic_path?>save1.gif" border="0" OnMouseOver="this.src='<?=$pic_path?>save2.gif'"  OnMouseOut="this.src='<?=$pic_path?>save1.gif'">
															</a>
					 									</td>
					 								</tr>
					 							</table>
											</td>												
										</tr>																					  							
    								</table> 
    								</div>
     							</td>
    						</tr>   
    						</tbody> 
    						<tbody id="tbody_4" style="display:none;">
    						<tr> 
     							<td valign="top">
     								<div class="edit_vlan_table" style="height:330px;">
 									<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:10px 0px 0px 3px;">
										<tr>
											<td id="td_left" colspan="2">
												&nbsp;&nbsp;<?=$m_pvid_aut_status?>&nbsp;&nbsp;&nbsp;&nbsp;
												<?=$G_TAG_SCRIPT_START?>genRaidoEnableDisable("pvid_auto", "on_change_pvid_auto()");<?=$G_TAG_SCRIPT_END?>
											</td>
										</tr>										
										<tr>	
											<td>
<?if($check_lan == ""){echo "<!--";}?>
												<table width="500" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:5px 0px 0px 3px;padding-left:5px;">
													<tr class="list_head" align="center"><td></td>
														<?=$G_TAG_SCRIPT_START?>unit_pvid_title(["<?=$m_port?>","<?=$m_admin_pvid?>","<?=$m_lan1_pvid?>","<?=$m_lan2_pvid?>","","","","","",""],[70,40,40,40,40,40,40,40,40,10]);<?=$G_TAG_SCRIPT_END?>
													</tr>	
												</table> 
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr  align="center" style="background:#cccccc"><td></td>
														<?=$G_TAG_SCRIPT_START?>unit_pvid_setting(["pvid","admin_pvid","eth_pvid","eth_pvid2","","","","","",""],[70,40,40,40,40,40,40,40,40,10]);<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table>  
<?if($check_lan == ""){echo "-->";}?>
<?if($check_lan != ""){echo "<!--";}?>
												<table width="500" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:5px 0px 0px 3px;padding-left:5px;">
													<tr class="list_head" align="center"><td></td>
														<?=$G_TAG_SCRIPT_START?>unit_pvid_title(["<?=$m_port?>","<?=$m_admin_pvid?>","<?=$m_eth_pvid?>","","","","","","",""],[70,40,40,40,40,40,40,40,40,10]);<?=$G_TAG_SCRIPT_END?>
													</tr>	
												</table> 
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr  align="center" style="background:#cccccc"><td></td>
														<?=$G_TAG_SCRIPT_START?>unit_pvid_setting(["pvid","admin_pvid","eth_pvid","","","","","","",""],[70,40,40,40,40,40,40,40,40,10]);<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table> 
<?if($check_lan != ""){echo "-->";}?>
											</td>
										</tr>
									
										<tr>	
											<td>		
										<fieldset>
										<legend>2.4GHz</legend>
										<table width="500" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:10px 0px 0px 3px;padding-left:5px;">
													
													<tr class="list_head" align="center">	<?=$G_TAG_SCRIPT_START?>unit_pvid_title(["<?=$m_ms?><?=$m_port?>","<?=$m_pri_pvid?>","<?=$m_m?>1","<?=$m_m?>2","<?=$m_m?>3","<?=$m_m?>4","<?=$m_m?>5","<?=$m_m?>6","<?=$m_m?>7",""],[70,40,40,40,40,40,40,40,40,10]);<?=$G_TAG_SCRIPT_END?>
													</tr>													
												 	<tr  align="center" style="background:#cccccc">													<?=$G_TAG_SCRIPT_START?>unit_pvid_setting(["pvid","pri_pvid","ms_1_pvid","ms_2_pvid","ms_3_pvid","ms_4_pvid","ms_5_pvid","ms_6_pvid","ms_7_pvid",""],[70,40,40,40,40,40,40,40,40,10]);<?=$G_TAG_SCRIPT_END?>
												 	</tr> 												 	
												 		<tr class="list_head" align="center">	<?=$G_TAG_SCRIPT_START?>unit_pvid_title(["<?=$m_wds?><?=$m_port?>","<?=$m_w1?>","<?=$m_w2?>","<?=$m_w3?>","<?=$m_w4?>","<?=$m_w5?>","<?=$m_w6?>","<?=$m_w7?>","<?=$m_w8?>",""],[70,40,40,40,40,40,40,40,40,10]);<?=$G_TAG_SCRIPT_END?>
													</tr>											
												 	<tr  align="center" style="background:#cccccc">													<?=$G_TAG_SCRIPT_START?>unit_pvid_setting(["pvid","wds_1_pvid","wds_2_pvid","wds_3_pvid","wds_4_pvid","wds_5_pvid","wds_6_pvid","wds_7_pvid","wds_8_pvid",""],[70,40,40,40,40,40,40,40,40,10]);<?=$G_TAG_SCRIPT_END?>
												 	</tr>
										</table>		
											</fieldset>
																																										
											</td>											
										</tr>
										<tr><td height=5></td></tr>
										<tr>	
											<td>			
											
											<fieldset>
										<legend>5GHz</legend>	
											<table width="500" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:10px 0px 0px 3px;padding-left:5px;">
													
											
												 <tr class="list_head" align="center">	<?=$G_TAG_SCRIPT_START?>unit_pvid_title(["<?=$m_ms?><?=$m_port?>","<?=$m_pri_pvid?>","<?=$m_m?>1","<?=$m_m?>2","<?=$m_m?>3","<?=$m_m?>4","<?=$m_m?>5","<?=$m_m?>6","<?=$m_m?>7",""],[70,40,40,40,40,40,40,40,40,10]);<?=$G_TAG_SCRIPT_END?>
													</tr>		
												 	 	<tr  align="center" style="background:#cccccc">												<?=$G_TAG_SCRIPT_START?>unit_pvid_setting(["pvid_a","pri_pvid_a","ms_1_pvid_a","ms_2_pvid_a","ms_3_pvid_a","ms_4_pvid_a","ms_5_pvid_a","ms_6_pvid_a","ms_7_pvid_a",""],[70,40,40,40,40,40,40,40,40,10]);<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												 		<tr class="list_head" align="center">	<?=$G_TAG_SCRIPT_START?>unit_pvid_title(["<?=$m_wds?><?=$m_port?>","<?=$m_w1?>","<?=$m_w2?>","<?=$m_w3?>","<?=$m_w4?>","<?=$m_w5?>","<?=$m_w6?>","<?=$m_w7?>","<?=$m_w8?>",""],[70,40,40,40,40,40,40,40,40,10]);<?=$G_TAG_SCRIPT_END?>
													</tr>	
												 		<tr  align="center" style="background:#cccccc">													<?=$G_TAG_SCRIPT_START?>unit_pvid_setting(["pvid_a","wds_1_pvid_a","wds_2_pvid_a","wds_3_pvid_a","wds_4_pvid_a","wds_5_pvid_a","wds_6_pvid_a","wds_7_pvid_a","wds_8_pvid_a",""],[70,40,40,40,40,40,40,40,40,10]);<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table>  
											</fieldset>																													
											</td>											
										</tr>
  										<tr>
											<td height="30" align="right">
												<table width="100%">
													<tr>
														<td align="right" height="50">
															<a href="javascript:submit('pvid')">
																<img src="<?=$pic_path?>save1.gif" border="0" OnMouseOver="this.src='<?=$pic_path?>save2.gif'"  OnMouseOut="this.src='<?=$pic_path?>save1.gif'">
															</a>
					 									</td>
					 								</tr>
					 							</table>
											</td>												
										</tr>																															
     								</table> 
    								</div>
     							</td>    							
   							</tr> 		     											
   							</tbody>     							   						 						
  				 		</table>
					</td>
				</tr>	
			</table>
<!-- ________________________________  Main Content End _______________________________ -->
		</td>
	</tr>
</table>
</form>
</body>
<script>
var ifrMain_vlan = get_obj("ifrMain_vlan");
</script>
</html>			
							
