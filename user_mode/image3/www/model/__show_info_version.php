<body onload="init();" <?=$G_BODY_ATTR?>>
<form name="frm" id="frm">
<center>
<?require("/www/model/__logo.php");?>
<table <?=$G_LOG_MAIN_TABLE_ATTR?>>
<tr valign="middle" align="center">
	<td>
	<br>
<!-- ________________________________ Main Content Start ______________________________ -->
	<table width="80%">
	<tr>
		<td colspan="2" id="box_header">
			<h1><?=$m_context_title?></h1><br><br>
			<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td width="200" id="td_left"><?=$m_firmware_external?>&nbsp;:</td>
				<td id="td_right">&nbsp;<?=$cfg_firmware_enternal?></td>
			</tr>
			<tr>
				<td width="200" id="td_left"><?=$m_firmware_internal?>&nbsp;:</td>
				<td id="td_right">&nbsp;<?=$cfg_firmware_internal?></td>
			</tr>
			<tr>
				<td width="200" id="td_left"><?=$m_date?>&nbsp;:</td>
				<td id="td_right">&nbsp;<?=$cfg_date?></td>
			</tr>
			<tr>
				<td width="200" id="td_left"><?=$m_checksum?>&nbsp;:</td>
				<td id="td_right">&nbsp;<?=$cfg_checksum?></td>
			</tr>
			<tr>
				<td width="200" id="td_left"><?=$m_wlan_domain?>&nbsp;:</td>
				<td id="td_right">&nbsp;<?=$cfg_wlan_domain?></td>
			</tr>
			<tr>
				<td width="200" id="td_left"><?=$m_wan_mac?>&nbsp;:</td>
				<td id="td_right">&nbsp;<?=$cfg_wan_mac?></td>
			</tr>
			<tr>
				<td width="200" id="td_left"><?=$m_lan_mac?>&nbsp;:</td>
				<td id="td_right">&nbsp;<?=$cfg_lan_mac?></td>
			</tr>
			<tr>
				<td width="200" id="td_left"><?=$m_wlan_mac?>&nbsp;:</td>
				<td id="td_right">&nbsp;<?=$cfg_wlan_mac?></td>
			</tr>
			<tr>
				<td width="200" id="td_left"><?=$m_apps?>&nbsp;:</td>
				<td id="td_right">&nbsp;<?=$cfg_apps?></td>
			</tr>
			<tr>
				<td width="200" id="td_left"><?=$m_wlan_drive?>&nbsp;:</td>
				<td id="td_right">&nbsp;<?=$cfg_wlan_drive?></td>
			</tr>
			<tr>
				<td width="200" id="td_left"><?=$m_ssid?>&nbsp;:</td>
				<td id="td_right">&nbsp;<?=$cfg_ssid?></td>
			</tr>
			</table>
			<center>
			<?
				//echo $m_context;
				echo "<br><br><br>\n";
				if($USE_BUTTON=="1")
				{echo "<input type=button name='bt' value='".$m_button_dsc."' onclick='click_bt();'>\n"; }
			}
			?>
			</center>
		</td>
	</tr>
	</table>
<!-- ________________________________  Main Content End _______________________________ -->
	<br>
	</td>
</tr>
</table>
<center>
</form>
</body>
</html>
