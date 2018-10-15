<html>
	<title>Wireless Station Details</title>
	<head>
		<link rel="stylesheet" href="include/css/style.css" type="text/css">
		<link rel="stylesheet" href="include/css/default.css" type="text/css">
	</head>
	<body style="padding: 5px;">
		<table class="tableStyle" style="text-align: center;">
			<tr>
				<td>	
					<table class="tableStyle">
						<tr>
							<td colspan="3">
								<table class='tableStyle'>
									<tr>
										<td colspan='2' class='subSectionTabTopLeft spacer80Percent font12BoldBlue'>Site Survey List</td>
										<td class='subSectionTabTopRight spacer20Percent'></td>
									</tr>
									<tr>
										<td colspan='3' class='subSectionTabTopShadow'></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td class="subSectionBodyDot">&nbsp;</td>
							<td class="spacer100Percent paddingsubSectionBody" style="padding: 0px;">
								<table class="tableStyle BlockContent" id="layeredWinTable" style="background-color: #FFFFFF;">
											<table class="BlockContentTable">
													<tr>
														<th>&nbsp;</th>
														<th>SSID</th>
														<th>Security</th>
														<th>Encryption</th>
														<th>Channel</th>
													</tr>
													{foreach name=profiles key=key item=value from=$data.monitor.apList.detectedApTable.wlan0}
													<tr {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if} id="row_{$smarty.foreach.profiles.iteration}">
														<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if} style="width: 5%;"><input type="button"  id="ApScanId" name="profileid0" value="Select" onclick="window.opener.copyAPDetails(this.parentNode.parentNode);"></td>
														<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>{$value.apSsid}</td>
														{php}
															$test =	array(	"open"	=>	"Open System",
										                            		"wep"	=>	"Open System",
										                            		"wpa"	=>	"WPA-PSK",
										                            		"wpa2"	=>	"WPA2-PSK",
										                            		"wpapsk"	=>	"WPA-PSK",
										                            		"wpa2psk"	=>	"WPA2-PSK"
										                       	 	);
															$this->_tpl_vars['apAuthProto'] = array_search($test[$this->_tpl_vars['value']['apAuthProto']],$this->_tpl_vars['clientAuthenticationTypeList']);
															$this->_tpl_vars['apAuthProtoLabel'] = $test[$this->_tpl_vars['value']['apAuthProto']];
														{/php}
														<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>{if $value.apAuthProto eq 'wep'}WEP{else}{$apAuthProtoLabel}{/if}<input type="hidden" name="authType" id="authType" value="{$apAuthProto}"></td>
														{php}
														$test1 =	array(	"none"	=>	"None",
										                            		"NA"	=>	"&nbsp;",
										                            		"64"	=>	"64 bit WEP",
										                            		"128"   =>  "128 bit WEP",
										                            		"152"	=>	"152 bit WEP",
										                            		"tkip"	=>	"TKIP",
										                            		"ccmp"	=>	"AES"
										                      	  );
														//print_r($this->_tpl_vars['clientEncryptionTypeList']);
															$this->_tpl_vars['apPairwiseCipher'] =array_search($test1[$this->_tpl_vars['value']['apPairwiseCipher']],$this->_tpl_vars['clientEncryptionTypeList'][$this->_tpl_vars['apAuthProto']]);
															//if (empty($this->_tpl_vars['apPairwiseCipher'])) $this->_tpl_vars['apPairwiseCipher']='64';
															$this->_tpl_vars['apPairwiseCipherLabel'] =$test1[$this->_tpl_vars['value']['apPairwiseCipher']];
														{/php}
														<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>{$apPairwiseCipherLabel}<input type="hidden" name="encType" id="encType" value="{$apPairwiseCipher}"></td>
														
														<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>{$value.apChannel}</td>
													</tr>
													{/foreach}
												</table>
											<tr class="Alternate">
												<td colspan="2" style="text-align: right; padding: 5px;"><input type="button" value="Refresh" onclick="window.location.href='siteSurvey.php';">&nbsp;<input type="button" value="Close" onclick="window.close();"></td>
											</tr>
								</table>
							</td>
							<td class="subSectionBodyDotRight">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="3" class="subSectionBottom">&nbsp;</td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
	</body>
</html>