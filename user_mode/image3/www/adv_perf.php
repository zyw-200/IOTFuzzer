<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_perf";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_perf";
set("/runtime/web/help_page",$MY_NAME);
/* --------------------------------------------------------------------------- */
if($ACTION_POST != "")
{
	require("/www/model/__admin_check.php");
	require("/www/__action_adv.php");
	$ACTION_POST = "";
	//exit;
}

/* --------------------------------------------------------------------------- */
require("/www/model/__html_head.php");
set("/runtime/web/next_page",$first_frame);
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
echo "<!--debug\n";
$check_band = query("/wlan/inf:2/ap_mode");
if($band_reload == 0 || $band_reload == 1) // change band
{	
	echo "reload <br>\n";
	$cfg_band = $band_reload;
}
else
{
	$cfg_band = query("/wlan/ch_mode");
}
echo $cfg_band;
$switch = query("/runtime/web/display/switchable");
if($switch == 1)
{
	anchor("/wlan/inf:1");
}
else
{
	if($cfg_band == 0) // 11g
	{
		echo "anchor 11g";
		anchor("/wlan/inf:1");
	}
	else
	{
		echo "anchor 11a";
		anchor("/wlan/inf:2");
	}
}

if($cfg_band == 0)
{
	$cfg_ack_time=query("acktimeout_g");
	if($cfg_ack_time==""){$cfg_ack_time=48;}
}
else
{
	$cfg_ack_time=query("acktimeout_a");
	if($cfg_ack_time==""){$cfg_ack_time=25;}
}
$cfg_index = $cfg_band+1;
$cfg_wl_enable = query("enable");
$cfg_wlmode = query("wlmode");
$cfg_rate = query("fixedrate");
$cfg_beacon = query("beaconinterval");
$cfg_dtim = query("dtim");
$cfg_frag = query("fraglength");
$cfg_rts = query("rtslength");
$cfg_power = query("txpower");
$cfg_wmm = query("wmm/enable");
$cfg_shortgi = query("shortgi");
$cfg_igmp = query("igmpsnoop");
$cfg_link_integrality =query("ethlink");
$cfg_ap_mode	=query("ap_mode");
$display_ack_time=query("/runtime/web/display/ack_timeout_range");
$cfg_channel_width = query("cwmmode");
$cfg_mcast = query("mcastrate");
$cfg_auth = query("authentication");
$cfg_wepmode = query("wpa/wepmode");
$cfg_ht2040=query("coexistence/enable");
$cfg_m2u = query("/sys/dhcp_mc2uc");
$cfg_display_bandrate_control = query("/runtime/web/display/bandrate_control");
if($cfg_mcast==""){$cfg_mcast=0;}

if($cfg_wepmode==1 || $cfg_auth==9 || $cfg_wepmode==2) //11ag,wep,802.1x,tkip
{$cfg_rate_less = 1;}else{$cfg_rate_less = 0;}
set("/runtime/rate_less", $cfg_rate_less);
$cfg_brate_state = query("/wlan/inf:1/multicast_bwctrl");
$cfg_brate_value = query("/wlan/inf:1/multicast_bw_rate");
$max_datarate_n = query("/sys/data_rate/gmax");
$max_datarate_ac = query("/sys/data_rate/amax");
$m_best_nmax = $m_best_st.$max_datarate_n.$m_best_end;
$m_best_acmax = $m_best_st.$max_datarate_ac.$m_best_end;
echo "-->";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>
var w_wlmode = "";
var w_rate = "";
var w_mcast = "";

function on_change_band(s)
{
	var f = get_obj("frm");
	self.location.href = "adv_perf.php?band_reload=" + s.value;
}

function on_change_wl_enable(s)
{
	//alert(s.value);
}

function on_change_wlmode(s)
{
	//alert(s.value);
	var f = get_obj("frm");
	get_obj("rate_a").style.display = "none";
	get_obj("rate_g").style.display = "none";
	get_obj("rate_best").style.display = "none";
	get_obj("g_mcast_bg").style.display = "none";
    get_obj("g_mcast_bgn").style.display = "none";
    get_obj("a_mcast_a").style.display = "none";
    get_obj("a_mcast_an").style.display = "none";
    get_obj("mcast_n").style.display = "none";
	get_obj("a_mcast_ac").style.display = "none";
	
	if(s.value != 2) // NOT Mixed g_b, a only
	{
		f.shortgi.disabled=false;
		if("<?=$cfg_auth?>" == "1" || "<?=$cfg_auth?>" == "9" || "<?=$cfg_wepmode?>" == "2" || "<?=$cfg_wepmode?>" == "1")
		{f.shortgi.disabled=true;}
		get_obj("rate_best").style.display = "";
		w_rate = get_obj("rate_best");
		w_rate.disabled = true;
		w_rate.options[0].text = "<?=$m_best_nmax?>";
		if(s.value == 3)
		{
			f.wmm.value = 1;
	        f.wmm.disabled = true;
			get_obj("mcast_n").style.display = "";
			get_obj("mcast_n").disabled = true;
			w_mcast = get_obj("mcast_n");
		}
		else if(s.value == 1)
		{
			f.wmm.value = 1;
	        f.wmm.disabled = true;
			if("<?=$cfg_band?>" == 0)
			{
				if("<?=$cfg_rate_less?>" == 0)
				{
					get_obj("g_mcast_bgn").style.display = "";
					w_mcast = get_obj("g_mcast_bgn");
				}
				else
				{
					get_obj("g_mcast_bg").style.display = "";
					w_mcast = get_obj("g_mcast_bg");
				}
			}
			else
			{
				if("<?=$cfg_rate_less?>" == 0)
                {
                    get_obj("a_mcast_an").style.display = "";
					w_mcast = get_obj("a_mcast_an");
                }
                else
                {
                    get_obj("a_mcast_a").style.display = "";
					w_mcast = get_obj("a_mcast_a");
                }
			}
		}
		else if(s.value ==4 )
			{		//modified by yuejun for wmm
					f.wmm.value = 1;
					f.wmm.disabled = true;
					get_obj("a_mcast_ac").style.display = "";
					get_obj("a_mcast_ac").disabled = true;
					w_mcast = get_obj("a_mcast_ac");
					w_rate.options[0].text = "<?=$m_best_acmax?>";
			}
	}
	else
	{
		if("<?=$cfg_band?>" == 0)
		{
			get_obj("g_mcast_bg").style.display = "";
			w_mcast = get_obj("g_mcast_bg");
			get_obj("rate_g").style.display = "";
			w_rate = get_obj("rate_g");
		}
		else
		{
			get_obj("a_mcast_a").style.display = "";
			w_mcast = get_obj("a_mcast_a");
			get_obj("rate_a").style.display = "";
			w_rate = get_obj("rate_a");
		}
		w_rate.disabled = false;
		select_index(w_rate, "<?=$cfg_rate?>");
		f.wmm.disabled = false;
		f.shortgi.disabled=true;
		select_index(f.shortgi, "0");
	}
}

function on_change_power(s)
{
	//alert(s.value);
}

function on_change_wmm(s)
{
	//alert(s.value);
}

function on_change_shortgi(s)
{
	//alert(s.value);
}

function on_change_mcast()
{
	var f = get_obj("frm");
    if(w_mcast.value !=0)
    {
        f.igmp.value=0;
    }
}
function on_change_igmp(s)
{
	var f = get_obj("frm");
	if(f.igmp.value==1)
	{
		select_index(w_mcast, "0");
	}
	//alert(s.value);
}

/* page init functoin */
function init()
{
	var f = get_obj("frm");

	get_obj("wlmode_a").style.display = "none";
	get_obj("wlmode_sw").style.display = "none";
	get_obj("wlmode_ac").style.display = "none";
	get_obj("wlmode_g").style.display = "none";
	select_index(f.wl_enable, "<?=$cfg_wl_enable?>");
	
	if("<?=$cfg_band?>" == 0) // 11g
	{
		get_obj("wlmode_g").style.display = "";
		w_wlmode = get_obj("wlmode_g");
		get_obj("ack_timeout_msg").innerHTML = "<?=$m_ack_timeout_g_msg?>";
	}	
	else 
	{
		if("<?=$switch?>" == 1 || "<?=$TITLE?>" == "DAP-2690b")
		{
			get_obj("wlmode_sw").style.display = "";
			w_wlmode = get_obj("wlmode_sw");
		}
		else
		{
			get_obj("wlmode_ac").style.display = "";
			w_wlmode = get_obj("wlmode_ac");
		}
		get_obj("ack_timeout_msg").innerHTML = "<?=$m_ack_timeout_a_msg?>";
	}	
	 if("<?=$cfg_channel_width?>"==0 || "<?=$cfg_ap_mode?>"==4 || "<?=$cfg_ap_mode?>"==1 || "<?=$cfg_band?>"==1)
        {
                f.ht_status.disabled=true;
        }
        else
        {
                f.ht_status.disabled=false;
        }
	select_index(f.band, "<?=$cfg_band?>");
  	if("<?=$check_band?>" == "")
		f.band.disabled = true;
	select_index(w_wlmode, "<?=$cfg_wlmode?>");
	select_index(f.wmm, "<?=$cfg_wmm?>");
	on_change_wlmode(w_wlmode);
	if("<?=$cfg_ap_mode?>" =="1") //apc
		{
			w_wlmode.disabled = true;
		w_mcast.disabled = true;
  	}
	if(f.frag != null)
	{
		f.frag.value = "<?=$cfg_frag?>";	
	}
	if(f.rts != null)
	{
		f.rts.value = "<?=$cfg_rts?>";	
	}	

	select_index(f.power, "<?=$cfg_power?>");
	f.ack_timeout.value = "<?=$cfg_ack_time?>";
	if("<?=$cfg_ap_mode?>"==1)
	{	
		if("<?=$cfg_channel_width?>"==0)
		{
			select_index(f.shortgi, "0");
			f.shortgi.disabled=true;
		}
		else
		{
			select_index(f.shortgi, "1");
			f.shortgi.disabled=true;
		}
	}
	else
	{
	select_index(f.shortgi, "<?=$cfg_shortgi?>");
	}
	//select_index(f.link_integrality, "<?=$cfg_link_integrality?>");
	
	select_index(f.igmp, "<?=$cfg_igmp?>");		
		
	if("<?=$cfg_ap_mode?>" =="1") //apc
	{
		f.dtim.disabled = true;
		f.dtim.value="";
		f.bi.disabled = true;
		f.bi.value="";
		f.igmp.disabled = true;
	}
	else if("<?=$cfg_ap_mode?>" =="3" || "<?=$cfg_ap_mode?>" =="4")//WDS
	{
		f.bi.value = "<?=$cfg_beacon?>";
		f.dtim.value = "<?=$cfg_dtim?>";
		if("<?=$cfg_ap_mode?>" =="4")
		{		
			f.igmp.disabled = true;		
		}	
	}	
	else
	{
		f.bi.value = "<?=$cfg_beacon?>";
		f.dtim.value = "<?=$cfg_dtim?>";				
	}
	if("<?=$cfg_display_bandrate_control?>" == "1")
	{
		if("<?=$cfg_ap_mode?>"==1 || "<?=$cfg_ap_mode?>"==4)
    {
       	f.bandrate_state.disabled=true;
    }
    else
    {
    		f.bandrate_state.disabled=false;
    }
	}
	if(("<?=$cfg_band?>"==0 && "<?=$cfg_rate_less?>"==1 && "<?=$cfg_mcast?>">12) || ("<?=$cfg_band?>"==1 && "<?=$cfg_rate_less?>"==1 && "<?=$cfg_mcast?>">8))
	{select_index(w_mcast, "0");}
    else
	{select_index(w_mcast, "<?=$cfg_mcast?>");}
		select_index(f.ht_status, "<?=$cfg_ht2040?>");
	if(f.bandrate_state != null)
	{
	        f.band_rate.value = "<?=$cfg_brate_value?>";
	        select_index(f.bandrate_state, "<?=$cfg_brate_state?>");
		f.bandrate_state.value = "<?=$cfg_brate_state?>";
		on_change_bandrate_state();
	}
	if(f.m2u_status != null)
	{
		select_index(f.m2u_status, "<?=$cfg_m2u?>")
		if("<?=$cfg_ap_mode?>" != 0 && "<?=$cfg_ap_mode?>" != 3)
			f.m2u_status.disabled = true;
	}
	AdjustHeight();
}

function on_change_bandrate_state()
{
	var f=get_obj("frm");
	if(f.bandrate_state.value == 0)
	{
		f.band_rate.disabled = true;
	}
	else
	{
		f.band_rate.disabled = false;
	}
}

function on_change_ht_status()
{
	var f=get_obj("frm");
	f.f_coexistence.value = f.ht_status.value;
}
/* parameter checking */
function check()
{
	var f=get_obj("frm");
	
	if(f.wl_enable.value != "0")
	{
		if("<?=$cfg_ap_mode?>" !="1") //apc
		{
			if(!is_in_range(f.bi.value,40,500))
			{
				alert("<?=$a_invalid_bi?>");
				if(f.bi.value=="") f.bi.value=100;
				field_select(f.bi);
				return false;
			}	
			if(!is_in_range(f.dtim.value,1,15))
			{
				alert("<?=$a_invalid_dtim?>");
				if(f.dtim.value=="") f.dtim.value=1;
				field_select(f.dtim);
				return false;
			}						
		}
		if(f.frag != null)
		{
			if(!is_in_range(f.frag.value,256,2346))
			{
				alert("<?=$a_invalid_frag?>");
				if(f.frag.value=="") f.frag.value=2346;
				field_select(f.frag);
				return false;
			}		
		}
		if(f.rts != null)
		{
			if(!is_in_range(f.rts.value,256,2346))
			{
				alert("<?=$a_invalid_rts?>");
				if(f.rts.value=="") f.rts.value=2346;
				field_select(f.rts);
				return false;
			}		
		}
		if ("<?=$cfg_band?>"==1)
		{
			if("<?=$display_ack_time?>" != "1")
			{
			if(!is_in_range(f.ack_timeout.value,25,200))
			{
				alert("<?=$a_invalid_ack_timeouta?>");
				if(f.ack_timeout.value=="") f.ack_timeout.value=25;
				field_select(f.ack_timeout);
				return false;
			}		
		}
		else
		{
				if(!is_in_range(f.ack_timeout.value,50,200))
				{
					alert("<?=$a_invalid_ack_timeouta?>");
					if(f.ack_timeout.value=="") f.ack_timeout.value=50;
					field_select(f.ack_timeout);
					return false;
				}
			}
		}
		else
		{
			if("<?=$display_ack_time?>" != "1")
			{
			if(!is_in_range(f.ack_timeout.value,48,200))
			{
				alert("<?=$a_invalid_ack_timeoutg?>");
				if(f.ack_timeout.value=="") f.ack_timeout.value=48;
				field_select(f.ack_timeout);
				return false;
			}		
		}
			else
			{
				if(!is_in_range(f.ack_timeout.value,64,200))
				{
					alert("<?=$a_invalid_ack_timeoutg?>");
					if(f.ack_timeout.value=="") f.ack_timeout.value=64;
					field_select(f.ack_timeout);
					return false;
				}	
			}
		}
	
		if(f.bandrate_state != null)
		{
			if(f.bandrate_state.value == 1)
			{
				if(!is_digit(f.band_rate.value))
				{
					alert("<?=$a_invalid_bandrate?>");
					f.band_rate.select();
					return false;
				}
				if(parseInt(f.band_rate.value, [10]) < 1 || parseInt(f.band_rate.value, [10]) > 1024)
				{
					alert("<?=$a_invalid_bandrate?>");
					f.band_rate.select();
					return false;
				}
			}
		}
		if("<?=$check_band?>" != "")
		{
			if(f.bandrate_state != null)
			{
				if(f.bandrate_state.value != "<?=$cfg_brate_state?>" || (f.bandrate_state.value == 1 && f.band_rate.value != "<?=$cfg_brate_value?>"))	
					alert("<?=$a_two_band_share_the_same_bw?>");
			}
			if(f.m2u_status != null)
			{
				if(f.m2u_status.value != "<?=$cfg_m2u?>")
					alert("<?=$a_two_band_share_m2u?>");
			}
		}
	}
	f.f_wlmode.value = w_wlmode.value;
	f.f_rate.value = w_rate.value;
	f.f_mcast.value = w_mcast.value;
	f.f_coexistence.value = f.ht_status.value;
	fields_disabled(f, false);
	return true;
}

function submit()
{
	if(check()) get_obj("frm").submit();
}

</script>

<body <?=$G_BODY_ATTR?> onload="init();">

<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">

<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="f_wlmode"		value="">
<input type="hidden" name="f_rate"		value="">
<input type="hidden" name="f_mcast"      value="">
<input type="hidden" name="f_coexistence" value="">
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
					<td width="35%" id="td_left">
						<?=$m_band?> 
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("band", [0,1], ["<?=$m_band_2.4G?>","<?=$m_band_5G?>"], "on_change_band(this)");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
				<tr>
					<td width="35%" id="td_left">
						<?=$m_wl_enable?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("wl_enable", [0,1], ["<?=$m_off?>","<?=$m_on?>"], "on_change_wl_enable(this)");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>				
				<tr>
					<td id="td_left">
						<?=$m_wlmode?>
					</td>
<? if(query("wpa/wepmode") =="1" || query("wpa/wepmode") =="2" || query("authentication")=="9")	{echo "<!--";} ?>						
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("wlmode_g", [1,2,3], ["<?=$m_wlmode_n_g_b?>","<?=$m_wlmode_g_b?>","<?=$m_wlmode_n?>"], "on_change_wlmode(this)");<?=$G_TAG_SCRIPT_END?>
						<?=$G_TAG_SCRIPT_START?>genSelect("wlmode_a", [1,2], ["<?=$m_wlmode_n_a?>","<?=$m_wlmode_a?>"], "on_change_wlmode(this)");<?=$G_TAG_SCRIPT_END?>
						<?=$G_TAG_SCRIPT_START?>genSelect("wlmode_sw", [1,2,3], ["<?=$m_wlmode_n_a?>","<?=$m_wlmode_a?>","<?=$m_wlmode_n?>"], "on_change_wlmode(this)");<?=$G_TAG_SCRIPT_END?>
						<?=$G_TAG_SCRIPT_START?>genSelect("wlmode_ac", [1,2,3,4], ["<?=$m_wlmode_n_a?>","<?=$m_wlmode_a?>","<?=$m_wlmode_n?>","<?=$m_wlmode_ac?>"], "on_change_wlmode(this)");<?=$G_TAG_SCRIPT_END?>
					</td>
<? if(query("wpa/wepmode") =="1" || query("wpa/wepmode") =="2" || query("authentication")=="9") {echo "-->";} ?>
<? if(query("wpa/wepmode") !="1" && query("wpa/wepmode") !="2" && query("authentication")!="9")	{echo "<!--";} ?>						
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("wlmode_g", [1,2], ["<?=$m_wlmode_n_g_b?>","<?=$m_wlmode_g_b?>"], "on_change_wlmode(this)");<?=$G_TAG_SCRIPT_END?>
						<?=$G_TAG_SCRIPT_START?>genSelect("wlmode_a", [1,2], ["<?=$m_wlmode_n_a?>","<?=$m_wlmode_a?>"], "on_change_wlmode(this)");<?=$G_TAG_SCRIPT_END?>
						<?=$G_TAG_SCRIPT_START?>genSelect("wlmode_sw", [1,2,3], ["<?=$m_wlmode_n_a?>","<?=$m_wlmode_a?>","<?=$m_wlmode_n?>"], "on_change_wlmode(this)");<?=$G_TAG_SCRIPT_END?>
							<?=$G_TAG_SCRIPT_START?>genSelect("wlmode_ac", [1,2,3,4], ["<?=$m_wlmode_n_a?>","<?=$m_wlmode_a?>","<?=$m_wlmode_n?>","<?=$m_wlmode_ac?>"], "on_change_wlmode(this)");<?=$G_TAG_SCRIPT_END?>
					</td>
<? if(query("wpa/wepmode") !="1" && query("wpa/wepmode") !="2" && query("authentication")!="9") {echo "-->";} ?>						
				</tr>				
				<tr>
					<td id="td_left">
						<?=$m_rate?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("rate_g", [11,10,9,8,7,6,5,4,3,2,1,0], ["<?=$m_54?>","<?=$m_48?>","<?=$m_36?>","<?=$m_24?>","<?=$m_18?>","<?=$m_12?>","<?=$m_9?>","<?=$m_6?>","<?=$m_11?>","<?=$m_5.5?>","<?=$m_2?>","<?=$m_1?>"], "");<?=$G_TAG_SCRIPT_END?>
						<?=$G_TAG_SCRIPT_START?>genSelect("rate_a", [7,6,5,4,3,2,1,0], ["<?=$m_54?>","<?=$m_48?>","<?=$m_36?>","<?=$m_24?>","<?=$m_18?>","<?=$m_12?>","<?=$m_9?>","<?=$m_6?>"], "");<?=$G_TAG_SCRIPT_END?>
						<?=$G_TAG_SCRIPT_START?>genSelect("rate_best", [31], ["<?=$m_best_nmax?>"], "");<?=$G_TAG_SCRIPT_END?>
						&nbsp;<?=$m_mbps?>
					</td>
				</tr>	
				<tr>
					<td id="td_left">
						<?=$m_beacon_interval?>
					</td>
					<td id="td_right">
						<input name="bi" id="bi" class="text" type="text" size="10" maxlength="3" value="">
					</td>
				</tr>	
				<tr>
					<td id="td_left">
						<?=$m_dtim?>
					</td>
					<td id="td_right">
						<input name="dtim" id="dtim" class="text" type="text" size="10" maxlength="2" value="">
					</td>
				</tr>
<? if(query("/runtime/web/display/frag") != "1")	{echo "<!--";} ?>									
				<tr>
					<td id="td_left">
						<?=$m_frag?>
					</td>
					<td id="td_right">
						<input name="frag" id="frag" class="text" type="text" size="10" maxlength="4" value="">
					</td>
				</tr>
<? if(query("/runtime/web/display/frag") != "1") {echo "-->";} ?>
<? if(query("/runtime/web/display/rts") != "1")	{echo "<!--";} ?>	
								<tr>
					<td id="td_left">
						<?=$m_rts?>
					</td>
					<td id="td_right">
						<input name="rts" id="rts" class="text" type="text" size="10" maxlength="4" value="">
					</td>
				</tr>	
<? if(query("/runtime/web/display/rts") != "1") {echo "-->";} ?>
				<tr>
					<td id="td_left">
						<?=$m_power?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("power", [1,2,3,4], ["<?=$m_100?>%","<?=$m_50?>%","<?=$m_25?>%","<?=$m_12.5?>%"], "on_change_power(this)");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>	
				<tr>
					<td id="td_left">
						<?=$m_wmm?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("wmm", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"], "on_change_wmm(this)");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>	
				<tr>
					<td id="td_left">
						<?=$m_ack_timeout?>&nbsp;<span id="ack_timeout_msg"><?=$m_ack_timeout_g_msg?><span>
					</td>
					<td id="td_right">
						<input name="ack_timeout" id="ack_timeout" class="text" type="text" size="10" maxlength="3" value="">&nbsp;<?=$m_ms?>
					</td>
				</tr>	
				<tr>
					<td id="td_left">
						<?=$m_shortgi?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("shortgi", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"], "on_change_shortgi(this)");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>		
				<tr>
					<td id="td_left">
						<?=$m_igmp?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("igmp", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"], "on_change_igmp(this)");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>			
<!--				<tr>
					<td id="td_left">
						<?=$m_link_integrality?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("link_integrality", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"], "");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>-->	
				<tr>
                    <td width="35%" id="td_left">
                        <?=$m_multicast_rate?>
                    </td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>
							genSelect("g_mcast_bgn", [0,1,2,3,4,5,6,7,8,9,10,11,12], ["<?=$m_disable?>","1","2","5.5","11","6","9","12","18","24","36","48","54"], "on_change_mcast();");
							genSelect("g_mcast_bg", [0,1,2,3,4,5,6,7,8,9,10,11,12], ["<?=$m_disable?>","1","2","5.5","11","6","9","12","18","24","36","48","54"], "on_change_mcast();");
							genSelect("a_mcast_an", [0,1,2,3,4,5,6,7,8], ["<?=$m_disable?>","6","9","12","18","24","36","48","54"], "on_change_mcast();");
							genSelect("a_mcast_a", [0,1,2,3,4,5,6,7,8], ["<?=$m_disable?>","6","9","12","18","24","36","48","54"], "on_change_mcast();");
							genSelect("mcast_n", [0,1,2,3,4,5,6,7,8,9,10,11,12], ["<?=$m_disable?>","6.5","13","19.5","26","39","52","58.5","65","78","104","117","130"], "on_change_mcast();");							
								genSelect("a_mcast_ac", [0,1,2,3,4,5,6,7,8], ["<?=$m_disable?>","6","9","12","18","24","36","48","54"], "on_change_mcast();");
						<?=$G_TAG_SCRIPT_END?><?=$m_mbps?>
					</td>
                </tr>
<? if(query("/runtime/web/display/bandrate_control") !="1") {echo "<!--";} ?>
				<tr>
					<td id="td_left">
						<?=$m_bandrate_state?>
					</td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("bandrate_state", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"], "on_change_bandrate_state()");<?=$G_TAG_SCRIPT_END?>
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_bandrate?>
					</td>
					<td id="td_right">
						<input name="band_rate" id="band_rate" class="text" type="text" size="10" maxlength="4" value="<?=$cfg_brate_value?>">kbps
					</td>	
				</tr>
<? if(query("/runtime/web/display/bandrate_control") !="1") {echo "-->";} ?>
<tr>
					<td id="td_left"><?=$m_ht2040?></td>
					<td id="td_right">
						<?=$G_TAG_SCRIPT_START?>genSelect("ht_status", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"], "on_change_ht_status()");<?=$G_TAG_SCRIPT_END?>
					</td>
		</tr>
<? if(query("/runtime/web/display/multi2uni") !="1")  {echo "<!--";} ?>
				<tr>
					<td id="td_left"><?=$m_m2u?></td>
					<td id="td_right"><?=$G_TAG_SCRIPT_START?>genSelect("m2u_status", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"], "");<?=$G_TAG_SCRIPT_END?></td>
				</tr>
<? if(query("/runtime/web/display/multi2uni") !="1")  {echo "-->";} ?>
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
				
