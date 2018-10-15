<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_updnsetting_s";
$MY_MSG_FILE  = "adv_updnsetting.php";
$MY_ACTION    = "adv_updnsetting";
$NEXT_PAGE    = $MY_NAME; 
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

/* --------------------------------------------------------------------------- */

anchor("/wlan/inf:1");
$cfg_apmode=query("ap_mode");

$cfg_multi_state=query("multi/state");
if($cfg_multi_state==""){$cfg_multi_state=0;}
$cfg_multi1_state=query("multi/index:1/state");
$cfg_multi2_state=query("multi/index:2/state");
$cfg_multi3_state=query("multi/index:3/state");
$cfg_multi4_state=query("multi/index:4/state");
$cfg_multi5_state=query("multi/index:5/state");
$cfg_multi6_state=query("multi/index:6/state");
$cfg_multi7_state=query("multi/index:7/state");

if(query("wds/list/index:1/mac")!=""){$cfg_wds1_state=1;}else{$cfg_wds1_state=0;}
if(query("wds/list/index:2/mac")!=""){$cfg_wds2_state=1;}else{$cfg_wds2_state=0;}
if(query("wds/list/index:3/mac")!=""){$cfg_wds3_state=1;}else{$cfg_wds3_state=0;}
if(query("wds/list/index:4/mac")!=""){$cfg_wds4_state=1;}else{$cfg_wds4_state=0;}
if(query("wds/list/index:5/mac")!=""){$cfg_wds5_state=1;}else{$cfg_wds5_state=0;}
if(query("wds/list/index:6/mac")!=""){$cfg_wds6_state=1;}else{$cfg_wds6_state=0;}
if(query("wds/list/index:7/mac")!=""){$cfg_wds7_state=1;}else{$cfg_wds7_state=0;}
if(query("wds/list/index:8/mac")!=""){$cfg_wds8_state=1;}else{$cfg_wds8_state=0;}

$cfg_eth0_select=query("/lan/ethernet/updownlink");
if($cfg_eth0_select==""){$temp_eth0_select=0;}else{$temp_eth0_select=$cfg_eth0_select;}
$cfg_pri_select=query("/wlan/inf:1/updownlink");
if($cfg_pri_select==""){$temp_pri_select=0;}else{$temp_pri_select=$cfg_pri_select;}
anchor("/wlan/inf:1/multi");
$cfg_multi1_select=query("index:1/updownlink");
if($cfg_multi1_select==""){$temp_multi1_select=0;}else{$temp_multi1_select=$cfg_multi1_select;}
$cfg_multi2_select=query("index:2/updownlink");
if($cfg_multi2_select==""){$temp_multi2_select=0;}else{$temp_multi2_select=$cfg_multi2_select;}
$cfg_multi3_select=query("index:3/updownlink");
if($cfg_multi3_select==""){$temp_multi3_select=0;}else{$temp_multi3_select=$cfg_multi3_select;}
$cfg_multi4_select=query("index:4/updownlink");
if($cfg_multi4_select==""){$temp_multi4_select=0;}else{$temp_multi4_select=$cfg_multi4_select;}
$cfg_multi5_select=query("index:5/updownlink");
if($cfg_multi5_select==""){$temp_multi5_select=0;}else{$temp_multi5_select=$cfg_multi5_select;}
$cfg_multi6_select=query("index:6/updownlink");
if($cfg_multi6_select==""){$temp_multi6_select=0;}else{$temp_multi6_select=$cfg_multi6_select;}
$cfg_multi7_select=query("index:7/updownlink");
if($cfg_multi7_select==""){$temp_multi7_select=0;}else{$temp_multi7_select=$cfg_multi7_select;}
anchor("/wlan/inf:1/wds/list");
$cfg_wds1_select=query("index:1/updownlink");
if($cfg_wds1_select==""){$temp_wds1_select=0;}else{$temp_wds1_select=$cfg_wds1_select;}
$cfg_wds2_select=query("index:2/updownlink");
if($cfg_wds2_select==""){$temp_wds2_select=0;}else{$temp_wds2_select=$cfg_wds2_select;}
$cfg_wds3_select=query("index:3/updownlink");
if($cfg_wds3_select==""){$temp_wds3_select=0;}else{$temp_wds3_select=$cfg_wds3_select;}
$cfg_wds4_select=query("index:4/updownlink");
if($cfg_wds4_select==""){$temp_wds4_select=0;}else{$temp_wds4_select=$cfg_wds4_select;}
$cfg_wds5_select=query("index:5/updownlink");
if($cfg_wds5_select==""){$temp_wds5_select=0;}else{$temp_wds5_select=$cfg_wds5_select;}
$cfg_wds6_select=query("index:6/updownlink");
if($cfg_wds6_select==""){$temp_wds6_select=0;}else{$temp_wds6_select=$cfg_wds6_select;}
$cfg_wds7_select=query("index:7/updownlink");
if($cfg_wds7_select==""){$temp_wds7_select=0;}else{$temp_wds7_select=$cfg_wds7_select;}
$cfg_wds8_select=query("index:8/updownlink");
if($cfg_wds8_select==""){$temp_wds8_select=0;}else{$temp_wds8_select=$cfg_wds8_select;}

$cfg_e2w=query("/trafficctrl/updownlinkset/bandwidth/downlink");
$cfg_w2e=query("/trafficctrl/updownlinkset/bandwidth/uplink");
$cfg_ipv6 = query("/inet/entry:1/ipv6/valid");
$max_rate = query("/sys/data_rate/gmax");
$m_range_g = $m_range_st.$max_rate.$m_range_end;
?>

<script>
var tra_value = [['index','downlink','uplink']
<?
for("/trafficctrl/trafficmgr/rule/index")
{
	echo ",\n ['".$@."','".query("downlink")."','".query("uplink")."']";
}
?>
];
var max_down=0,max_up=0;
for(var i=1;i<tra_value.length;i++)
{
    if(max_down < tra_value[i][1])
    {
        max_down=tra_value[i][1];
    }
    if(max_up < tra_value[i][2])
    {
        max_up=tra_value[i][2];
    }
}
function init()
{
	var f=get_obj("frm");
	init_mode();	
	if("<?=$cfg_ipv6?>" == "1")
	{
		fields_disabled(f, true);	
	}	
	AdjustHeight();
}

function init_mode()
{
	var f=get_obj("frm");
	which_to_select(<?=$temp_eth0_select?>,f.d_eth0,f.u_eth0);
	enable_two_obj(f.d_eth0,f.u_eth0);
	if(<?=$cfg_apmode?>==0)
	{
		which_to_select(<?=$temp_pri_select?>,f.d_pri,f.u_pri);
		enable_two_obj(f.d_pri,f.u_pri);
		disable_wds();
		judge_multi();
	}
	if(<?=$cfg_apmode?>==3)
	{
		which_to_select(<?=$temp_pri_select?>,f.d_pri,f.u_pri);
		enable_two_obj(f.d_pri,f.u_pri);
		judge_multi();
		judge_wds();
	}
	if(<?=$cfg_apmode?>==4)
	{
		disable_multi();
		disable_two_obj(f.d_pri,f.u_pri);
		judge_wds();
	}
	if(<?=$cfg_apmode?>==1)
	{
		which_to_select(<?=$temp_pri_select?>,f.d_pri,f.u_pri);
		enable_two_obj(f.d_pri,f.u_pri);
		disable_multi();
		disable_wds();
	}
}

function which_to_select(cfg_value,d_name,u_name)
{
	var f=get_obj("frm");
	switch(cfg_value)
	{
		case 0:d_name.checked=u_name.checked=false;break;
		case 1:d_name.checked=true;break;
		case 2:u_name.checked=true;break;
	}
}
function judge_multi()
{
	var f=get_obj("frm");
	if(<?=$cfg_multi_state?>==0)
		{
			disable_multi();
		}
		else
			{
				if(<?=$cfg_multi1_state?>==1){which_to_select(<?=$temp_multi1_select?>,f.d_multi1,f.u_multi1);enable_two_obj(f.d_multi1,f.u_multi1);}
					else{disable_two_obj(f.d_multi1,f.u_multi1);}
				if(<?=$cfg_multi2_state?>==1){which_to_select(<?=$temp_multi2_select?>,f.d_multi2,f.u_multi2);enable_two_obj(f.d_multi2,f.u_multi2);}
					else{disable_two_obj(f.d_multi2,f.u_multi2);}
				if(<?=$cfg_multi3_state?>==1){which_to_select(<?=$temp_multi3_select?>,f.d_multi3,f.u_multi3);enable_two_obj(f.d_multi3,f.u_multi3);}
					else{disable_two_obj(f.d_multi3,f.u_multi3);}
				if(<?=$cfg_multi4_state?>==1){which_to_select(<?=$temp_multi4_select?>,f.d_multi4,f.u_multi4);enable_two_obj(f.d_multi4,f.u_multi4);}
					else{disable_two_obj(f.d_multi4,f.u_multi4);}
				if(<?=$cfg_multi5_state?>==1){which_to_select(<?=$temp_multi5_select?>,f.d_multi5,f.u_multi5);enable_two_obj(f.d_multi5,f.u_multi5);}
					else{disable_two_obj(f.d_multi5,f.u_multi5);}
				if(<?=$cfg_multi6_state?>==1){which_to_select(<?=$temp_multi6_select?>,f.d_multi6,f.u_multi6);enable_two_obj(f.d_multi6,f.u_multi6);}
					else{disable_two_obj(f.d_multi6,f.u_multi6);}
				if(<?=$cfg_multi7_state?>==1){which_to_select(<?=$temp_multi7_select?>,f.d_multi7,f.u_multi7);enable_two_obj(f.d_multi7,f.u_multi7);}
					else{disable_two_obj(f.d_multi7,f.u_multi7);}
			}
}

function disable_multi()
{
	var f=get_obj("frm");
	disable_two_obj(f.d_multi1,f.u_multi1);
	disable_two_obj(f.d_multi2,f.u_multi2);
	disable_two_obj(f.d_multi3,f.u_multi3);
	disable_two_obj(f.d_multi4,f.u_multi4);
	disable_two_obj(f.d_multi5,f.u_multi5);
	disable_two_obj(f.d_multi6,f.u_multi6);
	disable_two_obj(f.d_multi7,f.u_multi7);
}

function judge_wds()
{
	var f=get_obj("frm");
	if(<?=$cfg_wds1_state?>==1){which_to_select(<?=$temp_wds1_select?>,f.d_wds1,f.u_wds1);enable_two_obj(f.d_wds1,f.u_wds1);}
		else{disable_two_obj(f.d_wds1,f.u_wds1);}
	if(<?=$cfg_wds2_state?>==1){which_to_select(<?=$temp_wds2_select?>,f.d_wds2,f.u_wds2);enable_two_obj(f.d_wds2,f.u_wds2);}
		else{disable_two_obj(f.d_wds2,f.u_wds2);}
	if(<?=$cfg_wds3_state?>==1){which_to_select(<?=$temp_wds3_select?>,f.d_wds3,f.u_wds3);enable_two_obj(f.d_wds3,f.u_wds3);}
		else{disable_two_obj(f.d_wds3,f.u_wds3);}
	if(<?=$cfg_wds4_state?>==1){which_to_select(<?=$temp_wds4_select?>,f.d_wds4,f.u_wds4);enable_two_obj(f.d_wds4,f.u_wds4);}
		else{disable_two_obj(f.d_wds4,f.u_wds4);}
	if(<?=$cfg_wds5_state?>==1){which_to_select(<?=$temp_wds5_select?>,f.d_wds5,f.u_wds5);enable_two_obj(f.d_wds5,f.u_wds5);}
		else{disable_two_obj(f.d_wds5,f.u_wds5);}
	if(<?=$cfg_wds6_state?>==1){which_to_select(<?=$temp_wds6_select?>,f.d_wds6,f.u_wds6);enable_two_obj(f.d_wds6,f.u_wds6);}
		else{disable_two_obj(f.d_wds6,f.u_wds6);}
	if(<?=$cfg_wds7_state?>==1){which_to_select(<?=$temp_wds7_select?>,f.d_wds7,f.u_wds7);enable_two_obj(f.d_wds7,f.u_wds7);}
		else{disable_two_obj(f.d_wds7,f.u_wds7);}
	if(<?=$cfg_wds8_state?>==1){which_to_select(<?=$temp_wds8_select?>,f.d_wds8,f.u_wds8);enable_two_obj(f.d_wds8,f.u_wds8);}
		else{disable_two_obj(f.d_wds8,f.u_wds8);}
}

function disable_wds()
{
	var f=get_obj("frm");
	disable_two_obj(f.d_wds1,f.u_wds1);
	disable_two_obj(f.d_wds2,f.u_wds2);
	disable_two_obj(f.d_wds3,f.u_wds3);
	disable_two_obj(f.d_wds4,f.u_wds4);
	disable_two_obj(f.d_wds5,f.u_wds5);
	disable_two_obj(f.d_wds6,f.u_wds6);
	disable_two_obj(f.d_wds7,f.u_wds7);
	disable_two_obj(f.d_wds8,f.u_wds8);
}

function enable_two_obj(obj_name1,obj_name2)
{
	var f=get_obj("frm");
	obj_name1.disabled=obj_name2.disabled=false;
	change_state(obj_name1,obj_name2);
}

function disable_two_obj(obj_name1,obj_name2)
{
	var f=get_obj("frm");
	obj_name1.disabled=obj_name2.disabled=true;
	obj_name1.checked=obj_name2.checked=false;
}

function change_state(name1,name2)
{
	var f=get_obj("frm");
	if(name1.checked)
	{
		name1.disabled=false;
		name2.disabled=true;
		name2.checked=false;
	}
	else
		{
			if(name2.checked)
			{
				name1.disabled=true;
				name2.disabled=false;
				name2.checked=true;
			}
			else
				{
					name1.checked=name2.checked=false;
					name1.disabled=name2.disabled=false;
				}
		}
}

function check_values()
{
	var f=get_obj("frm");
	if(f.e2w.value!="")
	{
		if(!is_digit(f.e2w.value))
		{
			alert("<?=$a_invalid_value_for_speed ?>");
			f.e2w.select();
			return false;
		}
		if(f.e2w.value<1 || parseInt(f.e2w.value, [10]) > parseInt("<?=$max_rate?>", [10]))
		{
			alert("<?=$a_invalid_range_for_speed_st ?>" + "<?=$max_rate?>" + "<?=$a_invalid_range_for_speed_end?>");
			f.e2w.select();
			return false;
		}
		if(parseInt(f.e2w.value,[10]) < parseInt(max_down,[10]))
        {
            alert("<?=$a_e2w_larger_than_max ?>");
            f.e2w.select();
            return false;
        }
	}
	else
		{
			alert("<?=$a_empty_value_for_speed ?>");
			f.e2w.focus();
			return false;
		}
	
	if(f.w2e.value!="")
	{
		if(!is_digit(f.w2e.value))
		{
			alert("<?=$a_invalid_value_for_speed ?>");
			f.w2e.select();
			return false;
		}
		if(f.w2e.value<1 || parseInt(f.w2e.value, [10]) > parseInt("<?=$max_rate?>", [10]))
		{
			alert("<?=$a_invalid_range_for_speed_st ?>" + "<?=$max_rate?>" + "<?=$a_invalid_range_for_speed_end?>");
			f.w2e.select();
			return false;
		}
		if(parseInt(f.w2e.value,[10]) < parseInt(max_up,[10]))
        {
            alert("<?=$a_w2e_larger_than_max ?>");
            f.w2e.select();
            return false;
        }
	}
	else
		{
			alert("<?=$a_empty_value_for_speed ?>");
			f.w2e.focus();
			return false;
		}
			
	return true;
}

function check()
{
	var f=get_obj("frm");
	if(check_values()==true)
	{
		for(var i=0;i<f.e2w.value.length;i++)
		{
			if(f.e2w.value.charAt(i)!=0)
			{
				f.e2w.value=f.e2w.value.substring(i);
				break;
			}
		}
		for(var i=0;i<f.w2e.value.length;i++)
		{
			if(f.w2e.value.charAt(i)!=0)
			{
				f.w2e.value=f.w2e.value.substring(i);
				break;
			}
		}
		return true;
	}
}

function submit()
{
	var f=get_obj("frm");
	if(check()==true)
	{
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
			<table id="table_set_main"  border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
				<tr>
					<td>&nbsp;&nbsp;<?=$m_ethernet?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="checkbox" id="d_eth0" name="d_eth0" value="1" onclick="change_state(get_obj('d_eth0'),get_obj('u_eth0'))"><?=$m_downlink?>&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="checkbox" id="u_eth0" name="u_eth0" value="1" onclick="change_state(get_obj('d_eth0'),get_obj('u_eth0'))"><?=$m_uplink?>&nbsp;</td>			
				</tr>
			</table>
			<table id="table_set_main1"  border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
				<tr>
					<td id="td_left"><?=$m_downlink_interface?></td>					
				</tr>
				<tr>
					<td colspan="4">
						<table>
							<tr>						
								<td><input type="checkbox" id="d_pri" name="d_pri" value="1" onclick="change_state(get_obj('d_pri'),get_obj('u_pri'))"><?=$m_primaryssid?>&nbsp;</td>
								<td><input type="checkbox" id="d_multi1" name="d_multi1" value="1" onclick="change_state(get_obj('d_multi1'),get_obj('u_multi1'))"><?=$m_multissid1?>&nbsp;</td>
								<td><input type="checkbox" id="d_multi2" name="d_multi2" value="1" onclick="change_state(get_obj('d_multi2'),get_obj('u_multi2'))"><?=$m_multissid2?>&nbsp;</td>
								<td><input type="checkbox" id="d_multi3" name="d_multi3" value="1" onclick="change_state(get_obj('d_multi3'),get_obj('u_multi3'))"><?=$m_multissid3?>&nbsp;</td>
							</tr>			
							<tr>
								<td><input type="checkbox" id="d_multi4" name="d_multi4" value="1" onclick="change_state(get_obj('d_multi4'),get_obj('u_multi4'))"><?=$m_multissid4?>&nbsp;</td>
								<td><input type="checkbox" id="d_multi5" name="d_multi5" value="1" onclick="change_state(get_obj('d_multi5'),get_obj('u_multi5'))"><?=$m_multissid5?>&nbsp;</td>
								<td><input type="checkbox" id="d_multi6" name="d_multi6" value="1" onclick="change_state(get_obj('d_multi6'),get_obj('u_multi6'))"><?=$m_multissid6?>&nbsp;</td>
								<td><input type="checkbox" id="d_multi7" name="d_multi7" value="1" onclick="change_state(get_obj('d_multi7'),get_obj('u_multi7'))"><?=$m_multissid7?>&nbsp;</td>
							</tr>			
							<tr>
								<td><input type="checkbox" id="d_wds1" name="d_wds1" value="1" onclick="change_state(get_obj('d_wds1'),get_obj('u_wds1'))"><?=$m_wds1?>&nbsp;</td>
								<td><input type="checkbox" id="d_wds2" name="d_wds2" value="1" onclick="change_state(get_obj('d_wds2'),get_obj('u_wds2'))"><?=$m_wds2?>&nbsp;</td>
								<td><input type="checkbox" id="d_wds3" name="d_wds3" value="1" onclick="change_state(get_obj('d_wds3'),get_obj('u_wds3'))"><?=$m_wds3?>&nbsp;</td>
								<td><input type="checkbox" id="d_wds4" name="d_wds4" value="1" onclick="change_state(get_obj('d_wds4'),get_obj('u_wds4'))"><?=$m_wds4?>&nbsp;</td>
							</tr>
							<tr>
								<td><input type="checkbox" id="d_wds5" name="d_wds5" value="1" onclick="change_state(get_obj('d_wds5'),get_obj('u_wds5'))"><?=$m_wds5?>&nbsp;</td>
								<td><input type="checkbox" id="d_wds6" name="d_wds6" value="1" onclick="change_state(get_obj('d_wds6'),get_obj('u_wds6'))"><?=$m_wds6?>&nbsp;</td>
								<td><input type="checkbox" id="d_wds7" name="d_wds7" value="1" onclick="change_state(get_obj('d_wds7'),get_obj('u_wds7'))"><?=$m_wds7?>&nbsp;</td>
								<td><input type="checkbox" id="d_wds8" name="d_wds8" value="1" onclick="change_state(get_obj('d_wds8'),get_obj('u_wds8'))"><?=$m_wds8?>&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				
				<tr>
					<td>&nbsp;</td>
				</tr>
				
				<tr>
					<td id="td_left"><?=$m_uplink_interface?></td>					
				</tr>
				<tr>
					<td colspan="4">
						<table>
							<tr>						
								<td><input type="checkbox" id="u_pri" name="u_pri" value="1" onclick="change_state(get_obj('d_pri'),get_obj('u_pri'))"><?=$m_primaryssid?>&nbsp;</td>
								<td><input type="checkbox" id="u_multi1" name="u_multi1" value="1" onclick="change_state(get_obj('d_multi1'),get_obj('u_multi1'))"><?=$m_multissid1?>&nbsp;</td>
								<td><input type="checkbox" id="u_multi2" name="u_multi2" value="1" onclick="change_state(get_obj('d_multi2'),get_obj('u_multi2'))"><?=$m_multissid2?>&nbsp;</td>
								<td><input type="checkbox" id="u_multi3" name="u_multi3" value="1" onclick="change_state(get_obj('d_multi3'),get_obj('u_multi3'))"><?=$m_multissid3?>&nbsp;</td>
							</tr>			
							<tr>
								<td><input type="checkbox" id="u_multi4" name="u_multi4" value="1" onclick="change_state(get_obj('d_multi4'),get_obj('u_multi4'))"><?=$m_multissid4?>&nbsp;</td>
								<td><input type="checkbox" id="u_multi5" name="u_multi5" value="1" onclick="change_state(get_obj('d_multi5'),get_obj('u_multi5'))"><?=$m_multissid5?>&nbsp;</td>
								<td><input type="checkbox" id="u_multi6" name="u_multi6" value="1" onclick="change_state(get_obj('d_multi6'),get_obj('u_multi6'))"><?=$m_multissid6?>&nbsp;</td>
								<td><input type="checkbox" id="u_multi7" name="u_multi7" value="1" onclick="change_state(get_obj('d_multi7'),get_obj('u_multi7'))"><?=$m_multissid7?>&nbsp;</td>
							</tr>			
							<tr>
								<td><input type="checkbox" id="u_wds1" name="u_wds1" value="1" onclick="change_state(get_obj('d_wds1'),get_obj('u_wds1'))"><?=$m_wds1?>&nbsp;</td>
								<td><input type="checkbox" id="u_wds2" name="u_wds2" value="1" onclick="change_state(get_obj('d_wds2'),get_obj('u_wds2'))"><?=$m_wds2?>&nbsp;</td>
								<td><input type="checkbox" id="u_wds3" name="u_wds3" value="1" onclick="change_state(get_obj('d_wds3'),get_obj('u_wds3'))"><?=$m_wds3?>&nbsp;</td>
								<td><input type="checkbox" id="u_wds4" name="u_wds4" value="1" onclick="change_state(get_obj('d_wds4'),get_obj('u_wds4'))"><?=$m_wds4?>&nbsp;</td>
							</tr>
							<tr>
								<td><input type="checkbox" id="u_wds5" name="u_wds5" value="1" onclick="change_state(get_obj('d_wds5'),get_obj('u_wds5'))"><?=$m_wds5?>&nbsp;</td>
								<td><input type="checkbox" id="u_wds6" name="u_wds6" value="1" onclick="change_state(get_obj('d_wds6'),get_obj('u_wds6'))"><?=$m_wds6?>&nbsp;</td>
								<td><input type="checkbox" id="u_wds7" name="u_wds7" value="1" onclick="change_state(get_obj('d_wds7'),get_obj('u_wds7'))"><?=$m_wds7?>&nbsp;</td>
								<td><input type="checkbox" id="u_wds8" name="u_wds8" value="1" onclick="change_state(get_obj('d_wds8'),get_obj('u_wds8'))"><?=$m_wds8?>&nbsp;</td>
							</tr>
						</table>
					</td>
				</tr>
				
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>
	<table id="table_set_main2" width="100%"  border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
				<tr>
					<td width="40%" id="td_left">
						<?=$m_downlink_bandwidth?><?=$m_range_g?>
					</td>
					<td>
						<input type="text" id="e2w" name="e2w" maxlength="4" size="6" value="<?=$cfg_e2w?>">Mbits/sec
					</td>
				</tr>
							
				<tr>
					<td width="40%" id="td_left">
						<?=$m_uplink_bandwidth?><?=$m_range_g?>
					</td>
					<td>
						<input type="text" id="w2e" name="w2e" maxlength="4" size="6" value="<?=$cfg_w2e?>">Mbits/sec
					</td>
				</tr>
		
				<tr>
					<td colspan="4">
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
