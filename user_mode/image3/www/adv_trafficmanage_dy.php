<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_trafficmanage_dy";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = "adv_trafficmanage_dy";
$NEXT_PAGE    = "adv_trafficmanage_dy";
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
if($band_reload == 0 || $band_reload == 1) // change band
{ 	
	$cfg_band = $band_reload;
}
else
{
$cfg_band = query("/wlan/ch_mode");
}
$cfg_index = $cfg_band + 1;
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
$list_row=0;
for("/trafficctrl/trafficmgr/rule/index")
{
	$list_row++;
}
?>
<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>
var t_list=[['index','name','clientip','clientmac','downlink','uplink']
<?
for("/trafficctrl/trafficmgr/rule/index")
{
	echo ",\n ['".$@."','".get("j","name")."','".query("clientip")."','".query("clientmac")."','".query("downlink")."','".query("uplink")."']";
}
?>
];
var t_list_a=[['index','band','type','name','downtype','uptype','downlink','uplink','enable']
<?
$t_list_a_num=1;
for("/tc_monitor/mssid")
{

	echo ",\n ['".$@."','".query("band")."','".query("state")."','".query("nameindex")."','".query("downratetype")."','".query("upratetype")."','".query("downrate")."','".query("uprate")."']";
	$t_list_a_num++;
}
?>
];
var t_list_flow_sum=[['flowdown','flowup']
<?
 echo",\n['".$cfg_e2w."000','".$cfg_w2e."000']";
?>];
var t_list_flow=[['index','downtype','uptype','downlink','uplink']
<?
$t_list_a_flow=1;
for("/tc_monitor/mssid")
{

	if( query("downratetype")==1 && query("upratetype")==1 )
	{
		echo ",\n ['".$@."','".query("downratetype")."','".query("upratetype")."','".query("downrate")."000','".query("uprate")."000']";
	}
	else if( query("downratetype")==1 && query("upratetype")!=1 )
	{
		echo ",\n ['".$@."','".query("downratetype")."','".query("upratetype")."','".query("downrate")."000','".query("uprate")."']";
	}
	else if( query("downratetype")!=1 && query("upratetype")==1 )
	{
		echo ",\n ['".$@."','".query("downratetype")."','".query("upratetype")."','".query("downrate")."','".query("uprate")."000']";
	}
		else 
	{
		echo ",\n ['".$@."','".query("downratetype")."','".query("upratetype")."','".query("downrate")."','".query("uprate")."']";
	}
	$t_list_a_flow++;
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
	if("<?query("/tc_monitor/state");?>" == "1"  )
	{
		f.tramgr_type.value=1;
	}
	else if("<?query("/tc_monitor/state");?>"== "0")
	{
		f.tramgr_type.value=0;
	}
	else 
	{
		f.tramgr_type.value=0;
	}
	f.averagetype.value=1;
	f.f_ssid.value=0;
	f.f_band.value=0;
	f.band.value=0;
 	f.speed_e.value =1;
	f.speed_w.value =1;
	change_tramgr_state();	
	AdjustHeight();
}
function change_tramgr_type()
{
	var f=get_obj("frm");
	if(f.tramgr_type.value==1)
	{
			get_obj("specifal_mode").style.display="none";
			get_obj("average_mode").style.display="";
			get_obj("specifal_mode1").style.display="none";
			get_obj("specifal_mode2").style.display="";
	}
	else
	{	

			get_obj("specifal_mode").style.display="";
			get_obj("average_mode").style.display="none";
			get_obj("specifal_mode1").style.display="";
			get_obj("specifal_mode2").style.display="";
			
	}
		AdjustHeight();
	
}

function change_tramgr_state()
{
	var f=get_obj("frm");
	if(f.tramgr_state.value==0)
	{
		f.tramgr_type.disabled = f.de_for[0].disabled=f.de_for[1].disabled=f.e2w.disabled=f.w2e.disabled=true;
		get_obj("specifal_mode").style.display="none";	
		get_obj("average_mode").style.display="none";
		get_obj("specifal_mode1").style.display="none";
		get_obj("specifal_mode2").style.display="none";
	}
	if(f.tramgr_state.value==1)
	{
		f.e2w.disabled=f.w2e.disabled=true;
		f.tramgr_type.disabled = false;
		change_tramgr_type();
	}
		if(<?=$judge_enable?>==0)
		{
			alert("<?=$m_can_not_enable ?>");
			f.tramgr_state.selectedIndex=0;
			return false;
		}
		else
			{
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
				
				do_t_clr();
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

		if(is_blank(f.cli_ip.value) && is_blank(f.cli_mac.value))
		{
			alert("<?=$a_empty_ip_or_mac_address ?>");
			f.cli_ip.focus();
			return false;
		}
		if(!is_blank(f.cli_ip.value))
		{
		  if(!is_valid_ip3(f.cli_ip.value,0))
		  {
				alert("<?=$a_invalid_ip?>");
				f.cli_ip.select();
				return false;
		  }
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
function check_list_values_a()
{
	var f = get_obj("frm");
	var downcheck;
	var upcheck;
	var result=0;
	var result1;
	if(f.e2wrule_a.value=="" && f.w2erule_a.value=="")
			{
				alert("<?=$a_empty_value_for_two_speed ?>");
				f.e2wrule_a.select();
				return false;
			}
			if(f.e2wrule_a.value!="")
				{
					if(!is_digit(f.e2wrule_a.value))
					{
						alert("<?=$a_invalid_value_for_speed?>");
						f.e2wrule_a.select();
						return false;
					}
				}						
			if(f.w2erule_a.value!="")
			{
					if(!is_digit(f.w2erule_a.value))
					{
						alert("<?=$a_invalid_value_for_speed?>");
						f.w2erule_a.select();
						return false;
					}
			}
			if(f.speed_e.value==1000)
			{
				downcheck = parseInt(<?=$cfg_e2w?>,[10]);
			}
			else
			{
				downcheck =parseInt(<?=$cfg_e2w?> +"000",[10]);
			}
			if(f.speed_w.value==1000)
			{
				upcheck = parseInt(<?=$cfg_w2e?>,[10]);
			}
			else
			{
				upcheck =parseInt(<?=$cfg_e2w?> +"000",[10]);
			}
			
				if(f.e2wrule_a.value < 1 || f.e2wrule_a.value > downcheck || isNaN(f.e2wrule_a.value))
				{
					alert("<?=$a_GreaterThanPrimary_value_for_speed?>");
					f.e2wrule_a.select();
					return false;
				}
			if(f.w2erule_a.value < 1 || f.w2erule_a.value > upcheck || isNaN(f.w2erule_a.value))
				{
					alert("<?=$a_GreaterThanPrimary_value_for_speed?>");
					f.w2erule_a.select();
					return false;
				}				
				
			//	result=flowcheck(0);
				//result1=flowcheck(1);
				//result=result + result1;
			if(result > 0)
					{
							f.e2wrule_a.select();
							return false;
					}
					else
					{
							return true;
					}
		
		return true;
}
function do_t_add_a()
{
	var f = get_obj("frm");
	if(check_list_values_a()==true)
	{
		for(var i=0;i<f.e2wrule_a.value.length;i++)
		{
			if(f.e2wrule_a.value.charAt(i)!=0)
			{
				f.e2wrule_a.value=f.e2wrule_a.value.substring(i);
				break;
			}
		}
		for(var i=0;i<f.w2erule_a.value.length;i++)
		{
			if(f.w2erule_a.value.charAt(i)!=0)
			{
				f.w2erule_a.value=f.w2erule_a.value.substring(i);
				break;
			}
		}
		if(f.f_ssid.value == "0" && f.f_band.value == "0" )//ath0 and ath16 
		{
				f.which_edit.value=1;				
		}	
		else if(f.f_ssid.value == "0" && f.f_band.value == "1")
		{
			f.which_edit.value=9;	
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
function on_change_band(s)
{
	var f=get_obj("frm");
		f.f_band.value=f.band.value;
}

function change_ssid(s)
{
	var f=get_obj("frm");
	var id=0;
	f.f_ssid.value= f.ssid.value;	
	if(f.f_band.value==0)
			{
				f.which_edit.value=parseInt(f.f_ssid.value,[10])+1;
			
			}
			else if(f.f_band.value == 1)
			{
				f.which_edit.value=parseInt(f.f_ssid.value,[10])+9;

			}
	for(id = 1;id < <?=$t_list_a_num?>;id++ )//ath1 ~ ath15 5G
	{
		if(f.f_ssid.value == t_list_a[id][3] && f.f_band.value == t_list_a[id][1] )
		{
				f.band.value=t_list_a[id][1];
				//f.averagetype.value=t_list_a[id][2];
				f.ssid.value=t_list_a[id][3];
			if(t_list_a[id][4]!="")
					f.speed_e.value=t_list_a[id][4];
				else
					f.speed_e.value=1;
					if(t_list_a[id][5]!="")
						f.speed_w.value=t_list_a[id][5];
					else
						f.speed_e.value=1;
				if(t_list_a[id][8]!= 0)
				{
					f.e2wrule_a.value=t_list_a[id][6];
					f.w2erule_a.value=t_list_a[id][7];
				}
				else
				{
					f.e2wrule_a.value="";
					f.w2erule_a.value="";
				}
		}
	}
	

}
function do_t_clr()
{
	var f=get_obj("frm");
	f.tra_name.value="";
	f.cli_ip.value=f.cli_mac.value="";
	f.e2wrule.value=f.w2erule.value="";
}
function do_t_clr_a()
{
	var f=get_obj("frm");
	f.which_edit.value="";
	f.averagetype.value=1;
	f.band.value=f.ssid.value=0;
	f.f_band.value=f.f_ssid.value=0;
	f.speed_e.value=f.speed_w.value=1;
	f.e2wrule_a.value=f.w2erule_a.value="";
}

function do_edit_a(id)
{
	var f=get_obj("frm");
	f.which_edit.value=id;
	f.which_edit_sign.value=1;
	f.band.value=t_list_a[id][1];
	f.averagetype.value=t_list_a[id][2];
	f.ssid.value=t_list_a[id][3];
	f.speed_e.value=t_list_a[id][4];
	f.speed_w.value=t_list_a[id][5];
	f.e2wrule_a.value=t_list_a[id][6];
	f.w2erule_a.value=t_list_a[id][7];
	f.f_band.value=f.band.value;
	f.f_ssid.value=f.ssid.value;
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
	if(confirm("<?=$a_rule_del_confirm?>"+id+"?")==true)
	{
		f.which_delete.value=id;
		//f.ACTION_POST.value="delete";
		f.action.value="delete";
		f.submit();
  	return true;
	}	
}
function do_del_a(id)
{
	var f=get_obj("frm");	
	if(confirm("<?=$a_rule_del_confirm?>"+id+"?")==true)
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
function flowcheck(s)  //s =0 down s=1 up
{
	var i;
	var index;
	var sum=0;
	var f=get_obj("frm");
	var current;
	var flowsum;
	if(s ==0 )
	{
			index=3;
			if(f.speed_e.value == 1000 )
			{
				current=parseInt(f.e2wrule_a.value+"000",[10]);
			}
			else
			{
				current=parseInt(f.e2wrule_a.value,[10]);
			}
			flowsum=parseInt(t_list_flow_sum[1][0],[10]);
	
	}
	else
	{
			index=4;
			if(f.speed_w.value == 1000 )
			{
				current=parseInt(f.w2erule_a.value+"000",[10]);
			}
			else
			{
				current=parseInt(f.w2erule_a.value,[10]);
			}
			current=parseInt(f.w2erule_a.value,[10]);
			flowsum=parseInt(t_list_flow_sum[1][1],[10]);

	}
	for(i=1;i<<?=$t_list_a_num?>;i++)
	{
		if( t_list_a[i][2] == 0  && t_list_a[i][8] == 1)
		{
			if(f.which_edit_sign.value=="")
				sum+=parseInt(t_list_flow[i][index],[10]);
		}
	}
	sum+=current;
	if(sum>flowsum)
	{
		alert("<?=$a_Dynamic_flow_max?>");
		return 1;
	}
	return 0;
}
</script>

<body <?=$G_BODY_ATTR?> onload="init();">

<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">
<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="f_band" value="">
<input type="hidden" name="f_ssid" value="">
<input type="hidden" name="which_edit_sign" value="">
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
      		<td width="25%" id="td_left"><?=$m_Trafficmanage_type?></td>
      		<td>
      			<select id="tramgr_type" name="tramgr_type" onChange="change_tramgr_type()">
      				<option value="0"><?=$m_Trafficmanage_type_s?></option>
      				<option value="1"><?=$m_Trafficmanage_type_a?></option>
      			</select>
      		</td>
      	</tr>
      	<tr>
      	<td colspan="2">
       	<div id="specifal_mode1" style="display:none;">
       		<table border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
       				<tr>
				      		<td width="25%" id="td_left"><?=$m_UnlistedClientsTraffic?></td>
				      		<td>
				      			<input type="radio" id="de_for" name="de_for" value="0" <? if($cfg_de_for==0){echo "checked";}?>><?=$m_deny?>&nbsp;
				      			<input type="radio" id="de_for" name="de_for" value="1" <? if($cfg_de_for==1){echo "checked";}?>><?=$m_forward?>
				      		</td>
				      	</tr>
				   </table>
				  </div>
				  	<div id="specifal_mode2" style="display:none;">
				   <table>
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
				     </table>
				    </div>
				     <div id="specifal_mode" style="display:none;">
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
//	echo "<td width=16%>".$list_name."</td>\n";
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
      		
      					</td>
      				</tr>
      			</table>
        </div>
       <div id="average_mode" style="display:none;">
         			<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
      				<tr>
      					<td class="table_tool_td" valign="middle" colspan="2"><b><?=$m_context_add_title?></b></td>
      					<td class="table_tool_td" valign="middle" colspan="2"></td>
      				</tr>
      				<tr>
      					<td width="25%"  id="td_left"><?=$m_average?></td>
      					<td >
										<?=$G_TAG_SCRIPT_START?>genSelect("averagetype", [1,2,3,4], ["<?=$m_averagetype_s?>","<?=$m_averagetype_fstation?>","<?=$m_averagetype_fw?>","<?=$m_averagetype_fssid?>"], "");<?=$G_TAG_SCRIPT_END?>
									</td>								
      				</tr>
      				<tr>
      						<td width="25%"> <?=$m_band?></td>
      						<td>
      							<?=$G_TAG_SCRIPT_START?>genSelect("band", [0,1], ["<?=$m_band_2_4G?>","<?=$m_band_5G?>"], "on_change_band(this)");<?=$G_TAG_SCRIPT_END?>
      						</td>
      				</tr>			
      			<tr>
      					<td width="25%"><?=$m_ssid?></td>
      					<? if(query("/runtime/web/display/mssid_index4") !="0")	{echo "<!--";} ?>										
									<td>
										<?=$G_TAG_SCRIPT_START?>genSelect("ssid", [0,1,2,3,4,5,6,7], ["<?=$m_pri_ssid?>","<?=$m_ms_ssid1?>","<?=$m_ms_ssid2?>","<?=$m_ms_ssid3?>","<?=$m_ms_ssid4?>","<?=$m_ms_ssid5?>","<?=$m_ms_ssid6?>","<?=$m_ms_ssid7?>"], "change_ssid(this)");<?=$G_TAG_SCRIPT_END?>
									</td>
										<? if(query("/runtime/web/display/mssid_index4") !="0")	{echo "-->";} ?>										
										<? if(query("/runtime/web/display/mssid_index4") =="0")	{echo "<!--";} ?>										
																		<td>
																			<?=$G_TAG_SCRIPT_START?>genSelect("ssid", [0,1,2,3], ["<?=$m_pri_ssid?>","<?=$m_ms_ssid1?>","<?=$m_ms_ssid2?>","<?=$m_ms_ssid3?>"], "change_ssid(this)");<?=$G_TAG_SCRIPT_END?>
																		</td>
										<? if(query("/runtime/web/display/mssid_index4") =="0")	{echo "-->";} ?>		
      				</tr>      				
    				 	
      				<tr>
      					<td width="25%"><?=$m_Downlink_Speed?></td>
      					<td><input type="text" id="e2wrule_a" name="e2wrule_a" maxlength="6" size="8" value=""><?=$G_TAG_SCRIPT_START?>genSelect("speed_e", [1,1000], ["<?=$m_speed_k?>","<?=$m_speed_m?>"], "");<?=$G_TAG_SCRIPT_END?></td>
      				</tr>
      				<tr>
      					<td width="25%" ><?=$m_Uplink_Speed?></td>
      					<td><input type="text" id="w2erule_a" name="w2erule_a" maxlength="6" size="8" value=""><?=$G_TAG_SCRIPT_START?>genSelect("speed_w", [1,1000], ["<?=$m_speed_k?>","<?=$m_speed_m?>"], "");<?=$G_TAG_SCRIPT_END?></td>
      				</tr>
    		 			<tr>
      					<td width="25%"></td>
      					<td>
      						<input type="button" id="t_add_a" name="t_add_a" value="<?=$m_b_add?>" onClick="do_t_add_a()">
      						<input type="button" id="t_clr_a" name="t_clr_a" value="<?=$m_b_cancel?>" onClick="do_t_clr_a()">
      					</td>
      				</tr>  
      			</table>
      				<table class="table_tool" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
      				<tr>
      					<td class="table_tool_td" valign="middle" colspan="2"><b><?=$m_context_qos_title?></b></td>
      				</tr>
      				
      				<tr>
      					<td colspan="2">
      						<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;">
      							<tr class="list_head" align="left">
      								<td width="10%"><?=$m_band?></td>
      								<td width="20%"><?=$m_type?></td>
      								<td width="10%"><?=$m_ssid?></td>
      								<td width="20%"><?=$m_Downlink_Speed ?></td>
      								<td width="20%"><?=$m_Uplink_Speed ?></td>
      								<td width="5%"><?=$m_edit ?></td>
      								<td width="5%"><?=$m_del ?></td>
      							</tr>
      						</table>
      					<div class="div_tab_tr"> 
      					 	<table id="acla_tab" width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> style="padding-left:3px;table-layout:fixed;">

				<?
				$key=0;
				$select_id=0;
				for("/tc_monitor/mssid")
				{
					$key++;
					if(query("state")==1)
					{
								$list_type=$m_averagetype_s;
					}
					else if(query("state")==2)
					{
							$list_type=$m_averagetype_fssid;
					}
					else if(query("state")==3)
					{
							$list_type=$m_averagetype_fw;
					}
					else if(query("state")==4)
					{
							$list_type=$m_averagetype_fstation;
					}
					if(query("band")==0)
					{
						$list_band="2.4G";
						$select_id=1;
					}
					else if (query("band")==1)
					{
						$list_band="5G";
						$select_id=9;
					}
					$select_id+=query("nameindex");
					if(query("upratetype")==1)
					{
						$list_uptype="Kbits/sec";
					}
					else if (query("upratetype")==1000)
					{
						$list_uptype="Mbits/sec";
					}
						if(query("downratetype")==1)
					{
						$list_downtype="Kbits/sec";
					}
					else if (query("downratetype")==1000)
					{
						$list_downtype="Mbits/sec";
					}
					$list_name=query("name");
					$list_e2w=query("downrate");
					$list_w2e=query("uprate");
					if(query("state") != "0" )
					{
							if($key%2==1)
							{
								echo "<tr style='background:#CCCCCC;'>\n";
							}
							else
							{
								echo "<tr style='background:#B3B3B3;'>\n";
							}
							echo "<td width=10%>".$list_band."</td>\n";
								echo "<td width=20%>".$G_TAG_SCRIPT_START."genTableName(\"".$list_type."\",18);".$G_TAG_SCRIPT_END."</td>\n";
						  echo "<td width=10%>".$list_name."</td>\n";		
						  echo "<td width=20%>".$list_e2w.$list_downtype."</td>\n";
						  echo "<td width=20%>".$list_w2e.$list_uptype."</td>\n";
						  echo "<td width=5%><a href='javascript:do_edit_a(\"".$select_id."\")'><img src='/pic/edit.jpg' border=0></a></td>";
						  echo "<td width=5%><a href='javascript:do_del_a(\"".$select_id."\")'><img src='/pic/delete.jpg' border=0></a></td>";
							echo "</tr>\n";
					}
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
