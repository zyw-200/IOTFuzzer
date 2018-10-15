<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "st_info";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "st_info";
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
if(query("/wlan/inf:2/ap_mode") == "")
{
	$cfg_band = query("/wlan/ch_mode");
	if($cfg_band == 0) // 11g
	{
		echo "anchor 11g";
		$cfg_band = " (2.4GHz)";
	}
	else
	{
		echo "anchor 11a";
		$cfg_band = "(5GHz)";
	}
	anchor("/wlan/inf:1");
}
else
{
	$cfg_band = " (2.4GHz)";
	$cfg_band_5g = "(5GHz)";
}
echo "-->";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>

function print_rssi(f_rssi)
{
/*	rssi = 42*f_rssi;
	rssi = rssi/100;
	rssi = rssi - 95;*/
	rssi = f_rssi - 95;
	str = "";
	str += "<td width=\"60\">"+rssi+"</td>\n";
	document.write(str);
}

/* page init functoin */
function init()
{
	var f=get_obj("frm");
	setTimeout("location.reload()",10000);
	AdjustHeight();
}

/* parameter checking */
function check()
{
	var f = get_obj("frm");

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
<table id="table_frame" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
	<tr>
		<td valign="top">
			<table id="table_header" <?=$G_TABLE_ATTR_CELL_ZERO?>>
			<tr>
				<td id="td_header" valign="middle"><?=$m_context_title?></td>
			</tr>
			</table>
<!-- ________________________________ Main Content Start ______________________________ -->
			<table id="table_set_main"  border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
			<tr>
				<td>
					<table border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="padding: 0px 10px 0px 0px;">
						<tr>
							<td width="25%" align="left">
								<font color="#3399cc"><b><?=$m_client_info?></b></font>
							</td>
							<td id="td_right">
								<?=$m_st_association?><?=$cfg_band?>&nbsp;:&nbsp;
<?
$association_num = 0;
for("/runtime/stats/wlan/inf:1/client")
{
	$association_num++;
}

for("/wlan/inf:1/multi/index")
{
	$mssid_index = $@;
	if(query("state") !="0" && query("/wlan/inf:1/multi/state") == "1")
	{
		for("/runtime/stats/wlan/inf:1/mssid:".$mssid_index."/client")
		{
			$association_num++;	
		}
	}		
}
if($TITLE == "DAP-1353")
{
	for("/runtime/stats/wlan/inf:1/rootap/client")
	{
		$association_num++;
	}
}
echo $association_num."\n";
?>							
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<table width="100%" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
									<tr class="list_head" align="center">
										<td width="100">
											<?=$m_ssid?>
										</td>
										<td width="100">
											<?=$m_mac?>
										</td>
										<td width="40">
											<?=$m_band?>
										</td>
										<td width="130">
											<?=$m_auth?>
										</td>
										<td width="60">
											<?=$m_rssi?>
										</td>
										<td>
											<?=$m_power?>
										</td>																																																													
									</tr>									
								</table>
								<div class="div_client_tab">
									<table id="client_tab" width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>						
<?
$tmp_1 = "";
$tmp = "";
$psmode = "";
if($TITLE == "DAP-1353")
{
	for("/runtime/stats/wlan/inf:1/rootap/client")
	{
		if(query("psmode") == 1)
		{
			$psmode = $m_on;
		}
		else
		{
			$psmode = $m_off;
		}
		$tmp_1 = $@%2;
		if($tmp_1 != 1)
		{
			echo "<tr align=\"center\" style=\"background:#cccccc;\">\n";
			$tmp = 1;
		}
		else
		{
			echo "<tr align=\"center\" style=\"background:#b3b3b3;\">\n";
			$tmp = 0;
		}
		if(query("/wlan/inf:1/ap_mode")==2) //apr    
		{
			echo "<td width=\"100\">".$G_TAG_SCRIPT_START."genTableSSID(\"".get("j","sta_ssid")."\",0);".$G_TAG_SCRIPT_END."</td>\n";
		}
		else
		{
			echo "<td width=\"100\">".$m_primary_ssid."</td>\n";
		}
		echo "<td width=\"100\">".query("mac")."</td>\n";
		echo "<td width=\"40\">".query("band")."</td>\n";
		$tmp_auth=query("/wlan/inf:1/authentication");
		if($tmp_auth!=9)
		{
			echo "<td width=\"130\">".query("auth")."</td>\n";
		}
		else
		{
			echo "<td width=\"130\">802.1X/WEP</td>\n";
		}
		echo "<td width=\"60\">".query("rssi")."%</td>\n";
		echo "<td>".$psmode ."</td>\n";
		echo "</tr>\n";
	}
}

for("/runtime/stats/wlan/inf:1/client")
{	
	if(query("psmode") == 1)
	{
		$psmode = $m_on;
	}
	else
	{
		$psmode = $m_off;
	}
	
	$tmp_1 = $@%2;
	if($tmp_1 == 1)
	{
		echo "<tr align=\"center\" style=\"background:#cccccc;\">\n";
		$tmp = 1;
	}
	else
	{
		echo "<tr align=\"center\" style=\"background:#b3b3b3;\">\n";
		$tmp = 0;
	}
	if(query("/wlan/inf:1/ap_mode")==1) //apc
	{
		echo "<td width=\"100\">".$G_TAG_SCRIPT_START."genTableSSID(\"".get("j","sta_ssid")."\",0);".$G_TAG_SCRIPT_END."</td>\n";
	}
	else
	{
	echo "<td width=\"100\">".$m_primary_ssid."</td>\n";
	}
	echo "<td width=\"100\">".query("mac")."</td>\n";
	echo "<td width=\"40\">".query("band")."</td>\n";
	$tmp_auth=query("/wlan/inf:1/authentication");
	if($tmp_auth!=9)
	{
        	echo "<td width=\"130\">".query("auth")."</td>\n";
	}
	else
	{
		echo "<td width=\"130\">802.1X/WEP</td>\n";
	}
	if(query("/runtime/web/display/stinfo_rssi") != "1") //%
	{
		echo "<td width=\"60\">".query("rssi")."%</td>\n";
	}
	else	//num
	{
		echo "<td width=\"60\">".query("signalstrength")."</td>\n";
	}
	echo "<td>".$psmode ."</td>\n";
	echo "</tr>\n";	
}

$ssid = "";
$mssid_index = "";
$ms_psmode = "";
for("/wlan/inf:1/multi/index")
{
	$mssid_index = $@;
	if(query("state") !="0" && query("/wlan/inf:1/multi/state") == "1")
	{
		for("/runtime/stats/wlan/inf:1/mssid:".$mssid_index."/client")
		{
			if(query("psmode") == 1)
			{
				$ms_psmode = $m_on;
			}
			else
			{
				$ms_psmode = $m_off;
			}
			$ssid = get(j,"/wlan/inf:1/multi/index:".$mssid_index."/ssid");
			$tmp_auth = query("/wlan/inf:1/multi/index:".$mssid_index."/auth");
			if($tmp != 1)
			{
				echo "<tr align=\"center\" style=\"background:#cccccc;\">\n";
				$tmp = 1;
			}
			else
			{
				echo "<tr align=\"center\" style=\"background:#b3b3b3;\">\n";
				$tmp = 0;
			}
			echo "<td width=\"100\">".$G_TAG_SCRIPT_START."genTableSSID(\"".$ssid."\",0);".$G_TAG_SCRIPT_END."</td>\n";
			echo "<td width=\"100\">".query("mac")."</td>\n";
			echo "<td width=\"40\">".query("band")."</td>\n";
			if($tmp_auth!=9)
			{
        			echo "<td width=\"130\">".query("auth")."</td>\n";
			}
			else
			{
				echo "<td width=\"130\">802.1X/WEP</td>\n";
			}
			if(query("/runtime/web/display/stinfo_rssi") != "1") //%
			{
				echo "<td width=\"60\">".query("rssi")."%</td>\n";
			}
			else    //num
			{
				echo "<td width=\"60\">".query("signalstrength")."</td>\n";
			}
			echo "<td>".$ms_psmode."</td>\n";
			echo "</tr>\n";							
		}
	}
}
?>
									</table>
								</div>					
							</td>	
						</tr>				
					</table>
					
<?if(query("/wlan/inf:2/ap_mode") == "") {echo "<!--";}?>
					<table border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="padding: 0px 10px 0px 0px;">
						<tr>
							<td width="25%" align="left">
								<font color="#3399cc"><b><?=$m_client_info?></b></font>
							</td>
							<td id="td_right">
								<?=$m_st_association?><?=$cfg_band_5g?>&nbsp;:&nbsp;
<?
anchor("/wlan/inf:2");
$association_num_5g = 0;
for("/runtime/stats/wlan/inf:2/client")
{
	$association_num_5g++;
}

for("/wlan/inf:2/multi/index")
{
	$mssid_index_5g = $@;
	if(query("state") !="0")
	{
		for("/runtime/stats/wlan/inf:2/mssid:".$mssid_index_5g."/client")
		{
			$association_num_5g++;	
		}
	}		
}
echo $association_num_5g."\n";
?>							
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<table width="100%" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
									<tr class="list_head" align="center">
										<td width="100">
											<?=$m_ssid?>
										</td>
										<td width="100">
											<?=$m_mac?>
										</td>
										<td width="40">
											<?=$m_band?>
										</td>
										<td width="130">
											<?=$m_auth?>
										</td>
										<td width="60">
											<?=$m_rssi?>
										</td>
										<td>
											<?=$m_power?>
										</td>																																																													
									</tr>									
								</table>
								<div class="div_client_tab">
									<table id="client_tab" width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>						
<?
$tmp_1_5g = "";
$tmp_5g = "";
$psmode_5g = "";
for("/runtime/stats/wlan/inf:2/client")
{	
	if(query("psmode") == 1)
	{
		$psmode_5g = $m_on;
	}
	else
	{
		$psmode_5g = $m_off;
	}
	
	$tmp_1_5g = $@%2;
	if($tmp_1_5g == 1)
	{
		echo "<tr align=\"center\" style=\"background:#cccccc;\">\n";
		$tmp_5g = 1;
	}
	else
	{
		echo "<tr align=\"center\" style=\"background:#b3b3b3;\">\n";
		$tmp_5g = 0;
	}
	if(query("/wlan/inf:2/ap_mode")==1) //apc
	{
		echo "<td width=\"100\">".$G_TAG_SCRIPT_START."genTableSSID(\"".get("j","sta_ssid")."\",0);".$G_TAG_SCRIPT_END."</td>\n";
	}
	else
	{
	echo "<td width=\"100\">".$m_primary_ssid."</td>\n";
	}
	echo "<td width=\"100\">".query("mac")."</td>\n";
	echo "<td width=\"40\">".query("band")."</td>\n";
	$tmp_auth_5g=query("/wlan/inf:2/authentication");
	if($tmp_auth_5g!=9)
	{
        	echo "<td width=\"130\">".query("auth")."</td>\n";
	}
	else
	{
		echo "<td width=\"130\">802.1X/WEP</td>\n";
	}
	if(query("/runtime/web/display/stinfo_rssi") != "1") //%
    {
        echo "<td width=\"60\">".query("rssi")."%</td>\n";
    }
    else    //num
    {
		echo "<td width=\"60\">".query("signalstrength")."</td>\n";
    }
	echo "<td>".$psmode_5g ."</td>\n";
	echo "</tr>\n";	
}

$ssid_5g = "";
$mssid_index_5g = "";
$ms_psmode_5g = "";
for("/wlan/inf:2/multi/index")
{
	$mssid_index_5g = $@;
	if(query("state") !="0")
	{
		for("/runtime/stats/wlan/inf:2/mssid:".$mssid_index_5g."/client")
		{
			if(query("psmode") == 1)
			{
				$ms_psmode_5g = $m_on;
			}
			else
			{
				$ms_psmode_5g = $m_off;
			}
			$ssid_5g = get(j,"/wlan/inf:2/multi/index:".$mssid_index_5g."/ssid");
			$tmp_auth_5g = query("/wlan/inf:2/multi/index:".$mssid_index_5g."/auth");
			if($tmp_5g != 1)
			{
				echo "<tr align=\"center\" style=\"background:#cccccc;\">\n";
				$tmp_5g = 1;
			}
			else
			{
				echo "<tr align=\"center\" style=\"background:#b3b3b3;\">\n";
				$tmp_5g = 0;
			}
			echo "<td width=\"100\">".$G_TAG_SCRIPT_START."genTableSSID(\"".$ssid_5g."\",0);".$G_TAG_SCRIPT_END."</td>\n";
			echo "<td width=\"100\">".query("mac")."</td>\n";
			echo "<td width=\"40\">".query("band")."</td>\n";
			if($tmp_auth_5g!=9)
			{
        			echo "<td width=\"130\">".query("auth")."</td>\n";
			}
			else
			{
				echo "<td width=\"130\">802.1X/WEP</td>\n";
			}
			if(query("/runtime/web/display/stinfo_rssi") != "1") //%
			{
				echo "<td width=\"60\">".query("rssi")."%</td>\n";
			}
			else    //num
			{
				echo "<td width=\"60\">".query("signalstrength")."</td>\n";
			}
			echo "<td>".$ms_psmode_5g."</td>\n";
			echo "</tr>\n";							
		}
	}
}
?>
									</table>
								</div>					
							</td>	
						</tr>				
					</table>
<?if(query("/wlan/inf:2/ap_mode") == "") {echo "-->";}?>
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
