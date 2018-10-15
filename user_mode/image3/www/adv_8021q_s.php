<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_8021q_s";
$MY_MSG_FILE  = "adv_8021q.php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_8021q_s";
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
$cfg_ap_mode	=query("/wlan/inf:1/ap_mode");
$cfg_auth = query("/wlan/inf:1/authentication");
$security_eap = "";
if($cfg_auth=="2" || $cfg_auth=="4" || $cfg_auth=="6") 
{$security_eap="1";} //eap

$cfg_vlan = query("/sys/vlan_state");
$cfg_nap_enable = query("/sys/vlan_mode");
$cfg_limit_admin_status = query("/sys/adminlimit/status");
$cfg_admin_pvid = query("/sys/vlan_id");
$cfg_eth_pvid = query("/lan/ethernet/vlan_id");
$cfg_pri_pvid = query("/wlan/inf:1/vlan_id");
$cfg_pvid_auto= query("/sys/auto_set_pvid");
anchor("/wlan/inf:1/multi");
$cfg_ms_1_pvid = query("index:1/vlan_id");
$cfg_ms_2_pvid = query("index:2/vlan_id");
$cfg_ms_3_pvid = query("index:3/vlan_id");
$cfg_ms_4_pvid = query("index:4/vlan_id");
$cfg_ms_5_pvid = query("index:5/vlan_id");
$cfg_ms_6_pvid = query("index:6/vlan_id");
$cfg_ms_7_pvid = query("index:7/vlan_id");
anchor("/wlan/inf:1/wds");
$cfg_wds_1_pvid = query("index:1/vlan_id");
$cfg_wds_2_pvid = query("index:2/vlan_id");
$cfg_wds_3_pvid = query("index:3/vlan_id");
$cfg_wds_4_pvid = query("index:4/vlan_id");
$cfg_wds_5_pvid = query("index:5/vlan_id");
$cfg_wds_6_pvid = query("index:6/vlan_id");
$cfg_wds_7_pvid = query("index:7/vlan_id");
$cfg_wds_8_pvid = query("index:8/vlan_id");
$ssid_flag = query("/runtime/web/display/mssid_index4");
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
if ("<?=$ssid_flag?>"=="1")
	{
		var num_ssid=4;
		var num_wds=5;
		var max_num=9;
	}
	else
	{
		var num_ssid=8;
		var num_wds=9;
		var max_num=17;
	}
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
	
/*	if(f.vlan[1].checked && ("<?=$cfg_nap_enable?>" != 1 ||("<?=$cfg_nap_enable?>" == 1 && "<?=$security_eap?>" != 1)))
	{	
		if(("<?=$cfg_ap_mode?>" =="1" ||"<?=$cfg_ap_mode?>" =="2")&& check_apmode_msg == 0)
		{
		 	alert("<?=$a_invalid_apmode?>");
			check_apmode_msg = 1;
		}		
		fields_disabled(f, false);	
		on_change_pvid_auto();
	}
	else
	{
		fields_disabled(f, true);	
		f.vlan[0].disabled=false;
		f.vlan[1].disabled=false;
	}		
	if(f.vlan[1].checked)
	{
		if("<?=$cfg_limit_admin_status?>"==1)
		{
			alert("<?=$a_enable_vlan?>");	
		}	
	}
*/
	if(f.vlan[0].checked || ("<?=$cfg_nap_enable?>" == 1 && "<?=$security_eap?>" == 1))
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
		if(("<?=$cfg_ap_mode?>" =="1" ||"<?=$cfg_ap_mode?>" =="2")&& check_apmode_msg== 0)
        {
            alert("<?=$a_invalid_apmode?>");
            check_apmode_msg = 1;
        }       
        fields_disabled(f, false);  
        on_change_pvid_auto();
	}

	if("<?=$vlan_enable?>"!="")
	{
		f.vid.disabled = true;
	}
	
	f.sec_ssid_untag_all[1].disabled = true;
	f.sec_p1[1].disabled = true;
	
	for(var i=1;i<num_ssid;i++)
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
		f.pri_pvid.disabled=false;
		for(var i=1;i<num_ssid;i++)
		{
			get_obj("ms_"+i+"_pvid").disabled=false;
		}
		for(var j=1;j<num_wds;j++)
		{
			get_obj("wds_"+j+"_pvid").disabled=false;
		}		
	}
	else
	{
		f.admin_pvid.disabled=true;
		f.eth_pvid.disabled=true;
		f.pri_pvid.disabled=true;
		for(var m=1;m<num_ssid;m++)
		{
			get_obj("ms_"+m+"_pvid").disabled=true;
		}
		for(var n=1;n<num_wds;n++)
		{
			get_obj("wds_"+n+"_pvid").disabled=true;
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
	
	if(f.vlan[1].checked)
	{
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
	//fields_disabled(f, false);
	get_obj("final_form").submit();
	}
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
	
	if(get_obj("rule_egress_eth"+index).value == tag_untag)
	{
		str+="<?=$m_eth?>, ";
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

//	if(f_final.f_rule_edit.value != "")
	if("<?=$evid?>"!= "")
	{
		//sec_plan(2);
		f_final.f_rule_edit.value="<?=$evid?>";
		//f.vid.value = document.getElementById("rule_vid"+f_final.f_rule_edit.value).value;
		//f.ssid.value = vlan_list[f_final.f_rule_edit.value][1];
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
		
		var temp=0;	
		for(var i=1; i< 17; i++)
		{
			if ("<?=$ssid_flag?>"=="1")
			{
				if(i==5)
		{
					i=i+4;
				}				
			}	
			if(get_obj("rule_egress_ath"+"<?=$evid?>"+"_"+i).value == 2)
			{
				if(i==1) //primary
				{
					f.sec_p1[0].checked =  true;	
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
			if ("<?=$ssid_flag?>"=="1")
			{
				if(i==12)
				{
					i=i+4;
				}				
			}					
		}
	}
		
	if("<?=$cfg_nap_enable?>" == 1 && "<?=$security_eap?>" == 1) 
	{
		get_obj("vlan_mode").innerHTML = "<?=$m_dynamic?>";		
	}
	
	if("<?=$cfg_pvid_auto?>" == 0)
		f.pvid_auto[0].checked = true;
	else
		f.pvid_auto[1].checked = true;	
			
	on_change_vlan();

	f.admin_pvid.value = "<?=$cfg_admin_pvid?>";
	f.eth_pvid.value = "<?=$cfg_eth_pvid?>";
	f.pri_pvid.value = "<?=$cfg_pri_pvid?>";

	f.ms_1_pvid.value = "<?=$cfg_ms_1_pvid?>";	
	f.ms_2_pvid.value = "<?=$cfg_ms_2_pvid?>";	
	f.ms_3_pvid.value = "<?=$cfg_ms_3_pvid?>";	
	if("<?=$ssid_flag?>"!="1")
	{
	f.ms_4_pvid.value = "<?=$cfg_ms_4_pvid?>";	
	f.ms_5_pvid.value = "<?=$cfg_ms_5_pvid?>";	
	f.ms_6_pvid.value = "<?=$cfg_ms_6_pvid?>";	
	f.ms_7_pvid.value = "<?=$cfg_ms_7_pvid?>";	
	}
	f.wds_1_pvid.value = "<?=$cfg_wds_1_pvid?>";
	f.wds_2_pvid.value = "<?=$cfg_wds_2_pvid?>";
	f.wds_3_pvid.value = "<?=$cfg_wds_3_pvid?>";
	f.wds_4_pvid.value = "<?=$cfg_wds_4_pvid?>";
	if("<?=$ssid_flag?>"!="1")
	{
	f.wds_5_pvid.value = "<?=$cfg_wds_5_pvid?>";
	f.wds_6_pvid.value = "<?=$cfg_wds_6_pvid?>";
	f.wds_7_pvid.value = "<?=$cfg_wds_7_pvid?>";
	f.wds_8_pvid.value = "<?=$cfg_wds_8_pvid?>";	
	}
/*********************  Page Height  *******************/

	//if("<?=$evid?>"== "")
	//{
		var page_height = 500,page_height_1 = 0,page_height_0 = 0;
		
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
	//AdjustHeight();
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
					
				if(f_final.f_rule_edit!="" &&"<?=$rule_list?>"==18)
				{
					alert("<?=$a_max_rule_list_s?>");
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
			
			f_final.f_apply.value = "rule_add";
			//get_obj("final_form").submit();
			
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
				
				if(!is_in_range(f.pri_pvid.value,1,4094))
				{
					alert("<?=$a_invalid_pvid?>");
					if(f.pri_pvid.value=="") f.pri_pvid.value=1;
					field_select(f.pri_pvid);
					return false;
				}					
			
				f_final.f_pri_pvid.value = f.pri_pvid.value;
				
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
			}			
			f_final.f_apply.value = "pvid";
		}	
	}	
	
	//fields_disabled(f, false);
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
<input type="hidden" id="f_eth_egress" name="f_eth_egress"		value="">
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
<input type="hidden" name="f_pri_pvid"		value="">
<?
$idx = 1;
while($idx < 8)
{
	echo "<input type=\"hidden\" id=\"f_ms_".$idx."_pvid\"	 name=\"f_ms_".$idx."_pvid\"		value=\"\">\n";
	echo "<input type=\"hidden\" id=\"f_wds_".$idx."_pvid\"  name=\"f_wds_".$idx."_pvid\"		value=\"\">\n";
	$idx++;	
}
?>
<input type="hidden" id="f_wds_8_pvid" name="f_wds_8_pvid"		value="">
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
					<td id="td_left"  colspan="2">
						&nbsp;&nbsp;&nbsp;&nbsp;<?=$m_vlan_status?>&nbsp;&nbsp;:&nbsp;&nbsp;
						<?=$G_TAG_SCRIPT_START?>genRaidoEnableDisable("vlan", "on_change_vlan()");<?=$G_TAG_SCRIPT_END?>&nbsp;&nbsp;&nbsp;&nbsp;
						<a href="javascript:submit('rule')">
							<img src="<?=$pic_path?>save1.gif" border="0" OnMouseOver="this.src='<?=$pic_path?>save2.gif'"  OnMouseOut="this.src='<?=$pic_path?>save1.gif'">
						</a>
					</td>
				</tr>
    			<tr>
    				<td id="td_left" colspan="2">
    					&nbsp;&nbsp;&nbsp;&nbsp;<?=$m_vlan_mode?>&nbsp;&nbsp;:&nbsp;&nbsp;
    					<span id="vlan_mode"><?=$m_static?></span>
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
									<iframe id="ifrMain_vlan" name="ifrMain_vlan"  src="adv_8021q_vlan_list_s.php" height="100%" frameborder="0" scrolling="no" width="555" onLoad=""></iframe>
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
									<div class="div_client_tab" >
									<table id="vlan_port_tab" width="100%" border="0" align="left" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">					
<?									   						
$admin_port_tag_count = 0;
$admin_port_untag_count = 0;
/*for("/runtime/sys/group_vlan/sys:1/tag/index")
{
	echo "<input type=\"hidden\" id=\"admin_port_tag".$@."\" name=\"admin_port_tag".$@."\" value=\"".query("group_vid")."\">\n";	
	$admin_port_tag_count++;
}

for("/runtime/sys/group_vlan/sys:1/untag/index")
{
	echo "<input type=\"hidden\" id=\"admin_port_untag".$@."\" name=\"admin_port_untag".$@."\" value=\"".query("group_vid")."\">\n";	
	$admin_port_untag_count++;
}*/
for("/sys/group_vlan/index")
{
    if(1  ==   query("sys:1/egress"))
    {
            echo "<input type=\"hidden\" id=\"admin_port_tag".$admin_port_tag_count."\" name=\"admin_port_tag".$admin_port_tag_count."\" value=\"".query("group_vid")."\">\n";
            $admin_port_tag_count++;
    }
}

for("/sys/group_vlan/index")
{
    if(2  ==   query("sys:1/egress") )
    {
          echo "<input type=\"hidden\" id=\"admin_port_untag".$admin_port_untag_count."\" name=\"admin_port_untag".$admin_port_untag_count."\" value=\"".query("group_vid")."\">\n";
            $admin_port_untag_count++;
    }
}

echo "<tr align=\"left\" style=\"background:#b3b3b3;\">\n";
	echo "<td width=\"100\">".$G_TAG_SCRIPT_START."genTableSSID(\"".$m_admin."\",\"0\");".$G_TAG_SCRIPT_END."</td>\n";
	echo "<td width=\"140\">".$G_TAG_SCRIPT_START."print_port_list_debug(\"admin\",\"tag\",".$admin_port_tag_count.");".$G_TAG_SCRIPT_END."</td>\n";
	echo "<td width=\"140\">".$G_TAG_SCRIPT_START."print_port_list_debug(\"admin\",\"untag\",".$admin_port_untag_count.");".$G_TAG_SCRIPT_END."</td>\n";
	echo "<td width=\"60\">&nbsp;&nbsp;".query("/sys/vlan_id")."</td>\n";			
echo "</tr>\n";	

$eth_port_tag_count = 0;
$eth_port_untag_count = 0;

/*for("/runtime/sys/group_vlan/eth:1/tag/index")
{
	echo "<input type=\"hidden\" id=\"eth_port_tag".$@."\" name=\"eth_port_tag".$@."\" value=\"".query("group_vid")."\">\n";	
	$eth_port_tag_count++;
}

for("/runtime/sys/group_vlan/eth:1/untag/index")
{
	echo "<input type=\"hidden\" id=\"eth_port_untag".$@."\" name=\"eth_port_untag".$@."\" value=\"".query("group_vid")."\">\n";	
	$eth_port_untag_count++;
}*/
for("/sys/group_vlan/index")
{
    if(1  ==   query("eth:1/egress") )
    {
            echo "<input type=\"hidden\" id=\"eth_port_tag".$eth_port_tag_count."\" name=\"eth_port_tag".$eth_port_tag_count."\" value=\"".query("group_vid")."\">\n";
            $eth_port_tag_count++;
    }
}

for("/sys/group_vlan/index")
{
    if(2  ==   query("eth:1/egress") )
        {
            echo "<input type=\"hidden\" id=\"eth_port_untag".$eth_port_untag_count."\" name=\"eth_port_untag".$eth_port_untag_count."\" value=\"".query("group_vid")."\">\n";
            $eth_port_untag_count++;
        }
}

echo "<tr align=\"left\" style=\"background:#cccccc;\">\n";
	echo "<td width=\"100\">".$G_TAG_SCRIPT_START."genTableSSID(\"".$m_eth."\",\"0\");".$G_TAG_SCRIPT_END."</td>\n";
	echo "<td width=\"140\">".$G_TAG_SCRIPT_START."print_port_list_debug(\"eth\",\"tag\",".$eth_port_tag_count.");".$G_TAG_SCRIPT_END."</td>\n";
	echo "<td width=\"140\">".$G_TAG_SCRIPT_START."print_port_list_debug(\"eth\",\"untag\",".$eth_port_untag_count.");".$G_TAG_SCRIPT_END."</td>\n";
	echo "<td width=\"60\">&nbsp;&nbsp;".query("/lan/ethernet/vlan_id")."</td>\n";			
echo "</tr>\n";	

$pri_port_tag_count = 0;
$pri_port_untag_count = 0;


/*for("/runtime/sys/group_vlan/ath:1/tag/index")
{
	echo "<input type=\"hidden\" id=\"pri_port_tag".$@."\" name=\"pri_port_tag".$@."\" value=\"".query("group_vid")."\">\n";	
	$pri_port_tag_count++;
}

for("/runtime/sys/group_vlan/ath:1/untag/index")
{
	echo "<input type=\"hidden\" id=\"pri_port_untag".$@."\" name=\"pri_port_untag".$@."\" value=\"".query("group_vid")."\">\n";	
	$pri_port_untag_count++;
}*/
for("/sys/group_vlan/index")
{
    if(1  ==   query("ath:1/egress") )
        {
            echo "<input type=\"hidden\" id=\"pri_port_tag".$pri_port_tag_count."\" name=\"pri_port_tag".$pri_port_tag_count."\" value=\"".query("group_vid")."\">\n";
            $pri_port_tag_count++;
        }
}
for("/sys/group_vlan/index")
{
    if(2  ==   query("ath:1/egress") )
    {
    echo "<input type=\"hidden\" id=\"pri_port_untag".$pri_port_untag_count."\" name=\"pri_port_untag".$pri_port_untag_count."\" value=\"".query("group_vid")."\">\n";
    $pri_port_untag_count++;
    }
}

echo "<tr align=\"left\" style=\"background:#b3b3b3;\">\n";
	echo "<td width=\"100\">".$G_TAG_SCRIPT_START."genTableSSID(\"".$m_primary."\",\"0\");".$G_TAG_SCRIPT_END."</td>\n";
	echo "<td width=\"140\">".$G_TAG_SCRIPT_START."print_port_list_debug(\"pri\",\"tag\",".$pri_port_tag_count.");".$G_TAG_SCRIPT_END."</td>\n";
	echo "<td width=\"140\">".$G_TAG_SCRIPT_START."print_port_list_debug(\"pri\",\"untag\",".$pri_port_untag_count.");".$G_TAG_SCRIPT_END."</td>\n";
	echo "<td width=\"60\">&nbsp;&nbsp;".query("/wlan/inf:1/vlan_id")."</td>\n";			
echo "</tr>\n";	

$ath_num = 2;
$tmp = "";
if(query("/runtime/web/display/mssid_index4")!=1)
{
	$max_num = 17;	
}
else
{
	$max_num = 9;
}

while($ath_num < 17)
{
	$ath_port_tag_count = 0;
	$ath_port_untag_count = 0;
	$ath_ssid = "";
	$ath_pvid = 0;
	if(query("/runtime/web/display/mssid_index4")=="1")
	{
		if($ath_num==5)
		{
			$ath_num = $ath_num+4;	
		}
	}
/*	for("/runtime/sys/group_vlan/ath:".$ath_num."/tag/index")
	{
		echo "<input type=\"hidden\" id=\"ath".$ath_num."_port_tag".$@."\" name=\"ath".$ath_num."_port_tag".$@."\" value=\"".query("group_vid")."\">\n";	
		$ath_port_tag_count++;
	}

	for("/runtime/sys/group_vlan/ath:".$ath_num."/untag/index")
	{
		echo "<input type=\"hidden\" id=\"ath".$ath_num."_port_untag".$@."\" name=\"ath".$ath_num."_port_untag".$@."\" value=\"".query("group_vid")."\">\n";	
		$ath_port_untag_count++;
	}*/
for("/sys/group_vlan/index")
    {
        if(1  ==   query("ath:".$ath_num."/egress") ){
        echo "<input type=\"hidden\" id=\"ath".$ath_num."_port_tag".$ath_port_tag_count."\" name=\"ath".$ath_num."_port_tag".$ath_port_tag_count."\" value=\"".query("group_vid")."\">\n";
        $ath_port_tag_count++;
    }
    }

    for("/sys/group_vlan/index")
    {
        if(2  ==   query("ath:".$ath_num."/egress") ){
        echo "<input type=\"hidden\" id=\"ath".$ath_num."_port_untag".$ath_port_untag_count."\" name=\"ath".$ath_num."_port_untag".$ath_port_untag_count."\" value=\"".query("group_vid")."\">\n";
        $ath_port_untag_count++;
    }
    }

	if($ath_num < 9)
	{
			$ath_num_1 = $ath_num - 1;
		$ath_ssid_tmp = "m_m".$ath_num_1;
		$ath_ssid = $$ath_ssid_tmp;
		if($ath_num==0)
		{
				$ath_ssid = $m_primary;
		}
		$ath_pvid = query("/wlan/inf:1/multi/index:".$ath_num_1."/vlan_id");
	}
	else
	{
	/*	if(query("/runtime/web/display/mssid_index4")!=1)
		{*/
		$ath_num_1 = $ath_num - 8;
/*		}
		else
		{
			$ath_num_1 = $ath_num - 4;	
		}
*/		
		$ath_ssid_tmp = "m_w".$ath_num_1;
		$ath_ssid = $$ath_ssid_tmp;
		$ath_pvid = query("/wlan/inf:1/wds/index:".$ath_num_1."/vlan_id");
	}
	
	
	if($tmp == 1)
	{
		echo "<tr align=\"left\" style=\"background:#b3b3b3;\">\n";
		$tmp = 0;
	}
	else
	{
		echo "<tr align=\"left\" style=\"background:#cccccc;\">\n";
		$tmp = 1;
	}
	echo "<td width=\"100\">".$G_TAG_SCRIPT_START."genTableSSID(\"".$ath_ssid."\",\"0\");".$G_TAG_SCRIPT_END."</td>\n";
	echo "<td width=\"140\">".$G_TAG_SCRIPT_START."print_port_list_debug(\"ath".$ath_num."\",\"tag\",".$ath_port_tag_count.");".$G_TAG_SCRIPT_END."</td>\n";
	echo "<td width=\"140\">".$G_TAG_SCRIPT_START."print_port_list_debug(\"ath".$ath_num."\",\"untag\",".$ath_port_untag_count.");".$G_TAG_SCRIPT_END."</td>\n";
	echo "<td width=\"60\">&nbsp;&nbsp;".$ath_pvid."</td>\n";			
	echo "</tr>\n";		
	if(query("/runtime/web/display/mssid_index4")=="1")
	{
		if($ath_num==12)
		{
			$ath_num = $ath_num+4;	
		}
	}
	$ath_num++;
}
	anchor("/sys/group_vlan/index:".$evid);
	echo "<input type=\"hidden\" id=\"rule_vid".$evid."\" name=\"rule_vid".$evid."\" value=\"".query("group_vid")."\">\n";	
	echo "<input type=\"hidden\" id=\"rule_egress_eth".$evid."\" name=\"rule_egress_eth".$evid."\" value=\"".query("eth:1/egress")."\">\n";	
	echo "<input type=\"hidden\" id=\"rule_egress_sys".$evid."\" name=\"rule_egress_sys".$evid."\" value=\"".query("sys:1/egress")."\">\n";	
	$while_index = 1;
	while($while_index < 17)
	{
		echo "<input type=\"hidden\" id=\"rule_egress_ath".$evid."_".$while_index."\" name=\"rule_egress_ath".$evid."_".$while_index."\" value=\"".query("ath:".$while_index."/egress")."\">\n";
		$while_index++;	
	}	

?>
									  
									</table>
									</div>										   							
     							</td>
    						</tr>
    						</tbody>   						
    						<tbody id="tbody_3" style="display:none;">
    						<tr> 
     							<td valign="top">
     								<div class="edit_vlan_table" style="height:300px;">
 									<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:5px 0px 0px 3px;">
  										<tr>
											<td>
												&nbsp;&nbsp;<?=$m_vlan_id?> &nbsp;&nbsp;
												<input name="vid" id="vid" class="text" type="text" size="5" maxlength="5" value="">
												&nbsp;&nbsp;&nbsp;&nbsp;
												<?=$m_vlan_name?> &nbsp;&nbsp;
												<input name="ssid" id="ssid" class="text" type="text" size="20" maxlength="32" value="">
												&nbsp;&nbsp;<!--input type="button" id="b_add" name="b_add" value=" <?=$m_b_add?> " onclick="check('rule_add')"-->										
											</td>												
										</tr>
										<tr>
											<td>
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
											</td>
										</tr>		  							
<? if(query("/runtime/web/display/mssid_index4") =="1")	{echo "<!--";} ?>												  							
										<tr>
											<td>
											
												<table width="500" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:5px 0px 0px 3px;padding-left:5px;">
													<tr class="list_head" align="center">
														<?=$G_TAG_SCRIPT_START?>unit_set_title(["<?=$m_ms?><?=$m_port?>","<?=$m_sec_all?>","<?=$m_p1?>","<?=$m_m1?>","<?=$m_m2?>","<?=$m_m3?>","<?=$m_m4?>","<?=$m_m5?>","<?=$m_m6?>","<?=$m_m7?>"],[80,60,30,30,30,30,30,30,30,30]);<?=$G_TAG_SCRIPT_END?>
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
											</td>
										</tr>	
										<tr>
											<td>
												<table width="500" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:5px 0px 0px 3px;padding-left:5px;">
													<tr class="list_head" align="center">
														<?=$G_TAG_SCRIPT_START?>unit_set_title(["<?=$m_wds?><?=$m_port?>","<?=$m_sec_all?>","<?=$m_w1?>","<?=$m_w2?>","<?=$m_w3?>","<?=$m_w4?>","<?=$m_w5?>","<?=$m_w6?>","<?=$m_w7?>","<?=$m_w8?>"],[80,60,30,30,30,30,30,30,30,30]);<?=$G_TAG_SCRIPT_END?>
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
											</td>
										</tr>	
<? if(query("/runtime/web/display/mssid_index4") =="1")	{echo "-->";} ?>	
<? if(query("/runtime/web/display/mssid_index4") !="1")	{echo "<!--";} ?>
										<tr>
											<td>
											
												<table width="500" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:5px 0px 0px 3px;padding-left:5px;">
													<tr class="list_head" align="center">
														<?=$G_TAG_SCRIPT_START?>unit_set_title(["<?=$m_ms?><?=$m_port?>","<?=$m_sec_all?>","<?=$m_p1?>","<?=$m_m1?>","<?=$m_m2?>","<?=$m_m3?>","","","",""],[80,60,30,30,30,30,30,30,30,30]);<?=$G_TAG_SCRIPT_END?>
													</tr>	
												</table> 
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr align="center" style="background:#b3b3b3">
														<?=$G_TAG_SCRIPT_START?>unit_setting(["<?=$m_untag?>","sec_ssid_untag_all","sec_p1","sec_m1","sec_m2","sec_m3","","","",""],[80,60,30,30,30,30,30,30,30,30],"0","do_sec_all('0','ms_port')");<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table>  
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr align="center" style="background:#ffffff">
														<?=$G_TAG_SCRIPT_START?>unit_setting(["<?=$m_tag?>","sec_ssid_untag_all","sec_p1","sec_m1","sec_m2","sec_m3","","","",""],[80,60,30,30,30,30,30,30,30,30],"1","do_sec_all('1','ms_port')");<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table> 		
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr align="center" style="background:#b3b3b3">
														<?=$G_TAG_SCRIPT_START?>unit_setting(["<?=$m_not_member?>","sec_ssid_untag_all","sec_p1","sec_m1","sec_m2","sec_m3","","","",""],[80,60,30,30,30,30,30,30,30,30],"2","do_sec_all('2','ms_port')");<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table> 																																		
											</td>											
										</tr>	
										<tr>
											<td>
												<table width="500" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:5px 0px 0px 3px;padding-left:5px;">
													<tr class="list_head" align="center">
														<?=$G_TAG_SCRIPT_START?>unit_set_title(["<?=$m_wds?><?=$m_port?>","<?=$m_sec_all?>","<?=$m_w1?>","<?=$m_w2?>","<?=$m_w3?>","<?=$m_w4?>","","","",""],[80,60,30,30,30,30,30,30,30,30]);<?=$G_TAG_SCRIPT_END?>
													</tr>	
												</table> 
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr align="center" style="background:#b3b3b3">
														<?=$G_TAG_SCRIPT_START?>unit_setting(["<?=$m_untag?>","sec_wds_untag_all","sec_w1","sec_w2","sec_w3","sec_w4","","","",""],[80,60,30,30,30,30,30,30,30,30],"0","do_sec_all('0','wds_port')");<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table>  
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr align="center" style="background:#ffffff">
														<?=$G_TAG_SCRIPT_START?>unit_setting(["<?=$m_tag?>","sec_wds_untag_all","sec_w1","sec_w2","sec_w3","sec_w4","","","",""],[80,60,30,30,30,30,30,30,30,30],"1","do_sec_all('1','wds_port')");<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table> 		
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr align="center" style="background:#b3b3b3">
														<?=$G_TAG_SCRIPT_START?>unit_setting(["<?=$m_not_member?>","sec_wds_untag_all","sec_w1","sec_w2","sec_w3","sec_w4","","","",""],[80,60,30,30,30,30,30,30,30,30],"2","do_sec_all('2','wds_port')");<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table> 																																		
											</td>
										</tr>			
<? if(query("/runtime/web/display/mssid_index4") !="1")	{echo "-->";} ?>												
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
     								<div class="edit_vlan_table" style="height:300px;">
 									<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:10px 0px 0px 3px;">
										<tr>
											<td id="td_left" colspan="2">
												&nbsp;&nbsp;<?=$m_pvid_aut_status?>&nbsp;&nbsp;&nbsp;&nbsp;
												<?=$G_TAG_SCRIPT_START?>genRaidoEnableDisable("pvid_auto", "on_change_pvid_auto()");<?=$G_TAG_SCRIPT_END?>
											</td>
										</tr>										
										<tr>	
											<td>
												<table width="500" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:5px 0px 0px 3px;padding-left:5px;">
													<tr class="list_head" align="center">
														<?=$G_TAG_SCRIPT_START?>unit_pvid_title(["<?=$m_port?>","<?=$m_admin_pvid?>","<?=$m_eth_pvid?>","","","","","","",""],[70,40,40,40,40,40,40,40,40,10]);<?=$G_TAG_SCRIPT_END?>
													</tr>	
												</table> 
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr  align="center" style="background:#cccccc">
														<?=$G_TAG_SCRIPT_START?>unit_pvid_setting(["pvid","admin_pvid","eth_pvid","","","","","","",""],[70,40,40,40,40,40,40,40,40,10]);<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table>  																																	
											</td>
										</tr>
<? if(query("/runtime/web/display/mssid_index4") =="1")	{echo "<!--";} ?>											
										<tr>	
											<td>
												<table width="500" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:30px 0px 0px 3px;padding-left:5px;">
													<tr class="list_head" align="center">
														<?=$G_TAG_SCRIPT_START?>unit_pvid_title(["<?=$m_ms?><?=$m_port?>","<?=$m_pri_pvid?>","<?=$m_m?>1","<?=$m_m?>2","<?=$m_m?>3","<?=$m_m?>4","<?=$m_m?>5","<?=$m_m?>6","<?=$m_m?>7",""],[70,40,40,40,40,40,40,40,40,10]);<?=$G_TAG_SCRIPT_END?>
													</tr>	
												</table> 
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr  align="center" style="background:#cccccc">
														<?=$G_TAG_SCRIPT_START?>unit_pvid_setting(["pvid","pri_pvid","ms_1_pvid","ms_2_pvid","ms_3_pvid","ms_4_pvid","ms_5_pvid","ms_6_pvid","ms_7_pvid",""],[70,40,40,40,40,40,40,40,40,10]);<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table>  																																	
											</td>											
										</tr>
										<tr>	
											<td>
												<table width="500" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:30px 0px 0px 3px;padding-left:5px;">
													<tr class="list_head" align="center">
														<?=$G_TAG_SCRIPT_START?>unit_pvid_title(["<?=$m_wds?><?=$m_port?>","<?=$m_w?>1","<?=$m_w?>2","<?=$m_w?>3","<?=$m_w?>4","<?=$m_w?>5","<?=$m_w?>6","<?=$m_w?>7","<?=$m_w?>8",""],[70,40,40,40,40,40,40,40,40,10]);<?=$G_TAG_SCRIPT_END?>
													</tr>	
												</table> 
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 20px 3px;padding-left:5px;">
												 	<tr  align="center" style="background:#cccccc">
														<?=$G_TAG_SCRIPT_START?>unit_pvid_setting(["pvid","wds_1_pvid","wds_2_pvid","wds_3_pvid","wds_4_pvid","wds_5_pvid","wds_6_pvid","wds_7_pvid","wds_8_pvid",""],[70,40,40,40,40,40,40,40,40,10]);<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table>  																																	
											</td>											
										</tr>
<? if(query("/runtime/web/display/mssid_index4") =="1")	{echo "-->";} ?>
<? if(query("/runtime/web/display/mssid_index4") !="1")	{echo "<!--";} ?>	
										<tr>	
											<td>
												<table width="500" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:30px 0px 0px 3px;padding-left:5px;">
													<tr class="list_head" align="center">
														<?=$G_TAG_SCRIPT_START?>unit_pvid_title(["<?=$m_ms?><?=$m_port?>","<?=$m_pri_pvid?>","<?=$m_m?>1","<?=$m_m?>2","<?=$m_m?>3","","","","",""],[70,40,40,40,40,40,40,40,40,10]);<?=$G_TAG_SCRIPT_END?>
													</tr>	
												</table> 
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 0px 3px;padding-left:5px;">
												 	<tr  align="center" style="background:#cccccc">
														<?=$G_TAG_SCRIPT_START?>unit_pvid_setting(["pvid","pri_pvid","ms_1_pvid","ms_2_pvid","ms_3_pvid","","","","",""],[70,40,40,40,40,40,40,40,40,10]);<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table>  																																	
											</td>											
										</tr>
										<tr>	
											<td>
												<table width="500" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:30px 0px 0px 3px;padding-left:5px;">
													<tr class="list_head" align="center">
														<?=$G_TAG_SCRIPT_START?>unit_pvid_title(["<?=$m_wds?><?=$m_port?>","<?=$m_w?>1","<?=$m_w?>2","<?=$m_w?>3","<?=$m_w?>4","","","","",""],[70,40,40,40,40,40,40,40,40,10]);<?=$G_TAG_SCRIPT_END?>
													</tr>	
												</table> 
												<table width="500" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="margin:0px 0px 20px 3px;padding-left:5px;">
												 	<tr  align="center" style="background:#cccccc">
														<?=$G_TAG_SCRIPT_START?>unit_pvid_setting(["pvid","wds_1_pvid","wds_2_pvid","wds_3_pvid","wds_4_pvid","","","","",""],[70,40,40,40,40,40,40,40,40,10]);<?=$G_TAG_SCRIPT_END?>
												 	</tr> 
												</table>  																																	
											</td>											
										</tr>
<? if(query("/runtime/web/display/mssid_index4") !="1")	{echo "-->";} ?>										
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
							
