<?php @include('sessionCheck.inc'); ?>
<html>
	<head>
		<title>Netgear</title>
		<style>
			<!--
				TABLE {
					margin-left: auto;
					margin-right: auto;
				}
				TD {
					padding: 5px;
					text-align: left;
					vertical-align: top;
				}
				.right {
					text-align: right;
				}
			-->
		</style>		
	</head>
	<body align="center">
		<form name="hiddenForm" action="boardData.php" method="post" align="center">
			<div align="center">
			<table align="center" style="margin: 20px; width: 40%; text-align: center; border: 1px solid #46008F">			
				<tr>
					<td width="50%" class="right"><label for="macAddress"><b>MAC Address</b></label></td>
					<td width="50%">
						<small>
						<?php 
							$macAddress = explode(' ',conf_get("system:monitor:macAddress"));
							echo $macAddress[1];							
						?>
						</small><br>
					</td>
				</tr>
				<tr>
					<td width="50%" class="right"><label for="reginfo"><b>Region</b></label></td>
					<td width="50%">
						<small>
						<?php 
							$regInfo = explode(' ',conf_get("system:monitor:region"));							$productId=explode(' ',conf_get("system:monitor:productId"));
							if ($regInfo[1] == "NA")
								echo "North America (NA)";
							else if($regInfo[1] == "JP"){
							if($productId[1]=="WNDAP660" || $productId[1]=="WNDAP360" || $productId[1]=="WNAP320")
								echo "Japan (JP)";
							}
							else
								echo "Worldwide (WW)";
						?>
						</small><br>
					</td>
				</tr>
				<tr>
					<td width="50%" class="right"><label for="ssid"><b>SSID</b></label></td>
					<td width="50%">
						<small>
						<?php 
							$ssid = explode(' ',conf_get("system:vapSettings:vapSettingTable:wlan0:vap0:ssid"));
							echo $ssid[1];							
						?>
						</small><br>
					</td>
				</tr>
				<tr>
					<td width="50%" class="right"><label for="Channel"><b>Channel</b></label></td>
					<td width="50%">
						<small>
						<?php 
							$Channel = explode(' ',conf_get("system:wlanSettings:wlanSettingTable:wlan0:channel"));
							$autoChannel = explode(' ',conf_get("system:monitor:currentChannel:wlan0"));
							echo $Channel[1] == "0"?'Auto ('.$autoChannel[1].')':$Channel[1];
						?>
						</small><br>
					</td>
				</tr>
				<tr>
					<td width="50%" class="right"><label for="firmware"><b>Firmware Version</b></label></td>
					<td width="50%">
						<?php 
							$sysVersion = explode(' ',conf_get("system:monitor:sysVersion"));
							echo $sysVersion[1];							
						?><small>
						</small><br>
					</td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: center;"><input type="button" name="Refresh" value="Refresh" onclick="window.location.reload();"></td>					
				</tr>
			</table>
			
			</div>
		</form>
	</body>
</html>
