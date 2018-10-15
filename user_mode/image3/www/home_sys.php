<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME		="home_sys";
$MY_MSG_FILE	=$MY_NAME.".php";
$NEXT_PAGE	="home_sys";
set("/runtime/web/help_page",$MY_NAME);
/* --------------------------------------------------------------------------- */
if ($ACTION_POST!="")
{
	require("/www/model/__admin_check.php");
	require("/www/__action.php");
	$ACTION_POST="";
	
	//$db_dirty=0;

	if($db_dirty > 0)	{$SUBMIT_STR="submit ";}
	else				{$SUBMIT_STR="";}

	$NEXT_PAGE=$MY_NAME;
	if($SUBMIT_STR!="")	{require($G_SAVING_URL);}
	else				{require($G_NO_CHANGED_URL);}
}

/* --------------------------------------------------------------------------- */
require("/www/model/__html_head.php");
require("/www/comm/__js_ip.php");
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.	
$cfg_ipv6 = query("/inet/entry:1/ipv6/valid");
$cfg_ipv6_lan_type = query("/inet/entry:1/ipv6/mode");
if($cfg_ipv6_lan_type == "auto")
{
	$wanipaddr6 = query("/runtime/inet/entry:1/ipv6/ipaddr");
}
else
{
	$wanipaddr6 = query("/inet/entry:1/ipv6/ipaddr");
}
$cfg_link_ip = query("/runtime/inet/entry:1/ipv6/linkipaddr");
if($wanipaddr6 == $cfg_link_ip)
{$cfg_same_ip = 1;}
else
{$cfg_same_ip = 0;}

$check_band = query("/wlan/inf:2/ap_mode");

$cfg_mssid_state = query("/wlan/inf:1/multi/state");
$cfg_date	= query("/runtime/time/date");
$cfg_time	= query("/runtime/time/time");
$cfg_name	= query("/sys/hostname");
$cfg_ap_mode ="";	
$cfg_sysname = get("h","/sys/systemName");
$cfg_location = get("h","/sys/systemLocation");
$cfg_fdate = query("/runtime/sys/info/firmwarebuildate");
$ssid_flag = query("/runtime/web/display/mssid_index4");

anchor("/runtime/wan/inf:1");
$cfg_ip	= query("ip");
$cfg_mac = query("mac");
$cfg_mac_s = query("macstart");
$cfg_mac_e = query("macend");

$cfg_mode = query("/wlan/inf:1/ap_mode");
if($cfg_mode == 0)
{
	$cfg_mode = $m_ap;	
}
else if($cfg_mode == 1)
{
	$cfg_mode = $m_wireless_client;	
}
else if($cfg_mode == 3)
{
	$cfg_mode = $m_wds_ap;	
}
else if($cfg_mode == 4)
{
	$cfg_mode = $m_wds;	
}
else if($cfg_mode == 2)
{
	$cfg_mode = $m_ap_repeater;
}

$cfg_mssid_state_5g = query("/wlan/inf:2/multi/state");

$cfg_mac_5g = query("/runtime/layout/wlanmac_a");
$cfg_mac_5g_s = query("/runtime/wan/inf:2/macstart");
$cfg_mac_5g_e = query("/runtime/wan/inf:2/macend");

$cfg_mode_5g = query("/wlan/inf:2/ap_mode");
if($cfg_mode_5g == 0)
{
    $cfg_mode_5g = $m_ap;  
}
else if($cfg_mode_5g == 1)
{
    $cfg_mode_5g = $m_wireless_client; 
}
else if($cfg_mode_5g == 3)
{
    $cfg_mode_5g = $m_wds_ap;  
}
else if($cfg_mode_5g == 4)
{
    $cfg_mode_5g = $m_wds; 
}

/* --------------------------------------------------------------------------- */
?>

<script>
function shortTime()
{
	t="<?query("/runtime/sys/uptime");?>";

	var str=new String("");
	var tmp=parseInt(t, [10]);
	var sec=0,min=0,hr=0,day=0;
	sec=t % 60;  //sec
	min=parseInt(t/60, [10]) % 60; //min
	hr=parseInt(t/(60*60), [10]) % 24; //hr
	day=parseInt(t/(60*60*24), [10]); //day

	if(day>=0 || hr>=0 || min>=0 || sec >=0)
		str=(day >0? day+" <?=$m_days?>, ":"0 <?=$m_days?>, ")+(hr >0? hr+":":"00:")+(min >0? min+":":"00:")+(sec >0? sec :"00");
	document.write(str);
}
/* page init functoin */
function init()
{
	get_obj("single_band").style.display = "none";
	get_obj("two_bands").style.display = "none";
	if("<?=$check_band?>" == "")
	{
		get_obj("single_band").style.display = "";
	}
	else
	{
		get_obj("two_bands").style.display = "";
	}
	AdjustHeight();
}

/* parameter checking */
function print_ms_mac(id)
{
	var mac,mac_addr,temp;

	mac_addr = get_mac("<?=$cfg_mac?>");
	if("<?=$TITLE?>"=="DAP-2590")
	{
		if(id == 1)
	{
		temp=parseInt(mac_addr[6], [16])+1;
		if(temp < 0x10)
		{		
			mac_addr[6]="0"+temp.toString(16);
	}
	else
	{
			mac_addr[6]=temp.toString(16);
		}
		mac = mac_addr[1]+":"+ mac_addr[2]+":"+mac_addr[3]+":"+ mac_addr[4]+":"+ mac_addr[5]+":"+mac_addr[6];
	}
	else
	{
		temp=parseInt(mac_addr[6], [16])+7;
		if(temp < 0x10)
		{	
			mac_addr[6]="0"+temp.toString(16);
		}
		else
		{
			mac_addr[6]=temp.toString(16);
		}
		mac = mac_addr[1]+":"+ mac_addr[2]+":"+mac_addr[3]+":"+ mac_addr[4]+":"+ mac_addr[5]+":"+mac_addr[6];
		
		}
	}
	else
	{
	if(id == 1)
	{
		mac = "02:"+ mac_addr[2]+":"+mac_addr[3]+":"+ mac_addr[4]+":"+ mac_addr[5]+":"+mac_addr[6];
	}
	else
	{
		if("<?=$ssid_flag?>" == 1)
		{
			mac = "06:"+ mac_addr[2]+":"+mac_addr[3]+":"+ mac_addr[4]+":"+ mac_addr[5]+":"+mac_addr[6];
		}
		else
		{	
		mac = "1E:"+ mac_addr[2]+":"+mac_addr[3]+":"+ mac_addr[4]+":"+ mac_addr[5]+":"+mac_addr[6];
		}
	}
	}
	document.write(mac);
}

function check()
{
	
}

</script>
<body onload="init();" bgcolor="#CCDCE2" leftmargin="0" marginheight="0" marginwidth="0" topmargin="0" style="overflow-x: auto; overflow-y: auto;" >
<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">
<input type="hidden" name="ACTION_POST" value="SOMETHING">
<table id="table_frame" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td valign="top">
		<table id="table_header" cellspacing="0" cellpadding="0">
		<tr>
			<td id="td_header" valign="middle"><?=$m_context_title?></td>
		</tr>	
		</table>
		<table id="table_set_main"  border="0"  cellspacing="0" cellpadding="0">
<!-- ________________________________ Main Content Start ______________________________ -->

		<tr>	
			<td width="150" height="25" id="td_left"><?=$m_model_name?></td>	
			<td id="td_right"><?=$cfg_name?></td>	
		</tr>
		<tr>	
			<td height="25"  id="td_left"><?=$m_firm_version?></td>	
			<td id="td_right"><?query("/runtime/sys/info/firmwareversion");?>&nbsp;&nbsp;&nbsp;<?=$cfg_fdate?></td>	
		</tr>	
		<tr>	
			<td height="25"  id="td_left"><?=$m_sysname?></td>	
			<td id="td_right"><?=$cfg_sysname?></td>	
		</tr>	
		<tr>	
			<td height="25"  id="td_left"><?=$m_location?></td>	
			<td id="td_right"><?=$cfg_location?></td>	
		</tr>	
		<tr>	
			<td height="25"  id="td_left"><?=$m_sys_time?></td>	
			<td id="td_right"><?=$cfg_date?>&nbsp;<?=$cfg_time?></td>	
		</tr>	
		<tr>	
			<td height="25"  id="td_left"><?=$m_up_time?></td>	
			<td id="td_right"><?=$G_TAG_SCRIPT_START?>shortTime();<?=$G_TAG_SCRIPT_END?></td>	
		</tr>	
		<tbody id="single_band">
		<tr>	
			<td height="25"  id="td_left"><?=$m_ap_mode?></td>	
			<td id="td_right"><?=$cfg_mode?></td>	
		</tr>			
		<tr>	
			<td height="25"  id="td_left"><?=$m_mac?></td>	
			<td id="td_right"><?=$cfg_mac?></td>	
		</tr>		
		<tr>
			<td height="25"  id="td_left"><?=$m_mssid?></td>
			<td id="td_right"><?=$cfg_mac_s?> ~ <?=$cfg_mac_e?></td>
		</tr>
		</tbody>
		<tbody id="two_bands">
		<tr>	
			<td height="25"  id="td_left"><?=$m_ap_mode?>(2.4GHz)</td>	
			<td id="td_right"><?=$cfg_mode?></td>	
		</tr>			
		<tr>    
            <td height="25"  id="td_left"><?=$m_ap_mode?>(5GHz)</td>  
            <td id="td_right"><?=$cfg_mode_5g?></td>   
        </tr>
		<tr>	
			<td height="25"  id="td_left"><?=$m_mac?>(2.4GHz)</td>	
			<td id="td_right"><?=$cfg_mac?></td>	
		</tr>		
	<? if($cfg_mssid_state !="1")	{echo "<!--";} ?>
		<tr>	
			<td height="25"  id="td_left"><?=$m_mssid?>(2.4GHz)</td>	
			<td id="td_right"><?=$cfg_mac_s?> ~ <?=$cfg_mac_e?></td>	
		</tr>	
	<? if($cfg_mssid_state !="1")	{echo "-->";} ?>		
	<tr>	
			<td height="25"  id="td_left"><?=$m_mac?>(5GHz)</td>	
			<td id="td_right"><?=$cfg_mac_5g?></td>	
		</tr>		
	<? if($cfg_mssid_state_5g !="1")	{echo "<!--";} ?>
		<tr>	
			<td height="25"  id="td_left"><?=$m_mssid?>(5GHz)</td>	
			<td id="td_right"><?=$cfg_mac_5g_s?> ~ <?=$cfg_mac_5g_e?></td>	
		</tr>	
	<? if($cfg_mssid_state_5g !="1")	{echo "-->";} ?>		
		</tbody>
		<tr>	
			<td height="25"  id="td_left"><?=$m_ip?></td>	
			<td id="td_right"><?=$cfg_ip?></td>	
		</tr>	
<? if($cfg_ipv6!="1")	{echo "<!--";} ?>
<?
if($cfg_same_ip == "0")
{
	echo "<tr>\n";
	echo "<td height=\"25\"  id=\"td_left\">".$m_ipv6_ip."</td>\n";
	echo "<td id=\"td_right\">".$wanipaddr6."</td>\n";
	echo "</tr>\n";
}
?>
		<tr>	
			<td height="25"  id="td_left"><?=$m_link_ip?></td>	
			<td id="td_right"><?=$cfg_link_ip?></td>	
		</tr>	
<? if($cfg_ipv6!="1")	{echo "-->";} ?>				
<!-- ________________________________  Main Content End _______________________________ -->
</table>	
</form>
</body>
</html>
