	<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Client Settings','clientSettings')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody" style="padding: 0px;">
						<table class="tableStyle">
						<tr>
							<td>
								<div  id="WirelessBlock">
									<table class="inlineBlockContent" style="margin-top: 10px; width: 100%;">
										<tr>
											<td>
												<ul class="inlineTabs">
													<li id="inlineTab1" {if $data.activeMode eq '2' OR $data.activeMode eq '1' OR $data.activeMode eq '0'}class="Active" activeRadio="true"{else} activeRadio="false"{/if} currentId="1"><a id="inlineTabLink1" href="javascript:void(0)" onclick="if (inputForm && inputForm.formLiveValidate()) {literal} { activateTab($('inlineTab1'),'1'); } {/literal}">802.11<span class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'><b class="RadioText{if $data.activeMode eq '0'}Active{/if}">b{if $data.activeMode eq '0'}<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span>{/if}</b></span>/<span class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'><b class="RadioText{if $data.activeMode eq '1'}Active{/if}">bg{if $data.activeMode eq '1'}<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span>{/if}</b></span>/<span class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'><b class="RadioText{if $data.activeMode eq '2'}Active{/if}">ng{if $data.activeMode eq '2'}<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span>{/if}</b></span></a></li>
												</ul>
											</td>
										</tr>
									</table>
								</div>
								<div id="IncludeTabBlock">
									<div  class="BlockContent" id="wlan1">
										<table class="BlockContent Trans">
											<tr class="Gray">
												<td class="DatablockLabel" style="width: 1%;">Wireless Mode</td>
												<td class="DatablockContent" style="width: 100%;">
													<span class="legendActive">2.4GHz Band</span>
													{php}
													$this->_tpl_vars['data']['activeMode']="3";
													{/php}
													<input type="radio" style="padding: 2px;" name="{$parentStr.wlanSettings.wlanSettingTable.wlan0.operateMode}" id="WirelessMode1" onclick="//DispChannelList(1,'0','chkRadio0');enable11nFields('hide',1);enableFields(this.value);" {if $data.activeMode eq '0'}checked="checked"{/if} value="0"><span id="mode_b" {if $data.activeMode eq '0'}class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11b<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span>{else}>11b{/if}</span>
													<input type="radio" style="padding: 2px;" name="{$parentStr.wlanSettings.wlanSettingTable.wlan0.operateMode}" id="WirelessMode1" onclick="//DispChannelList(1,'1','chkRadio0');enable11nFields('hide',1);enableFields(this.value);" {if $data.activeMode eq '1'}checked="checked"{/if} value="1"><span id="mode_bg" {if $data.activeMode eq '1'}class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11bg<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span>{else}>11bg{/if}</span>
													<input type="radio" style="padding: 2px;" name="{$parentStr.wlanSettings.wlanSettingTable.wlan0.operateMode}" id="WirelessMode1" onclick="//DispChannelList(1,'2','chkRadio0');enable11nFields('show',1);enableFields(this.value);" checked="checked" value="2"><span id="mode_ng" {if $data.activeMode eq '2'}class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11ng<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span>{else}>11ng{/if}</span>
													<input type="hidden" name="activeMode" id="activeMode" value="{$data.activeMode}">
													<input type="hidden" name="currentMode" id="currentMode" value="{$data.activeMode}">
													<input type="hidden" name="modeWlan0" id="modeWlan0" value="{if $data.activeMode gt 2}2{else}{$data.activeMode}{/if}">
												</td>
											</tr>
											{assign var="radioStatus" value=$parentStr.wlanSettings.wlanSettingTable.wlan0.radioStatus}
											{assign var="operateMode" value=$parentStr.wlanSettings.wlanSettingTable.wlan0.operateMode}
											{input_row row_id="radioRow1" label="Turn Radio On" id="chkRadio0" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.radioStatus type="checkbox" value=$data.wlanSettings.wlanSettingTable.wlan0.radioStatus onclick="setActiveMode(this,'WirelessMode1', true)"}
											
											<tr>
												<td class="DatablockLabel">Wireless Network Name (SSID)</td>
												<td class="DatablockContent">
													<input class="input" type="text" id="wirelessSSID0" name="{$parentStr.staSettings.staSettingTable.wlan0.sta0.ssid}" value="{$data.staSettings.staSettingTable.wlan0.sta0.ssid}" maxlength="32" validate="{literal}Presence, {allowSpace: true}^Length, { minimum: 2, maximum: 32 }{/literal}">&nbsp;
													<input name="clientMode_button" style="text-align: center;" value="Site Survey" onclick="showSurveyPopupWindow(); return false;" type="button">
												</td>
											</tr>
																																	
											{input_row label="Network Authentication" id="authenticationType" name=$parentStr.staSettings.staSettingTable.wlan0.sta0.authenticationType type="select" options=$clientAuthenticationTypeList selected=$data.staSettings.staSettingTable.wlan0.sta0.authenticationType onchange="DisplayClientSettings(this.value);"}

											{php}
												$this->_tpl_vars['encTypeList'] = $this->_tpl_vars['clientEncryptionTypeList'][$this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['authenticationType']];
											{/php}
											{input_row label="Data Encryption" id="key_size_11g" name="encryptionType" type="select" options=$encTypeList selected=$encryptionSel onchange="if ($('authenticationType').value=='0') DisplayClientSettings('1',1);"}

											{ip_field id="encryption" name=$parentStr.staSettings.staSettingTable.wlan0.sta0.encryption type="hidden" value=$data.staSettings.staSettingTable.wlan0.sta0.encryption}
											{if NOT ($data.staSettings.staSettingTable.wlan0.sta0.authenticationType eq 1 OR ($data.staSettings.staSettingTable.wlan0.sta0.authenticationType eq 0 AND $data.staSettings.staSettingTable.wlan0.sta0.encryption neq 0))}
												{ip_field id="wepKeyType" name=$parentStr.staSettings.staSettingTable.wlan0.sta0.wepKeyType type="hidden" value=$data.staSettings.staSettingTable.wlan0.sta0.wepKeyType disabled="true"}
											{else}
												{ip_field id="wepKeyType" name=$parentStr.staSettings.staSettingTable.wlan0.sta0.wepKeyType type="hidden" value=$data.staSettings.staSettingTable.wlan0.sta0.wepKeyType}
											{/if}
											{if NOT ($data.staSettings.staSettingTable.wlan0.sta0.authenticationType eq 1 OR ($data.staSettings.staSettingTable.wlan0.sta0.authenticationType eq 0 AND $data.staSettings.staSettingTable.wlan0.sta0.encryption neq 0))}
												{assign var="hideWepRow" value="style=\"display: none;\" disabled='true'"}
											{/if}

											{if NOT($data.staSettings.staSettingTable.wlan0.sta0.authenticationType eq 16 OR $data.staSettings.staSettingTable.wlan0.sta0.authenticationType eq 32 OR $data.staSettings.staSettingTable.wlan0.sta0.authenticationType eq 48)}
												{assign var="hideWPARow" value="style=\"display: none;\" disabled='true'"}
											{/if}
											<tr id="wep_row" {$hideWepRow}>
												<td class="DatablockLabel">Passphrase</td>
												<td class="DatablockContent">
													<input class="input" size="20" maxlength="39" id="wepPassPhrase" name="{$parentStr.staSettings.staSettingTable.wlan0.sta0.wepPassPhrase}" value="{$data.staSettings.staSettingTable.wlan0.sta0.wepPassPhrase|regex_replace:"/(.)/":'*'}" type="text" label="Passphrase" validate="Presence, {literal}{ allowQuotes: false, allowSpace: true, allowTrimmed:false }{/literal}" onkeydown="setActiveContent();">
													<input name="szPassphrase_button" style="text-align: center;" value="Generate Keys" onclick="gen_11g_keys()" type="button">
													<input type="hidden" id="wepPassPhrase_hidden" value="{$data.staSettings.staSettingTable.wlan0.sta0.wepPassPhrase}">
												</td>
											</tr>
											<tr mode="1" {$hideWepRow}>
												<td class="DatablockLabel">Key 1&nbsp;<input id="keyno_11g1" name="{$parentStr.staSettings.staSettingTable.wlan0.sta0.wepKeyNo}" value="1" {if $data.staSettings.staSettingTable.wlan0.sta0.wepKeyNo eq '1'}checked="checked"{/if} type="radio" onclick="setActiveContent();" {$disableWepRow}></td>
												<td class="DatablockContent">
													<input class="input" size="20" id="wepKey1" name="system['staSettings']['staSettingTable']['wlan0']['sta0']['wepKey1']" value="{$data.staSettings.staSettingTable.wlan0.sta0.wepKey1}" type="text" label="WEP Key 1" validate="{literal}Presence, { onlyIfChecked: 'keyno_11g1' }^HexaDecimal,{ isMasked: 'wepKey1' }^Length,{ isWep: true }{/literal}" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true);" onfocus="if(/^\*{literal}{1,}${/literal}/.test(this.value)) this.value='';setActiveContent();">
												</td>
											</tr>
											<tr mode="1" {$hideWepRow}>
												<td class="DatablockLabel">Key 2&nbsp;<input id="keyno_11g2" name="{$parentStr.staSettings.staSettingTable.wlan0.sta0.wepKeyNo}" value="2" {if $data.staSettings.staSettingTable.wlan0.sta0.wepKeyNo eq '2'}checked="checked"{/if} type="radio" onclick="setActiveContent();" {$disableWepRow}></td>
												<td class="DatablockContent">
													<input class="input" size="20" id="wepKey2" name="system['staSettings']['staSettingTable']['wlan0']['sta0']['wepKey2']" value="{$data.staSettings.staSettingTable.wlan0.sta0.wepKey2}" type="text" label="WEP Key 2" validate="{literal}Presence, { onlyIfChecked: 'keyno_11g2' }^HexaDecimal,{ isMasked: 'wepKey2' }^Length,{ isWep: true }{/literal}" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true);" onfocus="if(/^\*{literal}{1,}${/literal}/.test(this.value)) this.value='';setActiveContent();">
												</td>
											</tr>
											<tr mode="1" {$hideWepRow}>
												<td class="DatablockLabel">Key 3&nbsp;<input id="keyno_11g3" name="{$parentStr.staSettings.staSettingTable.wlan0.sta0.wepKeyNo}" value="3" {if $data.staSettings.staSettingTable.wlan0.sta0.wepKeyNo eq '3'}checked="checked"{/if} type="radio" onclick="setActiveContent();" {$disableWepRow}></td>
												<td class="DatablockContent">
													<input class="input" size="20" id="wepKey3" name="system['staSettings']['staSettingTable']['wlan0']['sta0']['wepKey3']" value="{$data.staSettings.staSettingTable.wlan0.sta0.wepKey3}" type="text" label="WEP Key 3" validate="{literal}Presence, { onlyIfChecked: 'keyno_11g3' }^HexaDecimal,{ isMasked: 'wepKey3' }^Length,{ isWep: true }{/literal}" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true);" onfocus="if(/^\*{literal}{1,}${/literal}/.test(this.value)) this.value='';setActiveContent();">
												</td>
											</tr>
											<tr mode="1" {$hideWepRow}>
												<td class="DatablockLabel">Key 4&nbsp;<input id="keyno_11g4" name="{$parentStr.staSettings.staSettingTable.wlan0.sta0.wepKeyNo}" value="4" {if $data.staSettings.staSettingTable.wlan0.sta0.wepKeyNo eq '4'}checked="checked"{/if} type="radio" onclick="setActiveContent();" {$disableWepRow}></td>
												<td class="DatablockContent">
													<input class="input" size="20" id="wepKey4" name="system['staSettings']['staSettingTable']['wlan0']['sta0']['wepKey4']" value="{$data.staSettings.staSettingTable.wlan0.sta0.wepKey4}" type="text" label="WEP Key 4" validate="{literal}Presence, { onlyIfChecked: 'keyno_11g4' }^HexaDecimal,{ isMasked: 'wepKey4' }^Length,{ isWep: true }{/literal}" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true);" onfocus="if(/^\*{literal}{1,}${/literal}/.test(this.value)) this.value='';setActiveContent();">
												</td>
											</tr>
											<tr id="wpa_row" {$hideWPARow}>
												<td class="DatablockLabel">WPA Passphrase (Network Key)</td>
												<td class="DatablockContent">
													<input id="wpa_psk" class="input" size="28" maxlength="63" name="{$parentStr.staSettings.staSettingTable.wlan0.sta0.presharedKey}" value="{$data.staSettings.staSettingTable.wlan0.sta0.presharedKey|regex_replace:"/(.)/":'*'}" type="text" label="WPA Passphrase (Network Key)" validate="Presence,{literal} { allowQuotes: false, allowSpace: true, allowTrimmed: false }{/literal}^Length,{literal}{minimum: 8}{/literal}" onkeydown="setActiveContent();">
												</td>
											</tr>

										</table>
									</div>
								</div>
							</div>
						</td>
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
  <div id="layeredWin" style="display: none; margin: auto; width: 70%;text-align: center;">
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
												{foreach name=profiles key=key item=value from=$data.monitor.apScanList.wlan0}
												<tr {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if} id="row_{$smarty.foreach.profiles.iteration}">
													<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if} style="width: 5%;"><input type="button"  id="ApScanId" name="profileid0" value="Select" onclick="window.opener.copyAPDetails(this.parentNode.parentNode);"></td>
													<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>{$value.ssid}</td>
													{php}
														$this->_tpl_vars['authType'] = $this->_tpl_vars['clientAuthenticationTypeList'][$this->_tpl_vars['value']['authenticationType']];
													{/php}
													<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>{$authType}<input type="hidden" name="authType" value="{$value.authenticationType}"</td>
													{php}
													//print_r($this->_tpl_vars['clientEncryptionTypeList']);
														$this->_tpl_vars['encType'] = $this->_tpl_vars['clientEncryptionTypeList'][$this->_tpl_vars['value']['authenticationType']][$this->_tpl_vars['value']['encryptionType']];
													{/php}
													<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>{$encType}<input type="hidden" name="encType" value="{$value.encryptionType}"</td>
													
													<td {if $smarty.foreach.profiles.iteration%2 eq 0}class="Alternate"{/if}>{$value.channel}</td>
												</tr>
												{/foreach}
											</table>
										<tr class="Alternate">
											<td colspan="2" style="text-align: right; padding: 5px;"><input type="button" value="Close" onclick="window.close();"></td>
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
</div>

    <script language="javascript">
	<!--
		var ChannelList_0 = {$ChannelList_0}; //11b
		var ChannelList_1 = {$ChannelList_1}; //11bg
		
		var ChannelList_0_20 = {$ChannelList_0_20};
		var ChannelList_1_20 = {$ChannelList_1_20};
		
		{if $data.activeMode neq '2'}
			enable11nFields('hide',1);
		{else}
			enable11nFields('show',1);
		{/if}
		
		{literal}window.onload=function changeHelp() {$('helpURL').value=$('helpURL').value+'_g';};
		{/literal}
			
		{if $data.wlanSettings.wlanSettingTable.wlan0.apMode neq 0}
			var disableChannelonWDS0 = true;
		{/if}
	
		toggleDisplay('{$interface}');
	-->
	</script>
