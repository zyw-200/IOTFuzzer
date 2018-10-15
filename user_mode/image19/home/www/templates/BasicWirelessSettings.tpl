{include_php file='../tmpl/modesList.php'}

	<tr>
		<td>
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Wireless Settings','radioSettings')</script></td>
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
													{if $config.TWOGHZ.status}
														<li id="inlineTab1" {if $data.activeMode eq '2' OR $data.activeMode eq '1' OR $data.activeMode eq '0' OR $data.activeMode0 eq '0' OR $data.activeMode0 eq '1' OR $data.activeMode0 eq '2'}class="Active" activeRadio="true"{else} activeRadio="false"{/if} currentId="1"><a id="inlineTabLink1" href="javascript:void(0)">802.11  {if $config.B0.status}<span class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'><b class="RadioText{if $data.activeMode eq '0' OR $data.activeMode0 eq '0'}Active{/if}">b{if $data.activeMode eq '0' OR $data.activeMode0 eq '0'}<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span>{/if}</b></span>{/if}{if $config.BG0.status}/<span class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'><b class="RadioText{if $data.activeMode eq '1' OR $data.activeMode0 eq '1'}Active{/if}">bg{if $data.activeMode eq '1' OR $data.activeMode0 eq '1'}<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span>{/if}</b></span>{/if}{if $config.NG0.status}/<span class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'><b class="RadioText{if $data.activeMode eq '2' OR $data.activeMode0 eq '2'}Active{/if}">ng{if $data.activeMode eq '2' OR $data.activeMode0 eq '2'}<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span>{/if}</b></span>{/if}{if $config.A0.status}/<span class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'><b class="RadioText{if $data.activeMode eq '3' OR $data.activeMode0 eq '3'}Active{/if}">a{if $data.activeMode eq '3' OR $data.activeMode0 eq '3'}<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span>{/if}</b></span>{/if}{if $config.NA0.status}/<span class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'><b class="RadioText{if $data.activeMode eq '4' OR $data.activeMode0 eq '4'}Active{/if}">na{if $data.activeMode eq '4' OR $data.activeMode0 eq '4'}<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span>{/if}</b></span>{/if}</a></li>
													{/if}
<!--@@@FIVEGHZSTART@@@-->
													{if $config.FIVEGHZ.status}<li id="inlineTab2" {if $data.activeMode eq '3' OR $data.activeMode eq '4' OR $data.activeMode1 eq '3' OR $data.activeMode1 eq '4'}class="Active" activeRadio="true"{else} activeRadio="false"{/if} currentId="2"><a id="inlineTabLink2" href="javascript:void(0)">802.11{if $config.B1.status}/<span class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'><b class="RadioText{if $data.activeMode eq '0' OR $data.activeMode1 eq '0'}Active{/if}">b{if $data.activeMode eq '0' OR $data.activeMode1 eq '0'}<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span>{/if}</b></span>{/if}{if $config.BG1.status}/<span class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'><b class="RadioText{if $data.activeMode eq '1' OR $data.activeMode1 eq '1'}Active{/if}">bg{if $data.activeMode eq '1' OR $data.activeMode1 eq '1'}<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span>{/if}</b></span>{/if}{if $config.NG1.status}/<span class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'><b class="RadioText{if $data.activeMode eq '2' OR $data.activeMode1 eq '2'}Active{/if}">ng{if $data.activeMode eq '2' OR $data.activeMode1 eq '2'}<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span>{/if}</b></span>{/if}{if $config.A1.status}/<span class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'><b class="RadioText{if $data.activeMode eq '3' OR $data.activeMode1 eq '3'}Active{/if}">a{if $data.activeMode eq '3' OR $data.activeMode1 eq '3'}<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span>{/if}</b></span>{/if}{if $config.NA1.status}/<span class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'><b class="RadioText{if $data.activeMode eq '4' OR $data.activeMode1 eq '4'}Active{/if}">na{if $data.activeMode eq '4' OR $data.activeMode1 eq '4'}<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span>{/if}</b></span>{/if}</a></li>

													{/if}
<!--@@@FIVEGHZEND@@@-->
												</ul>
											</td>
										</tr>
									</table>
								</div>
								<div id="IncludeTabBlock">
								<input type="hidden" name="activeMode" id="activeMode" value="{$data.activeMode}">
								<input type="hidden" name="currentMode" id="currentMode" value="{$data.activeMode}">
                                <input type="hidden" name="previousInterfaceNum" id="previousInterfaceNum">
{if $config.TWOGHZ.status}
	{if $config.CLIENT.status}
		{assign var="apMode" value=$data.wlanSettings.wlanSettingTable.wlan0.apMode}
	{/if}
									<div  class="BlockContent" id="wlan1">
										<table class="BlockContent Trans" id="table_wlan1">
											<tr class="Gray">
												<td class="DatablockLabel" style="width: 1%;">Wireless Mode</td>
												<td class="DatablockContent" style="width: 100%;">
													<span class="legendActive">2.4GHz Band</span>
{if $config.B0.status}<input type="radio" style="padding: 2px;" name="WirelessMode1" id="WirelessMode1" {if $data.activeMode eq '0' OR $data.activeMode0 eq '0' OR ($data.activeMode eq '' AND $defaultMode0 eq '0')}checked="checked"{/if} value="0"><span id="mode_b" {if $data.activeMode eq '0' OR $data.activeMode0 eq '0' }class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11b<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span>{else}>11b{/if}</span>{/if}
{if $config.BG0.status}<input type="radio" style="padding: 2px;" name="WirelessMode1" id="WirelessMode1" {if $data.activeMode eq '1' OR $data.activeMode0 eq '1' OR ($data.activeMode eq '' AND $defaultMode0 eq '1')}checked="checked"{/if} value="1"><span id="mode_bg" {if $data.activeMode eq '1' OR $data.activeMode0 eq '1' }class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11bg<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span>{else}>11bg{/if}</span>{/if}
{if $config.NG0.status}<input type="radio" style="padding: 2px;" name="WirelessMode1" id="WirelessMode1" {if $data.activeMode eq '2' OR $data.activeMode0 eq '2' OR ($data.activeMode eq '' AND $defaultMode0 eq '2')}checked="checked"{/if} value="2"><span id="mode_ng" {if $data.activeMode eq '2' OR $data.activeMode0 eq '2' }class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11ng<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span>{else}>11ng{/if}</span>{/if}
{if $config.A0.status}<input type="radio" style="padding: 2px;" name="WirelessMode1" id="WirelessMode1" {if $data.activeMode eq '3' OR $data.activeMode0 eq '3' OR ($data.activeMode eq '' AND $defaultMode0 eq '3')}checked="checked"{/if} value="3"><span id="mode_a" {if $data.activeMode eq '3' OR $data.activeMode0 eq '3' }class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11a<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span>{else}>11a{/if}</span>{/if}
{if $config.NA1.status}<input type="radio" style="padding: 2px;" name="WirelessMode1" id="WirelessMode1" {if $data.activeMode eq '4' OR $data.activeMode0 eq '4' OR ($data.activeMode eq '' AND $defaultMode0 eq '4')}checked="checked"{/if} value="4"><span id="mode_na" {if $data.activeMode eq '4' OR $data.activeMode0 eq '4' }class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11na<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span>{else}>11na{/if}</span>{/if}
													<input type="hidden" name="{$parentStr.wlanSettings.wlanSettingTable.wlan0.operateMode}" id="activeMode0" value="{$data.activeMode0}">
													<input type="hidden" name="radioStatus0" id="radioStatus0" value="{$data.radioStatus0}">
												{if $config.CLIENT.status}
													<input type="hidden" name="apMode0" id="apMode0" value="{$apMode}">
												{/if}
													<input type="hidden" name="modeWlan0" id="modeWlan0" value="{if $data.activeMode gt 2}2{else}{$data.activeMode}{/if}">
												</td>
											</tr>
											{assign var="radioStatus" value=$parentStr.wlanSettings.wlanSettingTable.wlan0.radioStatus}
											{assign var="operateMode" value=$parentStr.wlanSettings.wlanSettingTable.wlan0.operateMode}
                                                                                    {if $config.SCH_WIRELESS_ON_OFF.status}
                                                                                        <input type="hidden" name="sch_Stat" id="sch_Stat" value="{$data.basicSettings.scheduledWirelessStatus}">
                                                                                    {/if}
											{input_row row_id="radioRow1" label="Turn Radio On" id="chkRadio0" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.radioStatus type="checkbox" value=$data.wlanSettings.wlanSettingTable.wlan0.radioStatus}
                                                                                        {if $config.SCH_WIRELESS_ON_OFF.status}
                                                                                            {ip_field id="radioBkup0" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.scheduledRadioBackup type="hidden" value=$data.wlanSettings.wlanSettingTable.wlan0.scheduledRadioBackup}
                                                                                        {/if}
										{if $config.CLIENT.status AND $apMode eq 5}
											<tr>
												<td class="DatablockLabel">Wireless Network Name (SSID)</td>
												<td class="DatablockContent">
													{ip_field label="Wireless Network Name (SSID)" id="wirelessSSID0" name=$parentStr.staSettings.staSettingTable.wlan0.sta0.ssid type="text" value=$data.staSettings.staSettingTable.wlan0.sta0.ssid maxlength="32" validate="Presence, ((allowQuotes: true,allowSpace: true,allowTrimmed: false))^Length, (( minimum: 2, maximum: 32 ))"}
													<input name="clientMode_button" style="text-align: center;" value="Site Survey" onclick="showSurveyPopupWindow(); return false;" type="button">
												</td>
											</tr>
										{else}
                                                                                    {input_row label="Wireless Network Name (SSID)" id="wirelessSSID0" name=$parentStr.vapSettings.vapSettingTable.wlan0.vap0.ssid type="text" value=$data.vapSettings.vapSettingTable.wlan0.vap0.ssid maxlength="32" validate="Presence, ((allowQuotes: true,allowSpace: true,allowTrimmed: false))^Length, (( minimum: 2, maximum: 32 ))"}
                                                                                     {if $config.WN604.status}
                                                                                        <tr>
                                                                                            <td class="DatablockLabel">RF Switch Status</td>
                                                                                            <input type="hidden" id="rfSwitchStatus0" value="{$data.wlanSettings.wlanSettingTable.wlan0.rfSwitchStatus}">
                                                                                            {if $data.wlanSettings.wlanSettingTable.wlan0.rfSwitchStatus eq '1'}
                                                                                                <td class="DatablockLabel" id="rfSwitchStatusLabel0"><font color="green">ON</font></td>
                                                                                            {else}
                                                                                                <td class="DatablockLabel" id="rfSwitchStatusLabel0"><font color="red">OFF</font></td>
                                                                                            {/if}
                                                                                        </tr>
                                                                                      {/if}
                                                                                      {if $config.SCH_WIRELESS_ON_OFF.status}
                                                                                        <tr>
                                                                                            <td class="DatablockLabel">Scheduler Status</td>
                                                                                            <input type="hidden" id="schedular0" value="{$data.basicSettings.scheduledWirelessStatus}">
                                                                                            {if $data.basicSettings.scheduledWirelessStatus eq '1'}
                                                                                                <td class="DatablockLabel" id="schedStatusLabel0"><font color="green">ON</font></td>
                                                                                            {else}
                                                                                                <td class="DatablockLabel" id="schedStatusLabel0"><font color="red">OFF</font></td>
                                                                                            {/if}
                                                                                        </tr>

                                                                                    {/if}
										{/if}

											{assign var="broadcastSSID1" value=$data.vapSettings.vapSettingTable.wlan0.vap0.hideNetworkName}
											{input_row label="Broadcast Wireless Network Name (SSID)" id="idbroadcastSSID1" name=$parentStr.vapSettings.vapSettingTable.wlan0.vap0.hideNetworkName type="radio" options="0-Yes,1-No" value=$broadcastSSID1 selectCondition="==$broadcastSSID1" disableCondition="5==$apMode"}

											{input_row label="Channel / Frequency" id="ChannelList1" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.channel type="select" options=$ChannelList0 selected=$data.wlanSettings.wlanSettingTable.wlan0.channel onchange="checkWDSStatus(this,'0');" disableCondition="5==$apMode"}

											{input_row label="Data Rate" row_id="datarate11n1" id="DatarateList1" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.dataRate type="select" options=$DataRateList_0 selected=$data.wlanSettings.wlanSettingTable.wlan0.dataRate disableCondition="5==$apMode"}
										{if $config.MODE11N.status}
											{input_row label="MCS Index / Data Rate" row_id="mcsrate11n1" label_id="mcsrateLabel" id="MCSrateList1" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.mcsRate type="select" options=$MCSRateList_0 selected=$data.wlanSettings.wlanSettingTable.wlan0.mcsRate disableCondition="5==$apMode"}
{if $config.TWOGHZ.status AND $config.FIVEGHZ.status}
                                            {assign var="activeModeString" value="activeMode0"}
{else}
                                            {assign var="activeModeString" value="activeMode"}
{/if}
                                                                                        {if $config.EXT_CHAN.status}
                                                                                            {input_row label="Channel Width" row_id="bandwidth11n1" id="Bandwidth1" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.channelWidth type="select" options=$channelWidthList_0 selected=$data.wlanSettings.wlanSettingTable.wlan0.channelWidth onchange="DispChannelList(1,$('$activeModeString').value),DisplayExtControls(this.value);" disableCondition="5==$apMode"}
                                                                                        {else}
                                                                                            {input_row label="Channel Width" row_id="bandwidth11n1" id="Bandwidth1" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.channelWidth type="select" options=$channelWidthList_0 selected=$data.wlanSettings.wlanSettingTable.wlan0.channelWidth onchange="DispChannelList(1,$('$activeModeString').value);" disableCondition="5==$apMode"}
                                                                                        {/if}
                                                                                        {if $config.EXT_CHAN.status}
                                                                                            {if ($data.wlanSettings.wlanSettingTable.wlan0.channelWidth eq 0)}
                                                                                                    {assign var="hideExtRow" value="style=\"display: none;\" disabled='true'"}
                                                                                            {else}
                                                                                                    {assign var="hideExtRow" value="style=\"display: none;\" disabled='false'"}
                                                                                            {/if}
                                                                                            {if ($apMode eq 5)}
                                                                                                    {assign var="disableContnt" value="style=\"display: '';\" disabled='true'"}
                                                                                            {/if}
                                                                                            <tr mode="extCtrl" {$hideExtRow} id="extProtSpacRow_1">
                                                                                                <td class="DatablockLabel">Ext Protection Spacing</td>
                                                                                                <td class="DatablockContent" {$disableContnt}>
                                                                                                    {ip_field id="extProtSpec_0" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.extProtSpec type="select" options=$ext_protect_spacingList_0 selected=$data.wlanSettings.wlanSettingTable.wlan0.extProtSpec disableCondition="5==$apMode"}</td>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr mode="extCtrl" {$hideExtRow} id="extChanOffsetRow_1">
                                                                                                <td class="DatablockLabel">Ext Channel Offset</td>
                                                                                                <td class="DatablockContent" {$disableContnt}>
                                                                                                    {ip_field id="extChanOffset_0" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.extChanOffset type="select" options=$ext_chan_offsetList_0 selected=$data.wlanSettings.wlanSettingTable.wlan0.extChanOffset disableCondition="5==$apMode"}</td>
                                                                                                </td>
                                                                                            </tr>

                                                                                        {/if}

											{input_row label="Guard Interval" row_id="gi11n1" id="GI1" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.guardInterval type="select" options=$guardIntervalList selected=$data.wlanSettings.wlanSettingTable.wlan0.guardInterval onchange="DispChannelList(1,$('$activeModeString').value);" disableCondition="5==$apMode"}
										{/if}
											{input_row label="Output Power" id="PowerList1" name=$parentStr.wlanSettings.wlanSettingTable.wlan0.txPower type="select" options=$outputPowerList selected=$data.wlanSettings.wlanSettingTable.wlan0.txPower disableCondition="5==$apMode"}
										{if $config.CLIENT.status AND $apMode eq 5}
											{input_row label="Network Authentication" id="authenticationType" name=$parentStr.staSettings.staSettingTable.wlan0.sta0.authenticationType type="select" options=$clientAuthenticationTypeList selected=$data.staSettings.staSettingTable.wlan0.sta0.authenticationType onchange="DisplayClientSettings(this.value);"}

											{php}
												$this->_tpl_vars['encTypeList'] = $this->_tpl_vars['clientEncryptionTypeList'][$this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['authenticationType']];
											{/php}
											{input_row label="Data Encryption" id="key_size_11g" name="encryptionType" type="select" options=$encTypeList selected=$sta0encryptionSel onchange="if ($('authenticationType').value=='0') DisplayClientSettings($('authenticationType').value); setEncryption(this.value,$('authenticationType').value);"}
											{if NOT ($data.staSettings.staSettingTable.wlan0.sta0.authenticationType eq 1 OR ($data.staSettings.staSettingTable.wlan0.sta0.authenticationType eq 0 AND $data.staSettings.staSettingTable.wlan0.sta0.encryption neq 0))}
												{assign var="hideWepRow" value="style=\"display: none;\" disabled='true'"}
											{/if}
											{if NOT($data.staSettings.staSettingTable.wlan0.sta0.authenticationType eq 16 OR $data.staSettings.staSettingTable.wlan0.sta0.authenticationType eq 32 OR $data.staSettings.staSettingTable.wlan0.sta0.authenticationType eq 48)}
												{assign var="hideWPARow" value="style=\"display: none;\" disabled='true'"}
											{/if}
											{ip_field id="encryption" name=$parentStr.staSettings.staSettingTable.wlan0.sta0.encryption type="hidden" value=$data.staSettings.staSettingTable.wlan0.sta0.encryption}
											{if NOT ($data.staSettings.staSettingTable.wlan0.sta0.authenticationType eq 1 OR ($data.staSettings.staSettingTable.wlan0.sta0.authenticationType eq 0 AND $data.staSettings.staSettingTable.wlan0.sta0.encryption neq 0))}
												{ip_field id="wepKeyType" name=$parentStr.staSettings.staSettingTable.wlan0.sta0.wepKeyType type="hidden" value=$data.staSettings.staSettingTable.wlan0.sta0.wepKeyType disabled="true"}
											{else}
												{ip_field id="wepKeyType" name=$parentStr.staSettings.staSettingTable.wlan0.sta0.wepKeyType type="hidden" value=$data.staSettings.staSettingTable.wlan0.sta0.wepKeyType}
											{/if}
											<tr id="wep_row" {$hideWepRow}>
												<td class="DatablockLabel">Passphrase</td>
												<td class="DatablockContent">
													<input class="input" size="20" maxlength="39" id="wepPassPhrase" name="{$parentStr.staSettings.staSettingTable.wlan0.sta0.wepPassPhrase}" value="{$data.staSettings.staSettingTable.wlan0.sta0.wepPassPhrase|regex_replace:"/(.)/":'*'}" type="text" label="Passphrase"  onfocus="if(/^\*{literal}{1,}${/literal}/.test(this.value)) this.value='';setActiveContent();" validate="Presence, {literal}{ isMasked: 'wepPassPhrase', allowQuotes: true, allowSpace: true, allowTrimmed:false }{/literal}" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true); $('wepPassPhrase_hidden').value='';">&nbsp;
													<input name="szPassphrase_button" style="text-align: center;" value="Generate Keys" onclick="gen_11g_keys()" type="button">
													<input type="hidden" id="wepPassPhrase_hidden" value="{$data.staSettings.staSettingTable.wlan0.sta0.wepPassPhrase}">
												</td>
											</tr>
											<tr mode="1" {$hideWepRow}>
												<td class="DatablockLabel">Key 1&nbsp;<input id="keyno_11g1" name="{$parentStr.staSettings.staSettingTable.wlan0.sta0.wepKeyNo}" value="1" {if $data.staSettings.staSettingTable.wlan0.sta0.wepKeyNo eq '1'}checked="checked"{/if} type="radio" onclick="setActiveContent();" {$disableWepRow}></td>
												<td class="DatablockContent">
													<input class="input" size="20" id="wepKey1" name="system['staSettings']['staSettingTable']['wlan0']['sta0']['wepKey1']" value="{$data.staSettings.staSettingTable.wlan0.sta0.wepKey1|regex_replace:"/(.)/":'*'}" type="text" label="WEP Key 1" validate="{literal}Presence, { onlyIfChecked: 'keyno_11g1' }^HexaDecimal,{ isMasked: 'wepKey1' }^Length,{ isWep: true }{/literal}" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true);" onfocus="if(/^\*{literal}{1,}${/literal}/.test(this.value)) this.value='';setActiveContent();">
												</td>
											</tr>
											<tr mode="1" {$hideWepRow}>
												<td class="DatablockLabel">Key 2&nbsp;<input id="keyno_11g2" name="{$parentStr.staSettings.staSettingTable.wlan0.sta0.wepKeyNo}" value="2" {if $data.staSettings.staSettingTable.wlan0.sta0.wepKeyNo eq '2'}checked="checked"{/if} type="radio" onclick="setActiveContent();" {$disableWepRow}></td>
												<td class="DatablockContent">
													<input class="input" size="20" id="wepKey2" name="system['staSettings']['staSettingTable']['wlan0']['sta0']['wepKey2']" value="{$data.staSettings.staSettingTable.wlan0.sta0.wepKey2|regex_replace:"/(.)/":'*'}" type="text" label="WEP Key 2" validate="{literal}Presence, { onlyIfChecked: 'keyno_11g2' }^HexaDecimal,{ isMasked: 'wepKey2' }^Length,{ isWep: true }{/literal}" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true);" onfocus="if(/^\*{literal}{1,}${/literal}/.test(this.value)) this.value='';setActiveContent();">
												</td>
											</tr>
											<tr mode="1" {$hideWepRow}>
												<td class="DatablockLabel">Key 3&nbsp;<input id="keyno_11g3" name="{$parentStr.staSettings.staSettingTable.wlan0.sta0.wepKeyNo}" value="3" {if $data.staSettings.staSettingTable.wlan0.sta0.wepKeyNo eq '3'}checked="checked"{/if} type="radio" onclick="setActiveContent();" {$disableWepRow}></td>
												<td class="DatablockContent">
													<input class="input" size="20" id="wepKey3" name="system['staSettings']['staSettingTable']['wlan0']['sta0']['wepKey3']" value="{$data.staSettings.staSettingTable.wlan0.sta0.wepKey3|regex_replace:"/(.)/":'*'}" type="text" label="WEP Key 3" validate="{literal}Presence, { onlyIfChecked: 'keyno_11g3' }^HexaDecimal,{ isMasked: 'wepKey3' }^Length,{ isWep: true }{/literal}" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true);" onfocus="if(/^\*{literal}{1,}${/literal}/.test(this.value)) this.value='';setActiveContent();">
												</td>
											</tr>
											<tr mode="1" {$hideWepRow}>
												<td class="DatablockLabel">Key 4&nbsp;<input id="keyno_11g4" name="{$parentStr.staSettings.staSettingTable.wlan0.sta0.wepKeyNo}" value="4" {if $data.staSettings.staSettingTable.wlan0.sta0.wepKeyNo eq '4'}checked="checked"{/if} type="radio" onclick="setActiveContent();" {$disableWepRow}></td>
												<td class="DatablockContent">
													<input class="input" size="20" id="wepKey4" name="system['staSettings']['staSettingTable']['wlan0']['sta0']['wepKey4']" value="{$data.staSettings.staSettingTable.wlan0.sta0.wepKey4|regex_replace:"/(.)/":'*'}" type="text" label="WEP Key 4" validate="{literal}Presence, { onlyIfChecked: 'keyno_11g4' }^HexaDecimal,{ isMasked: 'wepKey4' }^Length,{ isWep: true }{/literal}" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true);" onfocus="if(/^\*{literal}{1,}${/literal}/.test(this.value)) this.value='';setActiveContent();">
												</td>
											</tr>
											<tr id="wpa_row" {$hideWPARow}>
												<td class="DatablockLabel">WPA Passphrase (Network Key)</td>
												<td class="DatablockContent">
													<input id="wpa_psk" class="input" size="28" maxlength="63" name="{$parentStr.staSettings.staSettingTable.wlan0.sta0.presharedKey}" value="{$data.staSettings.staSettingTable.wlan0.sta0.presharedKey|regex_replace:"/(.)/":'*'}" type="text" label="WPA Passphrase (Network Key)" validate="Presence,{literal} { allowQuotes: true, allowSpace: true, allowTrimmed: false }{/literal}^Length,{literal}{minimum: 8}{/literal}" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',false);" onfocus="if(/^\*{literal}{1,}${/literal}/.test(this.value)) this.value='';setActiveContent();">
												</td>
											</tr>
										{/if}
										</table>
									</div>
{/if}
<!--@@@FIVEGHZSTART@@@-->
{if $config.FIVEGHZ.status}
	{if $config.CLIENT.status}
		{assign var="apMode" value=$data.wlanSettings.wlanSettingTable.wlan1.apMode}
	{/if}
									<div  class="BlockContent" id="wlan2">
										<table class="BlockContent Trans" id="table_wlan2">
											<tr class="Gray">
												<td class="DatablockLabel" style="width: 1%;">Wireless Mode</td>
												<td class="DatablockContent" style="width: 100%;">
													<span class="legendActive">5GHz Band</span>
{if $config.B1.status}<input type="radio" style="padding: 2px;" name="WirelessMode{if $config.DUAL_CONCURRENT.status}2{else}1{/if}" id="WirelessMode{if $config.DUAL_CONCURRENT.status}2{else}1{/if}" {if $data.activeMode eq '0' OR $data.activeMode1 eq '0' OR ($data.activeMode eq '' AND $defaultMode1 eq '0')}checked="checked"{/if} value="0"><span id="mode_b" {if $data.activeMode eq '0'  OR $data.activeMode1 eq '0'}class="Active" id="radioAct" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11b<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span>{else}>11b{/if}</span>{/if}
{if $config.BG1.status}<input type="radio" style="padding: 2px;" name="WirelessMode{if $config.DUAL_CONCURRENT.status}2{else}1{/if}" id="WirelessMode{if $config.DUAL_CONCURRENT.status}2{else}1{/if}" {if $data.activeMode eq '1' OR $data.activeMode1 eq '1' OR ($data.activeMode eq '' AND $defaultMode1 eq '1')}checked="checked"{/if} value="1"><span id="mode_bg" {if $data.activeMode eq '1'  OR $data.activeMode1 eq '1'}class="Active" id="radioAct" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11bg<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span>{else}>11bg{/if}</span>{/if}
{if $config.NG1.status}<input type="radio" style="padding: 2px;" name="WirelessMode{if $config.DUAL_CONCURRENT.status}2{else}1{/if}" id="WirelessMode{if $config.DUAL_CONCURRENT.status}2{else}1{/if}" {if $data.activeMode eq '2' OR $data.activeMode1 eq '2' OR ($data.activeMode eq '' AND $defaultMode1 eq '2')}checked="checked"{/if} value="2"><span id="mode_ng" {if $data.activeMode eq '2'  OR $data.activeMode1 eq '2'}class="Active" id="radioAct" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11ng<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span>{else}>11ng{/if}</span>{/if}
{if $config.A1.status}<input type="radio" style="padding: 2px;" name="WirelessMode{if $config.DUAL_CONCURRENT.status}2{else}1{/if}" id="WirelessMode{if $config.DUAL_CONCURRENT.status}2{else}1{/if}" {if $data.activeMode eq '3' OR $data.activeMode1 eq '3' OR ($data.activeMode eq '' AND $defaultMode1 eq '3')}checked="checked"{/if} value="3"><span id="mode_a" {if $data.activeMode eq '3' OR $data.activeMode1 eq '3'}class="Active" id="radioAct" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11a<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span>{else}>11a{/if}</span>{/if}
{if $config.NA1.status}<input type="radio" style="padding: 2px;" name="WirelessMode{if $config.DUAL_CONCURRENT.status}2{else}1{/if}" id="WirelessMode{if $config.DUAL_CONCURRENT.status}2{else}1{/if}" {if $data.activeMode eq '4' OR $data.activeMode1 eq '4' OR ($data.activeMode eq '' AND $defaultMode1 eq '4')}checked="checked"{/if} value="4"><span id="mode_na" {if $data.activeMode eq '4'  OR $data.activeMode1 eq '4'}class="Active" id="radioAct" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11na<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span>{else}>11na{/if}</span>{/if}
													<input type="hidden" name="{$parentStr.wlanSettings.wlanSettingTable.wlan1.operateMode}" id="activeMode1" value="{$data.activeMode1}">
													<input type="hidden" name="radioStatus1" id="radioStatus1" value="{$data.radioStatus1}">
												{if $config.CLIENT.status}
													<input type="hidden" name="apMode1" id="apMode1" value="{$apMode}">
												{/if}
													<input type="hidden" name="modeWlan1" id="modeWlan1" value="{if $data.activeMode lt 3}4{else}{$data.activeMode}{/if}">
												</td>
											</tr>
                                                                                        {if $config.SCH_WIRELESS_ON_OFF.status}
                                                                                            {ip_field id="radioBkup1" name=$parentStr.wlanSettings.wlanSettingTable.wlan1.scheduledRadioBackup type="hidden" value=$data.wlanSettings.wlanSettingTable.wlan1.scheduledRadioBackup}
                                                                                        {/if}

											{assign var="radioStatus" value=$parentStr.wlanSettings.wlanSettingTable.wlan1.radioStatus}
											{assign var="operateMode" value=$parentStr.wlanSettings.wlanSettingTable.wlan1.operateMode}
                                            {if $config.DUAL_CONCURRENT.status}
                                                {assign var="modeString" value='WirelessMode2'}
                                            {else}
                                                {assign var="modeString" value='WirelessMode1'}
                                            {/if}
											{input_row row_id="radioRow2" label="Turn Radio On" id="chkRadio1" name=$parentStr.wlanSettings.wlanSettingTable.wlan1.radioStatus type="checkbox" value=$data.wlanSettings.wlanSettingTable.wlan1.radioStatus}

											{input_row label="Wireless Network Name (SSID)" id="wirelessSSID1" name=$parentStr.vapSettings.vapSettingTable.wlan1.vap0.ssid type="text" value=$data.vapSettings.vapSettingTable.wlan1.vap0.ssid maxlength="32" validate="Presence, ((allowQuotes: true,allowSpace: true,  allowTrimmed: false))^Length, (( minimum: 2, maximum: 32 ))"}
                                                                                      {if $config.SCH_WIRELESS_ON_OFF.status}
                                                                                        <tr>
                                                                                            <td class="DatablockLabel">Scheduler Status</td>
                                                                                            <input type="hidden" id="schedular1" value="{$data.basicSettings.scheduledWirelessStatus}">
                                                                                            {if $data.basicSettings.scheduledWirelessStatus eq '1'}
                                                                                                <td class="DatablockLabel" id="schedStatusLabel1"><font color="green">ON</font></td>
                                                                                            {else}
                                                                                                <td class="DatablockLabel" id="schedStatusLabel1"><font color="red">OFF</font></td>
                                                                                            {/if}
                                                                                        </tr>

                                                                                    {/if}


											{assign var="broadcastSSID2" value=$data.vapSettings.vapSettingTable.wlan1.vap0.hideNetworkName}
											{input_row label="Broadcast Wireless Network Name (SSID)" id="idbroadcastSSID1" name=$parentStr.vapSettings.vapSettingTable.wlan1.vap0.hideNetworkName type="radio" options="0-Yes,1-No" value=$broadcastSSID2 selectCondition="==$broadcastSSID2" disableCondition="5==$apMode"}

											{input_row label="Channel / Frequency" id="ChannelList2" name=$parentStr.wlanSettings.wlanSettingTable.wlan1.channel type="select" options=$ChannelList1 selected=$data.wlanSettings.wlanSettingTable.wlan1.channel onchange="checkWDSStatus(this,'1');" disableCondition="5==$apMode"}

											{input_row label="Data Rate" row_id="datarate11n2" id="DatarateList2" name=$parentStr.wlanSettings.wlanSettingTable.wlan1.dataRate type="select" options=$DataRateList_1 selected=$data.wlanSettings.wlanSettingTable.wlan1.dataRate disableCondition="5==$apMode"}
										{if $config.MODE11N.status}
											{input_row label="MCS Index / Data Rate" row_id="mcsrate11n2" label_id="mcsrateLabel" id="MCSrateList2" name=$parentStr.wlanSettings.wlanSettingTable.wlan1.mcsRate type="select" options=$MCSRateList_1 selected=$data.wlanSettings.wlanSettingTable.wlan1.mcsRate disableCondition="5==$apMode"}
{if $config.TWOGHZ.status AND $config.FIVEGHZ.status}
                                            {assign var="activeModeString" value="activeMode1"}
{else}
                                            {assign var="activeModeString" value="activeMode"}
{/if}
											{input_row label="Channel Width" row_id="bandwidth11n2" id="Bandwidth2" name=$parentStr.wlanSettings.wlanSettingTable.wlan1.channelWidth type="select" options=$channelWidthList_1 selected=$data.wlanSettings.wlanSettingTable.wlan1.channelWidth onchange="DispChannelList(2,$('$activeModeString').value);" disableCondition="5==$apMode"}

											{input_row label="Guard Interval" row_id="gi11n2" id="GI2" name=$parentStr.wlanSettings.wlanSettingTable.wlan1.guardInterval type="select" options=$guardIntervalList selected=$data.wlanSettings.wlanSettingTable.wlan1.guardInterval onchange="DispChannelList(2,$('$activeModeString').value);" disableCondition="5==$apMode"}
										{/if}
											{input_row label="Output Power" id="PowerList2" name=$parentStr.wlanSettings.wlanSettingTable.wlan1.txPower type="select" options=$outputPowerList selected=$data.wlanSettings.wlanSettingTable.wlan1.txPower disableCondition="5==$apMode"}
										</table>
									</div>
{/if}
<!--@@@FIVEGHZEND@@@-->
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

    {if $support5GHz eq true}
            <input id="supports5GHz" value='true' type="hidden">
    {else}
            <input id="supports5GHz" value='false' type="hidden">
    {/if}

	<script language="javascript">
	<!--
        var prevInterface = {$smarty.post.previousInterfaceNum|default:"''"};
    	{if $config.TWOGHZ.status}
			var ChannelList_0 = {$ChannelList_0}; //11b
			{if $config.MODE11G.status}
				var ChannelList_1 = {$ChannelList_1}; //11bg
				{if $config.MODE11N.status}
					var ChannelList_0_20 = {$ChannelList_0_20};
					var wlan0_40MHzSupport = {$wlan0_40MHzSupport};
					{if $wlan0_40MHzSupport eq true AND $ChannelList_0_40 neq ''}
						var ChannelList_0_40 = {$ChannelList_0_40};
					{/if}
                        
				{/if}
				{if $data.wlanSettings.wlanSettingTable.wlan0.apMode neq 0 AND $data.wlanSettings.wlanSettingTable.wlan0.apMode neq 5}
					var disableChannelonWDS0 = true;
				{/if}
				{if $interface eq 'wlan1'}
					{literal}window.onload=function changeHelp() {$('helpURL').value=$('helpURL').value+'_g';};{/literal}
				{/if}
			{/if}
			//<!-- Starting Generic code  for 2.4 GHZ -->
			var ChannelList_3 = {$ChannelList_3}; //11a
                        {if $config.MODE11N.status}
                                var ChannelList_1_20 = {$ChannelList_1_20};
                                var wlan1_40MHzSupport = {$wlan1_40MHzSupport};
                                {if $wlan1_40MHzSupport eq true AND $ChannelList_1_40 neq ''}
                                        var ChannelList_1_40 = {$ChannelList_1_40};
                                {/if}
                        {/if}
                        {if $data.wlanSettings.wlanSettingTable.wlan1.apMode neq 0 AND $data.wlanSettings.wlanSettingTable.wlan1.apMode neq 5}
                                var disableChannelonWDS1 = true;
			{/if}
			{if $interface eq 'wlan2'}
                        	{literal}window.onload=function changeHelp() {$('helpURL').value=$('helpURL').value+'_a';};{/literal}
			{/if}
//<!-- Ending Generic code  for 2.4 GHZ -->
	{/if}
//<!--@@@FIVEGHZSTART@@@-->
		{if $config.FIVEGHZ.status}
//<!-- Starting Generic code  for 5 GHZ -->
 var ChannelList_0 = {$ChannelList_0}; //11b
                        {if $config.MODE11G.status}
                                var ChannelList_1 = {$ChannelList_1}; //11bg
                                {if $config.MODE11N.status}
                                        var ChannelList_0_20 = {$ChannelList_0_20};
                                        var wlan0_40MHzSupport = {$wlan0_40MHzSupport};
                                        {if $wlan0_40MHzSupport eq true AND $ChannelList_0_40 neq ''}
                                                var ChannelList_0_40 = {$ChannelList_0_40};
                                        {/if}
                                {/if}
                                {if $data.wlanSettings.wlanSettingTable.wlan0.apMode neq 0 AND $data.wlanSettings.wlanSettingTable.wlan0.apMode neq 5}
                                        var disableChannelonWDS0 = true;
                                {/if}
                                {if $interface eq 'wlan1'}
                                        {literal}window.onload=function changeHelp() {$('helpURL').value=$('helpURL').value+'_g';};{/literal}
                                {/if}
                        {/if}
//<!-- Ending Generic code  for 5 GHZ -->
			var ChannelList_3 = {$ChannelList_3}; //11a
			{if $config.MODE11N.status}
				var ChannelList_1_20 = {$ChannelList_1_20};
				var wlan1_40MHzSupport = {$wlan1_40MHzSupport};
				{if $wlan1_40MHzSupport eq true AND $ChannelList_1_40 neq ''}
					var ChannelList_1_40 = {$ChannelList_1_40};
				{/if}

			{/if}
			{if $data.wlanSettings.wlanSettingTable.wlan1.apMode neq 0 AND $data.wlanSettings.wlanSettingTable.wlan1.apMode neq 5}
				var disableChannelonWDS1 = true;
			{/if}
			{if $interface eq 'wlan2'}
				{literal}window.onload=function changeHelp() {$('helpURL').value=$('helpURL').value+'_a';};{/literal}
			{/if}
		{/if}
//<!--@@@FIVEGHZEND@@@-->
		var form = new formObject();
		{if $config.TWOGHZ.status}
            $('cb_chkRadio0').observe('click',form.tab1.setActiveMode.bindAsEventListener(form.tab1,true));
            var i = 0;
			$RD('WirelessMode1').each( function(radio) {literal} {
                if (parseInt(radio.value) < numModes0) {
				    $(radio).observe('click',form.tab1.enableMode.bindAsEventListener(form.tab1, i++));
                }
			});
            {/literal}
        {/if}
//<!--@@@FIVEGHZSTART@@@-->
		{if $config.FIVEGHZ.status}
            $('cb_chkRadio1').observe('click',form.tab2.setActiveMode.bindAsEventListener(form.tab2,true));
            if (config.B1.status){
                        var i = 0; 
                } else if (!config.B1.status && config.BG1.status){
                        var i = 1;
                } else if (!config.B1.status && !config.BG1.status && config.NG1.status){
                        var i = 2;
                } else if (!config.B1.status && !config.BG1.status && !config.NG1.status && config.A1.status){
           		var i = 3;
                } else if (!config.B1.status && !config.BG1.status && !config.NG1.status && !config.A1.status && config.NA1.status){
                        var i = 4;
                }
			$RD({if $config.DUAL_CONCURRENT.status}'WirelessMode2'{else}'WirelessMode1'{/if}).each( function(radio) {literal}{
                if (parseInt(radio.value) >= i)
				    $(radio).observe('click',form.tab2.enableMode.bindAsEventListener(form.tab2, i++));
			});
            {/literal}
		{/if}
//<!--@@@FIVEGHZEND@@@-->

{literal}
            if (prevInterface != '') {
                    if(prevInterface == '1'){
                        form.tab1.activate();
                    }
                    else if(prevInterface == '2'){
                        form.tab2.activate();
                    }
             }
             else {
{/literal}
            {if $config.TWOGHZ.status}
                    form.tab1.activate();
            {/if}
//<!--@@@FIVEGHZSTART@@@-->
            {if $config.FIVEGHZ.status}
                {if !$config.TWOGHZ.status}
                        form.tab2.activate();
                {/if}
                {if $data.radioStatus1 eq '1' AND $data.radioStatus0 neq '1'}
                    form.tab2.activate();
                {/if}
            {/if}
//<!--@@@FIVEGHZEND@@@-->
{literal}
            }
{/literal}

{if !$config.DUAL_CONCURRENT.status && ($config.WNDAP620.status && $data.wlanSettings.wlanSettingTable.wlan0.apMode eq 5)}
$('inlineTab2').observe('click', disableForm);
{literal}
	function disableForm()
	{
	Form.disable(document.dataForm);
	}
{/literal}
{/if}

	-->
	</script>
