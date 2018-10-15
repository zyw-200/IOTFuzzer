<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "st_wds_info";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "st_wds_info";
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
$switch = query("/runtime/web/display/switchable");
$cfg_band = query("/wlan/ch_mode");
$check_band = query("/wlan/inf:2/ap_mode");
$cfg_auto_ch_scan = query("autochannel");
if ($cfg_auto_ch_scan == 1)
{
	$cfg_channel = query("/runtime/stats/wlan/inf:1/channel");
}
else
{
	$cfg_channel = query("channel");
}
$ap_mode	= query("ap_mode");
$str_sec = "";
$auth_sec = "";
$aes_psk_sec = "";
$auth	= query("authentication");
$wep_len	= query("keylength");
$sec	= query("wpa/wepmode");

if			($auth==0 && $sec==0)	{$auth_sec = $m_open; }
else if		($auth==0 && $sec==1)	{$auth_sec = $m_wep; }
else if		($auth==1)				{$auth_sec = $m_shared; }
else if		($auth==2 || $auth==3)	{$auth_sec = $m_wpa; }	
else if		($auth==4 || $auth==5)	{$auth_sec = $m_wpa2; }
else if		($auth==6 || $auth==7)	{$auth_sec = $m_wpa_auto; }

if		($auth==2 || $auth==4 || $auth==6) {$aes_psk_sec = $aes_psk_sec.$m_eap; }
else if		($auth==3 || $auth==5 || $auth==7) {$aes_psk_sec = $aes_psk_sec.$m_psk; }	

anchor("/wlan/inf:2");
$cfg_auto_ch_scan_5g = query("autochannel");
if ($cfg_auto_ch_scan_5g == 1)
{
	$cfg_channel_5g = query("/runtime/stats/wlan/inf:2/channel");
}
else
{
	$cfg_channel_5g = query("channel");
}
$ap_mode_5g	= query("ap_mode");
$str_sec_5g = "";
$auth_sec_5g = "";
$aes_psk_sec_5g = "";
$auth_5g	= query("authentication");
$wep_len_5g	= query("keylength");
$sec_5g	= query("wpa/wepmode");

if			($auth_5g==0 && $sec_5g==0)	{$auth_sec_5g = $m_open; }
else if		($auth_5g==0 && $sec_5g==1)	{$auth_sec_5g = $m_wep; }
else if		($auth_5g==1)				{$auth_sec_5g = $m_shared; }
else if		($auth_5g==2 || $auth_5g==3)	{$auth_sec_5g = $m_wpa; }	
else if		($auth_5g==4 || $auth_5g==5)	{$auth_sec_5g = $m_wpa2; }
else if		($auth_5g==6 || $auth_5g==7)	{$auth_sec_5g = $m_wpa_auto; }

if		($auth_5g==2 || $auth_5g==4 || $auth_5g==6) {$aes_psk_sec_5g = $aes_psk_sec_5g.$m_eap; }
else if		($auth_5g==3 || $auth_5g==5 || $auth_5g==7) {$aes_psk_sec_5g = $aes_psk_sec_5g.$m_psk; }	
echo "-->";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
?>
function Change_Wireless_Band()
{
	location.href = "Wireless.html?"+ Band.selectedIndex ;   //+"," + ApModeMenu.selectedIndex + "," + AuthMenu.selectedIndex;
	
	//document.forms[0].submit();
}

function CalcFreq()
{
	if("<?=$switch?>" == 1)
	{
		var channel = "<?=$cfg_channel?>";
		if("<?=$cfg_band?>" == 0)
		{
			if(channel != 14)
				get_obj("frequency").innerHTML = "("+(2.407 + (channel * 0.005)) + " GHz)";
			else
				get_obj("frequency").innerHTML = "(2.484 GHz)";
		}
		else
		{
			if(channel == "112")
				get_obj("frequency").innerHTML = "(5.56 GHz)";
			else
				get_obj("frequency").innerHTML = "("+(5 + (channel * 0.005)) + " GHz)";
		}
	}
	else
	{
	var channel = "<?=$cfg_channel?>";
	if(channel != 14)
		get_obj("frequency").innerHTML = "("+(2.407 + (channel * 0.005)) + " GHz)";
	else
		get_obj("frequency").innerHTML = "(2.484 GHz)";

	var channel_5g = "<?=$cfg_channel_5g?>";
    if(channel_5g == "112")
		get_obj("frequency_5g").innerHTML = "(5.56 GHz)";
    else	
		get_obj("frequency_5g").innerHTML = "("+(5 + (channel_5g * 0.005)) + " GHz)";
	}
}

/* page init functoin */
function init()
{
	var f=get_obj("frm");
	setTimeout("location.reload()",10000);
	get_obj("msg_5g").style.display = "none";
	if("<?=$check_band?>" != "")
	{
		get_obj("msg_5g").style.display = "";
	}
	CalcFreq();
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
								<?=$m_channel?>&nbsp;:&nbsp;<?=$cfg_channel?>&nbsp;<font id="frequency" name="frequency">freq</font>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
									<tr class="list_head" align="left">
										<td width="80">
											&nbsp;<?=$m_name?>
										</td>
										<td width="170">
											<?=$m_mac?>
										</td>
										<td width="160">
											<?=$m_auth?>
										</td>
										<td width="80">
											<?=$m_signal?>
										</td>
										<td width="50">
											<?=$m_status?>
										</td>																																																													
									</tr>									
								</table>
								<div class="div_client_tab">
									<table id="client_tab" width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>						
<?
anchor("/wlan/inf:1");
$tmp = "";
$wds_index = "";
$status = "";
$i = 0;
while($i<8)
{	
	$client_num = 0;
	$wds_index = $i+1;
	if(query("wds/list/index:".$wds_index."/mac")!="")
	{
		for("/runtime/stats/wlan/inf:1/wds:".$wds_index."/client")
		{
			$client_num++;
			
		}
		
		if($client_num != 0)
		{
			$status = $m_on;
		}
		else
		{
			$status = $m_off;
		}

		
		if($client_num != 0)
		{
			for("/runtime/stats/wlan/inf:1/wds:".$wds_index."/client")
			{		
				if($tmp != 1)
				{
					echo "<tr align=\"left\" style=\"background:#cccccc;\">\n";
					$tmp = 1;
				}
				else
				{
					echo "<tr align=\"left\" style=\"background:#b3b3b3;\">\n";
					$tmp = 0;
				}			
				echo "<td width=\"80\">W-".$wds_index."</td>\n";		
				echo "<td width=\"170\">".query("mac")."</td>\n";
				echo "<td width=\"160\">".query("auth")."</td>\n";
				echo "<td width=\"80\">".query("rssi")."%</td>\n";
				echo "<td width=\"50\">".$status."</td>\n";	
				echo "</tr>\n";	
			}		
		}
		else
		{

			if($tmp != 1)
			{
				echo "<tr align=\"left\" style=\"background:#cccccc;\">\n";
				$tmp = 1;
			}
			else
			{
				echo "<tr align=\"left\" style=\"background:#b3b3b3;\">\n";
				$tmp = 0;
			}		
			echo "<td width=\"80\">W-".$wds_index."</td>\n";	
			echo "<td width=\"170\">&nbsp;</td>\n";
			echo "<td width=\"160\">".query("auth")."</td>\n";
			echo "<td width=\"80\">&nbsp;</td>\n";
			echo "<td width=\"50\">".$status."</td>\n";	
			echo "</tr>\n";			
		}
	}
	$i++;		
}
?>
									</table>
								</div>					
							</td>	
						</tr>				
					</table>
					
					<div id="msg_5g">
					<table border="0"<?=$G_TABLE_ATTR_CELL_ZERO?> style="padding: 0px 10px 0px 0px;">
						<tr>
							<td width="25%" align="left">
								<font color="#3399cc"><b><?=$m_client_info?></b></font>
							</td>
							<td id="td_right">
								<?=$m_channel?>&nbsp;:&nbsp;<?=$cfg_channel_5g?>&nbsp;<font id="frequency_5g" name="frequency_5g">freq</font>
							</td>
						</tr>
						<tr>
							<td colspan="2">
								<table width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>
									<tr class="list_head" align="left">
										<td width="80">
											&nbsp;<?=$m_name?>
										</td>
										<td width="170">
											<?=$m_mac?>
										</td>
										<td width="160">
											<?=$m_auth?>
										</td>
										<td width="80">
											<?=$m_signal?>
										</td>
										<td width="50">
											<?=$m_status?>
										</td>																																																													
									</tr>									
								</table>
								<div class="div_client_tab">
									<table id="client_tab" width="100%" border="0" <?=$G_TABLE_ATTR_CELL_ZERO?>>						
<?
anchor("/wlan/inf:2");
$tmp_5g = "";
$wds_index_5g = "";
$status_5g = "";
$i_5g = 0;
while($i_5g<8)
{	
	$client_num_5g = 0;
	$wds_index_5g = $i_5g+1;
	if(query("wds/list/index:".$wds_index_5g."/mac")!="")
	{
		for("/runtime/stats/wlan/inf:2/wds:".$wds_index_5g."/client")
		{
			$client_num_5g++;
			
		}
		
		if($client_num_5g != 0)
		{
			$status_5g = $m_on;
		}
		else
		{
			$status_5g = $m_off;
		}

		
		if($client_num_5g != 0)
		{
			for("/runtime/stats/wlan/inf:2/wds:".$wds_index_5g."/client")
			{		
				if($tmp_5g != 1)
				{
					echo "<tr align=\"left\" style=\"background:#cccccc;\">\n";
					$tmp_5g = 1;
				}
				else
				{
					echo "<tr align=\"left\" style=\"background:#b3b3b3;\">\n";
					$tmp_5g = 0;
				}			
				echo "<td width=\"80\">W-".$wds_index_5g."</td>\n";		
				echo "<td width=\"170\">".query("mac")."</td>\n";
				echo "<td width=\"160\">".query("auth")."</td>\n";
				echo "<td width=\"80\">".query("rssi")."%</td>\n";
				echo "<td width=\"50\">".$status_5g."</td>\n";	
				echo "</tr>\n";	
			}		
		}
		else
		{

			if($tmp_5g != 1)
			{
				echo "<tr align=\"left\" style=\"background:#cccccc;\">\n";
				$tmp_5g = 1;
			}
			else
			{
				echo "<tr align=\"left\" style=\"background:#b3b3b3;\">\n";
				$tmp_5g = 0;
			}		
			echo "<td width=\"80\">W-".$wds_index_5g."</td>\n";	
			echo "<td width=\"170\">&nbsp;</td>\n";
			echo "<td width=\"160\">".query("auth")."</td>\n";
			echo "<td width=\"80\">&nbsp;</td>\n";
			echo "<td width=\"50\">".$status_5g."</td>\n";	
			echo "</tr>\n";			
		}
	}
	$i_5g++;		
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

			</table>
<!-- ________________________________  Main Content End _______________________________ -->
		</td>
	</tr>
</table>
</form>
</body>
</html>			
