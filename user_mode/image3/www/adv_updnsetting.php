<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_updnsetting";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_updnsetting";
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
$check_lan = query("/sys/2lan");
anchor("/wlan/inf:1");
$cfg_apmode=query("ap_mode");

$cfg_multi_state=query("multi/state");
if($cfg_multi_state==""){$cfg_multi_state=0;}
$cfg_multi1_state=query("multi/index:1/state");
if($cfg_multi1_state==""){$cfg_multi1_state=0;}
$cfg_multi2_state=query("multi/index:2/state");
if($cfg_multi2_state==""){$cfg_multi2_state=0;}
$cfg_multi3_state=query("multi/index:3/state");
if($cfg_multi3_state==""){$cfg_multi3_state=0;}
$cfg_multi4_state=query("multi/index:4/state");
if($cfg_multi4_state==""){$cfg_multi4_state=0;}
$cfg_multi5_state=query("multi/index:5/state");
if($cfg_multi5_state==""){$cfg_multi5_state=0;}
$cfg_multi6_state=query("multi/index:6/state");
if($cfg_multi6_state==""){$cfg_multi6_state=0;}
$cfg_multi7_state=query("multi/index:7/state");
if($cfg_multi7_state==""){$cfg_multi7_state=0;}

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
$cfg_eth2_select=query("/lan/ethernet:2/updownlink");
if($cfg_eth2_select==""){$temp_eth2_select=0;}else{$temp_eth2_select=$cfg_eth2_select;}
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


anchor("/wlan/inf:2");
$cfg_apmode_5g=query("ap_mode");

$cfg_multi_state_5g=query("multi/state");
if($cfg_multi_state_5g==""){$cfg_multi_state_5g=0;}
$cfg_multi1_state_5g=query("multi/index:1/state");
if($cfg_multi1_state_5g==""){$cfg_multi1_state_5g=0;}
$cfg_multi2_state_5g=query("multi/index:2/state");
if($cfg_multi2_state_5g==""){$cfg_multi2_state_5g=0;}
$cfg_multi3_state_5g=query("multi/index:3/state");
if($cfg_multi3_state_5g==""){$cfg_multi3_state_5g=0;}
$cfg_multi4_state_5g=query("multi/index:4/state");
if($cfg_multi4_state_5g==""){$cfg_multi4_state_5g=0;}
$cfg_multi5_state_5g=query("multi/index:5/state");
if($cfg_multi5_state_5g==""){$cfg_multi5_state_5g=0;}
$cfg_multi6_state_5g=query("multi/index:6/state");
if($cfg_multi6_state_5g==""){$cfg_multi6_state_5g=0;}
$cfg_multi7_state_5g=query("multi/index:7/state");
if($cfg_multi7_state_5g==""){$cfg_multi7_state_5g=0;}

if(query("wds/list/index:1/mac")!=""){$cfg_wds1_state_5g=1;}else{$cfg_wds1_state_5g=0;}
if(query("wds/list/index:2/mac")!=""){$cfg_wds2_state_5g=1;}else{$cfg_wds2_state_5g=0;}
if(query("wds/list/index:3/mac")!=""){$cfg_wds3_state_5g=1;}else{$cfg_wds3_state_5g=0;}
if(query("wds/list/index:4/mac")!=""){$cfg_wds4_state_5g=1;}else{$cfg_wds4_state_5g=0;}
if(query("wds/list/index:5/mac")!=""){$cfg_wds5_state_5g=1;}else{$cfg_wds5_state_5g=0;}
if(query("wds/list/index:6/mac")!=""){$cfg_wds6_state_5g=1;}else{$cfg_wds6_state_5g=0;}
if(query("wds/list/index:7/mac")!=""){$cfg_wds7_state_5g=1;}else{$cfg_wds7_state_5g=0;}
if(query("wds/list/index:8/mac")!=""){$cfg_wds8_state_5g=1;}else{$cfg_wds8_state_5g=0;}

$cfg_pri_select_5g=query("/wlan/inf:2/updownlink");
if($cfg_pri_select_5g==""){$temp_pri_select_5g=0;}else{$temp_pri_select_5g=$cfg_pri_select_5g;}
anchor("/wlan/inf:2/multi");
$cfg_multi1_select_5g=query("index:1/updownlink");
if($cfg_multi1_select_5g==""){$temp_multi1_select_5g=0;}else{$temp_multi1_select_5g=$cfg_multi1_select_5g;}
$cfg_multi2_select_5g=query("index:2/updownlink");
if($cfg_multi2_select_5g==""){$temp_multi2_select_5g=0;}else{$temp_multi2_select_5g=$cfg_multi2_select_5g;}
$cfg_multi3_select_5g=query("index:3/updownlink");
if($cfg_multi3_select_5g==""){$temp_multi3_select_5g=0;}else{$temp_multi3_select_5g=$cfg_multi3_select_5g;}
$cfg_multi4_select_5g=query("index:4/updownlink");
if($cfg_multi4_select_5g==""){$temp_multi4_select_5g=0;}else{$temp_multi4_select_5g=$cfg_multi4_select_5g;}
$cfg_multi5_select_5g=query("index:5/updownlink");
if($cfg_multi5_select_5g==""){$temp_multi5_select_5g=0;}else{$temp_multi5_select_5g=$cfg_multi5_select_5g;}
$cfg_multi6_select_5g=query("index:6/updownlink");
if($cfg_multi6_select_5g==""){$temp_multi6_select_5g=0;}else{$temp_multi6_select_5g=$cfg_multi6_select_5g;}
$cfg_multi7_select_5g=query("index:7/updownlink");
if($cfg_multi7_select_5g==""){$temp_multi7_select_5g=0;}else{$temp_multi7_select_5g=$cfg_multi7_select_5g;}
anchor("/wlan/inf:2/wds/list");
$cfg_wds1_select_5g=query("index:1/updownlink");
if($cfg_wds1_select_5g==""){$temp_wds1_select_5g=0;}else{$temp_wds1_select_5g=$cfg_wds1_select_5g;}
$cfg_wds2_select_5g=query("index:2/updownlink");
if($cfg_wds2_select_5g==""){$temp_wds2_select_5g=0;}else{$temp_wds2_select_5g=$cfg_wds2_select_5g;}
$cfg_wds3_select_5g=query("index:3/updownlink");
if($cfg_wds3_select_5g==""){$temp_wds3_select_5g=0;}else{$temp_wds3_select_5g=$cfg_wds3_select_5g;}
$cfg_wds4_select_5g=query("index:4/updownlink");
if($cfg_wds4_select_5g==""){$temp_wds4_select_5g=0;}else{$temp_wds4_select_5g=$cfg_wds4_select_5g;}
$cfg_wds5_select_5g=query("index:5/updownlink");
if($cfg_wds5_select_5g==""){$temp_wds5_select_5g=0;}else{$temp_wds5_select_5g=$cfg_wds5_select_5g;}
$cfg_wds6_select_5g=query("index:6/updownlink");
if($cfg_wds6_select_5g==""){$temp_wds6_select_5g=0;}else{$temp_wds6_select_5g=$cfg_wds6_select_5g;}
$cfg_wds7_select_5g=query("index:7/updownlink");
if($cfg_wds7_select_5g==""){$temp_wds7_select_5g=0;}else{$temp_wds7_select_5g=$cfg_wds7_select_5g;}
$cfg_wds8_select_5g=query("index:8/updownlink");
if($cfg_wds8_select_5g==""){$temp_wds8_select_5g=0;}else{$temp_wds8_select_5g=$cfg_wds8_select_5g;}

$cfg_e2w=query("/trafficctrl/updownlinkset/bandwidth/downlink");
$cfg_w2e=query("/trafficctrl/updownlinkset/bandwidth/uplink");
$max_rate = query("/sys/data_rate/amax");
$m_range_a = $m_range_st.$max_rate.$m_range_end;
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
	AdjustHeight();
}

function showCursor(targetObj)
{
	if(navigator.appName =="Microsoft Internet Explorer")
		targetObj.style.cursor = "hand";
	else
    	targetObj.style.cursor = "pointer";
}

function sec_plan(obj)
{
	if(obj=="0")
	{
		get_obj("tbody_1").style.display="";
		get_obj("tbody_2").style.display="none";
		get_obj("sec_1").className="sec_s";
		get_obj("sec_2").className="sec_n";
	}
	else if(obj=="1")
		{
			get_obj("tbody_1").style.display="none";
			get_obj("tbody_2").style.display="";
			get_obj("sec_1").className="sec_n";
			get_obj("sec_2").className="sec_s";
		}
	AdjustHeight();
}

function init_mode()
{
	var f=get_obj("frm");
	which_to_select(<?=$temp_eth0_select?>,f.d_eth0,f.u_eth0);
	enable_two_obj(f.d_eth0,f.u_eth0);
	if(f.d_eth2 != null)
	{
		which_to_select(<?=$temp_eth2_select?>,f.d_eth2,f.u_eth2);
		enable_two_obj(f.d_eth2,f.u_eth2);
	}
	if("<?=$cfg_apmode?>"==0)
	{
		which_to_select(<?=$temp_pri_select?>,f.d_pri,f.u_pri);
		enable_two_obj(f.d_pri,f.u_pri);
		disable_wds(0);
		judge_multi(0);
	}
	if("<?=$cfg_apmode?>"==3)
	{
		which_to_select(<?=$temp_pri_select?>,f.d_pri,f.u_pri);
		enable_two_obj(f.d_pri,f.u_pri);
		judge_multi(0);
		judge_wds(0);
	}
	if("<?=$cfg_apmode?>"==4)
	{
		disable_multi(0);
		disable_two_obj(f.d_pri,f.u_pri);
		judge_wds(0);
	}
	if("<?=$cfg_apmode?>"==1)
	{
		which_to_select(<?=$temp_pri_select?>,f.d_pri,f.u_pri);
		enable_two_obj(f.d_pri,f.u_pri);
		disable_multi(0);
		disable_wds(0);
	}
	
	if("<?=$cfg_apmode_5g?>"==0)
	{
		which_to_select(<?=$temp_pri_select_5g?>,f.d_pria,f.u_pria);
		enable_two_obj(f.d_pria,f.u_pria);
		disable_wds(1);
		judge_multi(1);
	}
	if("<?=$cfg_apmode_5g?>"==3)
	{
		which_to_select(<?=$temp_pri_select_5g?>,f.d_pria,f.u_pria);
		enable_two_obj(f.d_pria,f.u_pria);
		judge_multi(1);
		judge_wds(1);
	}
	if("<?=$cfg_apmode_5g?>"==4)
	{
		disable_multi(1);
		disable_two_obj(f.d_pria,f.u_pria);
		judge_wds(1);
	}
	if("<?=$cfg_apmode_5g?>"==1)
	{
		which_to_select(<?=$temp_pri_select_5g?>,f.d_pria,f.u_pria);
		enable_two_obj(f.d_pria,f.u_pria);
		disable_multi(1);
		disable_wds(1);
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
function judge_multi(s)
{
	var f=get_obj("frm");
	if(s=="0")
	{
		if("<?=$cfg_multi_state?>"==0)
		{
			disable_multi(0);
		}
		else
			{
				if("<?=$cfg_multi1_state?>"==1){which_to_select(<?=$temp_multi1_select?>,f.d_multi1,f.u_multi1);enable_two_obj(f.d_multi1,f.u_multi1);}
					else{disable_two_obj(f.d_multi1,f.u_multi1);}
				if("<?=$cfg_multi2_state?>"==1){which_to_select(<?=$temp_multi2_select?>,f.d_multi2,f.u_multi2);enable_two_obj(f.d_multi2,f.u_multi2);}
					else{disable_two_obj(f.d_multi2,f.u_multi2);}
				if("<?=$cfg_multi3_state?>"==1){which_to_select(<?=$temp_multi3_select?>,f.d_multi3,f.u_multi3);enable_two_obj(f.d_multi3,f.u_multi3);}
					else{disable_two_obj(f.d_multi3,f.u_multi3);}
				if("<?=$cfg_multi4_state?>"==1){which_to_select(<?=$temp_multi4_select?>,f.d_multi4,f.u_multi4);enable_two_obj(f.d_multi4,f.u_multi4);}
					else{disable_two_obj(f.d_multi4,f.u_multi4);}
				if("<?=$cfg_multi5_state?>"==1){which_to_select(<?=$temp_multi5_select?>,f.d_multi5,f.u_multi5);enable_two_obj(f.d_multi5,f.u_multi5);}
					else{disable_two_obj(f.d_multi5,f.u_multi5);}
				if("<?=$cfg_multi6_state?>"==1){which_to_select(<?=$temp_multi6_select?>,f.d_multi6,f.u_multi6);enable_two_obj(f.d_multi6,f.u_multi6);}
					else{disable_two_obj(f.d_multi6,f.u_multi6);}
				if("<?=$cfg_multi7_state?>"==1){which_to_select(<?=$temp_multi7_select?>,f.d_multi7,f.u_multi7);enable_two_obj(f.d_multi7,f.u_multi7);}
					else{disable_two_obj(f.d_multi7,f.u_multi7);}
			}
	}
	else if(s=="1")
	{	
		if("<?=$cfg_multi_state_5g?>"==0)
		{
			disable_multi(1);
		}
		else
			{
				if("<?=$cfg_multi1_state_5g?>"==1){which_to_select(<?=$temp_multi1_select_5g?>,f.d_multi1a,f.u_multi1a);enable_two_obj(f.d_multi1a,f.u_multi1a);}
					else{disable_two_obj(f.d_multi1a,f.u_multi1a);}
				if("<?=$cfg_multi2_state_5g?>"==1){which_to_select(<?=$temp_multi2_select_5g?>,f.d_multi2a,f.u_multi2a);enable_two_obj(f.d_multi2a,f.u_multi2a);}
					else{disable_two_obj(f.d_multi2a,f.u_multi2a);}
				if("<?=$cfg_multi3_state_5g?>"==1){which_to_select(<?=$temp_multi3_select_5g?>,f.d_multi3a,f.u_multi3a);enable_two_obj(f.d_multi3a,f.u_multi3a);}
					else{disable_two_obj(f.d_multi3a,f.u_multi3a);}
				if("<?=$cfg_multi4_state_5g?>"==1){which_to_select(<?=$temp_multi4_select_5g?>,f.d_multi4a,f.u_multi4a);enable_two_obj(f.d_multi4a,f.u_multi4a);}
					else{disable_two_obj(f.d_multi4a,f.u_multi4a);}
				if("<?=$cfg_multi5_state_5g?>"==1){which_to_select(<?=$temp_multi5_select_5g?>,f.d_multi5a,f.u_multi5a);enable_two_obj(f.d_multi5a,f.u_multi5a);}
					else{disable_two_obj(f.d_multi5a,f.u_multi5a);}
				if("<?=$cfg_multi6_state_5g?>"==1){which_to_select(<?=$temp_multi6_select_5g?>,f.d_multi6a,f.u_multi6a);enable_two_obj(f.d_multi6a,f.u_multi6a);}
					else{disable_two_obj(f.d_multi6a,f.u_multi6a);}
				if("<?=$cfg_multi7_state_5g?>"==1){which_to_select(<?=$temp_multi7_select_5g?>,f.d_multi7a,f.u_multi7a);enable_two_obj(f.d_multi7a,f.u_multi7a);}
					else{disable_two_obj(f.d_multi7a,f.u_multi7a);}
			}
	}
}

function disable_multi(s)
{
	var f=get_obj("frm");
	if(s=="0")
	{
	disable_two_obj(f.d_multi1,f.u_multi1);
	disable_two_obj(f.d_multi2,f.u_multi2);
	disable_two_obj(f.d_multi3,f.u_multi3);
	disable_two_obj(f.d_multi4,f.u_multi4);
	disable_two_obj(f.d_multi5,f.u_multi5);
	disable_two_obj(f.d_multi6,f.u_multi6);
	disable_two_obj(f.d_multi7,f.u_multi7);
	}
	else if(s=="1")
	{
	disable_two_obj(f.d_multi1a,f.u_multi1a);
	disable_two_obj(f.d_multi2a,f.u_multi2a);
	disable_two_obj(f.d_multi3a,f.u_multi3a);
	disable_two_obj(f.d_multi4a,f.u_multi4a);
	disable_two_obj(f.d_multi5a,f.u_multi5a);
	disable_two_obj(f.d_multi6a,f.u_multi6a);
	disable_two_obj(f.d_multi7a,f.u_multi7a);
	}
}

function judge_wds(s)
{
	var f=get_obj("frm");
	if(s=="0")
	{
	if("<?=$cfg_wds1_state?>"==1){which_to_select(<?=$temp_wds1_select?>,f.d_wds1,f.u_wds1);enable_two_obj(f.d_wds1,f.u_wds1);}
		else{disable_two_obj(f.d_wds1,f.u_wds1);}
	if("<?=$cfg_wds2_state?>"==1){which_to_select(<?=$temp_wds2_select?>,f.d_wds2,f.u_wds2);enable_two_obj(f.d_wds2,f.u_wds2);}
		else{disable_two_obj(f.d_wds2,f.u_wds2);}
	if("<?=$cfg_wds3_state?>"==1){which_to_select(<?=$temp_wds3_select?>,f.d_wds3,f.u_wds3);enable_two_obj(f.d_wds3,f.u_wds3);}
		else{disable_two_obj(f.d_wds3,f.u_wds3);}
	if("<?=$cfg_wds4_state?>"==1){which_to_select(<?=$temp_wds4_select?>,f.d_wds4,f.u_wds4);enable_two_obj(f.d_wds4,f.u_wds4);}
		else{disable_two_obj(f.d_wds4,f.u_wds4);}
	if("<?=$cfg_wds5_state?>"==1){which_to_select(<?=$temp_wds5_select?>,f.d_wds5,f.u_wds5);enable_two_obj(f.d_wds5,f.u_wds5);}
		else{disable_two_obj(f.d_wds5,f.u_wds5);}
	if("<?=$cfg_wds6_state?>"==1){which_to_select(<?=$temp_wds6_select?>,f.d_wds6,f.u_wds6);enable_two_obj(f.d_wds6,f.u_wds6);}
		else{disable_two_obj(f.d_wds6,f.u_wds6);}
	if("<?=$cfg_wds7_state?>"==1){which_to_select(<?=$temp_wds7_select?>,f.d_wds7,f.u_wds7);enable_two_obj(f.d_wds7,f.u_wds7);}
		else{disable_two_obj(f.d_wds7,f.u_wds7);}
	if("<?=$cfg_wds8_state?>"==1){which_to_select(<?=$temp_wds8_select?>,f.d_wds8,f.u_wds8);enable_two_obj(f.d_wds8,f.u_wds8);}
		else{disable_two_obj(f.d_wds8,f.u_wds8);}
	}
	else if(s=="1")
	{
	if("<?=$cfg_wds1_state_5g?>"==1){which_to_select(<?=$temp_wds1_select_5g?>,f.d_wds1a,f.u_wds1a);enable_two_obj(f.d_wds1a,f.u_wds1a);}
		else{disable_two_obj(f.d_wds1a,f.u_wds1a);}
	if("<?=$cfg_wds2_state_5g?>"==1){which_to_select(<?=$temp_wds2_select_5g?>,f.d_wds2a,f.u_wds2a);enable_two_obj(f.d_wds2a,f.u_wds2a);}
		else{disable_two_obj(f.d_wds2a,f.u_wds2a);}
	if("<?=$cfg_wds3_state_5g?>"==1){which_to_select(<?=$temp_wds3_select_5g?>,f.d_wds3a,f.u_wds3a);enable_two_obj(f.d_wds3a,f.u_wds3a);}
		else{disable_two_obj(f.d_wds3a,f.u_wds3a);}
	if("<?=$cfg_wds4_state_5g?>"==1){which_to_select(<?=$temp_wds4_select_5g?>,f.d_wds4a,f.u_wds4a);enable_two_obj(f.d_wds4a,f.u_wds4a);}
		else{disable_two_obj(f.d_wds4a,f.u_wds4a);}
	if("<?=$cfg_wds5_state_5g?>"==1){which_to_select(<?=$temp_wds5_select_5g?>,f.d_wds5a,f.u_wds5a);enable_two_obj(f.d_wds5a,f.u_wds5a);}
		else{disable_two_obj(f.d_wds5a,f.u_wds5a);}
	if("<?=$cfg_wds6_state_5g?>"==1){which_to_select(<?=$temp_wds6_select_5g?>,f.d_wds6a,f.u_wds6a);enable_two_obj(f.d_wds6a,f.u_wds6a);}
		else{disable_two_obj(f.d_wds6a,f.u_wds6a);}
	if("<?=$cfg_wds7_state_5g?>"==1){which_to_select(<?=$temp_wds7_select_5g?>,f.d_wds7a,f.u_wds7a);enable_two_obj(f.d_wds7a,f.u_wds7a);}
		else{disable_two_obj(f.d_wds7a,f.u_wds7a);}
	if("<?=$cfg_wds8_state_5g?>"==1){which_to_select(<?=$temp_wds8_select_5g?>,f.d_wds8a,f.u_wds8a);enable_two_obj(f.d_wds8a,f.u_wds8a);}
		else{disable_two_obj(f.d_wds8a,f.u_wds8a);}
	}
}

function disable_wds(s)
{
	var f=get_obj("frm");
	if(s=="0")
	{
	disable_two_obj(f.d_wds1,f.u_wds1);
	disable_two_obj(f.d_wds2,f.u_wds2);
	disable_two_obj(f.d_wds3,f.u_wds3);
	disable_two_obj(f.d_wds4,f.u_wds4);
	disable_two_obj(f.d_wds5,f.u_wds5);
	disable_two_obj(f.d_wds6,f.u_wds6);
	disable_two_obj(f.d_wds7,f.u_wds7);
	disable_two_obj(f.d_wds8,f.u_wds8);
	}
	else if(s=="1")
	{
	disable_two_obj(f.d_wds1a,f.u_wds1a);
	disable_two_obj(f.d_wds2a,f.u_wds2a);
	disable_two_obj(f.d_wds3a,f.u_wds3a);
	disable_two_obj(f.d_wds4a,f.u_wds4a);
	disable_two_obj(f.d_wds5a,f.u_wds5a);
	disable_two_obj(f.d_wds6a,f.u_wds6a);
	disable_two_obj(f.d_wds7a,f.u_wds7a);
	disable_two_obj(f.d_wds8a,f.u_wds8a);
	}
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
		fields_disabled(f, false);
		f.submit();
		return true;
	}
}

</script>

<body <?=$G_BODY_ATTR?> onload="init();">

<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">
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
					<td colspan="2">
					<table>
<?if($check_lan != ""){echo "<!--";}?>
					<tr>
					<td width="120">&nbsp;&nbsp;&nbsp;&nbsp;<?=$m_ethernet?></td>
					<td width="110"><input type="checkbox" id="d_eth0" name="d_eth0" value="1" onclick="change_state(get_obj('d_eth0'),get_obj('u_eth0'))"><?=$m_downlink?></td>
					<td width="110"><input type="checkbox" id="u_eth0" name="u_eth0" value="1" onclick="change_state(get_obj('d_eth0'),get_obj('u_eth0'))"><?=$m_uplink?></td>
					</tr>
<?if($check_lan != ""){echo "-->";}?>
<?if($check_lan == ""){echo "<!--";}?>
					<tr>
					<td width="120">&nbsp;&nbsp;&nbsp;&nbsp;<?=$m_lan1?></td>
					<td width="110"><input type="checkbox" id="d_eth2" name="d_eth2" value="1" onclick="change_state(get_obj('d_eth2'),get_obj('u_eth2'))"><?=$m_downlink?></td>
					<td width="110"><input type="checkbox" id="u_eth2" name="u_eth2" value="1" onclick="change_state(get_obj('d_eth2'),get_obj('u_eth2'))"><?=$m_uplink?></td>
					</tr>
					<tr>
                    <td width="120">&nbsp;&nbsp;&nbsp;&nbsp;<?=$m_lan2?></td>
                    <td width="110"><input type="checkbox" id="d_eth0" name="d_eth0" value="1" onclick="change_state(get_obj('d_eth0'),get_obj('u_eth0'))"><?=$m_downlink?></td>
                    <td width="110"><input type="checkbox" id="u_eth0" name="u_eth0" value="1" onclick="change_state(get_obj('d_eth0'),get_obj('u_eth0'))"><?=$m_uplink?></td>
                    </tr>
<?if($check_lan == ""){echo "-->";}?>
					</table>
					</td>
				</tr>
				<tr>
					<td colspan="2">
						<table border="0" <?=$G_TABLE_ATTR_CELL_ZERO?> width="100%" id="secTable" name="secTable">
    						<tr height="20" align="center"> 
     							<td id="sec_1" name="sec_1" class="sec_s" width="20%" onclick="sec_plan(0)" onMouseOver="showCursor(this);"><?=$m_band_2.4G?></td>
     							<td id="sec_2" name="sec_2" class="sec_n" width="20%" onclick="sec_plan(1)" onMouseOver="showCursor(this);"><?=$m_band_5G?></td>
     							<td width="40%">&nbsp;</td>
    						</tr>	
   					</table>
						<table id="mainTable" class="TabUpDn_body" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
<tbody id="tbody_1" style="">
<tr> 
  <td valign="top">
  	<table id="24g_dis">
			<tr>
				<td>
					<fieldset>		
				  <legend><?=$m_downlink_interface?></legend>
					<table width="100%" border="0">
						<tr class="table_UpDn">						
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
					</fieldset>
				</td>
			</tr>
			<tr>
				<td>
				<fieldset>
				<legend><?=$m_uplink_interface?></legend>
				<table width="100%" border="0">		
						<tr class="table_UpDn">						
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
				</fieldset>
				</td>
			</tr>
		</table>
	</td>
</tr>
</tbody>

<tbody id="tbody_2" style="display:none;">
	<tr>
		<td valign="top">
			<table id="5g_dis">
				<tr>
					<td>
					<fieldset>
					<legend><?=$m_downlink_interface?></legend>
          <table width="100%" border="0">
					<tr class="table_UpDn">						
						<td><input type="checkbox" id="d_pria" name="d_pria" value="1" onclick="change_state(get_obj('d_pria'),get_obj('u_pria'))"><?=$m_primaryssid?>&nbsp;</td>
						<td><input type="checkbox" id="d_multi1a" name="d_multi1a" value="1" onclick="change_state(get_obj('d_multi1a'),get_obj('u_multi1a'))"><?=$m_multissid1?>&nbsp;</td>
						<td><input type="checkbox" id="d_multi2a" name="d_multi2a" value="1" onclick="change_state(get_obj('d_multi2a'),get_obj('u_multi2a'))"><?=$m_multissid2?>&nbsp;</td>
						<td><input type="checkbox" id="d_multi3a" name="d_multi3a" value="1" onclick="change_state(get_obj('d_multi3a'),get_obj('u_multi3a'))"><?=$m_multissid3?>&nbsp;</td>
					</tr>			
					<tr>
						<td><input type="checkbox" id="d_multi4a" name="d_multi4a" value="1" onclick="change_state(get_obj('d_multi4a'),get_obj('u_multi4a'))"><?=$m_multissid4?>&nbsp;</td>
						<td><input type="checkbox" id="d_multi5a" name="d_multi5a" value="1" onclick="change_state(get_obj('d_multi5a'),get_obj('u_multi5a'))"><?=$m_multissid5?>&nbsp;</td>
						<td><input type="checkbox" id="d_multi6a" name="d_multi6a" value="1" onclick="change_state(get_obj('d_multi6a'),get_obj('u_multi6a'))"><?=$m_multissid6?>&nbsp;</td>
						<td><input type="checkbox" id="d_multi7a" name="d_multi7a" value="1" onclick="change_state(get_obj('d_multi7a'),get_obj('u_multi7a'))"><?=$m_multissid7?>&nbsp;</td>
					</tr>			
					<tr>
						<td><input type="checkbox" id="d_wds1a" name="d_wds1a" value="1" onclick="change_state(get_obj('d_wds1a'),get_obj('u_wds1a'))"><?=$m_wds1?>&nbsp;</td>
						<td><input type="checkbox" id="d_wds2a" name="d_wds2a" value="1" onclick="change_state(get_obj('d_wds2a'),get_obj('u_wds2a'))"><?=$m_wds2?>&nbsp;</td>
						<td><input type="checkbox" id="d_wds3a" name="d_wds3a" value="1" onclick="change_state(get_obj('d_wds3a'),get_obj('u_wds3a'))"><?=$m_wds3?>&nbsp;</td>
						<td><input type="checkbox" id="d_wds4a" name="d_wds4a" value="1" onclick="change_state(get_obj('d_wds4a'),get_obj('u_wds4a'))"><?=$m_wds4?>&nbsp;</td>
					</tr>
					<tr>
						<td><input type="checkbox" id="d_wds5a" name="d_wds5a" value="1" onclick="change_state(get_obj('d_wds5a'),get_obj('u_wds5a'))"><?=$m_wds5?>&nbsp;</td>
						<td><input type="checkbox" id="d_wds6a" name="d_wds6a" value="1" onclick="change_state(get_obj('d_wds6a'),get_obj('u_wds6a'))"><?=$m_wds6?>&nbsp;</td>
						<td><input type="checkbox" id="d_wds7a" name="d_wds7a" value="1" onclick="change_state(get_obj('d_wds7a'),get_obj('u_wds7a'))"><?=$m_wds7?>&nbsp;</td>
						<td><input type="checkbox" id="d_wds8a" name="d_wds8a" value="1" onclick="change_state(get_obj('d_wds8a'),get_obj('u_wds8a'))"><?=$m_wds8?>&nbsp;</td>
					</tr>
					</table>
          </fieldset>
				</td>
			</tr>
      <tr>
				<td>
     	   <fieldset>
      	  <legend><?=$m_uplink_interface?></legend>
        		<table width="100%" border="0">
							<tr class="table_UpDn">						
								<td><input type="checkbox" id="u_pria" name="u_pria" value="1" onclick="change_state(get_obj('d_pria'),get_obj('u_pria'))"><?=$m_primaryssid?>&nbsp;</td>
								<td><input type="checkbox" id="u_multi1a" name="u_multi1a" value="1" onclick="change_state(get_obj('d_multi1a'),get_obj('u_multi1a'))"><?=$m_multissid1?>&nbsp;</td>
								<td><input type="checkbox" id="u_multi2a" name="u_multi2a" value="1" onclick="change_state(get_obj('d_multi2a'),get_obj('u_multi2a'))"><?=$m_multissid2?>&nbsp;</td>
								<td><input type="checkbox" id="u_multi3a" name="u_multi3a" value="1" onclick="change_state(get_obj('d_multi3a'),get_obj('u_multi3a'))"><?=$m_multissid3?>&nbsp;</td>
							</tr>			
							<tr>
								<td><input type="checkbox" id="u_multi4a" name="u_multi4a" value="1" onclick="change_state(get_obj('d_multi4a'),get_obj('u_multi4a'))"><?=$m_multissid4?>&nbsp;</td>
								<td><input type="checkbox" id="u_multi5a" name="u_multi5a" value="1" onclick="change_state(get_obj('d_multi5a'),get_obj('u_multi5a'))"><?=$m_multissid5?>&nbsp;</td>
								<td><input type="checkbox" id="u_multi6a" name="u_multi6a" value="1" onclick="change_state(get_obj('d_multi6a'),get_obj('u_multi6a'))"><?=$m_multissid6?>&nbsp;</td>
								<td><input type="checkbox" id="u_multi7a" name="u_multi7a" value="1" onclick="change_state(get_obj('d_multi7a'),get_obj('u_multi7a'))"><?=$m_multissid7?>&nbsp;</td>
							</tr>			
							<tr>
								<td><input type="checkbox" id="u_wds1a" name="u_wds1a" value="1" onclick="change_state(get_obj('d_wds1a'),get_obj('u_wds1a'))"><?=$m_wds1?>&nbsp;</td>
								<td><input type="checkbox" id="u_wds2a" name="u_wds2a" value="1" onclick="change_state(get_obj('d_wds2a'),get_obj('u_wds2a'))"><?=$m_wds2?>&nbsp;</td>
								<td><input type="checkbox" id="u_wds3a" name="u_wds3a" value="1" onclick="change_state(get_obj('d_wds3a'),get_obj('u_wds3a'))"><?=$m_wds3?>&nbsp;</td>
								<td><input type="checkbox" id="u_wds4a" name="u_wds4a" value="1" onclick="change_state(get_obj('d_wds4a'),get_obj('u_wds4a'))"><?=$m_wds4?>&nbsp;</td>
							</tr>
							<tr>
								<td><input type="checkbox" id="u_wds5a" name="u_wds5a" value="1" onclick="change_state(get_obj('d_wds5a'),get_obj('u_wds5a'))"><?=$m_wds5?>&nbsp;</td>
								<td><input type="checkbox" id="u_wds6a" name="u_wds6a" value="1" onclick="change_state(get_obj('d_wds6a'),get_obj('u_wds6a'))"><?=$m_wds6?>&nbsp;</td>
								<td><input type="checkbox" id="u_wds7a" name="u_wds7a" value="1" onclick="change_state(get_obj('d_wds7a'),get_obj('u_wds7a'))"><?=$m_wds7?>&nbsp;</td>
								<td><input type="checkbox" id="u_wds8a" name="u_wds8a" value="1" onclick="change_state(get_obj('d_wds8a'),get_obj('u_wds8a'))"><?=$m_wds8?>&nbsp;</td>
							</tr>
						</table>
        	</fieldset>
				</td>
			</tr>
		</table>
	</td>
</tr>
</tbody>
						</table>
					</td>
				</tr>

				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td width=35% id="td_left">
						<?=$m_downlink_bandwidth?><?=$m_range_a?>
					</td>
					<td>
						<input type="text" id="e2w" name="e2w" maxlength="4" size="6" value="<?=$cfg_e2w?>">Mbits/sec
					</td>
				</tr>
							
				<tr>
					<td width=35% id="td_left">
						<?=$m_uplink_bandwidth?><?=$m_range_a?>
					</td>
					<td>
						<input type="text" id="w2e" name="w2e" maxlength="4" size="6" value="<?=$cfg_w2e?>">Mbits/sec
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
