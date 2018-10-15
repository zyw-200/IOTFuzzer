<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "st_device";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "st_device";
set("/runtime/web/help_page",$MY_NAME);
/* --------------------------------------------------------------------------- */
if($ACTION_POST != "")
{
	require("/www/model/__admin_check.php");
	require("/www/__action.php");
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
	anchor("/wlan/inf:1");
$ssid = get(h,"ssid");
$cfg_auto_ch_scan = query("autochannel");
$cfg_band = query("/wlan/ch_mode");
$cfg_apmode = query("ap_mode");
if ($cfg_auto_ch_scan==1 || $cfg_apmode==1)
{
	$channel = query("/runtime/stats/wlan/inf:1/channel");
}
else
{
	$channel = query("channel");
}
$data_rate = query("fixedrate");
$ssid_5g = get(h,"/wlan/inf:2/ssid");
$cfg_auto_ch_scan_5g = query("/wlan/inf:2/autochannel");
$cfg_apmode_5g = query("/wlan/inf:2/ap_mode");
//if ($cfg_auto_ch_scan_5g==1 || $cfg_apmode_5g==1)
//{
$channel_5g = query("/runtime/stats/wlan/inf:2/channel");
//}
//else
//{
//	$channel_5g = query("/wlan/inf:2/channel");
//}
$data_rate_5g = query("/wlan/inf:2/fixedrate");
$check_band = query("/wlan/inf:2/ap_mode");
if($check_band == "")
{
	if($cfg_band == 0)
	{
		if($data_rate == 31)
		{
			$data_rate = $m_auto;
		}
		else if($data_rate == 11)
		{
			$data_rate = $m_54;
		}
		else if($data_rate == 10)
		{
			$data_rate = $m_48;
		}
		else if($data_rate == 9)
		{
			$data_rate = $m_36;
		}
		else if($data_rate == 8)
		{
			$data_rate = $m_24;
		}
		else if($data_rate == 7)
		{
			$data_rate = $m_18;
		}
		else if($data_rate == 6)
		{
			$data_rate = $m_12;
		}
		else if($data_rate == 5)
		{
			$data_rate = $m_9;
		}
		else if($data_rate == 4)
		{
			$data_rate = $m_6;
		}
		else if($data_rate == 3)
		{
			$data_rate = $m_11;
		}
		else if($data_rate == 2)
		{
			$data_rate = $m_5;
		}
		else if($data_rate == 1)
		{
			$data_rate = $m_2;
		}
		else if($data_rate == 0)
		{
			$data_rate = $m_1;
		}
	}
	else
	{
		if($data_rate == 31)
		{
			$data_rate = $m_auto;
		}	
		else if($data_rate == 7)
		{
			$data_rate = $m_54;
		}		
		else if($data_rate == 6)
		{
			$data_rate = $m_48;
		}	
		else if($data_rate == 5)
		{
			$data_rate = $m_36;
		}	
		else if($data_rate == 4)
		{
			$data_rate = $m_24;
		}	
		else if($data_rate == 3)
		{
			$data_rate = $m_18;
		}	
		else if($data_rate == 2)
		{
			$data_rate = $m_12;
		}	
		else if($data_rate == 1)
		{
			$data_rate = $m_9;
		}	
		else if($data_rate == 0)
		{
			$data_rate = $m_6;
		}								
	}
}
else
{
	if($data_rate == 31)
	{
		$data_rate = $m_auto;
	}
	else if($data_rate == 11)
	{
		$data_rate = $m_54;
	}
	else if($data_rate == 10)
	{
		$data_rate = $m_48;
	}
	else if($data_rate == 9)
	{
		$data_rate = $m_36;
	}
	else if($data_rate == 8)
	{
		$data_rate = $m_24;
	}
	else if($data_rate == 7)
	{
		$data_rate = $m_18;
	}
	else if($data_rate == 6)
	{
		$data_rate = $m_12;
	}
	else if($data_rate == 5)
	{
		$data_rate = $m_9;
	}
	else if($data_rate == 4)
	{
		$data_rate = $m_6;
	}
	else if($data_rate == 3)
	{
		$data_rate = $m_11;
	}
	else if($data_rate == 2)
	{
		$data_rate = $m_5;
	}
	else if($data_rate == 1)
	{
		$data_rate = $m_2;
	}
	else if($data_rate == 0)
	{
		$data_rate = $m_1;
	}
	if($data_rate_5g == 31)
	{
		$data_rate_5g = $m_auto;
	}	
	else if($data_rate_5g == 7)
	{
		$data_rate_5g = $m_54;
	}		
	else if($data_rate_5g == 6)
	{
		$data_rate_5g = $m_48;
	}	
	else if($data_rate_5g == 5)
	{
		$data_rate_5g = $m_36;
	}	
	else if($data_rate_5g == 4)
	{
		$data_rate_5g = $m_24;
	}	
	else if($data_rate_5g == 3)
	{
		$data_rate_5g = $m_18;
	}	
	else if($data_rate_5g == 2)
	{
		$data_rate_5g = $m_12;
	}	
	else if($data_rate_5g == 1)
	{
		$data_rate_5g = $m_9;
	}	
	else if($data_rate_5g == 0)
	{
		$data_rate_5g = $m_6;
	}								
}
$str_sec = "";
$auth_sec = "";
$aes_psk_sec = "";
$auth	= query("authentication");
$wep_len	= query("wepkey:". query("defkey")."/keylength");

$sec	= query("wpa/wepmode");

if			($auth==0 && $sec==0)	{$auth_sec = $m_none; }
else if		($auth==0 && $sec==1)	{$auth_sec = $m_open; }
else if		($auth==1)				{$auth_sec = $m_shared; }
else if		($auth==2 || $auth==3)	{$auth_sec = $m_wpa; }	
else if		($auth==4 || $auth==5)	{$auth_sec = $m_wpa2; }
else if		($auth==6 || $auth==7)	{$auth_sec = $m_wpa_auto; }

if		($auth==2 || $auth==4 || $auth==6) {$aes_psk_sec = $aes_psk_sec.$m_eap; }
else if		($auth==3 || $auth==5 || $auth==7) {$aes_psk_sec = $aes_psk_sec.$m_psk; }	
if($auth==9)
{
	$auth_sec="802.1x/WEP";
}
else
{
if		($sec==1)   {$str_sec = $str_sec."&nbsp;".$wep_len."&nbsp;".$m_bits; }
else if	($sec==2)   {$str_sec = $str_sec."&nbsp;".$m_tkip; }
else if	($sec==3)   {$str_sec = $str_sec."&nbsp;".$m_aes; }
else if	($sec==4)   {$str_sec = $str_sec."&nbsp;".$m_cipher_auto; }
}

$str_sec_5g = "";
$auth_sec_5g = "";
$aes_psk_sec_5g = "";
$auth_5g	= query("/wlan/inf:2/authentication");
$wep_len_5g	= query("/wlan/inf:2/wepkey:". query("/wlan/inf:2/defkey")."/keylength");

$sec_5g	= query("/wlan/inf:2/wpa/wepmode");

if			($auth_5g==0 && $sec_5g==0)	{$auth_sec_5g = $m_none; }
else if		($auth_5g==0 && $sec_5g==1)	{$auth_sec_5g = $m_open; }
else if		($auth_5g==1)				{$auth_sec_5g = $m_shared; }
else if		($auth_5g==2 || $auth_5g==3)	{$auth_sec_5g = $m_wpa; }	
else if		($auth_5g==4 || $auth_5g==5)	{$auth_sec_5g = $m_wpa2; }
else if		($auth_5g==6 || $auth_5g==7)	{$auth_sec_5g = $m_wpa_auto; }

if		($auth_5g==2 || $auth_5g==4 || $auth_5g==6) {$aes_psk_sec_5g = $aes_psk_sec_5g.$m_eap; }
else if		($auth_5g==3 || $auth_5g==5 || $auth_5g==7) {$aes_psk_sec_5g = $aes_psk_sec_5g.$m_psk; }	
if($auth_5g==9)
{
	$auth_sec_5g="802.1x/WEP";
}
else
{
if		($sec_5g==1)   {$str_sec_5g = $str_sec_5g."&nbsp;".$wep_len_5g."&nbsp;".$m_bits; }
else if	($sec_5g==2)   {$str_sec_5g = $str_sec_5g."&nbsp;".$m_tkip; }
else if	($sec_5g==3)   {$str_sec_5g = $str_sec_5g."&nbsp;".$m_aes; }
else if	($sec_5g==4)   {$str_sec_5g = $str_sec_5g."&nbsp;".$m_cipher_auto; }
}

anchor("/runtime/wan/inf:1");
if (query("connectstatus") == "connected")
{
	$wanipaddr	= query("ip");
	$wansubnet	= query("netmask");
	$wangateway	= query("gateway");
	if($wangateway == "")
	{
		$wangateway = $m_na;	
	}
	
	$wandns		= query("primarydns")."&nbsp;".query("secondarydns");
}
else
{
	$wanipaddr	= $m_na;
	$wansubnet	= $m_na;
	$wangateway	= $m_na;
	$wandns		= $m_na;
}

$cfg_ipv6 = query("/inet/entry:1/ipv6/valid");
$cfg_ipv6_lan_type = query("/inet/entry:1/ipv6/mode");
if($cfg_ipv6_lan_type=="auto")
{
    $wanipaddr6 = query("/runtime/inet/entry:1/ipv6/ipaddr");
	$wanprefix = query("/runtime/inet/entry:1/ipv6/prefix");
	$wandns6 = query("/runtime/inet/entry:1/ipv6/dns");
	$wangateway6 = query("/runtime/inet/entry:1/ipv6/gateway");
}
else
{
    $wanipaddr6 = query("/inet/entry:1/ipv6/ipaddr");
	$wanprefix = query("/inet/entry:1/ipv6/prefix");
	$wandns6 = query("/inet/entry:1/ipv6/dns");
	$wangateway6 = query("/inet/entry:1/ipv6/gateway");
}
if($wangateway6 == "")
{$wangateway6 = $m_na;}
if($wandns6 == "")
{$wandns6 = $m_na;}
$cfg_link_ip = query("/runtime/inet/entry:1/ipv6/linkipaddr");
$cfg_link_prefix = query("/runtime/inet/entry:1/ipv6/linkprefix");
if($wanipaddr6 == $cfg_link_ip)
{$cfg_same_ip = 1;}
else
{$cfg_same_ip = 0;}

$cfg_mac = query("/runtime/wan/inf:1/mac");
$cfg_mac_s = query("/runtime/wan/inf:1/macstart");
$cfg_mac_e = query("/runtime/wan/inf:1/macend");
$cfg_cpu = query("/runtime/cpu/status/alluser_utilize");
$cfg_mem = query("/runtime/mem/status/alluser_utilize");
$ssid_flag = query("/runtime/web/display/mssid_index4");
$cfg_syslocation = get("h","/sys/systemLocation");
$cfg_ap_array_name = query("/wlan/inf:1/arrayname");
$cfg_role		= query("/wlan/inf:1/arrayrole_original");
if($cfg_role=="1")
{
	$cfg_role=$m_master;
}
else if($cfg_role=="2")
{
	$cfg_role=$m_backup_master;
}
else
{
	$cfg_role=$m_slave;
}
$cfg_mac_5g = query("/runtime/wan/inf:2/mac");
$cfg_mac_5g_s = query("/runtime/wan/inf:2/macstart");
$cfg_mac_5g_e = query("/runtime/wan/inf:2/macend");

$cfg_which_connect = query("/runtime/sys/swcontroller/active");
$cfg_sw_server = query("/sys/swcontroller/server_ip");
$cfg_service_port = query("/sys/swcontroller/service_port");
$cfg_live_port = query("/sys/swcontroller/live_port");
$cfg_sw_id = query("/sys/swcontroller/group_id");
$cfg_bsw_server = query("/sys/b_swcontroller/server_ip");
$cfg_bservice_port = query("/sys/b_swcontroller/service_port");
$cfg_blive_port = query("/sys/b_swcontroller/live_port");
$cfg_bsw_id = query("/sys/b_swcontroller/group_id");
echo "-->";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>

/* page init functoin */
function init()
{
	var f=get_obj("frm");
	get_obj("single_band").style.display = "none";
	get_obj("two_bands").style.display = "none";
	if("<?=$check_band?>" == "")
	{
		get_obj("single_band").style.display = "";
		if("<?=$cfg_band?>" == 1) //11a
			get_obj("band").innerHTML = "<?=$m_5g?>";	
	}
	else
	{
		get_obj("two_bands").style.display = "";
	}
	if(get_obj("connect_status") != null)
	{
		if("<?=$cfg_which_connect?>" == 1)
			get_obj("connect_status").innerHTML = "<?=$m_connect?>";
	/*	else if("<?=$cfg_which_connect?>" == 2)
			get_obj("bconnect_status").innerHTML = "<?=$m_connect?>";*/
	}
	AdjustHeight();	
}


/* parameter checking */
function check()
{
	var f=get_obj("frm");
		
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

<table id="table_frame" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
	<tr>
		<td valign="top" align="center">
			<table id="table_header" <?=$G_TABLE_ATTR_CELL_ZERO?>>
			<tr>
				<td id="td_header" valign="middle"><?=$m_context_title?></td>
			</tr>
			</table>
<!-- ________________________________ Main Content Start ______________________________ -->
			<table width="98%"  border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
				<tr>
					<td align="center" colspan="2">
						<b><?=$m_fw_version?>:<?query("/runtime/sys/info/firmwareversion");?></b>
					</td>
				</tr>	
				<tr>
					<td id="td_left">
						<?=$m_eth_mac?>:
					</td>
					<td id="td_right">
						<?=$cfg_mac?>
					</td>
				</tr>				
			<tbody id="single_band">		
				<tr>
					<td width="35%" id="td_left">
						<?=$m_wlan_mac?>:
					</td>
					<td id="td_right">
						<?=$m_pri?>:
						<?=$cfg_mac?>
					</td>
				</tr>	
				<tr>
					<td width="35%" id="td_left">
						&nbsp;
					</td>
					<td id="td_right">
						<?=$m_ms?>:
						<?=$cfg_mac_s?> ~ <?=$cfg_mac_e?>
					</td>
				</tr>	
			</tbody>
			<tbody id="two_bands">
<? if(query("/wlan/inf:1/enable")!=1){echo "<!--";} ?>		
				<tr>
					<td width="35%" id="td_left">
						<?=$m_wlan_mac?><?=$m_2.4g?>:
					</td>
					<td id="td_right">
						<?=$m_pri?>:
						<?=$cfg_mac?>
					</td>
				</tr>	
				<tr>
					<td width="35%" id="td_left">
						&nbsp;
					</td>
					<td id="td_right">
						<?=$m_ms?>:
						<?=$cfg_mac_s?> ~ <?=$cfg_mac_e?>
					</td>
				</tr>	
<? if(query("/wlan/inf:1/enable")!=1){echo "-->";} ?>	
<? if(query("/wlan/inf:2/enable")!=1){echo "<!--";} ?>	
				<tr>
					<td width="35%" id="td_left">
						<?=$m_wlan_mac?><?=$m_5g?>:
					</td>
					<td id="td_right">
						<?=$m_pri?>:
						<?=$cfg_mac_5g?>
					</td>
				</tr>	
				<tr>
					<td width="35%" id="td_left">
						&nbsp;
					</td>
					<td id="td_right">
						<?=$m_ms?>:
						<?=$cfg_mac_5g_s?> ~ <?=$cfg_mac_5g_e?>	
					</td>
				</tr>		
<? if(query("/wlan/inf:2/enable")!=1){echo "-->";} ?>							
			</tbody>
								
				<tr>
					<td bgcolor="#cccccc" colspan="2" height="16">
						<b><?=$m_ethernet?></b>
					</td>
				</tr>				
				<tr>
					<td id="td_left">
						<?=$m_ip?>
					</td>
					<td id="td_right">
						<?=$wanipaddr?>
					</td>
				</tr>			
				<tr>
					<td id="td_left">
						<?=$m_mask?>
					</td>
					<td id="td_right">
						<?=$wansubnet?>
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_gate?>
					</td>
					<td id="td_right">
						<?=$wangateway?>
					</td>
				</tr>									
				<tr>
					<td id="td_left">
						<?=$m_dns?>
					</td>
					<td id="td_right">
						<?=$wandns?>
					</td>
				</tr>
<?if($cfg_ipv6!= "1") {echo "<!--";}?>
<?
if($cfg_same_ip == "0")
{
	echo "<tr>\n";	
	echo "<td id=\"td_left\">".$m_ipv6_ip."</td>\n";
	echo "<td id=\"td_right\">".$wanipaddr6."</td>\n";
	echo "</tr>\n";
	echo "<tr>\n";
	echo "<td id=\"td_left\">".$m_ipv6_prefix."</td>\n";
	echo "<td id=\"td_right\">".$wanprefix."</td>\n";
	echo "</tr>\n";
}
?>
				<tr>
                    <td id="td_left">
                        <?=$m_link_ip?>
                    </td>
                    <td id="td_right">
                        <?=$cfg_link_ip?>
                    </td>
                </tr>
                <tr>
                    <td id="td_left">
                        <?=$m_link_prefix?>
                    </td>
                    <td id="td_right">
                        <?=$cfg_link_prefix?>
                    </td>
                </tr>
				<tr>
					<td id="td_left">
						<?=$m_ipv6_gateway?>
					</td>
					<td id="td_right">
						<?=$wangateway6?>
					</td>
				</tr>
<?if($cfg_ipv6!= "1") {echo "-->";}?>
<? if(query("/wlan/inf:1/enable")!=1){echo "<!--";} ?>									
				<tr>
					<td bgcolor="#cccccc" colspan="2" height="16">
						<b><?=$m_wireless?>&nbsp;<span id="band"><?=$m_2.4g?></span></b>
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_ssid?>
					</td>
					<td id="td_right">
						<?=$ssid?>
					</td>
				</tr>	
				<tr>
					<td id="td_left">
						<?=$m_channel?>
					</td>
					<td id="td_right">
						<?=$channel?>
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_rate?>
					</td>
					<td id="td_right">
						<?=$data_rate?>
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_sec?>
					</td>
					<td id="td_right">
						<?=$auth_sec?><?=$aes_psk_sec?><?=$str_sec?>
					</td>
				</tr>
<? if(query("/wlan/inf:1/enable")!=1){echo "-->";} ?>
<? if(query("/wlan/inf:2/enable")!=1){echo "<!--";} ?>	
				<tr>
					<td bgcolor="#cccccc" colspan="2" height="16">
						<b><?=$m_wireless?>&nbsp;<span id="band"><?=$m_5g?></span></b>
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_ssid?>
					</td>
					<td id="td_right">
						<?=$ssid_5g?>
					</td>
				</tr>	
				<tr>
					<td id="td_left">
						<?=$m_channel?>
					</td>
					<td id="td_right">
						<?=$channel_5g?>
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_rate?>
					</td>
					<td id="td_right">
						<?=$data_rate_5g?>
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_sec?>
					</td>
					<td id="td_right">
						<?=$auth_sec_5g?><?=$aes_psk_sec_5g?><?=$str_sec_5g?>
					</td>
				</tr>
<? if(query("/wlan/inf:2/enable")!=1){echo "-->";} ?>
<? if(query("/runtime/web/display/ap_array") !="1")	{echo "<!--";} ?>
				<tr>
					<td bgcolor="#cccccc" colspan="2" height="16">
						<b><?=$m_ap_array?></b>
					</td>
				</tr>								
				<tr>
					<td id="td_left">
						<?=$m_ap_array?>
					</td>
					<td id="td_right">
						<?=$cfg_ap_array_name?>
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_role?>
					</td>
					<td id="td_right">
						<?=$cfg_role?>
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_location?>
					</td>
					<td id="td_right">
						<?=$cfg_syslocation?>
					</td>
				</tr>		
<? if(query("/runtime/web/display/ap_array") !="1")	{echo "-->";} ?>									
<? if(query("/runtime/web/display/status/ap_status") !="1")	{echo "<!--";} ?>
				<tr>
					<td bgcolor="#cccccc" colspan="2" height="16">
						<b><?=$m_status?></b>
					</td>
				</tr>								
				<tr>
					<td id="td_left">
						<?=$m_cpu?>
					</td>
					<td id="td_right">
						<?=$cfg_cpu?>%
					</td>
				</tr>
				<tr>
					<td id="td_left">
						<?=$m_memory?>
					</td>
					<td id="td_right">
						<?=$cfg_mem?>%
					</td>
				</tr>	
<? if(query("/runtime/web/display/status/ap_status") !="1")	{echo "-->";} ?>		
<? if(query("/runtime/web/display/sw_ctrl") !="1")	{echo "<!--";} ?>
				<tr>
					<td bgcolor="#cccccc" colspan="2" height="16">
						<b><?=$m_swctrl?></b>
					</td>
				</tr>								
				<tr>
					<td id="td_left"><?=$m_connect_status?></td>
					<td id="td_right"><span id="connect_status"><?=$m_disconnect?></span></td>
				</tr>
				<tr>
					<td id="td_left"><?=$m_server_ip?></td>
					<td id="td_right"><?=$cfg_sw_server?></td>
				</tr>
				<tr>
					<td id="td_left"><?=$m_service_port?></td>
					<td id="td_right"><?=$cfg_service_port?></td>
				</tr>
				<tr>
					<td id="td_left"><?=$m_live_port?></td>
					<td id="td_right"><?=$cfg_live_port?></td>
				</tr>
				<tr>
					<td id="td_left"><?=$m_group_id?></td>
					<td id="td_right"><?=$cfg_sw_id?></td>
				</tr>
<? if(query("/runtime/web/display/sw_ctrl") !="1")  {echo "-->";} ?>
			</table>
<!-- ________________________________  Main Content End _______________________________ -->
		</td>
	</tr>
</table>
</form>
</body>
</html>			
